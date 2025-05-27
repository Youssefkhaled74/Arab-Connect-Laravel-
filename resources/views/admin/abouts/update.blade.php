@extends('layouts.admin.home')

<!-- title page -->
@section('title')
    <title>Abouts</title>
@endsection

<!-- custom css -->
@section('css')
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-8">
            <h1 class="page-header">About Edit</h1>
        </div>
        <div class="col-lg-4">
            <div class="breadcrumb_container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin/index')}}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{route('admin/abouts/index')}}/0/{{PAGINATION_COUNT}}">Abouts</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
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
                    <div class="panel-title pull-left">Abouts Form</div>
                </div>
                <div class="panel-body">
                    @isset($about)
                        <form role="form" action="{{url(route('admin/abouts/update', $about->id))}}" method="post" enctype="multipart/form-data">
                            <div class="tab-content">
                                @csrf

                                <div class="form-group input-group">
                                    <span class="input-group-addon" style="color: black;">content <span style="color: red;">*</span></span>
                                    <textarea class="form-control ckeditor content" name="content" placeholder="content">{!! $about->content !!}</textarea>
                                    @error('content')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="form-group input-group">
                                    <span class="input-group-addon" style="color: red;">*</span>
                                    <select class="form-control" name="type" id="types">
                                        <option value="">Type</option>
                                        <option value="1" {{(int)$about->type == 1 ? 'selected' : '' }}>about us</option>
                                        <option value="2" {{(int)$about->type == 2 ? 'selected' : '' }}>why us</option>
                                        <option value="3" {{(int)$about->type == 3 ? 'selected' : '' }}>what we offer</option>
                                    </select>
                                    @error('type')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="form-group input-group">
                                    <span class="input-group-addon" style="color: black;">photo <span style="color: red;"></span></span>
                                    <input name="photo" type="file" class="form-control" placeholder="Upload Image">
                                    @error('photo')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-success">Submit Button</button>
                            </div>
                        </form>
                    @endisset
                </div>
            </div>
        </div>
    </div>

@endsection

<!-- custom js -->
@section('script')
@endsection
