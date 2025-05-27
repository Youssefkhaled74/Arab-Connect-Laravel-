@extends('layouts.admin.home')

<!-- title page -->
@section('title')
    <title>Blogs</title>
@endsection

<!-- custom css -->
@section('css')
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-8">
            {{-- <h1 class="page-header">Add New Blog</h1> --}}
        </div>
        <div class="col-lg-4">
            <div class="breadcrumb_container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin/index')}}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{route('admin/blogs/index')}}/0/{{PAGINATION_COUNT}}">Blogs</a></li>
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
                    <div class="panel-title pull-left">Blogs Form</div>
                </div>
                <div class="panel-body">
                    <form role="form" action="{{url(route('admin/blogs/create'))}}" method="post" enctype="multipart/form-data">
                        <div class="tab-content">
                            @csrf
                            <div class="form-group input-group">
                                <span class="input-group-addon" style="color: black;">Title <span style="color: red;">*</span></span>
                                <textarea class="form-control content" name="title" placeholder="Title"></textarea>
                                @error('title')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon" style="color: red;">*</span>
                                <input name="slug" type="text" class="form-control" placeholder="slug">
                                @error('slug')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon" style="color: black;">Description <span style="color: red;">*</span></span>
                                <textarea class="form-control ckeditor content" name="description" placeholder="Description"></textarea>
                                @error('description')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon" style="color: red;">*</span>
                                <select class="form-control" name="category_id" id="categories">
                                    <option value="">Categories</option>
                                </select>
                                @error('category_id')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon" style="color: red;"></span>
                                <input name="meta_title" type="text" class="form-control" placeholder="meta_title">
                                @error('meta_title')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon" style="color: red;"></span>
                                <input name="meta_description" type="text" class="form-control" placeholder="meta_description">
                                @error('meta_description')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon" style="color: red;"></span>
                                <input name="meta_tags" type="text" class="form-control" placeholder="meta_tags">
                                @error('meta_tags')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon" style="color: red;"></span>
                                <input name="meta_keywords" type="text" class="form-control" placeholder="meta_keywords">
                                @error('meta_keywords')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="form-group input-group">
                                <span class="input-group-addon" style="color: black;">photos <span style="color: red;"></span></span>
                                <input name="photos[]" type="file" class="form-control" placeholder="Upload Image">
                                @error('photos')
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
<script>
    $('#categories').select2({
        ajax: {
            url: "{{ route('get/categories') }}",
            dataType: 'json',
            processResults: function (data) {
                return {
                    results:  $.map(data, function (item) {
                        return {
                            id: item.id,
                            text: item.name
                        }
                    })
                };
            },
            cache: true
        }
    });
</script>
@endsection
