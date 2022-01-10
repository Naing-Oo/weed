@extends('layouts.admin_master')

@section('title', 'Agents')

@section('create-new')
    {{-- @can('agent-create')
        <a href="{{ route('agents.create') }}" class="btn btn-primary" 
                    data-container="body" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="Create New Agent">
            <i class="fa fa-plus"></i> New
        </a>
    @endcan --}}
@endsection

@section('breadcrumb-item')
    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">User</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Agent Lists</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>User Name</th>
                                    <th>User Role</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>
                                            {{-- <a href="{{ url('users/' . $user->id) }}" class="btn btn-secondary ml-1" 
                                                    data-container="body" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="Details">
                                                <i class="far fa-list-alt" aria-hidden="true"></i>
                                            </a> --}}
                                            @can('agent-edit')
                                                <a href="{{ url('users/' . $user->id . '/edit') }}" class="btn btn-primary ml-1" 
                                                    data-container="body" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="Edit">
                                                    <i class="far fa-edit" aria-hidden="true"></i>
                                                </a>    
                                            @endcan
                                            @can('agent-delete')
                                                <button  data-id="{{ $user->id }}" class="btn btn-danger ml-1 delete-record" 
                                                    data-container="body" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="Delete">
                                                    <i class="far fa-trash-alt" aria-hidden="true"></i>
                                                </button>
                                            @endcan
                                        </td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->role_name }}</td>
                                        <td>{{ $user->email }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Ation</th>
                                    <th>Agent Name</th>
                                    <th>User Role</th>
                                    <th>Email</th>
                                </tr>
                            </tfoot>
                       </table>
                    </div>
                </div>
            </div>
        </div> 


        <div class="col-4">
            <div class="card shadow mb4">
                <div class="card-header">
                    <h4 class="card-title">Add User</h4>
                </div>
                <div class="card-body">
                <form method="POST" action="{{ route('users.store') }}">
                     @csrf
                    
                    <div class="col-lg-12 mb-3">
                        <label for="first_name" class="form-label">User Name</label>
                        <input type="text" class="form-control" name="name" placeholder="User Name" aria-describedby="nameHelp" value="{{ old('name')? old('name'): '' }}">
                        @if($errors->has('name'))
                            <small id="nameHelp" class="form-text text-danger">{{$errors->first('name')}}</small>
                        @endif
                    </div>
                    <div class="col-lg-12 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" placeholder="Email" aria-describedby="emailHelp" value="{{ old('email')? old('email'): '' }}">
                        @if($errors->has('email'))
                            <small id="emailHelp" class="form-text text-danger">{{$errors->first('email')}}</small>
                        @endif
                    </div>
                    <div class="col-lg-12 mb-3">
                        <label>Role</label>
                        <select
                            class="form-select shadow-none"
                            name="roles[]"
                            id="roles"
                            aria-describedby="rolesHelp"
                            >
                            <option value=""></option>
                            @foreach ($roles as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('roles'))
                            <small id="rolesHelp" class="form-text text-danger">{{$errors->first('roles')}}</small>
                        @endif
                    </div>
                    <div class="col-lg-12 mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Password" aria-describedby="passwordHelp" value="{{ old('password') }}">
                        @if($errors->has('password'))
                            <small id="passwordHelp" class="form-text text-danger">{{$errors->first('password')}}</small>
                        @endif
                    </div>
                    <div class="col-lg-12 mb-3">
                        <label for="password-confirm" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" aria-describedby="passwordConfirmHelp">
                        @if($errors->has('password_confirmation'))
                            <small id="passwordConfirmHelp" class="form-text text-danger">{{$errors->first('password_confirmation')}}</small>
                        @endif
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('script')
    <script>
       $(document).ready(function(){

            $("#datatable").DataTable();

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
                    url: "{{ url('users') }}/" + id,
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