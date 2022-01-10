@extends('layouts.admin_master')

@section('title', 'Edit Influencer')

@section('page-title','Influencer')

@section('breadcrumb-item')
    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('influencers.index') }}">Influencers</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@section('content')
<div class="card shadow mb4">
        <div class="card-header">
            <h4 class="card-title">Edit Influencer</h4>
        </div>
        <div class="card-body">
        <form method="POST" action="{{ route('influencers.update', $influencer->id) }}">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" name="first_name" placeholder="First Name" aria-describedby="firstNameHelp" value="{{ old('first_name')? old('first_name'): $influencer->first_name }}">
                    @if($errors->has('first_name'))
                        <small id="firstNameHelp" class="form-text text-danger">{{$errors->first('first_name')}}</small>
                    @endif
                </div>
                <div class="col-lg-6 mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" name="last_name" placeholder="Last Name" aria-describedby="lastNameHelp" value="{{ old('last_name')? old('last_name'): $influencer->last_name }}">
                    @if($errors->has('last_name'))
                        <small id="lastNameHelp" class="form-text text-danger">{{$errors->first('last_name')}}</small>
                    @endif
                </div>
            </div>  
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" placeholder="Password" aria-describedby="passwordHelp" value="{{ old('password') }}">
                    @if($errors->has('password'))
                        <small id="passwordHelp" class="form-text text-danger">{{$errors->first('password')}}</small>
                    @endif
                </div>
                <div class="col-lg-6 mb-3">
                    <label for="password-confirm" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" aria-describedby="passwordConfirmHelp">
                    @if($errors->has('password_confirmation'))
                        <small id="passwordConfirmHelp" class="form-text text-danger">{{$errors->first('password_confirmation')}}</small>
                    @endif
                </div>
            </div>           
            <div class="row">
                <div class="col-lg-6  mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" name="email" placeholder="Email" aria-describedby="emailHelp" readonly value="{{ old('email')? old('email'): $influencer->email }}">
                    @if($errors->has('email'))
                        <small id="emailHelp" class="form-text text-danger">{{$errors->first('email')}}</small>
                    @endif
                </div>
                <div class="col-lg-6 mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" name="phone" placeholder="Phone" aria-describedby="phoneHelp" value="{{ old('phone')? old('phone'): $influencer->phone }}">
                    @if($errors->has('phone'))
                        <small id="phoneHelp" class="form-text text-danger">{{$errors->first('phone')}}</small>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 mb-3">
                    <label>Join Date</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="join_date" placeholder="mm/dd/yyyy" aria-describedby="joinDateHelp" value="{{ old('join_date')? old('join_date'): $influencer->join_date }}">
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
                <div class="col-lg-3 mb-3">
                    <label>Resign Date</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="resign_date" placeholder="mm/dd/yyyy" aria-describedby="resignDateHelp" value="{{ old('resign_date')? old('resign_date'): $influencer->resign_date }}">
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
                <div class="col-lg-3 mb-3">
                    <label>Role</label>
                    <select
                        class="form-select shadow-none"
                        name="roles[]"
                        id="roles"
                        aria-describedby="rolesHelp"
                        >
                        <option></option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}" 
                            {{ in_array($role->name, $userRole)? 'selected' : '' }}
                            >{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('roles'))
                        <small id="rolesHelp" class="form-text text-danger">{{$errors->first('roles')}}</small>
                    @endif
                </div>
                <div class="col-lg-3 mb-3">
                    <label for="commission_rate" class="form-label">Commission Rate</label>
                    <input type="number" class="form-control" name="commission_rate" placeholder="Commission Rate" aria-describedby="commissionRateHelp" value="{{ old('commission_rate')? old('commission_rate'): $influencer->commission_rate }}">
                    @if($errors->has('commission_rate'))
                        <small id="commissionRateHelp" class="form-text text-danger">{{$errors->first('commission_rate')}}</small>
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
                        aria-describedby="agentIdHelp">
                        <option></option>
                        @foreach ($agents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->full_name }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('agent_id'))
                        <small id="agentIdHelp" class="form-text text-danger">{{$errors->first('agent_id')}}</small>
                    @endif
                </div>
                <div class="col-lg-6 mb-3">
                    <label>Sub Agents</label>
                    <select
                        class="form-select shadow-none"
                        name="sub_agent_id"
                        id="sub_agent_id"
                        aria-describedby="subAgentIdHelp">
                        
                    </select>
                    @if($errors->has('sub_agent_id'))
                        <small id="subAgentIdHelp" class="form-text text-danger">{{$errors->first('sub_agent_id')}}</small>
                    @endif
                </div>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" name="address" placeholder="Address" aria-describedby="addressHelp" value="{{ old('address')? old('address'): $influencer->address }}">
                @if($errors->has('address'))
                        <small id="addressNameHelp" class="form-text text-danger">{{$errors->first('address')}}</small>
                @endif
            </div>  
            <div class="mb-3">
                <label for="remark" class="form-label">Remark</label>
                <textarea class="form-control" name="remark" placeholder="Remark" rows="3" aria-describedby="remark">{{ old('remark')? old('remark'): $influencer->remark }}</textarea>
                @if($errors->has('remark'))
                    <small id="remarkHelp" class="form-text text-danger">{{$errors->first('remark')}}</small>
                @endif
            </div>
            <input type="hidden" name="id" value="$influencer->id">
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

            $('#agent_id').prop('selectedIndex', -1);
            let oldAgentId = "{{ old('agent_id') }}";
            let agentId = oldAgentId? oldAgentId: "{{ $influencer->agent_id }}";
            $('#agent_id option[value="' + agentId + '"]').attr('selected', 'selected');
            getSubAgentByAgentId(agentId);

            $('select[name="roles[]"]').change(function(e){
                 let roleName = $(e.currentTarget).val();
                 if(roleName == ''){
                    $('input[name="commission_rate"]').val('');
                 }
                 getCommissionRate(roleName);
            });

            $('select[name="agent_id"]').change(function(e){
                let id = $(e.currentTarget).val();
                if(id == 0){
                    $('select[name="sub_agent_id"]').html('');
                }
                getSubAgentByAgentId(id);         
            });

            function getCommissionRate(roleName){
                $.ajax({
                    type: "get",
                    url: "/influencers/get-commission-rate/" + roleName,
                    data: {
                        '_token': "{{ csrf_token() }}",
                        'roleName': roleName
                    },
                    success: function(data){
                        $('input[name="commission_rate"]').val(data.commission_rate);
                    },
                    error: function(error){
                        console.log(error);
                    }
                });
            }
            
            function getSubAgentByAgentId(id){
                $.ajax({
                    type: "get",
                    url: "/influencers/get-sub-agent-by-agent/" + id,
                    data: {
                        '_token': "{{ csrf_token() }}",
                        'id': id
                    },
                    success: function(data){
                        $('select[name="sub_agent_id"]').html("");
                        data.forEach(subAgent => {
                            let option = '<option value="' + subAgent.id + '">' + subAgent.full_name + '</option>';
                            $('select[name="sub_agent_id"]').append(option);
                        });

                        $('#sub_agent_id').prop('selectedIndex', -1);
                        let oldSubAgentId = "{{ old('sub_agent_id') }}";
                        let subAgentId = oldSubAgentId ? oldSubAgentId : "{{ $influencer->sub_agent_id }}";
                        $('#sub_agent_id option[value="' + subAgentId + '"]').attr('selected', 'selected');
                    },
                    error: function(error){
                        console.log(error);
                    }
                });
            }
       });
   </script>
@endsection

