@extends('layouts.admin_master')

@section('title', 'Edit User')

@section('page-title','User')

@section('breadcrumb-item')
    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@section('content')
<div class="card shadow mb4">
        <div class="card-header">
            <h4 class="card-title">Edit User</h4>
        </div>
        <div class="card-body">
        <form method="POST" action="{{ route('users.update', $user->id) }}">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label for="first_name" class="form-label">User Name</label>
                    <input type="text" class="form-control" name="name" placeholder="User Name" aria-describedby="nameHelp" value="{{ old('name')? old('name'): $user->name }}">
                    @if($errors->has('name'))
                        <small id="nameHelp" class="form-text text-danger">{{$errors->first('name')}}</small>
                    @endif
                </div>
                <div class="col-lg-6 mb-3">
                    <label>Role</label>
                    <select
                        class="form-select shadow-none"
                        name="roles[]"
                        id="roles"
                        aria-describedby="rolesHelp"
                        >
                        <option value=""></option>
                        @foreach ($roles as $key => $value)
                        <option value="{{ $key }}" {{ in_array($value, $userRole)? 'selected' : '' }}
                            >{{ $value }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('roles'))
                        <small id="rolesHelp" class="form-text text-danger">{{$errors->first('roles')}}</small>
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
            <input type="hidden" name="id" value="$user->id">
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
        </div>
    </div>
@endsection

@section('script')
   <script>
       $(document).ready(()=>{
           
       });
   </script>
@endsection

