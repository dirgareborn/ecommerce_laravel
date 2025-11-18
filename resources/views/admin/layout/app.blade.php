<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> {{ $title ?? 'Admin'}} | Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ url('admin/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="{{ url('admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ url('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

  <!--   <link rel="stylesheet" href="{{ url('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
     -->
    <!-- <link rel="stylesheet" href="{{ url('admin/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}"> -->
    <link rel="stylesheet" href="{{ url('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="{{ url('admin/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- <link rel="stylesheet" href="{{ url('admin/plugins/summernote/summernote-bs4.min.css') }}"> -->
    <link rel="stylesheet" href="{{ url('admin/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- <link rel="stylesheet" href="{{ url('admin/plugins/jqvmap/jqvmap.min.css') }}"> -->

    <link rel="stylesheet" href="{{ url('admin/css/adminlte.min.css?v=3.2.0') }}">
    <!-- FullCalendar CSS -->
<!-- <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/main.min.css" rel="stylesheet" /> -->

<!-- FullCalendar JS -->
<!-- <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/main.min.js"></script> -->

    @stack('style')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{ url('logo_100x100.webp') }}" alt="AdminLogo" height="60" width="60">
        </div>
      @include('admin.layout.header')
      @include('admin.layout.sidebar')
        <div class="content-wrapper">
        @include('admin.layout.breadcrumb')
        @yield('content')
        </div>
        @include('admin.layout.footer')
        <aside class="control-sidebar control-sidebar-dark">
        </aside>
    </div>


    <script src="{{ url('admin/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('admin/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <script src="{{ url('admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- <script src="{{ url('admin/plugins/chart.js/Chart.min.js') }}"></script> -->
    <!-- <script src="{{ url('admin/plugins/sparklines/sparkline.js') }}"></script> -->
    <!-- <script src="{{ url('admin/plugins/jqvmap/jquery.vmap.min.js') }}"></script> -->
    <!-- <script src="{{ url('admin/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script> -->
    <!-- <script src="{{ url('admin/plugins/jquery-knob/jquery.knob.min.js') }}"></script> -->
    <!-- <script src="{{ url('admin/plugins/moment/moment.min.js') }}"></script> -->
    <script src="{{ url('admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ url('admin/js/custom.js') }}"></script>
    <!-- <script src="{{ url('admin/plugins/daterangepicker/daterangepicker.js') }}"></script> -->
    <!-- <script src="{{ url('admin/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script> -->
    <!-- <script src="{{ url('admin/plugins/summernote/summernote-bs4.min.js') }}"></script> -->
    <!-- <script src="{{ url('admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script> -->
    <script src="{{ url('admin/js/adminlte.js?v=3.2.0') }}"></script>
    <!-- <script src="{{ url('admin/js/demo.js') }}"></script> -->
    <script src="{{ url('admin/js/pages/dashboard.js') }}"></script>
    <!-- <script src="{{ url('admin/js/datatables.js') }}"></script> -->

    <!-- DataTables  & Plugins -->
<script src="{{ url('admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ url('admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ url('admin/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.12.2/sweetalert2.min.js" integrity="sha512-JWPRTDebuCWNZTZP+EGSgPnO1zH4iie+/gEhIsuotQ2PCNxNiMfNLl97zPNjDVuLi9UWOj82DEtZFJnuOdiwZw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- <script src="{{ url('admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ url('admin/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ url('admin/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ url('admin/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ url('admin/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ url('admin/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ url('admin/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ url('admin/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ url('admin/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script> -->

@stack('scripts')
<!-- <script src="https://js.pusher.com/7.2/pusher.min.js"></script> -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('fullscreenBtn');
    const icon = btn.querySelector('i');

    // Fungsi toggle fullscreen
    function toggleFullscreen() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen();
        } else {
            document.exitFullscreen();
        }
    }

    // Event click untuk tombol
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        toggleFullscreen();
    });

    // Event untuk update icon dan localStorage
    document.addEventListener('fullscreenchange', function() {
        if (document.fullscreenElement) {
            icon.classList.remove('fa-expand-arrows-alt');
            icon.classList.add('fa-compress-arrows-alt');
            localStorage.setItem('fullscreen', '1');
        } else {
            icon.classList.remove('fa-compress-arrows-alt');
            icon.classList.add('fa-expand-arrows-alt');
            localStorage.setItem('fullscreen', '0');
        }
    });

    // Restore status fullscreen jika sebelumnya aktif
    if (localStorage.getItem('fullscreen') === '1' && !document.fullscreenElement) {
        document.documentElement.requestFullscreen().catch(() => {
            localStorage.setItem('fullscreen', '0'); // jika gagal
        });
    }
});
</script>
</body>

</html>