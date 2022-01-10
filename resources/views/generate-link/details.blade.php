@extends('layouts.admin_master')

@section('title', 'Details')

@section('page-title', 'Transaction')

@section('breadcrumb-item')
    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ url('linkTransactions') }}">Transaction</a></li>
    <li class="breadcrumb-item active" aria-current="page">Details</li>    
@endsection

@section('content')
<div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Transaction Detail</h5>
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered">
                            <tbody>
                                <tr>
                                    <td>Sales Id</td>
                                    <td>{{ $linkTransaction->sales_id }}</td>
                                </tr>
                                <tr>
                                    <td>Seller Name</td>
                                    <td>{{ $linkTransaction->seller_name }}</td>
                                </tr>
                                <tr> 
                                    <td>Link</td>
                                    <td>{{ $linkTransaction->product_link }}</td>
                                </tr>
                                <tr>
                                    <td>Product Name</td>
                                    <td>{{ $linkTransaction->product_name }}</td>
                                </tr>
                                <tr>
                                    <td>Created By</td>
                                    <td>{{ $linkTransaction->created_by }}</td>
                                </tr>
                                <tr>
                                    <td>Updated By</td>
                                    <td>{{ $linkTransaction->updated_by }}</td>
                                </tr>
                                <tr>
                                    <td>Created At</td>
                                    <td>{{ $linkTransaction->created_at }}</td>
                                </tr>
                                <tr>
                                    <td>Updated At</td>
                                    <td>{{ $linkTransaction->updated_at }}</td>
                                </tr>
                               
                            </tbody>
                       </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection