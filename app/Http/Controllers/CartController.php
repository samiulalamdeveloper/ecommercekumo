<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Inventory;
use App\Models\Wishlist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // cart store
    function cart_store(Request $request) {
        if($request->abcd == 1) {
            if(Auth::guard('customerauth')->check()) {
                if($request->color_id == null) {
                    $color_id = 1;
                } else {
                    $color_id = $request->color_id;
                }
    
                if($request->size_id == null) {
                    $size_id = 1;
                } else {
                    $size_id = $request->size_id;
                }
    
                if($request->quantity > (Inventory::where('product_id', $request->product_id)->where('color_id', $color_id)->where('size_id', $size_id)->first()->quantity)) {
                    return back()->with('total_stock', 'Total Stock '.Inventory::where('product_id', $request->product_id)->where('color_id', $color_id)->where('size_id', $size_id)->first()->quantity);
                } else {
                    Cart::insert([
                        'customer_id' => Auth::guard('customerauth')->id(),
                        'product_id' => $request->product_id,
                        'color_id' => $color_id,
                        'size_id' => $size_id,
                        'quantity' => $request->quantity,
                    ]);
                }
                return back()->withSuccess('Cart added successfully');
                
            } else {
                return redirect()->route('customer.authentication')->withError('Please login to add cart');
            }
        } else {
            if($request->color_id == null) {
                $color_id = 1;
            } else {
                $color_id = $request->color_id;
            }

            if($request->size_id == null) {
                $size_id = 1;
            } else {
                $size_id = $request->size_id;
            }

            Wishlist::insert([
                'customer_id' => Auth::guard('customerauth')->id(),
                'product_id' => $request->product_id,
                'color_id' => $color_id,
                'size_id' => $size_id,
                'quantity' => $request->quantity,
                'created_at' => Carbon::now(),
            ]);
            return back()->withSuccess('Wishlisted successfully!');
        }
    }

    // cart remove
    function cart_remove($cart_id) {
        Cart::find($cart_id)->delete();
        return back()->withSuccess('Cart single item removed successfully!');
    }
    
    // clear cart
    function cart_clear() {
        Cart::where('customer_id', Auth::guard('customerauth')->id())->delete();
        return back()->withSuccess('Cart all item deleted successfully');
    }

    // cart 
    function cart(Request $request) {
        $carts = Cart::where('customer_id', Auth::guard('customerauth')->id())->get();
        $coupon_code = $request->coupon;
        $discount = 0;
        $message = null;
        $type = null;
        // Cart processing
        if($coupon_code == null) {
            $discount = 0;
        } else {
            if(Coupon::where('coupon_code', $coupon_code)->exists()) {
                if(Carbon::now()->format('Y-m-d') > Coupon::where('coupon_code', $coupon_code)->first()->validity) {
                    $discount = 0;
                    $message = 'Coupon code is Expired!';
                } else {
                    $discount = Coupon::where('coupon_code', $coupon_code)->first()->amount;
                    $type = Coupon::where('coupon_code', $coupon_code)->first()->type;
                }
            } else {
                $discount = 0;
                $message = 'Invalid Coupon Code!';
            }
        }
        return view('frontend.cart.cart', [
            'carts' => $carts,
            'coupon_code' => $coupon_code,
            'discount' => $discount,
            'message' => $message,
            'type' => $type
        ]);
    }

    // update cart
    function cart_update(Request $request) {
        // print_r($request->all());
        foreach($request->quantity as $cart_id=>$quantity){
            Cart::find($cart_id)->update([
                'quantity'=>$quantity,
            ]);
        }
        return back();
        // return back()->withSuccess('Cart updated successfully');
    }

}
