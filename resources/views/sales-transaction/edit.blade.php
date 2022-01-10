@extends('layouts.admin_master')

@section('title', 'Edit Downline')

@section('page-title','Downline')

@section('breadcrumb-item')
    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('downlines.index') }}">Downline</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@section('content')
<div class="card shadow mb4">
        <div class="card-header">
            <h4 class="card-title">Edit Downline</h4>
        </div>
        <div class="card-body">
        <form method="POST" action="{{ route('downlines.update', $downline->id) }}">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" name="first_name" placeholder="First Name" aria-describedby="firstNameHelp" value="{{ old('first_name')? old('first_name'): $downline->first_name }}">
                    @if($errors->has('first_name'))
                        <small id="firstNameHelp" class="form-text text-danger">{{$errors->first('first_name')}}</small>
                    @endif
                </div>
                <div class="col-lg-6 mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" name="last_name" placeholder="Last Name" aria-describedby="lastNameHelp" value="{{ old('last_name')? old('last_name'): $downline->last_name }}">
                    @if($errors->has('last_name'))
                        <small id="lastNameHelp" class="form-text text-danger">{{$errors->first('last_name')}}</small>
                    @endif
                </div>
            </div>             
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" name="email" placeholder="Email" aria-describedby="emailHelp" value="{{ old('email')? old('email'): $downline->email }}">
                    @if($errors->has('email'))
                        <small id="emailHelp" class="form-text text-danger">{{$errors->first('email')}}</small>
                    @endif
                </div>
                <div class="col-lg-6 mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" name="phone" placeholder="Phone" aria-describedby="phoneHelp" value="{{ old('phone')? old('phone'): $downline->phone }}">
                    @if($errors->has('phone'))
                        <small id="phoneHelp" class="form-text text-danger">{{$errors->first('phone')}}</small>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label>Join Date</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="join_date" placeholder="mm/dd/yyyy" aria-describedby="joinDateHelp" value="{{ old('join_date')? old('join_date'): $downline->join_date }}">
                        <div class="input-group-append">
                            <span class="input-group-text h-100">
                                <i class="mdi mdi-calendar"></i>
                            </span>
                        </div>
                    </div>
                    @if($errors->has('join_date'))
                        <small id="joinDateHelp" class="form-text text-danger">{{$errors->first('join_date')}}</small>
                    @endif
                </div>
                <div class="col-lg-6 mb-3">
                    <label>Resign Date</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="resign_date" placeholder="mm/dd/yyyy" aria-describedby="resignDateHelp" value="{{ old('resign_date')? old('resign_date'): $downline->resign_date }}">
                        <div class="input-group-append">
                            <span class="input-group-text h-100">
                                <i class="mdi mdi-calendar"></i>
                            </span>
                        </div>
                    </div>
                    @if($errors->has('resign_date'))
                        <small id="resignDateHelp" class="form-text text-danger">{{$errors->first('resign_date')}}</small>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label>Agents</label>
                    <select
                        class="form-select shadow-none"
                        name="agent_id"
                        id="agent_id"
                        style="width: 100%; height: 36px">
                        <option>Select</option>
                        @foreach ($agents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-6 mb-3">
                    <label for="commission_rate" class="form-label">Commission Rate</label>
                    <input type="number" class="form-control" name="commission_rate" placeholder="Commission Rate" aria-describedby="commissionRateHelp" value="{{ old('commission_rate')? old('commission_rate'): $downline->commission_rate }}">
                    @if($errors->has('commission_rate'))
                        <small id="commissionRateHelp" class="form-text text-danger">{{$errors->first('commission_rate')}}</small>
                    @endif
                </div>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" name="address" placeholder="Address" aria-describedby="addressHelp" value="{{ old('address')? old('address'): $downline->address }}">
                @if($errors->has('address'))
                        <small id="addressNameHelp" class="form-text text-danger">{{$errors->first('address')}}</small>
                @endif
            </div>   
            <div class="mb-3">
                <label for="remark" class="form-label">Remark</label>
                <textarea class="form-control" name="remark" placeholder="Remark" rows="3" aria-describedby="remark">{{ old('remark')? old('remark'): $downline->remark }}</textarea>
                @if($errors->has('remark'))
                    <small id="remarkHelp" class="form-text text-danger">{{$errors->first('remark')}}</small>
                @endif
            </div>
            <input type="hidden" name="id" value="$downline->id">
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
        </div>
    </div>
@endsection

@section('script')
   <script>
       $(document).ready(()=>{
           
            $('input[name="join_date"], input[name="resign_date"]').datepicker({
                autoclose: true,
                todayHighlight: true,
            });

            // $('#agent_id').prop('selectedIndex', -1);
            let oldAgentId = "{{ old('agent_id') }}";
            let agentId = oldAgentId? oldAgentId: "{{ $downline->agent_id }}";
            $('#agent_id option[value="' + agentId + '"]').attr('selected', 'selected');
            
       });
   </script>
@endsection

