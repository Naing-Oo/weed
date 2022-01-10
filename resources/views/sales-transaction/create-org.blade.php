@extends('layouts.app')

@section('title', 'New Transaction')

@section('page-title','Transaction')

@section('breadcrumb-item')
    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('salesTransactionHeaders.index') }}">Transaction</a></li>
    <li class="breadcrumb-item active" aria-current="page">Create</li>
@endsection

@section('content')
    <div class="card shadow mb4">
        <div class="card-header">
            <h4 class="card-title">New Transaction</h4>
        </div>
        <div class="card-body">
        <form method="POST" action="{{ route('salesTransactionHeaders.store') }}">
            @csrf

            <div class="row">
                <div class="col-lg-4 mb-3">
                    <label>Transaction Number</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="sales_number" placeholder="Sales Number" aria-describedby="salesNumberHelp" value="{{ old('sales_number') }}">
                    </div>
                    @if($errors->has('join_date'))
                        <small id="salesNumberHelp" class="form-text text-danger">{{$errors->first('sales_number')}}</small>
                    @endif
                </div>
                <div class="col-lg-4 mb-3">
                    <label>Transaction Date</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="sales_date" placeholder="mm/dd/yyyy" aria-describedby="salesDateHelp" value="{{ old('sales_date') }}">
                        <div class="input-group-append">
                            <span class="input-group-text h-100">
                                <i class="mdi mdi-calendar"></i>
                            </span>
                        </div>
                    </div>
                    @if($errors->has('sales_date'))
                        <small id="salesDateHelp" class="form-text text-danger">{{$errors->first('sales_date')}}</small>
                    @endif
                </div>
                <div class="col-lg-4 mb-3">
                    <label>Level</label>
                    <select
                        class="form-select shadow-none"
                        name="level_id"
                        id="level_id"
                        style="width: 100%; height: 36px">
                        <option>Select</option>
                            <option value="1">Agent</option>
                            <option value="2">Downline</option>
                            <option value="3">Influencer</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 mb-3">
                    <label>Agent Name</label> {{-- option --}}
                    <select
                        class="form-select shadow-none"
                        name="agent_id"
                        id="agent_id"
                        style="width: 100%; height: 36px">
                        <option>Select</option>
                            <option value="1">Agent</option>
                            <option value="2">Downline</option>
                            <option value="3">Influencer</option>
                    </select>
                </div>
                <div class="col-lg-8 mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" name="address" placeholder="Address" aria-describedby="addressHelp" value="{{ old('address') }}">
                @if($errors->has('address'))
                        <small id="addressNameHelp" class="form-text text-danger">{{$errors->first('address')}}</small>
                @endif
            </div>
            </div>
            <div class="row">
                <div class="col-lg-4 mb-3">
                    <label for="curr_code" class="form-label">Currency Code</label>
                    <input type="text" class="form-control" name="curr_code" placeholder="Currency Code" aria-describedby="currCodeHelp" value="{{ old('curr_code') }}">
                    @if($errors->has('curr_code'))
                        <small id="currCodeHelp" class="form-text text-danger">{{$errors->first('curr_code')}}</small>
                    @endif
                </div>
                <div class="col-lg-4 mb-3">
                    <label for="curr_rate" class="form-label">Currency Rate</label>
                    <input type="number" class="form-control" name="curr_rate" placeholder="Currency Rate" aria-describedby="currRateHelp" value="{{ old('curr_rate') }}">
                    @if($errors->has('curr_rate'))
                        <small id="currRateHelp" class="form-text text-danger">{{$errors->first('curr_rate')}}</small>
                    @endif
                </div>
                <div class="col-lg-4 mb-3">
                    <label for="sales_total_amount" class="form-label">Sales Amount</label>
                    <input type="number" class="form-control" name="sales_total_amount" placeholder="Sales Amount" aria-describedby="salesTotalAmountHelp" value="{{ old('sales_total_amount') }}">
                    @if($errors->has('sales_total_amount'))
                        <small id="salesTotalAmountHelp" class="form-text text-danger">{{$errors->first('sales_total_amount')}}</small>
                    @endif
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
        </div>
    </div>
@endsection

@section('script')
   <script>
       $(document).ready(()=>{
           
            $('input[name="sales_date"]').datepicker({
                autoclose: true,
                todayHighlight: true,
            });

            // let oldAgentId = "{{ old('agent_id') }}";
            // let agentId = oldAgentId? oldAgentId: '';
            // $('#agent_id option[value="' + agentId + '"]').attr('selected', 'selected');

            
       });
   </script>
@endsection

