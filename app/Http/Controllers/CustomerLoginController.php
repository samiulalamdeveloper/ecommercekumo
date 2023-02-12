<?php

namespace App\Http\Controllers;

use App\Models\Customerauth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Image;

class CustomerLoginController extends Controller
{
    // Customer profile
    function customer_profile() {
        return view('frontend.customer_authentication.customer_profile');
    }

    // Customer authentication
    function customer_login(Request $request) {
        $request->validate([
            'password' => 'required',
            'email' => 'required',
        ]);
        // if(Auth::guard('customerauth')->user()->email_verified_at == null) {
        //     return back()->withError('Your account is not verified yet! Please check your email and verified your email.');
        // } else {
        //     if(Auth::guard('customerauth')->attempt(['email'=>$request->email, 'password'=> $request->password])) {
        //         return redirect()->route('site')->withSuccess('Customer logged in successfully');
        //     } else {
        //         return back()->withError('Please register first');
        //     }
        // }
        if(Auth::guard('customerauth')->attempt(['email'=>$request->email, 'password'=>$request->password])){
            if(Auth::guard('customerauth')->user()->email_verified_at == null) {
                Auth::guard('customerauth')->logout();
                return redirect()->route('customer.authentication')->withError('Your account is not verified yet! Please check your email and verified your email.');
            } else {
                return redirect()->route('site')->withSuccess('Customer logged in successfully');
            }
        } else {
            return redirect()->route('customer.authentication')->withError('Please register first');
        }
    }

    // Customer profile update
    function customer_profile_update(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'image' => 'mimes:png,jpg,webp,jpeg|file|max:2000',
        ], [
            'image.max' => 'Image size maximum 2 mb',
        ]);
        if($request->password == null) {
            if($request->image == null) {
                Customerauth::find(Auth::guard('customerauth')->id())->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'country' => $request->country,
                    'address' => $request->address,
                ]);
                return back()->withSuccess('Updated without password or image!');
            } else {
                if(Auth::guard('customerauth')->user()->image != null) {
                    $delete_from = public_path('uploads/customer/'.Auth::guard('customerauth')->user()->image);
                    unlink($delete_from);
                }
                $uploaded_image = $request->image;
                $extension = $uploaded_image->getClientOriginalExtension();
                $file_name = Auth::guard('customerauth')->id().'.'.$extension;
                
                Image::make($uploaded_image)->resize(300, 200)->save(public_path('uploads/customer/'.$file_name));
                Customerauth::find(Auth::guard('customerauth')->id())->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'country' => $request->country,
                    'address' => $request->address,
                    'image' => $file_name
                ]);
                return back()->withSuccess('Updated without password!');
            }
        } else {
            if(Hash::check($request->old_password, Auth::guard('customerauth')->user()->password)) {
                if($request->image == null) {
                    Customerauth::find(Auth::guard('customerauth')->id())->update([
                        'name' => $request->name,
                        'email' => $request->email,
                        'country' => $request->country,
                        'address' => $request->address,
                        'password' => bcrypt($request->password),
                    ]);
                    return back()->withSuccess('Updated without image!');
                } else {
                    if(Auth::guard('customerauth')->user()->image != null) {
                        $delete_from = public_path('uploads/customer/'.Auth::guard('customerauth')->user()->image);
                        unlink($delete_from);
                    }
                    $uploaded_image = $request->image;
                    $extension = $uploaded_image->getClientOriginalExtension();
                    $file_name = Auth::guard('customerauth')->id().'.'.$extension;
                    Image::make($uploaded_image)->resize(300, 200)->save(public_path('uploads/customer/'.$file_name));
                    Customerauth::find(Auth::guard('customerauth')->id())->update([
                        'name' => $request->name,
                        'email' => $request->email,
                        'country' => $request->country,
                        'address' => $request->address,
                        'password' => bcrypt($request->password),
                        'image' => $file_name,
                    ]);
                    return back()->withSuccess('Updated Successfully!');
                }
            } else {
                return back()->withFail('Old password is wrong');
            }
        }
    }

    // Customer Logout
    function customer_logout() {
        Auth::guard('customerauth')->logout();
        return redirect()->route('site')->withLogout('Customer logged out successfully');
    }
}
