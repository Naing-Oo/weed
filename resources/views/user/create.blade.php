@extends('layouts.admin_master')

@section('title', 'New Agent')

@section('page-title','Agent')

@section('breadcrumb-item')
    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('agents.index') }}">Agents</a></li>
    <li class="breadcrumb-item active" aria-current="page">Create</li>
@endsection

@section('content')
<div class="card shadow mb4">
        <div class="card-header">
            <h4 class="card-title">New Agent</h4>
        </div>
        <div class="card-body">
        <form method="POST" action="{{ route('agents.store') }}">
            @csrf

            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" name="first_name" placeholder="First Name" aria-describedby="firstNameHelp" value="{{ old('first_name') }}">
                    @if($errors->has('first_name'))
                        <small id="firstNameHelp" class="form-text text-danger">{{$errors->first('first_name')}}</small>
                    @endif
                </div>
                <div class="col-lg-6 mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" name="last_name" placeholder="Last Name" aria-describedby="lastNameHelp" value="{{ old('last_name') }}">
                    @if($errors->has('last_name'))
                        <small id="lastNameHelp" class="form-text text-danger">{{$errors->first('last_name')}}</small>
                    @endif
                </div>
            </div>             
             
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" name="email" placeholder="Email" aria-describedby="emailHelp" value="{{ old('email') }}">
                    @if($errors->has('email'))
                        <small id="emailHelp" class="form-text text-danger">{{$errors->first('email')}}</small>
                    @endif
                </div>
                <div class="col-lg-6 mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" name="phone" placeholder="Phone" aria-describedby="phoneHelp" value="{{ old('phone') }}">
                    @if($errors->has('phone'))
                        <small id="phoneHelp" class="form-text text-danger">{{$errors->first('phone')}}</small>
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
                <div class="col-lg-6 mb-3">
                    <label>Join Date</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="join_date" placeholder="mm/dd/yyyy" aria-describedby="joinDateHelp" value="{{ old('join_date') }}">
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
                        <input type="text" class="form-control" name="resign_date" placeholder="mm/dd/yyyy" aria-describedby="resignDateHelp" value="{{ old('resign_date') }}">
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
                    <label>Role</label>
                    <select
                        class="form-select shadow-none"
                        name="roles[]"
                        id="roles"
                        aria-describedby="rolesHelp"
                        >
                        <option value=""></option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('roles'))
                        <small id="rolesHelp" class="form-text text-danger">{{$errors->first('roles')}}</small>
                    @endif
                </div>
                <div class="col-lg-6 mb-3">
                    <label for="commission_rate" class="form-label">Commission Rate</label>
                    <input type="number" class="form-control" name="commission_rate" placeholder="Commission Rate" aria-describedby="commissionRateHelp" value="{{ old('commission_rate') }}">
                    @if($errors->has('commission_rate'))
                        <small id="commissionRateHelp" class="form-text text-danger">{{$errors->first('commission_rate')}}</small>
                    @endif
                </div>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" name="address" placeholder="Address" aria-describedby="addressHelp" value="{{ old('address') }}">
                @if($errors->has('address'))
                        <small id="addressNameHelp" class="form-text text-danger">{{$errors->first('address')}}</small>
                @endif
            </div>  
            <div class="mb-3">
                <label for="remark" class="form-label">Remark</label>
                <textarea class="form-control" name="remark" placeholder="Remark" rows="3" aria-describedby="remark">{{ old('remark') }}</textarea>
                @if($errors->has('remark'))
                    <small id="remarkHelp" class="form-text text-danger">{{$errors->first('remark')}}</small>
                @endif
            </div>
            
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

            let oldRoleName = "{{ old('roles') }}";
            let rolesName = oldRoleName ? oldRoleName : '';
            $('#role_id option[value="' + rolesName + '"]').attr('selected', 'selected');
            getCommissionRate(rolesName);

            $('select[name="roles[]"]').change(function(e){
                 let roleName = $(e.currentTarget).val();
                 if(roleName == ''){
                    $('input[name="commission_rate"]').val('');
                 }
                 getCommissionRate(roleName);
            });

            function getCommissionRate(roleName){
                $.ajax({
                    type: "get",
                    url: "/agents/get-commission-rate/" + roleName,
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
            
       });
   </script>
@endsection
