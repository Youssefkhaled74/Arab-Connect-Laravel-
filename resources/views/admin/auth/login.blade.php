<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>EGY PIN - LOGIN</title>

        <!-- Bootstrap Core CSS -->
        <link href="{{asset('admin/assets/css/bootstrap.min.css')}}" rel="stylesheet">

        <!-- MetisMenu CSS -->
        <link href="{{asset('admin/assets/css/metisMenu.min.css')}}" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="{{asset('admin/assets/css/startmin.css')}}" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="{{asset('admin/assets/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">

    </head>
    <body>

        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="login-panel panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Sign In</h3>
                        </div>
                        <div class="panel-body">
                            <form role="form" method="POST" action="{{url(route('admin/check-login'))}}">
                                <fieldset>
                                    @csrf
                                    <div class="form-group">
                                        <input class="form-control" placeholder="E-mail" name="email" type="email" autofocus>
                                        @error('email')
                                            <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                        @error('password')
                                            <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <!-- Change this to a button or input when using this as a form -->
                                    <button type="submit" class="btn btn-lg btn-success btn-block">Login</button>
                                </fieldset>
                            </form>
                            <br>
                            @include('flash::message')
                        </div>
                    </div>
                </div>
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

    </body>
</html>
