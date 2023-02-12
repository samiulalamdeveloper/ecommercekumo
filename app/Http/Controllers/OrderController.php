<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Mail\InvoiceMail;
use App\Models\BillingDetails;
use App\Models\Cart;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    // orders
    function orders() {
        $orders = Order::all();
        return view('admin.orders.orders', [
            'orders'=> $orders,
        ]);
    }

    // order status
    function order_status(Request $request) {
        $after_explode = explode(',', $request->status);
        $order_id = $after_explode[0];
        $status = $after_explode[1];
        Order::where('order_id', $order_id)->update([
            'status' => $status,
        ]);
        return back()->withSuccess('Order Status updated Successfully');
    }

    //order store
    function order_store(OrderRequest $request) {
        if($request->payment_method == 1) {
            // echo '#'.Str::upper(Str::random(3)).'-'.rand(99999, 10000) ;
            $order_id = '#'.'KUMO'.'-'.rand(10000, 99999);
            Order::insert([
                'order_id'=> $order_id,
                'customer_id' => Auth::guard('customerauth')->id(),
                'subtotal' => $request->subtotal,
                'discount' => $request->discount,
                'charge' => $request->charge,
                'total' => $request->subtotal + $request->charge,
                'payment_method' => $request->payment_method,
                'created_at' => Carbon::now(),
            ]);

            BillingDetails::insert([
                'order_id'=> $order_id,
                'customer_id' => Auth::guard('customerauth')->id(),
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'company' => $request->company,
                'address' => $request->address,
                'zip' => $request->zip,
                'country_id' => $request->country_id,
                'city_id' => $request->city_id,
                'notes' => $request->notes,
                'created_at' => Carbon::now(),
            ]);

            $carts = Cart::where('customer_id', Auth::guard('customerauth')->id())->get();

            foreach ($carts as $cart) {
                OrderProduct::insert([
                    'order_id'=> $order_id,
                    'customer_id'=> Auth::guard('customerauth')->id(),
                    'product_id' => $cart->product_id,
                    'price' => $cart->rel_to_product->after_discount,
                    'color_id' => $cart->color_id,
                    'size_id' => $cart->size_id,
                    'size_id' => $cart->size_id,
                    'quantity' => $cart->quantity,
                ]);

                Inventory::where('product_id', $cart->product_id)->where('color_id', $cart->color_id)->where('size_id', $cart->size_id)->decrement('quantity', $cart->quantity);
            }

            Cart::where('customer_id', Auth::guard('customerauth')->id())->delete();

            // Invoice mail
            Mail::to($request->email)->send(new InvoiceMail($order_id));

            // SMS start here (bulksmsbd)
            // $url = "http://bulksmsbd.net/api/smsapi";
            // $api_key = "PluE6Q3XYwcy7vHUvL7p";
            // $senderid = "samiul";
            // $number = $request->phone;
            // $message = "Congratulations! you order has been successfully placed. Please ready Tk: ".$request->subtotal + $request->charge;
        
            // $data = [
            //     "api_key" => $api_key,
            //     "senderid" => $senderid,
            //     "number" => $number,
            //     "message" => $message
            // ];
            // $ch = curl_init();
            // curl_setopt($ch, CURLOPT_URL, $url);
            // curl_setopt($ch, CURLOPT_POST, 1);
            // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            // $response = curl_exec($ch);
            // curl_close($ch);
            // return $response;
            // sms end here

            return redirect()->route('order.success')->withSuccess('Order delivered successfully')->withOrder($order_id);
        } else if ($request->payment_method == 2) {
            $data = $request->all();
            return redirect()->route('pay')->withData($data);
        } else {
            $data = $request->all();
            return redirect()->route('stripe')->withData($data);
        }
    }

    // order success
    function order_success() {
        if(session('order')) {
            $order_id = session('order');
            return view('frontend.order.order_success', [
                'order_id' => $order_id,
            ]);
        } else {
            abort('404');
        }
    }

    // download invoice
    function download_invoice($order_id) {
        $order_info = Order::find($order_id);
        $pdf = Pdf::loadView('invoice.invoice_download', [
            'order_id'=>$order_info->order_id,
        ]);
        return $pdf->download('invoice.pdf');
    }
}
