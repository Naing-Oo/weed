@extends('layouts.admin_master')

@section('title', 'Details')

@section('page-title', 'Influencer')

@section('breadcrumb-item')
    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('influencers.index') }}">Influencer</a></li>
    <li class="breadcrumb-item active" aria-current="page">Details</li>    
@endsection

@section('content')
<div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Influencer Details</h5>
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered">
                            <tbody>
                                <tr> 
                                    <td>First Name</td>
                                    <td>{{ $influencer->first_name }}</td>
                                </tr>
                                <tr>
                                    <td>Last Name</td>
                                    <td>{{ $influencer->last_name }}</td>
                                </tr>
                                <tr>
                                    <td>Agent Name</td>
                                    <td>{{ $influencer->agent->full_name }}</td>
                                </tr>
                                <tr>
                                    <td>Sub Agent Name</td>
                                    <td>{{ $influencer->subAgent->full_name }}</td>
                                </tr>
                                <tr>
                                    <td>Influencer Id</td>
                                    <td>{{ $influencer->sales_id }}</td>
                                </tr>
                                <tr>
                                    <td>Address</td>
                                    <td>{{ $influencer->address }}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>{{ $influencer->email }}</td>
                                </tr>
                                <tr>
                                    <td>Phone</td>
                                    <td>{{ $influencer->phone }}</td>
                                </tr>
                                <tr>
                                    <td>Join Date</td>
                                    <td>{{ $influencer->join_date }}</td>
                                </tr>
                                <tr>
                                    <td>Resign Date</td>
                                    <td>{{ $influencer->resign_date }}</td>
                                </tr>
                                <tr>
                                    <td>Commission Rate</td>
                                    <td>{{ $influencer->commission_rate }}</td>
                                </tr>
                                <tr>
                                    <td>Remark</td>
                                    <td>{{ $influencer->remark }}</td>
                                </tr>
                                <tr>
                                    <td>Created By</td>
                                    <td>{{ $influencer->created_by }}</td>
                                </tr>
                                <tr>
                                    <td>Updated By</td>
                                    <td>{{ $influencer->updated_by }}</td>
                                </tr>
                                <tr>
                                    <td>Created At</td>
                                    <td>{{ $influencer->created_at }}</td>
                                </tr>
                                <tr>
                                    <td>Updated At</td>
                                    <td>{{ $influencer->updated_at }}</td>
                                </tr>
                            </tbody>
                       </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection