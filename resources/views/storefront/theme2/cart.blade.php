@extends('layouts.theme2.shopfront')
@section('page-title')
    {{__('Cart')}} - {{($store->tagline) ?  $store->tagline : config('APP_NAME', ucfirst($store->name))}}
@endsection
@push('css-page')
    <style>
        .modal-body {
            padding: 25px !important;
            font-weight: bold;
        }
    </style>
@endpush
@section('content')
  
    <div class="wrapper">
        <section class="common-banner-section"  style="background-image:url({{ asset('assets/themes/theme2/images/comman-banner.png') }});">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5 col-md-6">
                        <div class="common-banner-content">
                            <a href="{{route('store.slug',$store->slug)}}" class="back-btn">
                                <span class="svg-ic">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="11" height="5" viewBox="0 0 11 5" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M10.5791 2.28954C10.5791 2.53299 10.3818 2.73035 10.1383 2.73035L1.52698 2.73048L2.5628 3.73673C2.73742 3.90636 2.74146 4.18544 2.57183 4.36005C2.40219 4.53467 2.12312 4.53871 1.9485 4.36908L0.133482 2.60587C0.0480403 2.52287 -0.000171489 2.40882 -0.000171488 2.2897C-0.000171486 2.17058 0.0480403 2.05653 0.133482 1.97353L1.9485 0.210321C2.12312 0.0406877 2.40219 0.044729 2.57183 0.219347C2.74146 0.393966 2.73742 0.673036 2.5628 0.842669L1.52702 1.84888L10.1383 1.84875C10.3817 1.84874 10.5791 2.04609 10.5791 2.28954Z" fill="white"></path>
                                    </svg>
                                </span>
                                {{__('Back to Home')}}
                            </a>
                            <div class="section-title">
                                <h2>{{__('My Cart')}}</h2>
                            </div>
                            <p>{{ __('Lorem Ipsum is simply dummy text of the printing and typesetting industry.Lorem Ipsum is simply dummy text of the printing and typesetting industry.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @php
            $cart = session()->get($store->slug);
        @endphp
        @if(!empty($cart['products']) || $cart['products'] = [])
            <section class="cart-page-section padding-bottom padding-top">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-9 col-12">
                            @php
                                $total = 0;
                                $arr_course_id = [];
                            @endphp
                            <ol class="cart-tble-list">
                                @foreach($products['products'] as $key => $product)
                                    @php
                                        $store_currency             = $store->currency;
                                        $price = str_replace($store_currency, '', $product['price']);
                                        $total += $price;
                                        array_push($arr_course_id , $product['id']);
                                    @endphp
                                    <li class="cart-item">
                                        <div class="cart-item-inner">
                                            <div class="product-image">
                                                <a href="{{route('store.viewcourse',[$store->slug,\Illuminate\Support\Facades\Crypt::encrypt($product['product_id'])])}}">
                                                <!-- <img src="assets/images/cart-img.png" alt=""> -->
                                                    @if(!empty($product['image']))
                                                        <img alt="Image placeholder" src="{{asset($product['image'])}}">
                                                    @else
                                                        <img src="{{ asset('assets/themes/theme2/images/cart-img.png') }}" alt="">
                                                    @endif
                                                </a>
                                            </div>
                                            <div class="product-cart-summry">
                                                <div class="name">
                                                    <a href="{{route('store.viewcourse',[$store->slug,\Illuminate\Support\Facades\Crypt::encrypt($product['product_id'])])}}">{{$product['product_name']}}</a>
                                                    <p>{{ __('Lorem Ipsum is simply dummy text of the printing and typesetting industry.') }}</p>
                                                </div>                                    
                                                {{-- <div class="qty-spinner-wrap">
                                                    <div class="qty-spinner">
                                                        <button type="button" class="quantity-decrement">
                                                            <svg width="12" height="2" viewBox="0 0 12 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M0 0.251343V1.74871H12V0.251343H0Z" fill="#61AFB3"></path>
                                                            </svg>
                                                        </button>
                                                        <input type="text" class="quantity" data-cke-saved-name="quantity" name="quantity" value="01" min="01" max="100">
                                                        <button type="button" class="quantity-increment ">
                                                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M6.74868 5.25132V0H5.25132V5.25132H0V6.74868H5.25132V12H6.74868V6.74868H12V5.25132H6.74868Z" fill="#61AFB3"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div> --}}
                                                                                                
                                                <div class="cart-price">
                                                    {{($price>1)? Utility::priceFormat($price): Utility::priceFormat($price).'(Free)'}}
                                                </div>
                                                <div class="cart-remove">
                                                    <a href="#" class="delete-icon" data-toggle="tooltip" data-original-title="{{__('Move to trash')}}" data-confirm="{{__('Are You Sure?').' | '.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-product-cart-{{$key}}').submit();">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" aria-hidden="true" focusable="false" role="presentation" class="icon icon-remove">
                                                            <path d="M14 3h-3.53a3.07 3.07 0 00-.6-1.65C9.44.82 8.8.5 8 .5s-1.44.32-1.87.85A3.06 3.06 0 005.53 3H2a.5.5 0 000 1h1.25v10c0 .28.22.5.5.5h8.5a.5.5 0 00.5-.5V4H14a.5.5 0 000-1zM6.91 1.98c.23-.29.58-.48 1.09-.48s.85.19 1.09.48c.2.24.3.6.36 1.02h-2.9c.05-.42.17-.78.36-1.02zm4.84 11.52h-7.5V4h7.5v9.5z" fill="currentColor"></path>
                                                            <path d="M6.55 5.25a.5.5 0 00-.5.5v6a.5.5 0 001 0v-6a.5.5 0 00-.5-.5zM9.45 5.25a.5.5 0 00-.5.5v6a.5.5 0 001 0v-6a.5.5 0 00-.5-.5z" fill="currentColor"></path>
                                                        </svg>
                                                    </a>
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['delete.cart_item',[$store->slug,$product['product_id']]],'id'=>'delete-product-cart-'.$key]) !!}
                                                    {!! Form::close() !!}
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                        <div class="col-lg-3 col-12">
                            @php
                                $total = 0;
                                $sub_total = 0;
                            @endphp
                            <div class="cart-summery">
                                <ul>
                                    @if(!empty($cart['products']))
                                        @foreach($cart['products'] as $k => $value)
                                            @php
                                                $total += $value['price'];
                                                $sub_total += $value['price'];

                                                $cart = session()->get($slug);
                                                $total_item = 0;
                                                if(isset($cart['products']))
                                                {
                                                    foreach($cart['products'] as $item)
                                                    {
                                                        if(isset($cart) && !empty($cart['products']))
                                                        {
                                                            $total_item = count($cart['products']);
                                                        }
                                                    }
                                                }
                                            @endphp
                                        @endforeach
                                    @endif
                                    <li>
                                        <span class="cart-sum-left">{{$total_item}} {{ __('Item') }}</span>
                                        <span class="cart-sum-right">{{ Utility::priceFormat($total)}}</span>
                                    </li> 
                                </ul>
                                <a href="{{route('store.checkout',[$store->slug,\Illuminate\Support\Facades\Crypt::encrypt(json_encode($arr_course_id)),(!empty($total))?$total:0])}}" class="btn checkout-btn">
                                    {{__('Proceed to checkout')}}
                                    <svg viewBox="0 0 10 5">
                                        <path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z">
                                        </path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @else
            <section class="empty-cart padding-top padding-bottom" data-offset-top="#header-main">
                <!-- SVG background -->
                <div class="container">

                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <div class="empty-detail-wrap">
                                <div class="empty-image">
                                    <img alt="Image placeholder" src="{{asset('assets/img/online-shopping.svg')}}">
                                </div>
                                <div class="empty-content">
                                    <div class="section-title">
                                        <h2>{{__('Your cart is empty')}}.</h6>
                                    </div>
                                    <h6>
                                        {{__('Your cart is currently empty.We have some great courses that you might want to learn')}}.
                                    </h6>
                                        <a href="{{route('store.slug',$store->slug)}}" class="btn">
                                        {{__('Return to shop')}}
                                            <svg viewBox="0 0 10 5">
                                                <path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z">
                                                </path>
                                            </svg>
                                        </a>
                                </div>
                            </div>
                        </div>
                    </div>            
                </div>
            </section>
        @endif 
    </div>
    
@endsection
@push('script-page')

@endpush
