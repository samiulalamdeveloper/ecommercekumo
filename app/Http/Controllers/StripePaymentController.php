<?php
    
namespace App\Http\Controllers;

use App\Models\Stripeorder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Stripe;
use App\Mail\InvoiceMail;
use App\Models\BillingDetails;
use App\Models\Cart;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\Mail;
     
class StripePaymentController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripe()
    {
        $data = session('data');
        $stripe_id = Stripeorder::insertGetId([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'company' => $data['company'],
            'zip' => $data['zip'],
            'country_id' => $data['country_id'],
            'city_id' => $data['city_id'],
            'notes' => $data['notes'],
            'charge' => $data['charge'],
            'payment_method' => $data['payment_method'],
            'subtotal' => $data['subtotal'],
            'discount' => $data['discount'],
            'customer_id' => $data['customer_id'],
            'total' => $data['subtotal'] + $data['charge'],
            'created_at' => Carbon::now(),
        ]);
        return view('frontend.stripe.stripe', [
            'data'=> $data,
            'stripe_id'=> $stripe_id,
        ]);
    }
    
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripePost(Request $request)
    {
        $stripedata =  Stripeorder::where('id', $request->stripe_id)->get();
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    
        Stripe\Charge::create ([
                "amount" => $stripedata->first()->total * 100,
                "currency" => "bdt",
                "source" => $request->stripeToken,
                "description" => "Test payment from itsolutionstuff.com." 
        ]);
      

        $order_id = '#'.'JETT'.'-'.rand(99999, 10000);
            Order::insert([
                'order_id'=>$order_id,
                'customer_id'=>$stripedata->first()->customer_id,
                'subtotal'=> $stripedata->first()->subtotal,
                'discount'=> $stripedata->first()->discount,
                'charge'=> $stripedata->first()->charge,
                'total'=> $stripedata->first()->total,
                'payment_method'=> $stripedata->first()->payment_method,
                'created_at'=>Carbon::now(),
            ]);

            BillingDetails::insert([
                'order_id'=>$order_id,
                'customer_id'=>$stripedata->first()->customer_id,
                'name'=>$stripedata->first()->name,
                'email'=>$stripedata->first()->email,
                'phone'=>$stripedata->first()->phone,
                'company'=>$stripedata->first()->company,
                'address'=>$stripedata->first()->address,
                'zip'=>$stripedata->first()->zip,
                'country_id'=>$stripedata->first()->country_id,
                'city_id'=>$stripedata->first()->city_id,
                'notes'=>$stripedata->first()->notes,
                'created_at'=>Carbon::now(),
            ]);

            $carts = Cart::where('customer_id', $stripedata->first()->customer_id)->get();

            foreach ($carts as $cart) {
                
                OrderProduct::insert([
                    'order_id'=>$order_id,
                    'customer_id'=>$stripedata->first()->customer_id,
                    'product_id'=>$cart->product_id,
                    'price'=>$cart->rel_to_product->after_discount,
                    'color_id'=>$cart->color_id,
                    'size_id'=>$cart->size_id,
                    'quantity'=>$cart->quantity,
                    'created_at'=>Carbon::now(),
                ]);

                Inventory::where('product_id', $cart->product_id)->where('color_id', $cart->color_id)->where('size_id', $cart->size_id)->decrement('quantity', $cart->quantity);
            }

            // Invoice mail
            Mail::to($stripedata->first()->email)->send(new InvoiceMail($order_id));

            Cart::where('customer_id', $stripedata->first()->customer_id)->delete();

            return redirect()->route('order.success')->withOrder($order_id)->withSuccess('Order completed successfully');;
    }
}