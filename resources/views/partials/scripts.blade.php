<!-- jQuery -->
<script src="{{url('plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{url('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{url('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{url('plugins/chart.js/Chart.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{url('plugins/sparklines/sparkline.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{url('plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{url('plugins/moment/moment.min.js')}}"></script>
<script src="{{url('plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{url('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- Summernote -->
<script src="{{url('plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{url('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{url('dist/js/adminlte.js')}}"></script>
<!-- DataTables -->
<script src="{{url('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{url('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{url('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{url('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{url('plugins/datatables-fixedheader/js/dataTables.fixedHeader.min.js')}}" type="text/javascript"></script>
<script src="{{url('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{url('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{url('plugins/jszip/jszip.min.js')}}"></script>
<script src="{{url('plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{url('plugins/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{url('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{url('plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{url('plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
<!-- Toastr-->
<script src="{{ url('js/toastr.min.js')}}"></script>
<!-- Validate -->
<script src="{{url('plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{url('plugins/print/jQuery.print.min.js')}}"></script>
<script src="{{url('js/jquery.classyqr.min.js')}}"></script>
<script src="{{url('js/select2.js')}}"></script>
<script src="{{url('plugins/sweet-alert/sweetalert.min.js')}}"></script>
<!-- Scripts Translation -->
<script>
  var translations = `{!! session("trans") !!}`;

  function trans(key) {
    var trans = JSON.parse(translations);
    return (trans[key] != null ? trans[key] : key);
  }
</script>
@if (!request()->is('admin/vitachatbot'))
<!-- Floating Chat Widget -->
<div id="vgChatFab" class="vg-chat-fab" title="AI Chat">
  <i class="fas fa-comments"></i>
  <span id="vgChatBadge" class="vg-badge-pill" style="display:none; position:absolute; top:-6px; right:-6px;">!</span>
</div>
<div id="vgChatModal" class="vg-chat-modal">
  <div class="vg-chat-header">
    <div class="vg-chat-title">Vita Ai Chatbot</div>
    <div class="vg-chat-controls">
      <button id="vgCloseBtn" class="vg-close-btn" aria-label="Close"><i class="fas fa-times"></i></button>
    </div>
  </div>
  <div class="vg-chat-body">
    <iframe id="vgChatFrame" class="vg-chat-iframe" src="/ai-chat/embed"></iframe>
  </div>
 </div>

<script>
  (function(){
    var fab = document.getElementById('vgChatFab');
    var modal = document.getElementById('vgChatModal');
    var closeBtn = document.getElementById('vgCloseBtn');
    var frame = document.getElementById('vgChatFrame');
    var title = document.querySelector('.vg-chat-title');
    var ctx = null;
    if(!fab || !modal) return;

    function open(){ modal.style.display = 'flex'; }
    function close(){ modal.style.display = 'none'; }

    fab.addEventListener('click', function(){ open(); });
    closeBtn && closeBtn.addEventListener('click', close);
    // Title is decorative only; no navigation on click
    
  })();
</script>
@endif
<!-- Sidebar hover push behavior -->
<script>
  (function(){
    var body = document.body;
    var sidebar = document.querySelector('.main-sidebar');
    var header = document.querySelector('.main-header');
    var footer = document.querySelector('.main-footer');
    var content = document.querySelector('.content-wrapper');
    if(!sidebar || !content){ return; }

    var isMini = function(){ return body.classList.contains('sidebar-collapse'); };
    var isDesktop = function(){ return window.innerWidth >= 992; };

    // 2) Default state: ensure sidebar is open on load (desktop)
    document.addEventListener('DOMContentLoaded', function(){
      if (isDesktop()) {
        body.classList.remove('sidebar-collapse');
      }
    });

    // Fixed open width and gap so hover shift is exactly 274px (250 + 24)
    var OPEN_WIDTH = 250; // px
    var GAP = 170; // px (white spacing)
    var CONTENT_SHIFT = OPEN_WIDTH + GAP; // 420px

    var applyShift = function(on){
      if(on){
        body.classList.add('sidebar-hover-open');
        if(header){ header.style.transition = 'margin-left .3s ease'; header.style.marginLeft = CONTENT_SHIFT + 'px'; header.style.borderLeft = GAP + 'px solid #ffffff'; }
        content.style.transition = 'margin-left .3s ease';
        content.style.marginLeft = CONTENT_SHIFT + 'px';
        content.style.borderLeft = GAP + 'px solid #ffffff';
        if(footer){ footer.style.transition = 'margin-left .3s ease'; footer.style.marginLeft = CONTENT_SHIFT + 'px'; footer.style.borderLeft = GAP + 'px solid #ffffff'; }
      } else {
        body.classList.remove('sidebar-hover-open');
        if(header){ header.style.marginLeft = ''; header.style.borderLeft = ''; }
        content.style.marginLeft = '';
        content.style.borderLeft = '';
        if(footer){ footer.style.marginLeft = ''; footer.style.borderLeft = ''; }
      }
    };

    sidebar.addEventListener('mouseenter', function(){ if(isMini() && isDesktop()) applyShift(true); });
    sidebar.addEventListener('mouseleave', function(){ if(isMini() && isDesktop()) applyShift(false); });

    // Respect pushmenu button (open/close on click)
    var toggleBtn = document.querySelector('[data-widget="pushmenu"]');
    if(toggleBtn){
      toggleBtn.addEventListener('click', function(e){
        // let AdminLTE handle its classes first
        setTimeout(function(){
          // clear any hover inline offsets when toggled
          applyShift(false);
        }, 0);
      });
    }
  })();
  
</script>
<!-- Main dashboard -->
@if(auth()->guard('admin')->check())
<script src="{{ url('js/admin/main.js')}}"></script>
@else
<script src="{{ url('js/patient/main.js')}}"></script>
@endif
<!-- Flash messages -->
<script>
  @if(session()->has('success'))
    toastr_success(trans("{{Session::get('success')}}"));
  @endif
  
  @if(Session::has('failed') || session()->has('errors'))
    toastr_error(trans("{{Session::get('failed')}}"));
  @endif
  
</script>
<!-- AI chat cleanup on logout -->
<script>
  (function(){
    try{
      var AI_USER_KEY = @json(auth()->guard('admin')->id() ? ('admin_' . auth()->guard('admin')->id()) : 'guest');
      function clearAIChatLocalStorage(userKey){
        try{
          var suffix = '_' + userKey;
          var keysToRemove = [];
          for (var i = 0; i < localStorage.length; i++){
            var k = localStorage.key(i);
            if(!k) continue;
            if (k.endsWith(suffix) || (k.indexOf('ai_conv_') === 0 && k.endsWith(suffix))){
              keysToRemove.push(k);
            }
          }
          keysToRemove.forEach(function(k){ localStorage.removeItem(k); });
        }catch(e){}
      }

      function attachLogoutHooks(){
        document.addEventListener('click', function(ev){
          var a = ev.target && ev.target.closest ? ev.target.closest('a[href]') : null;
          if(!a) return;
          var href = a.getAttribute('href') || '';
          if(href && href.toLowerCase().indexOf('logout') !== -1){
            clearAIChatLocalStorage(AI_USER_KEY);
          }
        });
        document.addEventListener('submit', function(ev){
          var form = ev.target;
          if(!form || !form.getAttribute) return;
          var action = (form.getAttribute('action') || '').toLowerCase();
          if(action.indexOf('logout') !== -1){
            clearAIChatLocalStorage(AI_USER_KEY);
          }
        });
      }

      attachLogoutHooks();
      window.clearAIChatLocalStorage = clearAIChatLocalStorage;
    }catch(e){}
  })();
</script>