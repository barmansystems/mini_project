<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow, noarchive">
    <title>پنل مدیریت | هلدینگ برادران مشرفی</title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{asset('/plugins/font-awesome/css/font-awesome.min.css')}}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('/dist/css/adminlte.min.css')}}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <!-- bootstrap rtl -->
    <link rel="stylesheet" href="{{asset('/dist/css/bootstrap-rtl.min.css')}}">
    <!-- template rtl version -->
    <link rel="stylesheet" href="{{asset('/dist/css/custom-style.css')}}">
    @yield('css-styles')


</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <!-- Navbar -->
    @include("partials.navbar")
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    @include("partials.sidebar")

    <!-- Content Wrapper. Contains page content -->
    @yield('content')
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    {{--    <aside class="control-sidebar control-sidebar-dark">--}}
    {{--        <!-- Control sidebar content goes here -->--}}
    {{--        <div class="p-3">--}}
    {{--            <h5>Title</h5>--}}
    {{--            <p>Sidebar content</p>--}}
    {{--        </div>--}}
    {{--    </aside>--}}
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    @include("partials.footer")
</div>
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('dist/js/adminlte.min.js')}}"></script>
@include('sweetalert::alert')
@yield('js-script')
</body>
</html>
