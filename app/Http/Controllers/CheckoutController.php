<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    //checkout
    function checkout() {
        $carts = Cart::where('customer_id', Auth::guard('customerauth')->id())->get();
        $countries = Country::all();
        return view('frontend.checkout.checkout', [
            'carts' => $carts,
            'countries' => $countries,
        ]);
    }
    
    // City
    function getCity(Request $request) {
        $str = '<option value="">-- Select City / Town --</option>';
        $cities = City::where('country_id', $request->country_id)->get();
        foreach($cities as $city) {
            $str .= '<option value="'.$city->id.'">'.$city->name.'</option>';
        }
        echo $str;
    }

}
