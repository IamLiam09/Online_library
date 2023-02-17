
<!DOCTYPE html>
<html lang="en" dir="{{env('SITE_RTL') == 'on'?'rtl':''}}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="LMSGo - Learning Management System">
    <meta name="author" content="Rajodiya Infotech">

    <title>{{'Product Details'}} - {{($store->tagline) ?  $store->tagline : config('APP_NAME', ucfirst($store->name))}}</title>

    <link rel="icon" href="{{asset(Storage::url('uploads/logo/').(!empty($settings->value)?$settings->value:'favicon.png'))}}" type="image/png">

    <link rel="stylesheet" href="{{asset('libs/@fortawesome/fontawesome-free/css/all.min.css')}}"><!-- Page CSS -->
    <link rel="stylesheet" href="{{asset('libs/animate.css/animate.min.css')}}">
    <link rel="stylesheet" href="{{asset('libs/swiper/dist/css/swiper.min.css')}}">
    <link rel="stylesheet" href="{{asset('libs/@fancyapps/fancybox/dist/jquery.fancybox.min.css')}}">
    <link rel="stylesheet" href="{{asset('libs/animate.css/animate.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/site.css')}}" id="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css')}}" id="stylesheet')}}">
    @if(env('SITE_RTL')=='on')
        <link rel="stylesheet" href="{{ asset('css/bootstrap-rtl.css') }}">
    @endif
    <script type="text/javascript" src="{{asset('libs/jquery/dist/jquery.min.js')}}"></script>
</head>
<style>
    .shoping_count:after {
        content: attr(value);
        font-size: 14px;
        background: #273444;
        border-radius: 50%;
        padding: 1px 5px 1px 4px;
        position: relative;
        left: -5px;
        top: -10px;
    }

    @media (min-width: 768px) {
        .header-account-page {
            height: 100px;
        }
    }
</style>
<body>
@php
    if(!empty(session()->get('lang')))
    {
        $currantLang = session()->get('lang');
    }else{
        $currantLang = $store->lang;
    }
    $languages= Utility::languages();
@endphp
<header class="header" id="header-main">
    <nav class="navbar navbar-main navbar-expand-lg navbar-dark bg-dark navbar-border store-frontside" id="navbar-main">
        <div class="container-fluid">
            <!-- User's navbar -->
            <div class="navbar-user d-lg-none">
                <a class="navbar-brand mr-lg-3 pt-0" href="{{route('store.slug',$store->slug)}}">
                    @if(!empty($store->logo))
                        <img alt="Image placeholder" src="{{asset(Storage::url('uploads/store_logo/'.$store->logo))}}" id="navbar-logo" style="height: 40px;">
                    @else
                        <img alt="Image placeholder" src="{{asset(Storage::url('uploads/store_logo/logo.png'))}}" id="navbar-logo" style="height: 40px;">
                    @endif
                </a>
                <div class="navbar-nav align-items-lg-center">
                    <span class="nav-link navbar-text mr-3 text-lg">{{ucfirst($store->name)}}</span>
                </div>
            </div>

            <div class="ml-auto">
                <ul class="nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="text-sm mb-0"><i class="fas fa-globe-asia"></i>
                                    {{Str::upper($currantLang)}}
                                </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                            @foreach($languages as $language)
                                <a href="{{route('change.languagestore',[$store->slug,$language])}}" class="dropdown-item @if($language == $currantLang) active-language @endif">
                                    <span> {{Str::upper($language)}}</span>
                                </a>
                            @endforeach
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('store.cart',$store->slug)}}">
                            <i class="fa badge shoping_count" id="shoping_count" style="font-size:16px" value="{{!empty($total_item)?$total_item:'0'}}">&#xf07a;</i>
                        </a>
                    </li>
                </ul>
            </div>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-main-collapse" aria-controls="navbar-main-collapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse navbar-collapse-fade" id="navbar-main-collapse">
                <ul class="navbar-nav align-items-lg-center">
                    <!-- Home  -->
                    <li class="nav-item dropdown dropdown-animate" data-toggle="hover">
                        @if(!empty($page_slug_urls))
                            @foreach($page_slug_urls as $k=>$page_slug_url)
                                @if($page_slug_url->enable_page_header == 'on')
                                    <ul class="navbar-nav align-items-lg-center">
                                        <li class="nav-item ">
                                            <a class="nav-link" href="{{env('APP_URL') . '/page/' . $page_slug_url->slug}}">{{ucfirst($page_slug_url->name)}}</a>
                                        </li>
                                    </ul>
                                @endif
                            @endforeach
                        @endif
                    </li>
                    <li class="nav-item dropdown dropdown-animate" data-toggle="hover">
                        @if($store->blog_enable == 'on' && $blog > 0)
                            <ul class="navbar-nav align-items-lg-center">
                                <li class="nav-item ">
                                    <a class="nav-link" href="{{route('store.blog',$store->slug)}}">{{__('Blog')}}</a>
                                </li>
                            </ul>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Topbar -->
    <div id="navbar-top-main" class="navbar-top  navbar-dark bg-dark border-bottom">
        <div class="container px-0">
            <div class="navbar-nav align-items-center">
                <a class="navbar-brand mr-lg-3 pt-0" href="{{route('store.slug',$store->slug)}}">
                    @if(!empty($store->logo))
                        <img alt="Image placeholder" src="{{asset(Storage::url('uploads/store_logo/'.$store->logo))}}" id="navbar-logo" style="height: 40px;">
                    @else
                        <img alt="Image placeholder" src="{{asset(Storage::url('uploads/store_logo/logo.png'))}}" id="navbar-logo" style="height: 40px;">
                    @endif
                </a>
                <div class="navbar-nav align-items-lg-center">
                    <span class="nav-link navbar-text mr-3 text-lg">{{ucfirst($store->name)}}</span>
                </div>
                @if(!empty($page_slug_urls))
                    @foreach($page_slug_urls as $k=>$page_slug_url)
                        @if($page_slug_url->enable_page_header == 'on')
                            <ul class="navbar-nav align-items-lg-center">
                                <li class="nav-item ">
                                    <a class="nav-link" href="{{env('APP_URL') . '/page/' . $page_slug_url->slug}}">{{ucfirst($page_slug_url->name)}}</a>
                                </li>
                            </ul>
                        @endif
                    @endforeach
                @endif
                @if($store->blog_enable == 'on' && $blog > 0)
                    <ul class="navbar-nav align-items-lg-center">
                        <li class="nav-item ">
                            <a class="nav-link" href="{{route('store.blog',$store->slug)}}">{{__('Blog')}}</a>
                        </li>
                    </ul>
                @endif
                <div class="ml-auto rtl_mr_auto">
                    <ul class="nav">
                        <li class="nav-item dropdown ml-lg-2">
                            <div class="dropdown-menu dropdown-menu-sm">
                                @foreach($languages as $language)
                                    <a href="{{route('change.languagestore',[$store->slug,$language])}}" class="dropdown-item @if($language == $currantLang) active-language @endif">
                                        <span> {{Str::upper($language)}}</span>
                                    </a>
                                @endforeach
                            </div>
                            <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="text-sm mb-0"><i class="fas fa-globe-asia"></i>
                                    {{Str::upper($currantLang)}}
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('store.cart',$store->slug)}}">
                                <i class="fa badge shoping_count" id="shoping_count" style="font-size:16px" value="{{!empty($total_item)?$total_item:'0'}}">&#xf07a;</i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="main-content">
    <header class="p-3 d-flex align-items-end">
        <!-- Header container -->
        <div class="container">
            <div class="row">
                <div class=" col-lg-12">
                    <!-- Salute + Small stats -->
                    <div class="row align-items-center">
                        <div class="col-md-5 mb-4 mb-md-0">
                            <span class="h2 mb-0 text-dark d-block">{{__('Course')}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <section>
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card {{ ($store_setting->enable_rating == 'off')?'card-fluid':''}}">
                        <div class="card-body">
                            <!-- Product title -->
                            <h5 class="h4">{{$products->name}}</h5>
                            <!-- Rating -->
                            <div class="row align-items-center">
                                <div class="col-sm-6">
                                    @if($store_setting->enable_rating == 'on')
                                        @for($i =1;$i<=5;$i++)
                                            @php
                                                $icon = 'fa-star';
                                                $color = '';
                                                $newVal1 = ($i-0.5);
                                                if($avg_rating < $i && $avg_rating >= $newVal1)
                                                {
                                                    $icon = 'fa-star-half-alt';
                                                }
                                                if($avg_rating >= $newVal1)
                                                {
                                                    $color = 'text-warning';
                                                }
                                            @endphp
                                            <i class="fas {{$icon .' '. $color}}"></i>
                                        @endfor
                                        {{$avg_rating}}/5 ({{$user_count}} {{__('reviews')}})
                                    @endif
                                </div>
                                <div class="col-sm-6 text-sm-right">
                                    <ul class="list-inline mb-0">
                                        <li class="list-inline-item">
                                            <span class="badge badge-pill badge-soft-info">{{__('ID: #')}}{{$products->SKU}}</span>
                                        </li>
                                        <li class="list-inline-item">
                                            @if($products->quantity == 0)
                                                <span class="badge badge-pill badge-soft-danger">
                                            {{__('Out of stock')}}
                                        </span>
                                            @else
                                                <span class="badge badge-pill badge-soft-success">
                                            {{__('In stock')}}
                                        </span>
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- Description -->
                            {!! $products->description !!}
                        </div>
                    </div>
                    @if($store_setting->enable_rating == 'on')
                        <div class="card">
                            <div class="card-body p-3">
                                <h5 class="float-left mb-0 pt-2">{{__('Rating')}}</h5>
                                <a href="#" class="btn btn-sm btn-primary btn-icon-only rounded-circle float-right text-white" data-size="lg" data-toggle="modal" data-url="{{route('rating',[$store->slug,$products->id])}}" data-ajax-popup="true" data-title="{{__('Create New Rating')}}">
                                    <i class="ti ti-plus"></i>
                                </a>

                            </div>
                            @foreach($product_ratings as $product_key => $product_rating)
                                @if($product_rating->rating_view == 'on')
                                    <div id="review_list" class="px-3 pt-2 border-top pb-0">
                                        <div class="theme-review float-left" id="comment_126267">
                                            <div class="theme_review_item">
                                                <div class="theme-review__heading">
                                                    <div class="theme-review__heading__item text-sm small">
                                                        <h6>{{$product_rating->title}}</h6>
                                                        <tr class="list-dotted ">
                                                            <td class="list-dotted__item">by {{$product_rating->name}} :</td>
                                                            <td class="list-dotted__item">{{$product_rating->created_at->diffForHumans()}}</td>
                                                        </tr>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="rate float-right">
                                            @for($i =0;$i<5;$i++)
                                                <i class="fas fa-star {{($product_rating->ratting > $i  ? 'text-warning' : '')}}"></i>
                                            @endfor
                                        </div>
                                        <span class="clearfix"></span>
                                        <br>
                                        <div class="main_reply_body">
                                            <p class="small">{{$product_rating->description}}</p>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="col-lg-6">
                    @if($products->enable_product_variant =='on')
                        <div class="card">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <input type="hidden" id="product_id" value="{{$products->id}}">
                                    <input type="hidden" id="variant_id" value="">
                                    <input type="hidden" id="variant_qty" value="">
                                    @foreach($product_variant_names as $key => $variant)
                                        <div class="col-sm-6 mb-4 mb-sm-0">
                                            <span class="d-block h6 mb-0">
                                                <th><span>{{ $variant->variant_name }}</span></th>

                                                <select name="product[{{$key}}]" id="pro_variants_name" class="form-control custom-select variant-selection  pro_variants_name{{$key}}">
                                                    <option value="">{{ __('Select')  }}</option>
                                                @foreach($variant->variant_options as $key => $values)
                                                        <option value="{{$values}}">{{$values}}</option>
                                                    @endforeach
                                            </select>
                                        </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-sm-6 mb-4 mb-sm-0">
                                    <span class="d-block h3 mb-0 variation_price">
                                        @if($products->enable_product_variant =='on')
                                            {{ Utility::priceFormat(0)}}
                                        @else
                                            {{ Utility::priceFormat($products->price)}}
                                        @endif
                                    </span>
                                </div>
                                <div class="col-sm-6 text-sm-right">
                                    <a class="action-item add_to_cart" data-toggle="tooltip" data-id="{{$products->id}}">
                                        <button type="button" class="btn btn-primary btn-icon">
                                            <span class="btn-inner--icon">
                                                <i class="fas fa-shopping-cart"></i>
                                            </span>
                                            <span class="btn-inner--text">{{__('Add to cart')}}</span>
                                        </button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Product images -->
                    <div class="card">
                        <div class="card-body">
                            <a href="{{asset(Storage::url('uploads/is_cover_image/'.$products->is_cover))}}" data-fancybox data-caption="My caption">
                                @if(!empty($products->is_cover))
                                    <img alt="Image placeholder" src="{{asset(Storage::url('uploads/is_cover_image/'.$products->is_cover))}}" class="img-fluid rounded">
                                @else
                                    <img alt="Image placeholder" src="{{asset(Storage::url('uploads/is_cover_image/default.jpg'))}}" class="img-fluid rounded">
                                @endif
                            </a>
                            <div class="row mt-4">
                                @foreach($products_image as $key => $productss)
                                    <div class="col-4">
                                        <div class="p-3 border rounded">
                                            @if(!empty($products_image[$key]->product_images))
                                                <a href="{{asset(Storage::url('uploads/product_image/'.$products_image[$key]->product_images))}}" class="stretched-link" data-fancybox="product">
                                                    <img alt="Image placeholder" src="{{asset(Storage::url('uploads/product_image/'.$products_image[$key]->product_images))}}" class="img-fluid">
                                                </a>
                                            @else
                                                <a href="{{asset(Storage::url('uploads/product_image/de'))}}" class="stretched-link" data-fancybox="product">
                                                    <img alt="Image placeholder" src="{{asset(Storage::url('uploads/product_image/default.jpg'))}}" class="img-fluid">
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4 text-center m-3">
            <a href="{{route('store.slug',$store->slug)}}" class="btn btn-link text-sm text-white badge-dark bor-radius py-2">{{__('Return to shop')}}</a>
        </div>
    </section>
</div>
<footer id="footer-main">
    <div class="footer footer-dark pt-4 pb-2">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="copyright text-sm font-weight-bold text-center text-md-left rtl-right pt-1">
                        {{$store->footer_note}}
                    </div>
                    <ul class="nav mt-3 mt-md-0">
                        @if(!empty($store->email))
                            <li class="nav-item">
                                <a class="nav-link pl-0" href="{{$store->email}}" target="_blank">
                                    <i class="fas fa-envelope"></i>
                                </a>
                            </li>
                        @endif
                        @if(!empty($store->whatsapp))
                            <li class="nav-item">
                                <a class="nav-link" href="{{$store->whatsapp}}" target=”_blank”>
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </li>
                        @endif
                        @if(!empty($store->facebook))
                            <li class="nav-item">
                                <a class="nav-link" href="{{$store->facebook}}" target="_blank">
                                    <i class="fab fa-facebook-square"></i>
                                </a>
                            </li>
                        @endif
                        @if(!empty($store->instagram))
                            <li class="nav-item">
                                <a class="nav-link" href="{{$store->instagram}}" target="_blank">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </li>
                        @endif
                        @if(!empty($store->twitter))
                            <li class="nav-item">
                                <a class="nav-link" href="{{$store->twitter}}" target="_blank">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            </li>
                        @endif
                        @if(!empty($store->youtube))
                            <li class="nav-item">
                                <a class="nav-link" href="{{$store->youtube}}" target="_blank">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
                <div class="col-md-6">
                    <div class="nav justify-content-center justify-content-md-end mt-3 mt-md-0">
                        @if(!empty($page_slug_urls))
                            @foreach($page_slug_urls as $k=>$page_slug_url)
                                @if($page_slug_url->enable_page_footer == 'on')
                                    <div class="nav-item ">
                                        <a class="nav-link" href="{{env('APP_URL') . '/page/' . $page_slug_url->slug}}">{{ucfirst($page_slug_url->name)}}</a>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<div class="modal fade" id="commonModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header align-items-center">
                <div class="modal-title">
                    <h6 class="mb-0" id="modelCommanModelLabel"></h6>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>
<!-- Core JS - includes jquery, bootstrap, popper, in-view and sticky-kit -->
<script src="{{asset('js/site.core.js')}}"></script>
<!-- notify -->
<script type="text/javascript" src="{{ asset('js/custom.js')}}"></script>
<script src="{{ asset('libs/bootstrap-notify/bootstrap-notify.min.js')}}"></script>
<script src="{{ asset('libs/@fancyapps/fancybox/dist/jquery.fancybox.min.js')}}"></script>
<!-- Page JS -->
<script src="{{asset('libs/swiper/dist/js/swiper.min.js')}}"></script>
<!-- Site JS -->
<script src="{{asset('js/site.js')}}"></script>
<!-- Demo JS - remove it when starting your project -->
<script src="{{asset('assets/js/demo.js')}}"></script>
@php
    $store_settings = \App\MOdels\Store::where('slug',$store->slug)->first();
@endphp

<script async src="https://www.googletagmanager.com/gtag/js?id={{$store_settings->google_analytic}}"></script>

{!! $store_settings->storejs !!}

<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }

    gtag('js', new Date());

    gtag('config', '{{ $store_settings->google_analytic }}');
</script>
<script>
    $(".add_to_cart").click(function (e) {
        e.preventDefault();
        var id = $(this).attr('data-id');
        var variants = [];
        $(".variant-selection").each(function (index, element) {
            variants.push(element.value);
        });

        if (jQuery.inArray('', variants) != -1) {
            show_toastr('Error', "{{ __('Please select all option.') }}", 'error');
            return false;
        }

        var variation_ids = $('#variant_id').val();

        $.ajax({
            url: '{{route('user.addToCart', ['__product_id',$store->slug,'variation_id'])}}'.replace('__product_id', id).replace('variation_id', variation_ids ?? 0),
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                variants: variants.join(' : '),
            },
            success: function (response) {
                if (response.status == "Success") {
                    show_toastr('Success', response.success, 'success');
                    $("#shoping_count").attr("value", response.item_count);
                } else {
                    show_toastr('Error', response.error, 'error');
                }
            },
            error: function (result) {
                console.log('error');
            }
        });
    });

    $(document).on('change', '#pro_variants_name', function () {

        var variants = [];
        $(".variant-selection").each(function (index, element) {
            variants.push(element.value);
        });
        if (variants.length > 0) {
            $.ajax({
                url: '{{route('get.products.variant.quantity')}}',
                data: {
                    "_token": $('meta[name="csrf-token"]').attr('content'),
                    variants: variants.join(' : '),
                    product_id: $('#product_id').val()
                },

                success: function (data) {
                    console.log(data);
                    $('.variation_price').html(data.price);
                    $('#variant_id').val(data.variant_id);
                    $('#variant_qty').val(data.quantity);
                }
            });
        }
    });

</script>
@if(Session::has('success'))
    <script>
        show_toastr('{{__('Success')}}', '{!! session('success') !!}', 'success');
    </script>
    {{ Session::forget('success') }}
@endif
@if(Session::has('error'))
    <script>
        show_toastr('{{__('Error')}}', '{!! session('error') !!}', 'error');
    </script>
    {{ Session::forget('error') }}
@endif
</body>
</html>

