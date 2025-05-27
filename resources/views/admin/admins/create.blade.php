@extends('layouts.admin.home')

<!-- title page -->
@section('title')
    <title>Admins</title>
@endsection

<!-- custom css -->
@section('css')
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-8">
            <h1 class="page-header">Add New Admin</h1>
        </div>
        <div class="col-lg-4">
            <div class="breadcrumb_container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin/index')}}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{route('admin/admins/index')}}/0/{{PAGINATION_COUNT}}">Admins</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add</li>
                </ol>
            </nav>
            </div>
        </div>
    </div>
    <!-- row -->
    <div class="row">
        <div class="col-lg-12">
            @include('flash::message')
            <div class="panel tabbed-panel panel-info">
                <div class="panel-heading clearfix">
                    <div class="panel-title pull-left">Admins Form</div>
                </div>
                <div class="panel-body">
                    <form role="form" action="{{url(route('admin/admins/create'))}}" method="post" enctype="multipart/form-data">
                        <div class="tab-content">
                            @csrf

                            <div class="form-group input-group">
                                <span class="input-group-addon" style="color: red;">*</span>
                                <input name="name" type="text" class="form-control" placeholder="Username">
                                @error('name')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon" style="color: red;">*</span>
                                <input name="email" type="email" class="form-control" placeholder="Email">
                                @error('email')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon" style="color: red;"></span>
                                <input name="phone" type="text" class="form-control" placeholder="Phone">
                                @error('phone')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <!-- <div class="form-group input-group">
                                <span class="input-group-addon" style="color: red;">*</span>
                                @error('role_id')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div> -->
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

                            <div class="form-group input-group">
                                <span class="input-group-addon" style="color: red;"></span>
                                <input name="photo" type="file" class="form-control" placeholder="Upload Image">
                                @error('photo')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-success">Submit Button</button>
                            <button type="reset" class="btn btn-primary">Reset Button</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

<!-- custom js -->
@section('script')
@endsection
