@extends('layouts.admin_master')

@section('title', 'Aub Agents')

@section('create-new')
    @can('sub_agent-create')
        <a href="{{ route('subAgents.create') }}" class="btn btn-primary"
            data-container="body" data-bs-toggle="tooltip"
            data-bs-placement="top" title="Create New Sub Agent">
            <i class="fa fa-plus"></i> New
        </a>
    @endcan
@endsection

@section('breadcrumb-item')
    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Sub Agent</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Sub Agent Lists</h5>
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Sub Agent Name</th>
                                    <th>Agent Name</th>
                                    <th>Sub Agent Id</th>
                                    <th>Address</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subAgents as $subAgent)
                                    <tr>
                                        <td>
                                            <a href="{{ url('subAgents/' . $subAgent->id) }}" class="btn btn-secondary ml-1"
                                                data-container="body" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Details">
                                                <i class="far fa-list-alt" aria-hidden="true"></i>
                                            </a>
                                            @can('sub_agent-edit')
                                                <a href="{{ url('subAgents/' . $subAgent->id . '/edit') }}" class="btn btn-primary ml-1"
                                                    data-container="body" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="Edit">
                                                    <i class="far fa-edit" aria-hidden="true"></i>
                                                </a>
                                            @endcan
                                            @can('sub_agent-delete')
                                                <button  data-id="{{ $subAgent->id }}" class="btn btn-danger ml-1 delete-record"
                                                    data-container="body" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="Delete">
                                                    <i class="far fa-trash-alt" aria-hidden="true"></i>
                                                </button>
                                            @endcan
                                        </td>
                                        <td>{{ $subAgent->full_name }}</td>
                                        <td>{{ $subAgent->agent->full_name }}</td>
                                        <td>{{ $subAgent->sales_id }}</td>
                                        <td>{{ $subAgent->address }}</td>
                                        <td>{{ $subAgent->email }}</td>
                                        <td>{{ $subAgent->phone }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                <th>Action</th>
                                    <th>Sub Agent Name</th>
                                    <th>Agent Name</th>
                                    <th>Sub Agent Id</th>
                                    <th>Address</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                </tr>
                            </tfoot>
                       </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
       $(document).ready(function(){
            $("#datatable").DataTable();

            $('[data-toggle="popover"]').popover(
                { trigger: 'hover'}
            );

            // success alert
            function swal_success(data) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: data,
                    showConfirmButton: false,
                    timer: 1000
                })
            }
            // error alert
            function swal_error(error) {
                Swal.fire({
                    position: 'centered',
                    icon: 'error',
                    title: error,
                    showConfirmButton: true,
                })
            }

            let message = '{{session()->get("message")}}';
            if(message != null && message != ''){
                swal_success(message);
            } 

            $('.delete-record').click(function(e){
                Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        deleteRecord(e);
                    }
                })
            });

            function deleteRecord(e){
                let id = $(e.currentTarget).data('id');
                $.ajax({
                    type: 'DELETE',
                    url: "{{ url('subAgents') }}/" + id,
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'id': id
                    },
                    success: function(data){
                        $(e.currentTarget).parent().parent().remove();
                        swal_success(data);
                    },
                    error: function(error){
                        swal_error(error);
                    },
                });
            }
        });

    </script>
@endsection