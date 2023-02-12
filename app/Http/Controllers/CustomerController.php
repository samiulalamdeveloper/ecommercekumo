<?php

namespace App\Http\Controllers;

use App\Models\Customerauth;
use App\Models\CustomerPassReset;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Notifications\CustomerPassResetNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Image;


class CustomerController extends Controller
{
    //customer order
    function customer_order() {
        $orders = Order::where('customer_id', Auth::guard('customerauth')->id())->get();
        return view('frontend.customer.customer_order', [
            'orders' => $orders,
        ]);
    }

    // Review Product
    function review_store(Request $request) {
        if($request->image == null) {
            OrderProduct::where('customer_id', $request->customer_id)->where('product_id', $request->product_id)->update([
                'review' => $request->review,
                'star' => $request->star,
            ]);
            return back()->withSuccess('You give '.$request->star.' star rating');
        } else {
            $uploaded_image = $request->image;
            $extension = $uploaded_image->getClientOriginalExtension();
            $file_name = $request->customer_id.'.'.$extension;
            Image::make($uploaded_image)->resize(300, 200)->save(public_path('uploads/review/'.$file_name));
            OrderProduct::where('customer_id', $request->customer_id)->where('product_id', $request->product_id)->update([
                'review' => $request->review,
                'star' => $request->star,
                'image' => $file_name,
            ]);
            return back()->withSuccess('You give '.$request->star.' star rating');
        }
    }

    // Customer Password Reset 
    function customer_pass_reset_req() {
        return view('frontend.cus_password_reset.cus_reset_password');
    }
    // Customer Password Reset Send
    function customer_pass_reset_send(Request $request) {
        $request->validate([
            'email' => 'required',
        ]);
        $customer_info = Customerauth::where('email', $request->email)->firstOrFail();
        CustomerPassReset::where('customer_id', $customer_info->id)->delete();
        $customer_inserted_info = CustomerPassReset::create([
            'customer_id' => $customer_info->id,
            'token' => uniqid(),
            'created_at' => Carbon::now(),
        ]);
        Notification::send($customer_info, new CustomerPassResetNotification($customer_inserted_info));
        return back()->with('success', 'We have sent you a password reset link! Please check your email');
    }

    // Customer pass reset form
    function customer_pass_reset_form($token) {
        return view('frontend.cus_password_reset.cust_pass_reset_form', [
            'token' => $token,
        ]);
    }

    // Password reset set
    function cus_password_reset_set(Request $request) {
        $request->validate([
            'password' => 'required',
            'password_confirmation' => 'required',
        ]);
        $customer = CustomerPassReset::where('token', $request->token)->firstOrFail();

        Customerauth::findOrFail($customer->customer_id)->update([
            'password' => Hash::make($request->password),
        ]);
        $customer->delete();
        return redirect()->route('customer.authentication')->withSuccess('Password reset successfully! Now you can login with your new password.');
    }

    
    
}
