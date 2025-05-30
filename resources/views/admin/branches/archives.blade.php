@extends('layouts.admin.home')

<!-- title page -->
@section('title')
    <title>Branches</title>
@endsection

<!-- custom css -->
@section('css')
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-8">
            <h1 class="page-header">Branches</h1>
        </div>
        <div class="col-lg-4">
            <div class="breadcrumb_container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin/index')}}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{route('admin/branches/index')}}/0/{{PAGINATION_COUNT}}">Branches</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Archive</li>
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
                    Branches Viwes
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    @include('flash::message')
                    <div class="row">
                        <div class="col-sm-6"></div>
                        <div class="col-sm-6 text-right">
                            <div id="dataTables-example_filter" class="dataTables_filter">
                                <label><input placeholder="search" type="search" class="form-control input-sm data_search" aria-controls="dataTables-example"></label>
                                <!-- <label>
                                    <div class="form-group">
                                        <select class="form-control data_search" name="job_id" id="jobs" aria-controls="dataTables-example" style="width: 220px">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                </label> -->
                            </div>
                        </div>
                    </div>
                    <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Activation</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="tableShowData">
                                @isset($branches)
                                    @foreach($branches as $branch)
                                        <tr class="odd gradeX">
                                            <td>{{$branch->id}}</td>
                                            <td>{{$branch->name}}</td>
                                            <!-- <td>
                                                <div class="ml-2 d-flex">
                                                    <img src="{{asset($branch->img)}}" alt="admin image"
                                                        class="img-fluid img-50 rounded-circle blur-up lazyloaded" width="100">
                                                </div>
                                            </td> -->
                                            <?php
                                                if($branch->is_activate == 1){$activate = '<span class="badge badge-info">active</span>';}
                                                else{$activate = '<span class="badge badge-danger">un active</span>';}
                                            ?>
                                            <td class="center">{!! $activate !!}</td>
                                            <td class="center">
                                                <ul class="nav navbar-center navbar-top-links" style="border-radius: 15px;">
                                                    <li class="dropdown">
                                                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                                             actions <b class="caret"></b>
                                                        </a>
                                                        <ul class="dropdown-menu dropdown-user">
                                                            <li >
                                                                <button class="dropdown-item btn btn-danger openBackFrom" data-toggle="modal" data-target="#myModalBack" data-id="{{$branch->id}}">
                                                                    back
                                                                </button>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endisset
                            </tbody>
                        </table>
                        <div style="margin-top: 20px; font-weight: 600; font-size: 16px;">
                            Showing 1 to <span id="showItems"></span> of <span>{{App\Models\Branch::archive()->count()}}</span> entries
                        </div>
                        <div class="ltn__pagination-area text-center mt-5">
                            <div class="ltn__pagination text-center">
                                <div id="load_more">
                                    <button type="button" name="load_more_button" style="width: 350px;" class="btn btn-info form-control px-5" data-id="'.$last_id.'" id="load_more_button">عرض المزيد</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="myModalBack" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabell"
                        aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title f-w-600" id="exampleModalLabell">Back Confirmation</h5>
                                </div>
                                <div class="modal-body">
                                    <form role="form" action="{{url(route('admin/branches/back'))}}" method="get">
                                        {{ csrf_field() }}
                                        <p>Are You Sure To Update This Record ?</p>
                                        <input id="back_record_id" name="record_id" type="hidden">
                                        <div class="modal-footer">
                                            <button class="btn btn-primary" type="submit">Sure</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
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
    <script>
        $(document).on('click', '.openBackFrom', function() {
            var id = $(this).attr('data-id');
            $('#back_record_id').val(id);
        });
    </script>
    <script>
        var _token = $('input[name="_token"]').val();
        var offset = <?php echo PAGINATION_COUNT ?>;
        var limit = <?php echo PAGINATION_COUNT ?>;
        let showItems = document.getElementById("showItems");
        showItems.innerHTML = limit
        let length = limit

        $(document).ready(function() {
            $(document).on('click', '#load_more_button', function() {
                let records = ``
                $('#load_more_button').html('<b>Loading... </b>');
                load_data(_token);

                function load_data(_token) {
                    $.ajax({
                        url: `{{ route("admin/branches/pagination/archives")}}/${offset}/${limit}`,
                        method: "POST",
                        data: {
                            _token: _token,
                        },
                        success: function(data) {
                            if (data.length > 0) {
                                for (let i = 0; i < data.length; i++) {
                                    image_path =  "{{ asset('') }}" + data[i].img;
                                    edit_route =  "{{ route('admin/branches/edit') }}" + '/' + data[i].id;
                                    records += `
                                        <tr>
                                            <td>${data[i].id}</td>
                                            <td>${data[i].name}</td>
                                            <!-- <td>
                                                <div class="ml-2 d-flex">
                                                    <img src="${image_path}" alt="admin image"
                                                        class="img-fluid img-50 rounded-circle blur-up lazyloaded" width="100">
                                                </div>
                                            </td> -->
                                            <td>${data[i].is_activate == 1 ? '<span class="badge badge-info">active</span>' : '<span class="badge badge-danger">un active</span>'}</td>
                                            <td>
                                                <ul class="nav navbar-center navbar-top-links" style="border-radius: 15px;">
                                                    <li class="dropdown">
                                                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                                             actions <b class="caret"></b>
                                                        </a>
                                                        <ul class="dropdown-menu dropdown-user">
                                                            <li >
                                                                <button class="dropdown-item btn btn-danger openBackFrom" data-toggle="modal" data-target="#myModalBack" data-id="${data[i].id}">
                                                                    back
                                                                </button>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    `
                                }
                                document.getElementById("tableShowData").innerHTML += records
                                offset += data.length
                                length += data.length
                                showItems.innerHTML = Number(length)
                                let btnData = `<button type="button" name="load_more_button" style="width: 350px;" class="btn btn-info form-control px-5" id="load_more_button">عرض المزيد</button>`
                                $('#load_more_button').remove();
                                document.getElementById("load_more").innerHTML = btnData
                            } else if (data.length === 0) {
                                let btnNoData = `<button type="button" name="load_more_button" style="width: 350px;" class="btn btn-primary form-control px-5" id="load_more_button_remove">No Data</button>`
                                $('#load_more_button').remove();
                                document.getElementById("load_more").innerHTML = btnNoData
                            }
                        }
                    })
                }
            });
        });

        $(document).on('keyup', '.data_search', function() {
            var q = $(this).val();
            var urlPath = "{{ route('admin/branches/search/archives') }}";
            event.preventDefault();
            search_in_data(q, urlPath)
        });

        function search_in_data(q, urlPath, record = '') {
            let records = ``
            $.ajax({
                url: urlPath,
                method: "POST",
                data: {
                    q: q,
                    record: record,
                    _token: _token
                },
                success: function(data) {
                    if (data.length > 0) {
                        for (let i = 0; i < data.length; i++) {

                            q == '' ? offset = <?php echo PAGINATION_COUNT ?> : ''
                            image_path =  "{{ asset('') }}" + data[i].img;
                            records += `
                                <tr class="odd gradeX text-center">
                                    <td>${data[i].id}</td>
                                    <td>${data[i].name}</td>
                                    <!-- <td>
                                        <div class="ml-2 d-flex">
                                            <img src="${image_path}" alt="admin image"
                                                class="img-fluid img-50 rounded-circle blur-up lazyloaded" width="100">
                                        </div>
                                    </td> -->
                                    <td>${data[i].is_activate == 1 ? '<span class="badge badge-info">active</span>' : '<span class="badge badge-danger">un active</span>'}</td>
                                    <td>
                                        <ul class="nav navbar-center navbar-top-links" style="border-radius: 15px;">
                                            <li class="dropdown">
                                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                                        actions <b class="caret"></b>
                                                </a>
                                                <ul class="dropdown-menu dropdown-user">
                                                    <li >
                                                        <button class="dropdown-item btn btn-danger openBackFrom" data-toggle="modal" data-target="#myModalBack" data-id="${data[i].id}">
                                                            back
                                                        </button>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            `
                        }
                        document.getElementById("tableShowData").style.display = null
                        document.getElementById("tableShowData").innerHTML = records
                        $('#load_more_button').remove();
                        $('#load_more_button_remove').remove();
                        length = data.length
                        showItems.innerHTML = Number(length)
                        if (data[0].searchButton == 1) {
                            let btnData = `<button type="button" name="load_more_button" style="width: 350px;" class="btn btn-info form-control px-5"id="load_more_button">عرض المزيد</button>`
                            document.getElementById("load_more").innerHTML = btnData
                        }
                    } else if (data.length === 0) {
                        length = data.length
                        showItems.innerHTML = Number(length)
                        document.getElementById("tableShowData").style.display = 'none'
                        let btnNoData = `<button type="button" name="load_more_button" style="width: 350px;" class="btn btn-primary form-control px-5" id="load_more_button_remove">No Data</button>`
                        document.getElementById("load_more").innerHTML = btnNoData
                    }
                }
            })
        }
    </script>
@endsection
