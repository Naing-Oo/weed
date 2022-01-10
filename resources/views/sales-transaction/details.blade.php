@extends('layouts.admin_master')

@section('title', 'Details')

@section('page-title', 'Sales Transaction')

@section('breadcrumb-item')
    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ url('sales-transaction') }}">Sales Transaction</a></li>
    <li class="breadcrumb-item active" aria-current="page">Details</li>    
@endsection

@section('content')
<div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Sales Transaction</h5>
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered">
                            <tbody>
                                <tr>
                                    <td>Sales Id</td>
                                    <td>{{ $transaction->sales_id }}</td>
                                </tr>
                                <tr>
                                    <td>Seller Name</td>
                                    <td>{{ $transaction->seller_name }}</td>
                                </tr>
                                <tr> 
                                    <td>Link</td>
                                    <td>{{ $transaction->product_link }}</td>
                                </tr>
                                <tr>
                                    <td>Product Name</td>
                                    <td>{{ $transaction->product_name }}</td>
                                </tr>
                                <tr>
                                    <td>Created By</td>
                                    <td>{{ $transaction->created_by }}</td>
                                </tr>
                                <tr>
                                    <td>Updated By</td>
                                    <td>{{ $transaction->updated_by }}</td>
                                </tr>
                                <tr>
                                    <td>Created At</td>
                                    <td>{{ $transaction->created_at }}</td>
                                </tr>
                                <tr>
                                    <td>Updated At</td>
                                    <td>{{ $transaction->updated_at }}</td>
                                </tr>
                               
                            </tbody>
                       </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection