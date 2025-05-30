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
            {{-- <h1 class="page-header">Contacts</h1> --}}
        </div>
        <div class="col-lg-4">
            <div class="breadcrumb_container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin/index')}}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{route('admin/contacts/index')}}/0/{{PAGINATION_COUNT}}">Contacts</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Index</li>
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
                    Contacts Viwes
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    @include('flash::message')
                    <div class="row">
                        <div class="col-sm-6"></div>
                        <div class="col-sm-6 text-right">
                            <div id="dataTables-example_filter" class="dataTables_filter">
                                <label><input placeholder="search" type="search" class="form-control input-sm data_search" aria-controls="dataTables-example"></label>
                            </div>
                        </div>
                    </div>
                    <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Message</th>
                                </tr>
                            </thead>
                            <tbody id="tableShowData">
                                @isset($contacts)
                                    @foreach($contacts as $contact)
                                        <tr class="odd gradeX">
                                            <td>{{$contact->id}}</td>
                                            <td>{{$contact->name}}</td>
                                            <td>{{$contact->email}}</td>
                                            <td>{{$contact->mobile}}</td>
                                            <td>{{$contact->message}}</td>
                                        </tr>
                                    @endforeach
                                @endisset
                            </tbody>
                        </table>
                        <div style="margin-top: 20px; font-weight: 600; font-size: 16px;">
                            Showing 1 to <span id="showItems"></span> of <span>{{App\Models\Contact::unArchive()->count()}}</span> entries
                        </div>
                        <div class="ltn__pagination-area text-center mt-5">
                            <div class="ltn__pagination text-center">
                                <div id="load_more">
                                    <button type="button" name="load_more_button" style="width: 350px;" class="btn btn-info form-control px-5" data-id="'.$last_id.'" id="load_more_button">عرض المزيد</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="myModalDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabell"
                        aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title f-w-600" id="exampleModalLabell">Delete Confirmation</h5>
                                </div>
                                <div class="modal-body">
                                    <form role="form" action="{{url(route('admin/contacts/delete'))}}" method="get">
                                        {{ csrf_field() }}
                                        <p>Are You Sure To Update This Record ?</p>
                                        <input id="delete_record_id" name="record_id" type="hidden">
                                        <div class="modal-footer">
                                            <button class="btn btn-primary" type="submit">Sure</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="myModalActivation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title f-w-600" id="exampleModalLabel">Activation Confirmation</h5>
                                </div>
                                <div class="modal-body">
                                    <form role="form" action="{{url(route('admin/contacts/activate'))}}" method="get">
                                        {{ csrf_field() }}
                                        <p>Are You Sure To Update This Record ?</p>
                                        <input id="activation_record_id" name="record_id" type="hidden">
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
        $(document).on('click', '.openDeleteFrom', function() {
            var id = $(this).attr('data-id');
            $('#delete_record_id').val(id);
        });
        $(document).on('click', '.openActivationFrom', function() {
            var id = $(this).attr('data-id');
            $('#activation_record_id').val(id);
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
                        url: `{{ route("admin/contacts/pagination")}}/${offset}/${limit}`,
                        method: "POST",
                        data: {
                            _token: _token,
                        },
                        success: function(data) {
                            if (data.length > 0) {
                                for (let i = 0; i < data.length; i++) {
                                    image_path =  "{{ asset('') }}" + data[i].img;
                                    edit_route =  "{{ route('admin/contacts/edit') }}" + '/' + data[i].id;
                                    records += `
                                        <tr>
                                            <td>${data[i].id}</td>
                                            <td>${data[i].name}</td>
                                            <td>${data[i].email}</td>
                                            <td>${data[i].mobile}</td>
                                            <td>${data[i].message}</td>
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
            var urlPath = "{{ route('admin/contacts/search') }}";
            event.preventDefault();
            search_in_data(_token, q, urlPath)
        });

        function search_in_data(_token, q, urlPath, record = '') {
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
                                    <td>${data[i].email}</td>
                                    <td>${data[i].mobile}</td>
                                    <td>${data[i].message}</td>
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
