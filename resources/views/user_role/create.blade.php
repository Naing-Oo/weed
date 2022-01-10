@extends('layouts.admin_master')

@section('title', 'New Role')

@section('page-title','Role')

@section('breadcrumb-item')
    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
    <li class="breadcrumb-item active" aria-current="page">Create</li>
@endsection


@section('content')
<div class="card shadow mb4">
    <div class="card-header">
            <h4 class="card-title">New Role</h4>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('roles.store') }}">
            @csrf

            <div class="row">
                <div class="col-lg-4 mb-3">
                    <label for="name" class="form-label">Role Name</label>
                    <input type="text" class="form-control" name="name" placeholder="Role Name" aria-describedby="nameHelp" value="{{ old('name') }}">
                    @if($errors->has('name'))
                        <small id="nameHelp" class="form-text text-danger">{{$errors->first('name')}}</small>
                    @endif
                </div>
                <div class="col-lg-4 mb-3">
                    <label for="commission_rate" class="form-label">Commission Rate</label>
                    <input type="number" class="form-control" name="commission_rate" placeholder="Commission Rate" aria-describedby="commissionRateHelp" value="{{ old('commission_rate') }}">
                    @if($errors->has('commission_rate'))
                        <small id="commissionRateHelp" class="form-text text-danger">{{$errors->first('commission_rate')}}</small>
                    @endif
                </div>
                <div class="col-lg-4 mb-3">
                    <label class="col-md-3">Permissions</label>
                    <div class="col-md-9">
                        @foreach($permission as $value)
                            <div class="form-check mr-sm-2">
                                <input
                                    type="checkbox"
                                    class="form-check-input"
                                    id="permission"
                                    name="permission[]"
                                    aria-describedby="permissionHelp"
                                    value="{{ $value->id }}"
                                />
                                <label
                                    class="form-check-label mb-0"
                                    for="permission"
                                    >{{ $value->name }}</label
                            >
                            </div>
                        @endforeach
                    </div>
                    @if($errors->has('permission'))
                        <small id="permissionHelp" class="form-text text-danger">{{$errors->first('permission')}}</small>
                    @endif
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</div>

@endsection