@extends('layouts.dashboard')
@section('content')
<div class="page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">App</a></li>
        <li class="breadcrumb-item active"><a href="{{ url('coupon') }}">Add Coupon</a></li>
    </ol>
</div>
<div class="row">
    <div class="col-md-8 col-lg-8">
        <div class="card">
            @if (session('deletesuccess'))
                <div class="alert alert-success">{{session('deletesuccess')}}</div>
            @endif
            <div class="card-header">
                <h2>Coupon List</h2>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                      <tr>
                        <th scope="col">SL</th>
                        <th scope="col">Coupon</th>
                        <th scope="col">Coupon Type</th>
                        <th scope="col">amount</th>
                        <th scope="col">validity</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($coupons as $sl=>$coupon)
                            
                      <tr>
                        <td>{{$sl+1}}</td>
                        <td>{{$coupon->coupon_code}}</td>
                        <td>{{($coupon->type == 1)?'percentage':'solid amount'}}</td>
                        <td>{{($coupon->type == 1)? $coupon->amount.' %': $coupon->amount.' Tk'}}</td>
                        <td>
                            <div class="badge badge-{{(Carbon\Carbon::now() > ($coupon->validity))?'danger':'primary'}}">{{Carbon\Carbon::now()->diffInDays($coupon->validity, false)}} days left</div>
                            {{-- @if (Carbon\Carbon::now()->format('Y-m-d') > ($coupon->validity))
                                <div class="badge badge-danger">{{Carbon\Carbon::now()->diffInDays($coupon->validity, false)}} days left</div>
                            @else 
                            <div class="badge badge-primary">{{Carbon\Carbon::now()->diffInDays($coupon->validity, false)}} days left</div>
                            @endif --}}
                        </td>
                        <td>
                            <a href="{{route('coupon.delete', $coupon->id)}}" class="btn btn-danger">Delete coupon</a>
                        </td>
                      </tr>
    
                      @endforeach
    
                    </tbody>
                  </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4">
        <div class="card">
            <div class="card-header">
                <h2>Add Coupon</h2>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{session('success')}}</div>
                @endif
                <form action="{{route('coupon.store')}}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Coupon code</label>
                        <input type="text" name="coupon_code" placeholder="coupon code" class="form-control">
                        @error('coupon_code')
                            <div class="alert alert-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <select name="type" class="form-control" id="">
                            <option value="">-- Select Type --</option>
                            <option value="1">Percentage</option>
                            <option value="2">Solid amount</option>
                        </select>
                        @error('type')
                            <div class="alert alert-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Discount amount</label>
                        <input type="number" name="amount" placeholder="discount" class="form-control">
                        @error('amount')
                            <div class="alert alert-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Discount validity</label>
                        <input type="date" name="validity" placeholder="coupon validity" class="form-control">
                        @error('validity')
                            <div class="alert alert-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <button class="btn btn-primary" type="submit">Add coupon</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection