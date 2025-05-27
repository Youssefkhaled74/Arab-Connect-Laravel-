@extends('layouts.admin.home')

<!-- title page -->
@section('title')
    <title>PaymentMethods</title>
@endsection

<!-- custom css -->
@section('css')
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-8">
            {{-- <h1 class="page-header">PaymentMethod Edit</h1> --}}
        </div>
        <div class="col-lg-4">
            <div class="breadcrumb_container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin/index')}}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{route('admin/paymentMethods/index')}}/0/{{PAGINATION_COUNT}}">PaymentMethods</a></li>
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
                    <div class="panel-title pull-left">PaymentMethods Form</div>
                </div>
                <div class="panel-body">
                    @isset($paymentMethod)
                        <form role="form" action="{{url(route('admin/paymentMethods/update', $paymentMethod->id))}}" method="post" enctype="multipart/form-data">
                            <div class="tab-content">
                                @csrf
                                

                                <div class="form-group input-group">
                                    <span class="input-group-addon" style="color: red;">*</span>
                                    <input name="name" type="text" class="form-control" placeholder="name" value="{{$paymentMethod->name}}">
                                    @error('name')
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
