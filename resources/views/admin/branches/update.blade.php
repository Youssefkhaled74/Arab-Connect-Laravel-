@extends('layouts.admin.home')

@section('title')
    <title>Edit Category</title>
@endsection

@section('css')
  
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <h1 class="page-header">Category Edit</h1>
        </div>
        <div class="col-lg-4">
            <div class="breadcrumb_container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin/index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin/branches/index') }}/0/{{ PAGINATION_COUNT }}">Branches</a></li>
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
                    <div class="panel-title pull-left">Branches Form</div>
                </div>
                <div class="panel-body">
                    @isset($branch)
                        <form role="form" action="{{ url(route('admin/branches/update', $branch->id)) }}" method="post" enctype="multipart/form-data">
                            <div class="tab-content">
                                @csrf
                                <div class="form-group">
                                    <label for="category">Category</label>
                                    <select name="category_id" id="categories" class="form-control">
                                        <option value="{{$branch->category_id}}">{{$branch->category->name}}</option>
                                    </select>
                                    @error('category_id')
                                        <span class="text-danger">{{ $message }}</span>
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

@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    
    <script>
        $(document).ready(function() {
            console.log(111);
            
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
        });
    </script>
@endsection
