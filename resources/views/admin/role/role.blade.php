@extends('layouts.dashboard')
@section('content')<div class="page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">App</a></li>
        <li class="breadcrumb-item active"><a href="#">Role List</a></li>
    </ol>
</div>
<div class="row">
    <div class="col-md-9 col-xl-9 col-12">
        <div class="card">
            <div class="card-header">
                <h2>Role List</h2>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>SL</th>
                        <th>Role</th>
                        <th>Permission</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $sl=>$role)
                            
                      <tr>
                        <td>{{$sl+1}}</td>
                        <td>{{$role->name}}</td>
                        <td>
                        @foreach ($role->getAllPermissions() as $permisssion)
                            <span class="badge badge-success my-2">{{$permisssion->name}}</span>
                        @endforeach
                        </td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn btn-success light sharp" data-toggle="dropdown">
                                    <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24">
                                            </rect>
                                            <circle fill="#000000" cx="5" cy="12" r="2">
                                            </circle>
                                            <circle fill="#000000" cx="12" cy="12" r="2">
                                            </circle>
                                            <circle fill="#000000" cx="19" cy="12" r="2">
                                            </circle>
                                        </g>
                                    </svg>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{route('role.edit', $role->id)}}">edit</a>
                                    <a class="dropdown-item" href="">delete</a>
                                </div>
                            </div>
                        </td>
                      </tr>
    
                      @endforeach
    
                    </tbody>
                  </table>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-xl-3 col-12">
        {{-- <div class="card">
            @if (session('permission'))
                <div class="alert alert-success">{{session('permission')}}</div>
            @endif
            <div class="card-header">
                <h3>Add new Permission</h3>
            </div>
            <div class="card-body">
                <form action="{{route('add.permission')}}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Permission Name</label>
                        <input type="text" name="permission" placeholder="enter permission" class="form-control">
                    </div>
                    <div class="mb-3">
                        <button class="btn btn-primary">Add permission</button>
                    </div>
                </form>
            </div>
        </div> --}}
        <div class="card">
            @if (session('role'))
                <div class="alert alert-success">{{session('role')}}</div>
            @endif
            <div class="card-header">
                <h3>Add new role</h3>
            </div>
            <div class="card-body">
                <form action="{{route('add.role')}}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Role Name</label>
                        <input type="text" name="role_name" placeholder="enter role" class="form-control">
                    </div>
                    <div class="mb-3">
                        <h5>Select Permission</h5>
                        <div class="form-group">
                            @foreach ($permission as $permission)
                                <div class="form-check form-check-inline">
                                    <label for="" class="form-check-label">
                                        <input type="checkbox" name="permission[]" class="form-check-input" value="{{$permission->id}}">{{$permission->name}}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="mb-3">
                        <button class="btn btn-primary" type="submit">Add role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-9 col-xl-9 col-12">
        <div class="card">
            @if (session('remove'))
                <div class="alert alert-success">{{session('remove')}}</div>
            @endif
            <div class="card-header">
                <h2>User List</h2>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>SL</th>
                        <th>User</th>
                        <th>Role</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $sl=>$user)
                            
                      <tr>
                        <td>{{$sl+1}}</td>
                        <td>{{$user->name}}</td>
                        <td>
                        @forelse ($user->getRoleNames() as $role)
                            <span class="badge badge-success my-2">{{$role}}</span>
                        @empty
                            <span>Not assigned yet</span>
                        @endforelse
                        </td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn btn-success light sharp" data-toggle="dropdown">
                                    <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24">
                                            </rect>
                                            <circle fill="#000000" cx="5" cy="12" r="2">
                                            </circle>
                                            <circle fill="#000000" cx="12" cy="12" r="2">
                                            </circle>
                                            <circle fill="#000000" cx="19" cy="12" r="2">
                                            </circle>
                                        </g>
                                    </svg>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{route('remove.user.role', $user->id)}}">remove</a>
                                    <a class="dropdown-item" href="">delete</a>
                                </div>
                            </div>
                        </td>
                      </tr>
    
                      @endforeach
    
                    </tbody>
                  </table>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        @can('add_role')
            
        
        <div class="card">
            @if (session('assign'))
                <div class="alert alert-success">{{session('assign')}}</div>
            @endif
            <div class="card-header">
                <h3>Add new permission</h3>
            </div>
            <div class="card-body">
                <form action="{{route('asign.role')}}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <select name="user_id" id="" class="form-control">
                            <option value="">Select User</option>
                            @foreach ($users as $user)
                                <option value="{{$user->id}}">{{$user->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <select name="role_id" id="" class="form-control">
                            <option value="">Select Role</option>
                            @foreach ($roles as $role)
                                <option value="{{$role->id}}">{{$role->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <button class="btn btn-primary">Assign Role</button>
                    </div>
                </form>
            </div>
        </div>

        @endcan
    </div>
</div>
@endsection