@extends('layouts.admin_master')

@section('title', 'Details')

@section('page-title', 'Agent')

@section('breadcrumb-item')
    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('agents.index') }}">Agents</a></li>
    <li class="breadcrumb-item active" aria-current="page">Details</li>    
@endsection

@section('content')
<div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Agent Details</h5>
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered">
                            <tbody>
                                <tr> 
                                    <td>First Name</td>
                                    <td>{{ $agent->first_name }}</td>
                                </tr>
                                <tr>
                                    <td>Last Name</td>
                                    <td>{{ $agent->last_name }}</td>
                                </tr>
                                <tr>
                                    <td>Agent Id</td>
                                    <td>{{ $agent->sales_id }}</td>
                                </tr>
                                <tr>
                                    <td>Address</td>
                                    <td>{{ $agent->address }}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>{{ $agent->email }}</td>
                                </tr>
                                <tr>
                                    <td>Phone</td>
                                    <td>{{ $agent->phone }}</td>
                                </tr>
                                <tr>
                                    <td>Join Date</td>
                                    <td>{{ $agent->join_date }}</td>
                                </tr>
                                <tr>
                                    <td>Resign Date</td>
                                    <td>{{ $agent->resign_date }}</td>
                                </tr>
                                <tr>
                                    <td>Commission Rate</td>
                                    <td>{{ $agent->commission_rate }}</td>
                                </tr>
                                <tr>
                                    <td>Remark</td>
                                    <td>{{ $agent->remark }}</td>
                                </tr>
                                <tr>
                                    <td>Created By</td>
                                    <td>{{ $agent->created_by }}</td>
                                </tr>
                                <tr>
                                    <td>Updated By</td>
                                    <td>{{ $agent->updated_by }}</td>
                                </tr>
                                <tr>
                                    <td>Created At</td>
                                    <td>{{ $agent->created_at }}</td>
                                </tr>
                                <tr>
                                    <td>Updated At</td>
                                    <td>{{ $agent->updated_at }}</td>
                                </tr>
                            </tbody>
                       </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection