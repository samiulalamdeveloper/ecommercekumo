@extends('layouts.dashboard')
@section('content')
<!-- ======================= Top Breadcrubms ======================== -->
<div class="col-xl-12 col-lg-12 col-md-12">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Orders</li>
        </ol>
    </nav>
</div>
<!-- ======================= Top Breadcrubms ======================== -->
<div class="col-12">
    <div class="card">
        @if (session('success'))
            <div class="alert alert-success">{{session('success')}}</div>
        @endif
        <div class="card-header">
            <h2>Orders List</h2>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                  <tr>
                    <th>SL</th>
                    <th>Customer Name</th>
                    <th>Order Date</th>
                    <th>Order Id</th>
                    <th>Sub Total</th>
                    <th>Discount</th>
                    <th>Charge</th>
                    <th>Total</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $sl=>$order)
                        
                  <tr>
                    <td>{{$sl+1}}</td>
                    <td>{{$order->rel_to_customer->name}}</td>
                    <td>{{$order->created_at->format('d-m-Y')}}</td>
                    <td>{{$order->order_id}}</td>
                    <td>{{$order->subtotal}}</td>
                    <td>{{$order->discount}}</td>
                    <td>{{$order->charge}}</td>
                    <td>{{$order->total}}</td>
                    <td>
                        @php
                            if($order->payment_method == 1) {
                                echo 'Cash on delivery';
                            } else if($order->payment_method == 2) {
                                echo 'SSL CommerZ';
                            } else {
                                echo 'Stripe';
                            }
                        @endphp
                    </td>
                    <td>
                        <span class="rounded p-2 bade badge-@php
                                if($order->status == 0) {
                                    echo 'success';
                                } 
                                else if($order->status == 1) {
                                    echo 'danger';
                                }
                                else if($order->status == 2) {
                                    echo 'secondary';
                                }
                                else if($order->status == 3) {
                                    echo 'info';
                                }
                                else {
                                    echo 'primary';
                                }  
                            @endphp">
                            @php
                                if($order->status == 0) {
                                    echo 'Placed';
                                } 
                                else if($order->status == 1) {
                                    echo 'Packaging';
                                }
                                else if($order->status == 2) {
                                    echo 'Shipped';
                                }
                                else if($order->status == 3) {
                                    echo 'Ready to deliver';
                                }
                                else {
                                    echo 'Delivered';
                                } 
                            @endphp
                        </span>
                    </td>
                    <td>
                        <form action="{{route('order.status')}}" method="POST">
                            @csrf
                            <div class="dropdown">
                                <button type="button" class="btn btn-success light sharp" data-toggle="dropdown">
                                    <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="5" cy="12" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="19" cy="12" r="2"/></g></svg>
                                </button>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item" name="status" type="submit" value="{{$order->order_id.','.'0'}}">Placed</button>
                                    <button class="dropdown-item" name="status" type="submit" value="{{$order->order_id.','.'1'}}">Packaging</button>
                                    <button class="dropdown-item" name="status" type="submit" value="{{$order->order_id.','.'2'}}">Shipped</button>
                                    <button class="dropdown-item" name="status" type="submit" value="{{$order->order_id.','.'3'}}">Ready to deliver</button>
                                    <button class="dropdown-item" name="status" type="submit" value="{{$order->order_id.','.'4'}}">Delivered</button>
                                </div>
                            </div>
                        </form>
                    </td>
                  </tr>

                  @endforeach

                </tbody>
              </table>
        </div>
    </div>
</div>  
@endsection