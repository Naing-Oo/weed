@extends('layouts.admin_master')

@section('title', 'Details')

@section('page-title', 'Role')

@section('breadcrumb-item')
    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
    <li class="breadcrumb-item active" aria-current="page">Details</li>    
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Role Details</h5>
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered">
                            <tbody>
                                <tr>
                                    <td>Role Name</td>
                                    <td>{{ $role->name }}</td>
                                </tr>   
                                <tr>
                                    <td>Commission Rate</td>
                                    <td>{{ $role->commission_rate }}</td>
                                </tr>                                     
                                <tr>
                                    <td>Created At</td>
                                    <td>{{ $role->created_at }}</td>
                                </tr>                                        
                                <tr>
                                    <td>Updated At</td>
                                    <td>{{ $role->updated_at }}</td>
                                </tr>
                                <tr>
                                    <td>Created By</td>
                                    <td>{{ $role->created_by }}</td>
                                </tr>
                                <tr>
                                    <td>Updated By</td>
                                    <td>{{ $role->updated_by }}</td>
                                </tr>
                            </tbody>
                       </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Have Permissions</h5>
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered">
                            <tbody>
                                @foreach ($rolePermissions as $permission)
                                    <tr>
                                        <td>{{ $permission->name }}</td>
                                    </tr>                                        
                                @endforeach
                            </tbody>
                       </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection