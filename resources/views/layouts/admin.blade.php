<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Yasir Qadhi | Dashboard</title>

    @include('partials.header')
    <style>
        [class*=sidebar-dark-] {
            background-color: #0c1f32;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

{{--    <!-- Preloader -->--}}
{{--    <div class="preloader flex-column justify-content-center align-items-center">--}}
{{--        <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">--}}
{{--    </div>--}}
    @include('sweetalert::alert')

    @include('partials.navbar')
    <!-- Main Sidebar Container -->

    @include('partials.sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

{{--        @include('admin.dashboard')--}}

          @yield('content')

    </div>
    <!-- /.content-wrapper -->
    @include('partials.modal')


    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->


</div>
<!-- ./wrapper -->

@include('partials.footer')
{{--Scripts--}}
@include('partials.scripts')
</body>
</html>
