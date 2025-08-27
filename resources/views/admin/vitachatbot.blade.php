@extends('layouts.app')

@section('title') Vita Ai Chatbot @endsection

@section('css')
<style>
  .vitabot-bg{background:linear-gradient(135deg,#eef2ff 0%, #f8fafc 100%); border-radius:12px}
  .vitabot-card{border-radius:14px; overflow:hidden; box-shadow:0 14px 30px rgba(2,6,23,.12)}
  .vitabot-toolbar{display:flex;align-items:center;justify-content:space-between}
  .vitabot-title{font-weight:700; letter-spacing:.3px}
  .vitabot-actions .btn{border-radius:999px}
  .vitabot-frame{width:100%; height:calc(100vh - 260px); min-height:560px; border:0; border-radius:12px; box-shadow:0 10px 24px rgba(2,6,23,.12)}
  @media (max-width: 767.98px){ .vitabot-frame{height:calc(100vh - 300px)} }
  .badge-soft{background:#bfdbfe; color:#0f172a; border:1px solid #93c5fd}
</style>
@endsection

@section('breadcrumb')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark"><i class="far fa-comment-dots"></i> Vita Ai Chatbot</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Dashboard</a></li>
          <li class="breadcrumb-item active">Vita Ai Chatbot</li>
        </ol>
      </div>
    </div>
  </div>
  </div>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="vitabot-bg p-2 p-md-3">
      <div class="card vitabot-card">
        <div class="card-header vitabot-toolbar" style="background:#bfdbfe; color:#0f172a;">
          <div class="vitabot-actions ml-auto">
            <a id="vitabotFull" href="/ai-chat/embed" target="_blank" class="btn btn-outline-dark btn-sm mr-1"><i class="fas fa-expand"></i> Fullscreen</a>
            <button id="vitabotRefresh" class="btn btn-outline-dark btn-sm"><i class="fas fa-sync-alt"></i> Refresh</button>
          </div>
        </div>
        <div class="card-body">
          <iframe id="vitabotFrame" src="/ai-chat/embed" class="vitabot-frame"></iframe>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  (function(){
    function getOrCreateSid(){
      try{
        var sid = localStorage.getItem('ai_chat_sid');
        if(!sid){ sid = 'sess_' + Date.now() + '_' + Math.random().toString(36).slice(2,10); localStorage.setItem('ai_chat_sid', sid); }
        return sid;
      }catch(e){ return 'sess_' + Date.now(); }
    }
    var sid = getOrCreateSid();
    var btn = document.getElementById('vitabotRefresh');
    var frame = document.getElementById('vitabotFrame');
    var full = document.getElementById('vitabotFull');
    if(frame){ frame.src = '/ai-chat/embed?sid=' + encodeURIComponent(sid); }
    if(full){ full.href = '/ai-chat/embed?sid=' + encodeURIComponent(sid); }
    if(btn && frame){ btn.addEventListener('click', function(){
      try{ sid = localStorage.getItem('ai_chat_sid') || sid; }catch(e){}
      frame.src = '/ai-chat/embed?sid=' + encodeURIComponent(sid) + '&r=' + Date.now();
    }); }
    // Settings/Delete controls intentionally omitted on the admin page; use in-popup controls instead.
  })();
</script>
@endsection

