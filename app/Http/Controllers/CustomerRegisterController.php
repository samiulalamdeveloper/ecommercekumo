<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerAuthenticationRequest;
use App\Models\Customerauth;
use App\Models\CustomerEmailVerify;
use App\Notifications\CustomerEmailVerifyNotificatoin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class CustomerRegisterController extends Controller
{
    //customer register
    function customer_register(CustomerAuthenticationRequest $request) {
        Customerauth::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'created_at' => Carbon::now(),
        ]);
        $customer = Customerauth::where('email', $request->email)->firstOrFail();
        $customer_info = CustomerEmailVerify::create([
            'customer_id' => $customer->id,
            'token' => uniqid(),
            'created_at' => Carbon::now(),
        ]);
        Notification::send($customer, new CustomerEmailVerifyNotificatoin($customer_info));
        
        return back()->withSuccess('We have sent you an email verification link! Please check your email.');
        
    }

    // Customer Email Verify
    function customer_email_verify($token) {
        $customer = CustomerEmailVerify::where('token', $token)->firstOrFail();
        Customerauth::find($customer->customer_id)->update([
            'email_verified_at' => Carbon::now()->format('Y-m-d'),
        ]);
        $customer->delete();
        return back()->withSuccess('Your Email Verified Successfully! Now you can login');
    }
}
