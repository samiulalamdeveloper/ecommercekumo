@extends('frontend.master.master')
@section('content')
<section class="middle">
    <div class="container">
        <div class="row align-items-center justify-content-center ">
            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                <div class="mb-3">
                    <h3>Reset password Form</h3>
                </div>
                <form class="border p-3 rounded" method="POST" action="{{route('cus.password.reset.set')}}">				
                    @csrf
                    <div class="form-group">
                        <label>New Password *</label>
                        <input type="password" name="password" class="form-control" placeholder="password*">
                        <input type="hidden" name="token" class="form-control" value="{{$token}}" placeholder="password*">
                        @error('password')
                            <strong class="text-danger">{{$message}}</strong>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label>Confirm Password *</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="confirm password*">
                        @error('password_confirmation')
                            <strong class="text-danger">{{$message}}</strong>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-md full-width bg-dark text-light fs-md ft-medium">Reset password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>>
@endsection