@extends('layouts.dashboard')
@section('content')
    <div class="col-12">
        <div class="card">
            @if (session('success'))
                <div class="alert alert-success">{{session('success')}}</div>
            @endif
            <div class="card-header">
                <h3>Edit role</h3>
            </div>
            <div class="card-body">
                <form action="{{route('update.role')}}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Role Name</label>
                        <input type="hidden" name="role_id" value="{{$role->id}}" placeholder="enter role" class="form-control">
                        <input type="text" readonly value="{{$role->name}}" placeholder="enter role" class="form-control">
                    </div>
                    <div class="mb-3">
                        <h5>Select Permission</h5>
                        <div class="form-group">
                            @foreach ($permissions as $permission)
                                <div class="form-check form-check-inline">
                                    <label for="" class="form-check-label">
                                        <input type="checkbox" {{($role->hasPermissionTo($permission->name))?'checked': ''}} name="permission[]" class="form-check-input" value="{{$permission->id}}">{{$permission->name}}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="mb-3">
                        <button class="btn btn-primary" type="submit">Update role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection