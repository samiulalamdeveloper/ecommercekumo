@extends('frontend.master.master');
@section('content')
    <!-- ======================= Top Breadcrubms ======================== -->
    <div class="gray py-3">
        <div class="container">
            <div class="row">
                <div class="colxl-12 col-lg-12 col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Library</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Data</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- ======================= Top Breadcrubms ======================== -->

    <!-- ======================= Product Detail ======================== -->
    <section class="middle">
        <div class="container">
            <div class="row justify-content-between">

                <div class="col-xl-5 col-lg-6 col-md-12 col-sm-12">
                    <div class="quick_view_slide">
                        @foreach ($thumbnails as $thumbnail)
                            <div class="single_view_slide d-flex align-items-center justify-content-center">
                                <a href="{{ asset('uploads/products/thumbnails') }}/{{ $thumbnail->thumbnail }}"
                                    data-lightbox="roadtrip" class="d-block mb-4">
                                    <img src="{{ asset('uploads/products/thumbnails') }}/{{ $thumbnail->thumbnail }}"
                                        class="img-fluid rounded" alt="" />
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-xl-7 col-lg-6 col-md-12 col-sm-12">
                    <div class="prd_details pl-3">

                        <div class="prt_01 mb-1"><span
                                class="text-light bg-info rounded px-2 py-1">{{ App\Models\Category::where('id', $product_info->first()->category_id)->first()->category_name }}</span>
                        </div>
                        <div class="prt_02 mb-3">
                            <h2 class="ft-bold mb-1">{{ $product_info->first()->product_name }}</h2>
                            <div class="text-left">
                                @if ($total_review == 0)
                                    <div class="star-rating align-items-center d-flex justify-content-left mb-1 p-0">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star"></i>
                                        @endfor
                                    </div>
                                @else
                                    @php
                                        $rating = round($total_star / $total_review);
                                    @endphp
                                    <div class="star-rating align-items-center d-flex justify-content-left mb-1 p-0">
                                        @for ($r = 1; $r <= $rating; $r++)
                                            <i class="fas fa-star filled"></i>
                                        @endfor
                                        <?php
                                        //   $j=(5-$review->star);
                                        $i = 5 - $rating;
                                        ?>
                                        @for ($j = 1; $j <= $i; $j++)
                                            <i class="fas fa-star"></i>
                                        @endfor
                                        @if ($total_review < 2)
                                            <span class="small">({{$total_review}} Reviews)</span>
                                        @else
                                            <span class="small">({{$total_review}} Reviews)</span>
                                        @endif
                                    </div>
                                @endif


                                <div class="elis_rty">
                                    @if ($product_info->first()->product_discount == null)
                                    <span class="ft-bold theme-cl fs-lg mr-2">{{ $product_info->first()->after_discount }} Tk</span>
                                    @else
                                        <span class="ft-medium text-muted line-through fs-md mr-2">{{ $product_info->first()->product_price }} Tk</span>
                                        <span class="ft-bold theme-cl fs-lg mr-2">{{ $product_info->first()->after_discount }} Tk</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="prt_03 mb-4">
                            <p>{{ $product_info->first()->short_desp }}</p>
                        </div>
                        <form action="{{ route('cart.store') }}" method="POST">
                            @csrf
                            {{-- hidden id --}}
                            <input type="hidden" name="product_id" value="{{ $product_info->first()->id }}">
                            <div class="prt_04 mb-2">
                                <p class="d-flex align-items-center mb-0 text-dark ft-medium">Color:</p>
                                {{-- Color --}}
                                <div class="text-left">
                                    @php
                                        $color = null;
                                    @endphp
                                    @foreach ($available_colors as $color)
                                        @if ($color->rel_to_color->color_code == null)
                                            <div class="form-check form-option form-check-inline mb-1">
                                                <input class="form-check-input colorId" type="radio" value="1"
                                                    name="color_id" id="color{{ $color->rel_to_color->id }}">
                                                <label class="form-option-label rounded-circle"
                                                    style="background: {{ $color->rel_to_color->color_name }}"
                                                    for="color{{ $color->rel_to_color->id }}">
                                                    <span class="form-option-color rounded-circle">NA</span>
                                                </label>
                                            </div>
                                        @else
                                            <div class="form-check form-option form-check-inline mb-1">
                                                <input class="form-check-input colorId" type="radio"
                                                    value="{{ $color->rel_to_color->id }}" name="color_id"
                                                    id="color{{ $color->rel_to_color->id }}">
                                                <label class="form-option-label rounded-circle"
                                                    style="background: {{ $color->rel_to_color->color_name }}"
                                                    for="color{{ $color->rel_to_color->id }}">
                                                    <span class="form-option-color rounded-circle"></span>
                                                </label>
                                            </div>
                                        @endif
                                        @php
                                            $color = $color->rel_to_color->color_code;
                                        @endphp
                                    @endforeach
                                </div>
                            </div>

                            <div class="prt_04 mb-4">
                                <p class="d-flex align-items-center mb-0 text-dark ft-medium">Size:</p>
                                {{-- Size --}}
                                <div class="text-left pb-0 pt-2" id="size_id">
                                    @if ($color != null)
                                        @foreach ($sizes as $size)
                                            <div class="form-check size-option form-option form-check-inline mb-2">
                                                <input class="form-check-input" value="{{ $size->id }}" type="radio"
                                                    name="size_id" id="size{{ $size->id }}">
                                                <label class="form-option-label"
                                                    for="size{{ $size->id }}">{{ $size->size }}</label>
                                            </div>
                                        @endforeach
                                    @else
                                        @foreach (App\Models\Inventory::where('product_id', $product_info->first()->id)->get() as $size)
                                            @if ($size->rel_to_size->id == 4)
                                                {{-- You should update this by number 1 instead of 4 --}}
                                                <div class="form-check size-option form-option form-check-inline mb-2">
                                                    <input class="form-check-input" value="{{ $size->rel_to_size->id }}"
                                                        type="radio" name="size_id" id="size{{ $size->id }}">
                                                    <label class="form-option-label"
                                                        for="size{{ $size->id }}">{{ $size->rel_to_size->size }}</label>
                                                </div>
                                            @else
                                                <div class="form-check size-option form-option form-check-inline mb-2">
                                                    <input class="form-check-input" value="{{ $size->rel_to_size->id }}"
                                                        type="radio" name="size_id" id="size{{ $size->id }}">
                                                    <label class="form-option-label"
                                                        for="size{{ $size->id }}">{{ $size->rel_to_size->size }}</label>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <div class="prt_05 mb-4">
                                <div class="form-row mb-7">
                                    <div class="col-12 col-lg-auto">
                                        <!-- Quantity -->
                                        <select class="mb-2 custom-select" name="quantity">
                                            @for ($i = 1; $i <= 200; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                        @if (session('total_stock'))
                                            <div class="alert alert-danger">{{ session('total_stock') }}</div>
                                        @endif
                                    </div>
                                    <div class="col-12 col-lg">
                                        <!-- Submit -->
                                        <button type="submit" name="abcd" value="1"
                                            class="btn btn-block custom-height bg-dark mb-2">
                                            <i class="lni lni-shopping-basket mr-2"></i>Add to Cart
                                        </button>
                                    </div>
                                    <div class="col-12 col-lg-auto">
                                        <!-- Wishlist -->
                                        <button type="submit" name="abcd" value="2"
                                            class="btn custom-height btn-default btn-block mb-2 text-dark">
                                            <i class="lni lni-heart mr-2"></i>Wishlist
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="prt_06">
                            <p class="mb-0 d-flex align-items-center">
                                <span class="mr-4">Share:</span>
                                <a class="d-inline-flex align-items-center justify-content-center p-3 gray circle fs-sm text-muted mr-2"
                                    href="#!">
                                    <i class="fab fa-twitter position-absolute"></i>
                                </a>
                                <a class="d-inline-flex align-items-center justify-content-center p-3 gray circle fs-sm text-muted mr-2"
                                    href="#!">
                                    <i class="fab fa-facebook-f position-absolute"></i>
                                </a>
                                <a class="d-inline-flex align-items-center justify-content-center p-3 gray circle fs-sm text-muted"
                                    href="#!">
                                    <i class="fab fa-pinterest-p position-absolute"></i>
                                </a>
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ======================= Product Detail End ======================== -->

    <!-- ======================= Product Description ======================= -->
    <section class="middle">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-xl-11 col-lg-12 col-md-12 col-sm-12">
                    <ul class="nav nav-tabs b-0 d-flex align-items-center justify-content-center simple_tab_links mb-4"
                        id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="description-tab" href="#description" data-toggle="tab"
                                role="tab" aria-controls="description" aria-selected="true">Description</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="#information" id="information-tab" data-toggle="tab"
                                role="tab" aria-controls="information" aria-selected="false">Additional
                                information</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="#reviews" id="reviews-tab" data-toggle="tab" role="tab"
                                aria-controls="reviews" aria-selected="false">Reviews</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="myTabContent">

                        <!-- Description Content -->
                        <div class="tab-pane fade show active" id="description" role="tabpanel"
                            aria-labelledby="description-tab">
                            <div class="description_info">
                                <p class="p-0 mb-2">{!! $product_info->first()->long_desp !!}</p>
                            </div>
                        </div>

                        <!-- Additional Content -->
                        <div class="tab-pane fade" id="information" role="tabpanel" aria-labelledby="information-tab">
                            <div class="additionals">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th class="ft-medium text-dark">ID</th>
                                            <td>#1253458</td>
                                        </tr>
                                        <tr>
                                            <th class="ft-medium text-dark">SKU</th>
                                            <td>KUM125896</td>
                                        </tr>
                                        <tr>
                                            <th class="ft-medium text-dark">Color</th>
                                            <td>Sky Blue</td>
                                        </tr>
                                        <tr>
                                            <th class="ft-medium text-dark">Size</th>
                                            <td>Xl, 42</td>
                                        </tr>
                                        <tr>
                                            <th class="ft-medium text-dark">Weight</th>
                                            <td>450 Gr</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Reviews Content -->
                        <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                            <div class="reviews_info">
                                {{-- From Frontend Controller --}}
                                @foreach ($reviews as $review)
                                    <!-- Single Review -->
                                    <div class="single_rev d-flex align-items-start br-bottom py-3">
                                        <div class="single_rev_thumb">
                                            @if ($review->rel_to_customer->image == null)
                                                <img src="{{ Avatar::create($review->rel_to_customer->name)->toBase64() }}"
                                                    class="img-fluid circle" width="90" alt="" />
                                            @else
                                                <img src="{{ asset('uploads/customer') }}/{{ $review->rel_to_customer->image }}"
                                                    class="img-fluid circle" width="90" alt="" />
                                            @endif

                                        </div>
                                        <div class="single_rev_caption d-flex align-items-start pl-3">
                                            <div class="single_capt_left">
                                                <h5 class="mb-0 fs-md ft-medium lh-1">{{ $review->rel_to_customer->name }}
                                                </h5>
                                                <span class="small">{{ $review->updated_at->format('d-m-Y') }}</span>
                                                <p>{{ $review->review }}</p>
                                                @if ($review->image)
                                                    <div class="my-2">
                                                        <a href="{{ asset('uploads/review') }}/{{ $review->image }}"
                                                            target="_blank"><img
                                                                src="{{ asset('uploads/review') }}/{{ $review->image }}"
                                                                width=100 alt=""></a>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="single_capt_right">
                                                <div
                                                    class="star-rating align-items-center d-flex justify-content-left mb-1 p-0">
                                                    @for ($i = 1; $i <= $review->star; $i++)
                                                        <i class="fas fa-star filled"></i>
                                                    @endfor
                                                    <?php
                                                    $l = 5 - $review->star;
                                                    ?>
                                                    @for ($j = 1; $j <= $l; $j++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @auth('customerauth')
                                @if (App\Models\OrderProduct::where('customer_id', Auth::guard('customerauth')->id())->where('product_id', $product_info->first()->id)->exists())
                                    @if (App\Models\OrderProduct::where('customer_id', Auth::guard('customerauth')->id())->where('product_id', $product_info->first()->id)->whereNotNull('review')->first() == false)
                                        <div class="reviews_rate">
                                            <form class="row" action="{{ route('review.store') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                                    <h4>Submit Rating</h4>
                                                </div>

                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                                    <div
                                                        class="revie_stars d-flex align-items-center justify-content-between px-2 py-2 gray rounded mb-2 mt-1">
                                                        <div class="srt_013">
                                                            <div class="submit-rating">
                                                                <input id="star-5" type="radio" name="star"
                                                                    value="5" />
                                                                <label for="star-5" title="5 stars">
                                                                    <i class="active fa fa-star" aria-hidden="true"></i>
                                                                </label>
                                                                <input id="star-4" type="radio" name="star"
                                                                    value="4" />
                                                                <label for="star-4" title="4 stars">
                                                                    <i class="active fa fa-star" aria-hidden="true"></i>
                                                                </label>
                                                                <input id="star-3" type="radio" name="star"
                                                                    value="3" />
                                                                <label for="star-3" title="3 stars">
                                                                    <i class="active fa fa-star" aria-hidden="true"></i>
                                                                </label>
                                                                <input id="star-2" type="radio" name="star"
                                                                    value="2" />
                                                                <label for="star-2" title="2 stars">
                                                                    <i class="active fa fa-star" aria-hidden="true"></i>
                                                                </label>
                                                                <input id="star-1" type="radio" name="star"
                                                                    value="1" />
                                                                <label for="star-1" title="1 star">
                                                                    <i class="active fa fa-star" aria-hidden="true"></i>
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="srt_014">
                                                            <h6 class="mb-0">5 Star</h6>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label class="medium text-dark ft-medium">Full Name</label>
                                                        <input type="text" name="name" readonly
                                                            value="{{ Auth::guard('customerauth')->user()->name }}"
                                                            class="form-control" />
                                                    </div>
                                                </div>

                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label class="medium text-dark ft-medium">Email Address</label>
                                                        <input type="email" name="email" readonly
                                                            value="{{ Auth::guard('customerauth')->user()->email }}"
                                                            class="form-control" />
                                                    </div>
                                                </div>

                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label class="medium text-dark ft-medium">Description</label>
                                                        <textarea name="review" class="form-control"></textarea>
                                                    </div>
                                                </div>

                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label class="medium text-dark ft-medium">Image</label>
                                                        <input type="file" name="image" class="form-control">
                                                    </div>
                                                </div>

                                                {{-- Hidden file --}}
                                                <input type="hidden" name="customer_id"
                                                    value="{{ Auth::guard('customerauth')->id() }}">
                                                <input type="hidden" name="product_id"
                                                    value="{{ $product_info->first()->id }}">

                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                                    <div class="form-group m-0">
                                                        <button type="submit"
                                                            class="btn btn-white stretched-link hover-black">Submit Review <i
                                                                class="lni lni-arrow-right"></i></button>
                                                    </div>
                                                </div>

                                            </form>
                                        </div>
                                    @else
                                        <div class="alert alert-info">
                                            <h3>You have already given a review for this product!</h3>
                                        </div>
                                    @endif
                                @else
                                    <div class="alert alert-info">
                                        <h3>You did not purchase any product right now!</h3>
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-info">
                                    <h3>Please login to give a review <a href="{{ route('customer.authentication') }}"
                                            class="float-right btn btn-success">Login here</a></h3>
                                </div>
                            @endauth

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ======================= Product Description End ==================== -->

    <!-- ======================= Similar Products Start ============================ -->
    <section class="middle pt-0">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="sec_title position-relative text-center">
                        <h2 class="off_title">Similar Products</h2>
                        <h3 class="ft-bold pt-3">Matching Producta</h3>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="slide_items">
                        @foreach ($related_products as $related)
                            <!-- single Item -->
                            <div class="single_itesm">
                                <div class="product_grid card b-0 mb-0">
                                    @if ($related->product_discount == null)
                                        <div
                                            class="badge bg-success text-white position-absolute ft-regular ab-left text-upper">
                                            Sale</div>
                                    @else
                                        <div
                                            class="badge bg-success text-white position-absolute ft-regular ab-left text-upper">
                                            Sale</div>
                                        <div
                                            class="badge bg-danger text-white position-absolute ft-regular ab-right text-upper">
                                            {{ $related->product_discount }}%</div>
                                    @endif
                                    <div
                                        class="badge bg-success text-white position-absolute ft-regular ab-left text-upper">
                                        Sale</div>
                                    <div class="card-body p-0">
                                        <div class="shop_thumb position-relative">
                                            <a class="card-img-top d-block overflow-hidden"
                                                href="{{ $related->slug }}"><img class="card-img-top"
                                                    src="{{ asset('uploads/products/preview') }}/{{ $related->preview }}"
                                                    alt="..."></a>
                                        </div>
                                    </div>
                                    <div class="card-footer b-0 p-3 pb-0 d-flex align-items-start justify-content-center">
                                        <div class="text-left">
                                            <div class="text-center">
                                                <h5 class="fw-bolder fs-md mb-0 lh-1 mb-1"><a
                                                        href="{{ route('product.details', $related->slug) }}">{{ $related->product_name }}</a>
                                                </h5>
                                                <div class="elis_rty">
                                                    <span class="ft-bold fs-md text-dark">{{ $related->after_discount }}
                                                        Tk</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>

        </div>
    </section>
    <!-- ======================= Similar Products Start ============================ -->
@endsection
@section('footer_script')
    <script>
        $('.colorId').click(function() {
            var color_id = $(this).val();
            var product_id = '{{ $product_info->first()->id }}';
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/getSize',
                type: 'POST',
                data: {
                    'color_id': color_id,
                    'product_id': product_id
                },
                success: function(data) {
                    $('#size_id').html(data)
                }
            })
        })
    </script>
@endsection
