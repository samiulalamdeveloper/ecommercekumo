<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    // wishlist
    function wishlist() {
        $wishlists = Wishlist::where('customer_id', Auth::guard('customerauth')->id())->get();
        return view('frontend.wishlist.wishlist', [
            'wishlists' => $wishlists
        ]);
    }
    // wishlist remove
    function wishlist_remove($wishlist_id) {
        Wishlist::find($wishlist_id)->delete();
        return back()->withSuccess('Wishlist item remove successfully');
    }
    // wishlist delete
    function wishlist_clear() {
        Wishlist::where('customer_id', Auth::guard('customerauth')->id())->delete();
        return back()->withSuccess('Wishlist deleted successfully');
    }
}
