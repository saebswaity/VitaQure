<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>{{$info['name']}} | @yield('title')</title>
<link rel="apple-touch-icon" sizes="57x57" href="{{url('img/apple-icon-57x57.png')}}">
<link rel="apple-touch-icon" sizes="60x60" href="{{url('img/apple-icon-60x60.png')}}">
<link rel="apple-touch-icon" sizes="72x72" href="{{url('img/apple-icon-72x72.png')}}">
<link rel="apple-touch-icon" sizes="76x76" href="{{url('img/apple-icon-76x76.png')}}">
<link rel="apple-touch-icon" sizes="114x114" href="{{url('img/apple-icon-114x114.png')}}">
<link rel="apple-touch-icon" sizes="120x120" href="{{url('img/apple-icon-120x120.png')}}">
<link rel="apple-touch-icon" sizes="144x144" href="{{url('img/apple-icon-144x144.png')}}">
<link rel="apple-touch-icon" sizes="152x152" href="{{url('img/apple-icon-152x152.png')}}">
<link rel="apple-touch-icon" sizes="180x180" href="{{url('img/apple-icon-180x180.png')}}">
<link rel="icon" type="image/png" sizes="192x192" href="{{url('img/android-icon-192x192.png')}}">
<link rel="icon" type="image/png" sizes="32x32" href="{{url('img/favicon-32x32.png')}}">
<link rel="icon" type="image/png" sizes="96x96" href="{{url('img/favicon-96x96.png')}}">
<link rel="icon" type="image/png" sizes="16x16" href="{{url('img/favicon-16x16.png')}}">
<link rel="manifest" href="{{url('img/manifest.json')}}">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="{{url('img/ms-icon-144x144.png')}}">
<meta name="theme-color" content="#ffffff">
<!-- Tell the browser to be responsive to screen width -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{url('plugins/fontawesome-free/css/all.min.css')}}">
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<!-- Tempusdominus Bbootstrap 4 -->
<link rel="stylesheet" href="{{url('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
<!-- iCheck -->
<link rel="stylesheet" href="{{url('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<!-- JQVMap -->
<link rel="stylesheet" href="{{url('plugins/jqvmap/jqvmap.min.css')}}">
<!-- Theme style -->
<link rel="stylesheet" href="{{url('dist/css/adminlte.min.css')}}">
<!-- overlayScrollbars -->
<link rel="stylesheet" href="{{url('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
<!-- Daterange picker -->
<link rel="stylesheet" href="{{url('plugins/daterangepicker/daterangepicker.css')}}">
<!-- summernote -->
<link rel="stylesheet" href="{{url('plugins/summernote/summernote-bs4.css')}}">
<!-- Google Font: Source Sans Pro -->
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
<!-- Datatables -->
<link rel="stylesheet" href="{{url('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{url('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{url('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('plugins/datatables-fixedheader/css/fixedHeader.bootstrap4.min.css')}}">
<!-- toastr css -->
<link rel="stylesheet" href="{{ URL::asset('css/toastr.min.css')}}">
<!-- select2 css -->
<link rel="stylesheet" href="{{ url('css/select2.css')}}" type="text/css">
<!-- jquery ui -->
<link rel="stylesheet" href="{{url('plugins/jquery-ui/jquery-ui.min.css')}}">
<!-- sweetalert -->
<link rel="stylesheet" href="{{url('plugins/sweet-alert/sweetalert.css')}}">
<!-- RTL -->
@if(session('rtl'))
<link rel="stylesheet" href="{{url('css/rtl.css')}}">
<link rel="stylesheet" href="{{url('css/bootstrap-rtl.min.css')}}">
@endif

<!-- Custom sidebar color override -->
<style>
  /* Make sidebar gray and keep text readable */
  .main-sidebar {
    background-color: #1e3a8a !important; /* deep indigo */
  }
  .main-sidebar .brand-link {
    background-color: #1e3a8a !important;
    border-bottom: 1px solid rgba(255,255,255,0.1) !important;
  }
  .main-sidebar .nav-sidebar > .nav-item > .nav-link,
  .main-sidebar .brand-text,
  .main-sidebar .nav-header {
    color: #ffffff !important;
  }
  .main-sidebar .nav-sidebar > .nav-item > .nav-link.active {
    background-color: rgba(255,255,255,0.12) !important;
    color: #ffffff !important;
  }
  /* Header and footer match sidebar color */
  .main-header.navbar {
    background-color: #1e3a8a !important;
  }
  .main-footer {
    background-color: #1e3a8a !important;
    color: #ffffff !important;
    border-top: none !important;
  }
  .main-footer a, .main-footer h6, .main-footer strong { color: #ffffff !important; }
  .main-footer .social img { filter: none !important; }
</style>

@yield('css')
<style>
  /* Floating Chat Widget */
  .vg-chat-fab{position:fixed;right:18px;bottom:18px;z-index:1050;width:56px;height:56px;border-radius:50%;background:#3b82f6;color:#fff;display:flex;align-items:center;justify-content:center;box-shadow:0 8px 24px rgba(0,0,0,.2);cursor:pointer}
  .vg-chat-fab:hover{filter:brightness(.95)}
  .vg-chat-modal{position:fixed;right:18px;bottom:84px;width:400px;max-width:92vw;height:560px;max-height:82vh;background:#fff;border-radius:14px;box-shadow:0 12px 36px rgba(0,0,0,.25);display:none;flex-direction:column;z-index:1050;overflow:hidden}
  .vg-chat-header{background:#1e3a8a;color:#fff;padding:10px 12px;display:flex;align-items:center;justify-content:flex-end;position:relative}
  .vg-chat-title{position:absolute;left:0;right:0;text-align:center;font-weight:700;cursor:pointer}
  .vg-chat-controls{display:flex;gap:8px;align-items:center}
  .vg-chat-controls select,.vg-chat-controls input[type="checkbox"],.vg-chat-controls button{font-size:.85rem}
  .vg-chat-body{flex:1;display:flex;flex-direction:column}
  .vg-chat-context{padding:8px;border-bottom:1px solid #e5e7eb}
  .vg-chat-context textarea{width:100%;height:70px;font-size:.85rem;border:1px solid #e5e7eb;border-radius:8px;padding:6px}
  .vg-chat-iframe{border:0;flex:1}
  .vg-badge-pill{background:#e5e7eb;color:#111827;border-radius:999px;padding:2px 8px;font-size:.75rem}
  .vg-close-btn{width:32px;height:32px;display:flex;align-items:center;justify-content:center;background:transparent;border:none;color:#fff;border-radius:6px;opacity:.9;transition:background .2s,opacity .2s}
  .vg-close-btn:hover{background:rgba(255,255,255,.15);opacity:1}
  .vg-close-btn i{pointer-events:none}
</style>