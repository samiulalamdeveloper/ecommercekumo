@extends('frontend.master.master')
@section('content')
<!-- ======================= Shop Style 1 ======================== -->
<section class="bg-cover" style="background:url({{asset('frontend_assets/img/banner-2.png')}}) no-repeat;">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="text-left py-5 mt-3 mb-3">
                    <h1 class="ft-medium mb-3">{{$category_info->category_name}}</h1>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ======================= Shop Style 1 ======================== -->
<section class="middle">
    <div class="container">
        <div class="row align-items-center rows-products">		
            @foreach ($categoriezed_product as $product)
            <!-- Single -->
            <div class="col-xl-3 col-lg-4 col-md-6 col-6">
                <div class="product_grid card b-0">
                    <div class="badge bg-info text-white position-absolute ft-regular ab-left text-upper">Sale</div>
                    @if ($product->product_discount)
                    <div class="badge bg-danger text-white position-absolute ft-regular ab-right text-upper">{{$product->product_discount}} %</div>
                    @endif
                    <div class="card-body p-0">
                        <div class="shop_thumb position-relative">
                            <a class="card-img-top d-block overflow-hidden" href="{{route('product.details', $product->slug)}}"><img class="card-img-top" src="{{asset('uploads/products/preview')}}/{{$product->preview}}" alt="..."></a>
                        </div>
                    </div>
                    <div class="card-footer b-0 p-0 pt-2 bg-white d-flex align-items-start justify-content-between">
                        <div class="text-left">
                            <div class="text-left">
                                <div class="elso_titl"><span class="small">{{$product->rel_to_category->category_name}}</span></div>
                                <h5 class="fs-md mb-0 lh-1 mb-1"><a href="shop-single-v1.html">{{$product->product_name}}</a></h5>
                                <div class="star-rating align-items-center d-flex justify-content-left mb-2 p-0">
                                    @php
                                        $total_review = App\Models\OrderProduct::where('product_id', $product->id)->where('review', '!=', null)->count();
                                        $total_star = App\Models\OrderProduct::where('product_id', $product->id)->where('review', '!=', null)->sum('star');

                                        $total_rating = 0;
                                        if($total_review != 0) {
                                            $total_rating = $total_star / $total_review;
                                        }

                                    @endphp
                                    @php
                                        for ($i = 1; $i <= $total_rating; $i++) {
                                            echo '<i class="fas fa-star filled"></i>';
                                        }
                                        for ($j = $total_rating + 1; $j <= 5; $j++) {
                                            echo '<i class="fas fa-star"></i>';
                                        }
                                    @endphp
                                </div>
                                <div class="elis_rty">
                                    @if ($product->product_discount)
                                        <span class="ft-bold text-dark fs-sm line-through mr-1">Tk {{$product->product_price}}</span>
                                        <span class="ft-bold text-dark fs-sm">Tk {{$product->after_discount}}</span>
                                    @else
                                        <span class="ft-bold text-dark fs-sm">Tk {{$product->after_discount}}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach	
        </div>
    </div>
</section>
@endsection