<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{asset('admin/plugins/fontawesome-free/css/all.min.css')}}">
<link rel="stylesheet" href="{{asset('admin/font-awesome/css/font-awesome.min.css')}}">

<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<!-- Tempusdominus Bootstrap 4 -->
<link rel="stylesheet" href="{{asset('admin/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
<!-- iCheck -->
<link rel="stylesheet" href="{{asset('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<!-- JQVMap -->
<link rel="stylesheet" href="{{asset('admin/plugins/jqvmap/jqvmap.min.css')}}">
<!-- Theme style -->
<link rel="stylesheet" href="{{asset('admin/dist/css/adminlte.min.css')}}">
<!-- overlayScrollbars -->
<link rel="stylesheet" href="{{asset('admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
<!-- Daterange picker -->
<link rel="stylesheet" href="{{asset('admin/plugins/daterangepicker/daterangepicker.css')}}">
<!-- summernote -->
<link rel="stylesheet" href="{{asset('admin/plugins/summernote/summernote-bs4.min.css')}}">
<link rel="stylesheet" href="{{asset('admin/plugins/sweetalert2/sweetalert2.css')}}">
<link rel="stylesheet" href="{{asset('admin/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">


<link rel="icon" href="{{asset('admin/dist/img/AdminLTELogo.png')}}" type="image/png">
<style>
    .select2-container .select2-selection--single {
        box-sizing: border-box;
        cursor: pointer;
        display: block;
        height: 36px;
        user-select: none;
        -webkit-user-select: none;
        border-radius: 7px;
    }


    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #530d9d;
        border-color: #530d9d;
        color: white!important;
        font-size: large;
        padding: 0 10px;
        font-weight: bolder;
        margin-top: 0.31rem;
    }
    .swal-button--confirm {
        background: #fb1717!important;;
    }

    .swal-button--cancel {
        background: #aaa;
    }

</style>
@stack('styles')

@vite(['resources/sass/app.scss', 'resources/js/app.js'])


