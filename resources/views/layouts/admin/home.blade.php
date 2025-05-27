<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        @yield('title')
        @yield('css')
        <!-- Bootstrap Core CSS -->
        <link href="{{asset('admin/assets/css/bootstrap.min.css')}}" rel="stylesheet">

        <!-- MetisMenu CSS -->
        <link href="{{asset('admin/assets/css/metisMenu.min.css')}}" rel="stylesheet">

        <!-- Timeline CSS -->
        <link href="{{asset('admin/assets/css/timeline.css')}}" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="{{asset('admin/assets/css/startmin.css')}}" rel="stylesheet">

        <!-- Morris Charts CSS -->
        <link href="{{asset('admin/assets/css/morris.css')}}" rel="stylesheet">

        <!-- Morris Charts CSS -->
        <link href="{{asset('admin/assets/css/morris.css')}}" rel="stylesheet">

        <!-- select_2 CSS -->
        <link href="{{asset('admin/assets/css/select_2.css')}}" rel="stylesheet">
        
        <!-- Custom Fonts -->
        <link href="{{asset('admin/assets/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">

    </head>
    <body>

        <div id="wrapper">
            <!-- Page Header Start-->
            @include('layouts.admin.navbar')
                <!-- Page Body Start -->
                <div id="page-wrapper">
                    @yield('content')
                </div>
        </div>

        <!-- jQuery -->
        <script src="{{asset('admin/assets/js/jquery.min.js')}}"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="{{asset('admin/assets/js/bootstrap.min.js')}}"></script>

        <!-- Metis Menu Plugin JavaScript -->
        <script src="{{asset('admin/assets/js/metisMenu.min.js')}}"></script>

        <!-- Custom Theme JavaScript -->
        <script src="{{asset('admin/assets/js/startmin.js')}}"></script>

        <!-- select_2 JavaScript -->
        <script src="{{asset('admin/assets/js/select_2.js')}}"></script>

        <!-- ckeditor JavaScript -->
        <script type="text/javascript" src="{{asset('ckeditor/ckeditor.js')}}"></script>
        @yield('script')

    </body>
</html>
