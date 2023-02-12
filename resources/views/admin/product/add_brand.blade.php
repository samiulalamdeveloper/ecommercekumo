@extends('layouts.dashboard')
@section('content')
<div class="row">
    <div class="col-xl-8 col-md-8">
        <div class="card">
            <div class="card-header">
                <h2>Brand List</h2>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>SL</th>
                        <th>Brand Name</th>
                        <th>Brand Logo</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($brand as $sl=>$brand)
                            
                      <tr>
                        <th>{{$sl+1}}</th>
                        <td>{{$brand->brand_name}}</td>
                        <td><img width="80" src="{{asset('uploads/brand')}}/{{$brand->brand_logo}}" alt=""></td>
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
    <div class="col-xl-4 col-md-4">
        <div class="card">
            @if (session('success'))
                <div class="alert alert-success">{{session('success')}}</div>
            @endif
            <div class="card-header">
                <h2>Add Brand</h2>
            </div>
            <div class="card-body">
                <form action="{{route('add.brand')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Brand Name</label>
                        <input type="text" name="brand_name" placeholder="Enter brand name" class="form-control">
                        @error('brand_name')
                            <div class="alert alert-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Brand Logo</label>
                        <input type="file" name="brand_logo">
                        @error('brand_logo')
                            <div class="alert alert-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <button class="btn btn-primary">Add brand</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection