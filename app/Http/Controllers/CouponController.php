<?php

namespace App\Http\Controllers;

use App\Http\Requests\CouponRequest;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponController extends Controller
{
    //coupon
    function coupon() {
        $coupons = Coupon::all();
        return view('admin.coupon.coupon', [
            'coupons' => $coupons
        ]);
    }

    // coupon store
    function coupon_store(CouponRequest $request) {
        Coupon::insert([
            'coupon_code' => $request->coupon_code,
            'type' => $request->type,
            'amount' => $request->amount,
            'validity' => $request->validity,
            'created_at' => Carbon::now(),
        ]);
        return back()->withSuccess('Coupon added successfully');
    }

    // coupon delete
    function coupon_delete($coupon_id) {
        Coupon::find($coupon_id)->delete();
        return back()->withDeletesuccess('Coupon deleted successfully');
    }
}
