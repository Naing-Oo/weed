@extends('layouts.admin_master')

@section('title', 'Details')

@section('page-title', 'SubAgent')

@section('breadcrumb-item')
    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('subAgents.index') }}">Sub Agents</a></li>
    <li class="breadcrumb-item active" aria-current="page">Details</li>    
@endsection

@section('content')
<div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Sub Agent Details</h5>
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered">
                            <tbody>
                                <tr> 
                                    <td>First Name</td>
                                    <td>{{ $subAgent->first_name }}</td>
                                </tr>
                                <tr>
                                    <td>Last Name</td>
                                    <td>{{ $subAgent->last_name }}</td>
                                </tr>
                                <tr>
                                    <td>Agent Name</td>
                                    <td>{{ $subAgent->agent->full_name }}</td>
                                </tr>
                                <tr>
                                    <td>Sub Agent Id</td>
                                    <td>{{ $subAgent->sales_id }}</td>
                                </tr>
                                <tr>
                                    <td>Address</td>
                                    <td>{{ $subAgent->address }}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>{{ $subAgent->email }}</td>
                                </tr>
                                <tr>
                                    <td>Phone</td>
                                    <td>{{ $subAgent->phone }}</td>
                                </tr>
                                <tr>
                                    <td>Join Date</td>
                                    <td>{{ $subAgent->join_date }}</td>
                                </tr>
                                <tr>
                                    <td>Resign Date</td>
                                    <td>{{ $subAgent->resign_date }}</td>
                                </tr>
                                <tr>
                                    <td>Commission Rate</td>
                                    <td>{{ $subAgent->commission_rate }}</td>
                                </tr>
                                <tr>
                                    <td>Remark</td>
                                    <td>{{ $subAgent->remark }}</td>
                                </tr>
                                <tr>
                                    <td>Created By</td>
                                    <td>{{ $subAgent->created_by }}</td>
                                </tr>
                                <tr>
                                    <td>Updated By</td>
                                    <td>{{ $subAgent->updated_by }}</td>
                                </tr>
                                <tr>
                                    <td>Created At</td>
                                    <td>{{ $subAgent->created_at }}</td>
                                </tr>
                                <tr>
                                    <td>Updated At</td>
                                    <td>{{ $subAgent->updated_at }}</td>
                                </tr>
                            </tbody>
                       </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection