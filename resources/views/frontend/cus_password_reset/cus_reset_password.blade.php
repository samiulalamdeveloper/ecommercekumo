@extends('frontend.master.master')
@section('content')
<section class="middle">
    <div class="container">
        <div class="row align-items-center justify-content-center ">
            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                <div class="mb-3">
                    <h3>Request password reset</h3>
                </div>
                <form class="border p-3 rounded" method="POST" action="{{route('customer.pass.reset.send')}}">				
                    @csrf
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" class="form-control" placeholder="Email*">
                        @error('email')
                            <strong class="text-danger">{{$message}}</strong>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-md full-width bg-dark text-light fs-md ft-medium">Request send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>>
@endsection