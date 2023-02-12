
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Example 1</title>
    <link rel="stylesheet" href="style.css" media="all" />
    <style>
        .clearfix:after {
  content: "";
  display: table;
  clear: both;
}

a {
  color: #5D6975;
  text-decoration: underline;
}

body {
  position: relative;
  width: 17cm;  
  height: 29.7cm; 
  margin: 0 auto; 
  color: #001028;
  background: #FFFFFF; 
  font-family: Arial, sans-serif; 
  font-size: 12px; 
  font-family: Arial;
}

header {
  padding: 10px 0;
  margin-bottom: 30px;
}

#logo {
  text-align: center;
  margin-bottom: 10px;
}

#logo img {
  width: 90px;
}

h1 {
  border-top: 1px solid  #5D6975;
  border-bottom: 1px solid  #5D6975;
  color: #5D6975;
  font-size: 2.4em;
  line-height: 1.4em;
  font-weight: normal;
  text-align: center;
  margin: 0 0 20px 0;
  background: url(dimension.png);
}

#project {
  float: left;
}

#project span {
  color: #5D6975;
  text-align: right;
  width: 52px;
  margin-right: 10px;
  display: inline-block;
  font-size: 0.8em;
}

#company {
  float: right;
  text-align: right;
}

#project div,
#company div {
  white-space: nowrap;        
}

table {
  width: 100%;
  border-collapse: collapse;
  border-spacing: 0;
  margin-bottom: 20px;
}

table tr:nth-child(2n-1) td {
  background: #F5F5F5;
}

table th,
table td {
  text-align: center;
}

table th {
  padding: 5px 20px;
  color: #5D6975;
  border-bottom: 1px solid #C1CED9;
  white-space: nowrap;        
  font-weight: normal;
}

table .service,
table .desc {
  text-align: left;
}

table td {
  padding: 20px;
  text-align: right;
}

table td.service,
table td.desc {
  vertical-align: top;
}

table td.unit,
table td.qty,
table td.total {
  font-size: 1.2em;
}

table td.grand {
  border-top: 1px solid #5D6975;;
}

#notices .notice {
  color: #5D6975;
  font-size: 1.2em;
}

footer {
  color: #5D6975;
  width: 100%;
  height: 30px;
  position: absolute;
  bottom: 0;
  border-top: 1px solid #C1CED9;
  padding: 8px 0;
  text-align: center;
}
    </style>
  </head>
  <body>
    <header class="clearfix">
      <div id="logo">
        <img src="https://i.postimg.cc/X77xVRMs/logo.png" width="80" height="auto" alt="logo" border="0" />
      </div>
      <h1>Order Id => {{$order_id}}</h1>
      <div id="company" class="clearfix">
        <strong>PAYMENT METHOD</strong>
        <div>@php
          $pm = App\Models\Order::where('order_id', $order_id)->first()->payment_method;
          
          if ($pm == 1) {
              echo 'Cash on delivery';
          }
          else if($pm == 2) {
              echo 'SSL commerz';
          }
          else {
              echo 'Stripe';
          }
      @endphp
      <br> Credit Card Type: {{$pm == 1 ? 'NA':''}}<br> Worldpay Transaction ID: <a href="#" style="color: #ff0000; text-decoration:underline;">{{$pm == 1 ? 'NA': ''}}</a><br></div>
        <div></div>
      </div>
      <div id="project">
        <div>CLIENT: {{App\Models\BillingDetails::where('order_id', $order_id)->first()->name}}</div><br>
        <div>EMAIL: {{App\Models\BillingDetails::where('order_id', $order_id)->first()->email}}</div><br>
        <div>DATE: {{App\Models\BillingDetails::where('order_id', $order_id)->first()->created_at->format('d-M-Y')}}</div>
      </div>
    </header>
    <main>
      <table>
        <thead>
          <tr>
            <th>SL</th>
            <th class="desc">Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          @php
              $subtotal = 0
          @endphp
          @foreach (App\Models\OrderProduct::where('order_id', $order_id)->get() as $sl => $order_product)
          <tr>
            <td>{{$sl+1}}</td>
            <td class="desc">{{$order_product->rel_to_product->product_name}}</td>
            <td class="unit">{{$order_product->rel_to_product->after_discount}} Tk</td>
            <td class="qty">{{$order_product->quantity}}</td>
            <td class="total">{{$order_product->rel_to_product->after_discount*$order_product->quantity}} Tk</td>
          </tr>
          @php
          $subtotal += $order_product->rel_to_product->after_discount*$order_product->quantity
          @endphp
          @endforeach
          <tr>
            <td colspan="4">SUBTOTAL</td>
            <td class="total">{{$subtotal}} Tk</td>
          </tr>
          <tr>
            <td colspan="4">Charge</td>
            <td class="total">{{App\Models\Order::where('order_id', $order_id)->first()->charge}} Tk</td>
          </tr>
          <tr>
            <td colspan="4">Discount</td>
            <td class="total">{{(App\Models\Order::where('order_id',$order_id)->first()->discount == null)? '0': (App\Models\Order::where('order_id',$order_id)->first()->discount)}} Tk</td>
          </tr>
          <tr>
            <td colspan="4" class="grand total">GRAND TOTAL</td>
            <td class="grand total">{{App\Models\Order::where('order_id', $order_id)->first()->total}} Tk</td>
          </tr>
        </tbody>
      </table>
      <div id="notices">
        <div class="notice"><div>Thank you for shopping from our store and for your order.</div></div>
      </div>
    </main>
  </body>
</html>