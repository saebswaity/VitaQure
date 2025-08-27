<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background:#f8fafc; }
        .chat-panel.expanded { width: 100%; }
        .chat-messages{ height: calc(100vh - 240px); overflow:auto; padding:8px; background:#fff; border:1px solid #e5e7eb; border-radius:8px; }
        .message{ display:flex; flex-direction:column; margin:8px 0; }
        .message.user .message-content{ background:#2563eb; color:#fff; align-self:flex-end; }
        .message.bot .message-content{ background:#e5e7eb; color:#111827; align-self:flex-start; }
        .message-content{ padding:10px 12px; border-radius:12px; max-width:80%; }
        .message-time{ font-size:.75rem; color:#6b7280; margin-top:4px; }
        .typing-indicator .typing-dots{ display:flex; gap:4px; }
        .typing-dot{ width:6px; height:6px; border-radius:50%; background:#9ca3af; animation: blink 1s infinite alternate; }
        .typing-dot:nth-child(2){ animation-delay: .2s; }
        .typing-dot:nth-child(3){ animation-delay: .4s; }
        @keyframes blink{ from{ opacity:.3 } to{ opacity:1 } }
        .error-message{ color:#b91c1c; background:#fee2e2; border:1px solid #fecaca; padding:8px; border-radius:8px; margin:6px 0; }
    </style>
    <script>
        const AI_BASE = '/ai-chat/api/';
        const AI_USER_KEY = @json(isset($userKey) ? $userKey : 'guest');
    </script>
</head>
<body>
    <div class="container-fluid py-2">
        <div class="row">
            <div class="col-12 chat-panel" id="chatPanel">
                <div class="card">
                    <div class="card-header d-flex justify-content-end align-items-center" id="chatHeaderBar">
                        <div class="d-flex align-items-center gap-2">
                            <button class="btn btn-sm btn-outline-danger" id="clearChat" title="Clear conversation"><i class="fas fa-trash"></i></button>
                            <button class="btn btn-sm btn-outline-primary" id="openSettings" data-bs-toggle="modal" data-bs-target="#settingsModal" title="Settings"><i class="fas fa-cog"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info" id="statusAlert" style="display:none"><small id="statusText"></small></div>
                        <div class="chat-messages" id="chatMessages">
                            <div class="welcome-message text-center text-muted mt-4">
                                <i class="fas fa-robot fa-3x mb-3"></i>
                                <h4>Welcome!</h4>
                                <p>Use the gear icon to configure and start chatting.</p>
                            </div>
                        </div>
                        <div class="message-input mt-3">
                            <div class="input-group">
                                <textarea class="form-control" id="messageInput" rows="2" placeholder="Type your message..." maxlength="1000"></textarea>
                                <button class="btn btn-primary" id="sendMessage" type="button"><i class="fas fa-paper-plane"></i></button>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <small class="text-muted"><span id="charCount">0</span>/1000</small>
                                <small class="text-muted">Ctrl+Enter to send</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Modal -->
    <div class="modal fade" id="settingsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-sliders-h"></i> Chat Settings</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Model</label>
                        <select id="modelSelect" class="form-select">
                            <option value="chatgpt">ChatGPT</option>
                            <option value="gemini">Gemini</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Initial Context</label>
                        <textarea id="initialContext" class="form-control" rows="3" placeholder="Add system context...">{{ isset($defaultContext) ? $defaultContext : '' }}</textarea>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="saveToggle">
                        <label class="form-check-label" for="saveToggle">Save conversation</label>
                    </div>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="showTechnical">
                        <label class="form-check-label" for="showTechnical">Show technical details (admins only)</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Apply</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (required for modal) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      // Fallback: ensure the settings modal opens on click even if data attributes are ignored
      document.addEventListener('DOMContentLoaded', function(){
        var btn = document.getElementById('openSettings');
        var modalEl = document.getElementById('settingsModal');
        if (btn && modalEl && window.bootstrap && bootstrap.Modal){
          var modal = new bootstrap.Modal(modalEl);
          btn.addEventListener('click', function(ev){ ev.preventDefault(); modal.show(); });
        }
        // Open from parent request
        window.addEventListener('message', function(e){
          var data = e.data || {}; if(!modalEl || !window.bootstrap || !bootstrap.Modal) return;
          if(data.type === 'open_settings'){ var m = bootstrap.Modal.getOrCreateInstance(modalEl); m.show(); }
          if(data.type === 'clear'){ document.getElementById('clearChat').click(); }
        });
      });
    </script>

    <script>
    (function(){
        function getParam(name){ try{ return new URLSearchParams(window.location.search).get(name) }catch(e){ return null } }
        function storageKey(name){ return name + '_' + AI_USER_KEY; }
        function getOrCreateSession(){
            var sid = getParam('sid') || localStorage.getItem(storageKey('ai_chat_sid'));
            if(!sid){ sid = 'sess_' + Date.now() + '_' + Math.random().toString(36).slice(2,10); localStorage.setItem(storageKey('ai_chat_sid'), sid); }
            return sid;
        }
        const sessionId = getOrCreateSession();
        const modelSel = document.getElementById('modelSelect');
        const ctx = document.getElementById('initialContext');
        const saveToggle = document.getElementById('saveToggle');
        const showTechnical = document.getElementById('showTechnical');
        const msg = document.getElementById('messageInput');
        const sendBtn = document.getElementById('sendMessage');
        const messages = document.getElementById('chatMessages');
        const clearBtn = document.getElementById('clearChat');
        const charCount = document.getElementById('charCount');
        const statusAlert = document.getElementById('statusAlert');
        const statusText = document.getElementById('statusText');

        function updateStatus(){
            statusText.textContent = `Model: ${modelSel.value} | Save: ${saveToggle.checked ? 'On' : 'Off'}` + (ctx.value ? ' | Context: set' : '');
            statusAlert.style.display = 'block';
            setTimeout(()=>statusAlert.style.display='none', 4000);
            try{ localStorage.setItem(storageKey('ai_chat_settings'), JSON.stringify({ model:modelSel.value, save: !!saveToggle.checked, context: ctx.value, showTechnical: !!(showTechnical && showTechnical.checked) })); }catch(e){}
        }

        function addMessage(text, who){
            const wrap = document.createElement('div');
            wrap.className = 'message ' + who;
            const bubble = document.createElement('div');
            bubble.className = 'message-content';
            bubble.textContent = text;
            const time = document.createElement('span');
            time.className = 'message-time';
            time.textContent = new Date().toLocaleTimeString();
            wrap.appendChild(bubble); wrap.appendChild(time);
            messages.appendChild(wrap); messages.scrollTop = messages.scrollHeight;
        }

        function typing(on){
            let el = document.getElementById('typingIndicator');
            if (on){
                if (el) return; el = document.createElement('div'); el.id='typingIndicator'; el.className='message bot typing-indicator';
                const d=document.createElement('div'); d.className='typing-dots'; d.innerHTML='<div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div>';
                el.appendChild(d); messages.appendChild(el); messages.scrollTop = messages.scrollHeight;
            } else if (el){ el.remove(); }
        }

        async function fetchModels(){
            try{ const r = await fetch(AI_BASE + 'models'); const j = await r.json(); if (j.models){ modelSel.innerHTML=''; j.models.forEach(m=>{ const o=document.createElement('option'); o.value=m; o.textContent=m==='chatgpt'?'ChatGPT':'Gemini'; modelSel.appendChild(o); });}}
            catch(e){ console.error(e); }
        }

        function persistPair(user, bot){
            try{
                const key = storageKey('ai_conv_' + sessionId);
                const arr = JSON.parse(localStorage.getItem(key) || '[]');
                arr.push({u:user,b:bot,t:Date.now()});
                localStorage.setItem(key, JSON.stringify(arr));
            }catch(e){}
        }

        function restore(){
            try{
                // settings
                const s = JSON.parse(localStorage.getItem(storageKey('ai_chat_settings')) || '{}');
                if(s.model){ modelSel.value = s.model; }
                if(typeof s.save === 'boolean'){ saveToggle.checked = !!s.save; } else { saveToggle.checked = true; }
                if(s.context){ ctx.value = s.context; }
                if(showTechnical && typeof s.showTechnical === 'boolean'){ showTechnical.checked = !!s.showTechnical; }

                // history
                const key = storageKey('ai_conv_' + sessionId);
                const arr = JSON.parse(localStorage.getItem(key) || '[]');
                if(Array.isArray(arr)){
                    arr.forEach(m=>{ addMessage(m.u,'user'); addMessage(m.b,'bot'); });
                }
            }catch(e){}
        }

        async function send(){
            const text = msg.value.trim(); if (!text) return; addMessage(text,'user'); msg.value=''; charCount.textContent='0'; typing(true);
            try{
                const r = await fetch(AI_BASE + 'chat', { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({ session_id: sessionId, message: text, model_type: modelSel.value, save: !!saveToggle.checked, initial_context: ctx.value, show_technical: !!(showTechnical && showTechnical.checked) }) });
                const j = await r.json(); typing(false); if (j.error){ addMessage(j.error,'bot'); } else { const reply=(j.response||''); addMessage(reply, 'bot'); if(saveToggle.checked){ persistPair(text, reply); } }
            }catch(e){ typing(false); addMessage('Failed to send message.','bot'); }
        }

        sendBtn.addEventListener('click', send);
        msg.addEventListener('keydown', (e)=>{ if(e.ctrlKey && e.key==='Enter'){ e.preventDefault(); send(); }});
        msg.addEventListener('input', ()=>{ charCount.textContent = String(msg.value.length); });
        [modelSel, saveToggle, ctx, showTechnical].forEach(el=>{ if(!el) return; el.addEventListener('change', updateStatus); el.addEventListener('input', updateStatus); });
        clearBtn.addEventListener('click', async()=>{ try{ await fetch(AI_BASE + 'clear', { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({ session_id: sessionId, model_type: modelSel.value })}); messages.innerHTML=''; localStorage.removeItem(storageKey('ai_conv_' + sessionId)); }catch(e){} });

        fetchModels();
        restore();
    })();
    </script>
</body>
</html>

