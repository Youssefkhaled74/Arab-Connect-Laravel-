@extends('layouts.admin.home')

<!-- title page -->
@section('title')
    <title>Admins</title>
@endsection

<!-- custom page -->
@section('css')
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-8">
            <h1 class="page-header">Update Information</h1>
        </div>
        <div class="col-lg-4">
            <div class="breadcrumb_container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin/index')}}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{route('admin/admins/index')}}/0/{{PAGINATION_COUNT}}">Admins</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Info</li>
                </ol>
            </nav>
            </div>
        </div> 
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Admin Form
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @include('flash::message')
                            <div class="panel tabbed-panel panel-info">
                                <div class="panel-heading clearfix">
                                    <div class="panel-title pull-left">Tabbed Panel Info</div>
                                    <div class="pull-right">
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a href="#basic_info" data-toggle="tab">Basic Information</a></li>
                                            <li><a href="#change_pass" data-toggle="tab">Change Password</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="tab-content">
                                        <div class="tab-pane fade in active" id="basic_info">
                                            <form role="form" action="{{url(route('admin/admins/info-update'))}}" method="post" enctype="multipart/form-data">
                                                @csrf
                                                <div class="form-group input-group">
                                                    <span class="input-group-addon" style="color: red;">*</span>
                                                    <input name="name" type="text" class="form-control" placeholder="Username" value="{{$admin->name}}">
                                                    @error('name')
                                                        <span class="text-danger">{{$message}}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group input-group">
                                                    <span class="input-group-addon" style="color: red;">*</span>
                                                    <input name="email" type="email" class="form-control" placeholder="Email" value="{{$admin->email}}">
                                                    @error('email')
                                                        <span class="text-danger">{{$message}}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group input-group">
                                                    <span class="input-group-addon" style="color: red;"></span>
                                                    <input name="phone" type="text" class="form-control" placeholder="Phone" value="{{$admin->phone}}">
                                                    @error('phone')
                                                        <span class="text-danger">{{$message}}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group input-group">
                                                    <span class="input-group-addon" style="color: red;"></span>
                                                    <input name="photo" type="file" class="form-control" placeholder="Upload Image">
                                                    @error('photo')
                                                        <span class="text-danger">{{$message}}</span>
                                                    @enderror
                                                </div>
                                                <button type="submit" class="btn btn-success">Submit Button</button>
                                            </form>
                                        </div>
                                        <div class="tab-pane fade" id="change_pass">
                                            <form role="form" action="{{url(route('admin/admins/change-password'))}}" method="post">
                                                @csrf
                                                <div class="form-group input-group">
                                                    <span class="input-group-addon" style="color: red;">*</span>
                                                    <input name="old_password" type="password" class="form-control" placeholder="Old Password">
                                                    @error('old_password')
                                                        <span class="text-danger">{{$message}}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group input-group">
                                                    <span class="input-group-addon" style="color: red;">*</span>
                                                    <input name="password" type="password" class="form-control" placeholder="Password">
                                                    @error('password')
                                                        <span class="text-danger">{{$message}}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group input-group">
                                                    <span class="input-group-addon" style="color: red;">*</span>
                                                    <input name="password_confirmation" type="password" class="form-control" placeholder="Password Confirmation">
                                                    @error('password_confirmation')
                                                        <span class="text-danger">{{$message}}</span>
                                                    @enderror
                                                </div>
                                                <button type="submit" class="btn btn-success">Submit Button</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.col-lg-6 -->
                    </div>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>

@endsection

<!-- custom js -->
@section('script')
@endsection