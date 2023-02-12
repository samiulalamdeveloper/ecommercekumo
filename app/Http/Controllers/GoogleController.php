<?php

namespace App\Http\Controllers;

use App\Models\Customerauth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    //google redirect
    function google_redirect() {
        return Socialite::driver('google')->redirect();
    }

    // google callback
    function google_callback() {
        $user = Socialite::driver('google')->user();
        if(Customerauth::where('email', $user->getEmail())->exists()) {
            if(Auth::guard('customerauth')->attempt(['email'=>$user->getEmail(), 'password'=>'32rfcdf$2@'])) {
                return redirect()->route('site')->withSuccess('Customer logged in successfully with google account.');
            }
        } else {
            Customerauth::insert([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => bcrypt('32rfcdf$2@'),
            ]);
            if(Auth::guard('customerauth')->attempt(['email'=>$user->getEmail(), 'password'=>'32rfcdf$2@'])) {
                    return redirect()->route('site')->withSuccess('Customer logged in successfully with google account.');
            }
        }
    }
}
