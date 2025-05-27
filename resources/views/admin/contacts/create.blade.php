@extends('layouts.admin.home')

<!-- title page -->
@section('title')
    <title>Contacts</title>
@endsection

<!-- custom css -->
@section('css')
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-8">
            <h1 class="page-header">Add New Contact</h1>
        </div>
        <div class="col-lg-4">
            <div class="breadcrumb_container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin/index')}}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{route('admin/contacts/index')}}/0/{{PAGINATION_COUNT}}">Contacts</a></li>
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
                    <div class="panel-title pull-left">Contacts Form</div>
                </div>
                <div class="panel-body">
                    <form role="form" action="{{url(route('admin/contacts/create'))}}" method="post" enctype="multipart/form-data">
                        <div class="tab-content">
                            @csrf
                            <!-- <div class="form-group input-group">
                                <span class="input-group-addon" style="color: red;">*</span>
                                <input name="name" type="text" class="form-control" placeholder="name">
                                @error('name')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon" style="color: red;">*</span>
                                <input name="quantity" type="number" class="form-control" placeholder="Quantity">
                                @error('quantity')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon" style="color: red;"></span>
                                <input name="special_price_end" type="date" class="form-control" placeholder="Special Price End">
                                @error('special_price_end')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon" style="color: red;">*</span>
                                <select class="form-control" name="brand_id" id="brands">
                                    <option value="">Brands</option>
                                </select>
                                @error('brand_id')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div> -->
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
    // $('#brands').select2({
    //     ajax: {
    //         url: "{{  }}",
    //         dataType: 'json',
    //         processResults: function (data) {
    //             return {
    //                 results:  $.map(data, function (item) {
    //                     return {
    //                         id: item.id,
    //                         text: item.title
    //                     }
    //                 })
    //             };
    //         },
    //         cache: true
    //     }
    // });
</script>
@endsection
