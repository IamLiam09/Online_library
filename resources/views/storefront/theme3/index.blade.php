@extends('layouts.theme3.shopfront')
@section('page-title')
    {{__('Home')}} - {{($store->tagline) ?  $store->tagline : config('APP_NAME', ucfirst($store->name))}}
@endsection
@push('css-page')
@endpush
@push('script-page')
    <!-- CART -->
    <script>
        $(document).on('click', '.add_to_cart', function (e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            $.ajax({
                type: "POST",
                url: '{{route('user.addToCart', ['__product_id',$store->slug])}}'.replace('__product_id', id),
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function (response) {
                    if (response.status == "Success") {
                        show_toastr('Success', response.success, 'success');
                        $('#cart-btn-' + id).html('Already in Cart');
                        $('.sale-section #cart-btn-' + id).html('Already in Cart');
                        $('.cart_item_count').html(response.item_count);
                    } else {
                        show_toastr('Error', response.error, 'error');
                    }

                },
                error: function (result) {
                }
            });
        });
    </script>
@endpush

@section('content')

    <!-- HEADER SECTION -->
    @php
        $main_homepage_header_text_key = array_search('Home-Header',array_column($getStoreThemeSetting, 'section_name'));        
        $header_enable = 'off';
        $homepage_header_title = 'Improve Your<br> Skills with <br> ModernCourse.';
        $homepage_header_offer_description = 'The Data Science Course 2021: Complete Data Science Bootcamp $490.00';
        $homepage_header_Sub_text = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.';
        $homepage_header_button = 'Show More Courses';
        $homepage_header_Bckground_Image = '';

        if(!empty($getStoreThemeSetting[$main_homepage_header_text_key])) {
            $homepage_header = $getStoreThemeSetting[$main_homepage_header_text_key];
            $header_enable = $homepage_header['section_enable'];

            $homepage_header_title_key = array_search('Title',array_column($homepage_header['inner-list'], 'field_name'));
            $homepage_header_title = $homepage_header['inner-list'][$homepage_header_title_key]['field_default_text'];

            $homepage_header_offer_description_key = array_search('homepage-header-offer-description',array_column($homepage_header['inner-list'], 'field_slug'));
            $homepage_header_offer_description = $homepage_header['inner-list'][$homepage_header_offer_description_key]['field_default_text'];
            
            $homepage_header_Sub_text_key = array_search('Sub Title',array_column($homepage_header['inner-list'], 'field_name'));
            $homepage_header_Sub_text = $homepage_header['inner-list'][$homepage_header_Sub_text_key]['field_default_text'];

            $homepage_header_button_key = array_search('Button',array_column($homepage_header['inner-list'], 'field_name'));
            $homepage_header_button = $homepage_header['inner-list'][$homepage_header_button_key]['field_default_text'];

            $homepage_header_Bckground_Image_key = array_search('Thumbnail Image',array_column($homepage_header['inner-list'], 'field_name'));
            $homepage_header_Bckground_Image = $homepage_header['inner-list'][$homepage_header_Bckground_Image_key]['field_default_text'];
        }
    @endphp        
    @if($header_enable == 'on')
        <section class="main-home-first-section padding-top  border-bottom  padding-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 col-12">
                        <div class="home-banner-content">
                            <div class="home-banner-content-inner">
                                @if(!empty($special_offer_courses) && isset($special_offer_courses))
                                    <div class="offer-announcement">
                                        <p> {{ $homepage_header_offer_description }} </p>
                                        <a href="{{route('store.viewcourse',[$store->slug,\Illuminate\Support\Facades\Crypt::encrypt($special_offer_courses->id)])}}" class="link-btn">{{ __('Get now') }} <svg viewBox="0 0 10 5">
                                            <path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z">
                                            </path>
                                        </svg></a>
                                    </div>
                                @endif
                                <h2 class="h1">{!! $homepage_header_title !!}</h2>
                                <p>{{ $homepage_header_Sub_text }} </p>
                                <div class="btn-group d-flex">
                                    <a href="{{route('store.search',[$store->slug])}}" class="btn">{{ $homepage_header_button }}<svg viewBox="0 0 10 5">
                                        <path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z">
                                        </path>
                                    </svg></a>
                                
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="banner-left-side">
                            <div class="advance-search">
                                <div class="advance-search-back">
                                    <div class="advance-search-image">
                                        @if(!empty($homepage_header_Bckground_Image))
                                            <img src="{{asset(Storage::url('uploads/'.$homepage_header_Bckground_Image))}}">                                  
                                        @else
                                            <img src="{{ asset('assets/themes/theme3/images/banner-img.jpg') }}" alt="">
                                        @endif                                        
                                    </div>
                                </div>
                                {{-- <div class="advance-search-form">
                                    <form action="">
                                        <div class="input-wrapper">
                                            <input type="search" placeholder="UX Design Certificate">
                                            <select>
                                                <option value="UX Design">UX Design</option>
                                                <option value="UX Design">UX Design</option>
                                                <option value="UX Design">UX Design</option>
                                                <option value="UX Design">UX Design</option>
                                            </select>
                                        </div>
                                    </form>
                                </div>  --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- FEATURED COURSE -->
    @php
        $main_homepage_featured_course_key = array_search('Home-Featured Course',array_column($getStoreThemeSetting, 'section_name'));
        $featured_course_enable = 'off';
        $homepage_featured_course_title = 'Featured Course';
        $homepage_featured_course_button = 'Learn More';

        if(!empty($getStoreThemeSetting[$main_homepage_featured_course_key])) {
            $homepage_featured_course_header = $getStoreThemeSetting[$main_homepage_featured_course_key];
            $featured_course_enable = $homepage_featured_course_header['section_enable'];

            $homepage_featured_course_title_key = array_search('Title',array_column($homepage_featured_course_header['inner-list'], 'field_name'));            
            $homepage_featured_course_title = $homepage_featured_course_header['inner-list'][$homepage_featured_course_title_key]['field_default_text'];
            
            $homepage_featured_course_button_key = array_search('Button',array_column($homepage_featured_course_header['inner-list'], 'field_name'));
            $homepage_featured_course_button = $homepage_featured_course_header['inner-list'][$homepage_featured_course_button_key]['field_default_text'];
        }          
    @endphp
    @if($featured_course_enable == 'on')
        @if($featured_course->count()>0)
            <section class="earn-degree-section border-bottom padding-bottom padding-top">
                <div class="container">
                    <div class="section-title d-flex align-items-center justify-content-between ">
                        
                        <h2>{{ $homepage_featured_course_title }}</h2>
                        <a href="{{route('store.search',[$store->slug])}}" class="btn"> {{ $homepage_featured_course_button }} 
                            <svg viewBox="0 0 10 5">
                                <path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z">
                                </path>
                            </svg>
                        </a>
                    </div>
                    <div class="row product-row course-degree">
                        @foreach($featured_course as $course)
                            @php
                                $cart = session()->get($slug);
                                $key = false;
                            @endphp                           
                            @if(!empty($cart['products']))
                                @foreach($cart['products'] as $k => $value)
                                    @if($course->id == $value['product_id'])
                                        @php
                                            $key = $k
                                        @endphp
                                    @endif
                                @endforeach
                            @endif   
                            <div class="col-md-4 col-sm-6 col-12 product-card course-degree">
                                <div class="product-card-inner">
                                    <div class="product-img">
                                        <a href="{{route('store.viewcourse',[$store->slug,\Illuminate\Support\Facades\Crypt::encrypt($course->id)])}}">
                                            @if(!empty($course->thumbnail))
                                                <img alt="card" src="{{asset(Storage::url('uploads/thumbnail/'.$course->thumbnail))}}" class="img-fluid">
                                            @else
                                                <img src="{{asset('assets/themes/theme3/images/bachelor-of-sci.jpg')}}" alt="card" class="img-fluid">
                                            @endif
                                        </a>
                                        
                                        <div class="subtitle-top d-flex align-items-center justify-content-between">
                                            <span class="badge">{{!empty($course->category_id)?$course->category_id->name:'-'}}</span>

                                            @if(Auth::guard('students')->check())
                                                @if(sizeof($course->student_wl)>0)
                                                    {{-- @foreach($course->student_wl as $student_wl) --}}
                                                        <a  class="wishlist-btn wishlist_btn add_to_wishlist" data-id="{{$course->id}}" tabindex="0" data-placement="top">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M15.1335 2.95108C16.73 4.16664 16.9557 6.44579 15.6274 7.93897L8.99983 15.3894L2.37233 7.93977C1.04381 6.44646 1.26946 4.167 2.86616 2.95128C4.50032 1.70704 6.87275 2.10393 7.99225 3.80885L8.36782 4.38082C8.59267 4.72325 9.05847 4.82238 9.40821 4.60224C9.51777 4.53328 9.60294 4.44117 9.66134 4.33666L10.0076 3.80914C11.1268 2.10394 13.4993 1.70679 15.1335 2.95108ZM8.99998 2.653C7.31724 0.526225 4.15516 0.102335 1.94184 1.78754C-0.33726 3.52284 -0.659353 6.77651 1.23696 8.90805L8.4334 16.9972C8.7065 17.3041 9.18204 17.3362 9.49557 17.0688C9.53631 17.0341 9.57231 16.996 9.60351 16.9553L16.7628 8.90721C18.6589 6.77579 18.3367 3.52246 16.0579 1.78734C13.8446 0.102142 10.6825 0.526185 8.99998 2.653Z" fill="white"/>
                                                            </svg>
                                                        </a> 
                                                    {{-- @endforeach --}}
                                                @else
                                                    <a href="#" class="wishlist-btn add_to_wishlist" tabindex="0" data-id="{{$course->id}}" data-placement="top">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M15.1335 2.95108C16.73 4.16664 16.9557 6.44579 15.6274 7.93897L8.99983 15.3894L2.37233 7.93977C1.04381 6.44646 1.26946 4.167 2.86616 2.95128C4.50032 1.70704 6.87275 2.10393 7.99225 3.80885L8.36782 4.38082C8.59267 4.72325 9.05847 4.82238 9.40821 4.60224C9.51777 4.53328 9.60294 4.44117 9.66134 4.33666L10.0076 3.80914C11.1268 2.10394 13.4993 1.70679 15.1335 2.95108ZM8.99998 2.653C7.31724 0.526225 4.15516 0.102335 1.94184 1.78754C-0.33726 3.52284 -0.659353 6.77651 1.23696 8.90805L8.4334 16.9972C8.7065 17.3041 9.18204 17.3362 9.49557 17.0688C9.53631 17.0341 9.57231 16.996 9.60351 16.9553L16.7628 8.90721C18.6589 6.77579 18.3367 3.52246 16.0579 1.78734C13.8446 0.102142 10.6825 0.526185 8.99998 2.653Z" fill="white"/>
                                                        </svg>
                                                    </a>
                                                @endif
                                            @else
                                                <a href="#" class="wishlist-btn add_to_wishlist" tabindex="0" data-id="{{$course->id}}" data-placement="top">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M15.1335 2.95108C16.73 4.16664 16.9557 6.44579 15.6274 7.93897L8.99983 15.3894L2.37233 7.93977C1.04381 6.44646 1.26946 4.167 2.86616 2.95128C4.50032 1.70704 6.87275 2.10393 7.99225 3.80885L8.36782 4.38082C8.59267 4.72325 9.05847 4.82238 9.40821 4.60224C9.51777 4.53328 9.60294 4.44117 9.66134 4.33666L10.0076 3.80914C11.1268 2.10394 13.4993 1.70679 15.1335 2.95108ZM8.99998 2.653C7.31724 0.526225 4.15516 0.102335 1.94184 1.78754C-0.33726 3.52284 -0.659353 6.77651 1.23696 8.90805L8.4334 16.9972C8.7065 17.3041 9.18204 17.3362 9.49557 17.0688C9.53631 17.0341 9.57231 16.996 9.60351 16.9553L16.7628 8.90721C18.6589 6.77579 18.3367 3.52246 16.0579 1.78734C13.8446 0.102142 10.6825 0.526185 8.99998 2.653Z" fill="white"/>
                                                    </svg>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <div class="product-content-top">
                                        <div class="university-logo">
                                            <img src="{{ asset('assets/themes/theme3/images/oxford.png') }}" alt="">
                                        </div>
                                            <h4>
                                                <a href="{{route('store.viewcourse',[$store->slug,\Illuminate\Support\Facades\Crypt::encrypt($course->id)])}}">{{$course->title}}</a>
                                            </h4>
                                            <div class="review-star d-flex align-items-center">
                                                @if($store->enable_rating == 'on')
                                                    @for($i =1;$i<=5;$i++)
                                                        @php
                                                            $icon = 'fa-star';
                                                            $color = '';
                                                            $newVal1 = ($i-0.5);
                                                            if($course->course_rating() < $i && $course->course_rating() >= $newVal1)
                                                            {
                                                                $icon = 'fa-star-half-alt';
                                                            }
                                                            if($course->course_rating() >= $newVal1)
                                                            {
                                                                $color = 'text-warning';
                                                            }
                                                        @endphp
                                                        <i class="fas {{$icon .' '. $color}}"></i>
                                                    @endfor                                                
                                                @endif
                                            </div>
                                        </div>
                                        <div class="product-content-bottom">
                                            <div class="course-detail">
                                                <p>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        viewBox="0 0 12 12" fill="none">
                                                        <g clip-path="url(#clip0_19_26)">
                                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                                d="M5.89035 0.981725C3.17939 0.981725 0.981725 3.17939 0.981725 5.89035C0.981725 7.16364 1.46654 8.3237 2.26163 9.196C2.67034 8.3076 3.38394 7.61471 4.24934 7.22454C3.75002 6.77528 3.43604 6.12405 3.43604 5.39949C3.43604 4.04401 4.53487 2.94518 5.89035 2.94518C7.24583 2.94518 8.34466 4.04401 8.34466 5.39949C8.34466 6.11254 8.04058 6.75457 7.55502 7.20298C8.43552 7.56901 9.18412 8.23342 9.54281 9.16977C10.3238 8.30051 10.799 7.15092 10.799 5.89035C10.799 3.17939 8.60131 0.981725 5.89035 0.981725ZM9.69387 10.3882C10.9703 9.30774 11.7807 7.69368 11.7807 5.89035C11.7807 2.6372 9.1435 0 5.89035 0C2.6372 0 0 2.6372 0 5.89035C0 7.69366 0.810351 9.30769 2.08677 10.3882C2.12022 10.426 2.15982 10.459 2.20478 10.4855C3.21384 11.2959 4.49547 11.7807 5.89035 11.7807C7.28519 11.7807 8.5668 11.2959 9.57584 10.4856C9.62075 10.4591 9.66038 10.4261 9.69387 10.3882ZM8.74856 9.88145L8.66025 9.61652C8.29993 8.53556 7.14229 7.8538 5.89035 7.8538C4.6233 7.8538 3.49161 8.64647 3.0586 9.83723L3.04038 9.88735C3.84386 10.4613 4.82767 10.799 5.89035 10.799C6.95667 10.799 7.94357 10.459 8.74856 9.88145ZM5.89035 6.87208C6.70364 6.87208 7.36294 6.21278 7.36294 5.39949C7.36294 4.5862 6.70364 3.9269 5.89035 3.9269C5.07706 3.9269 4.41776 4.5862 4.41776 5.39949C4.41776 6.21278 5.07706 6.87208 5.89035 6.87208Z"
                                                                fill="#8A94A6"></path>
                                                        </g>
                                                        <defs>
                                                            <clipPath id="">
                                                                <rect width="11.7807" height="11.7807" fill="white">
                                                                </rect>
                                                            </clipPath>
                                                        </defs>
                                                    </svg>{{$course->student_count->count()}} &nbsp; <span>{{__('Students')}}</span>
                                                </p>
                                                <p><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M1 6C1 3.23858 3.23858 1 6 1C8.76142 1 11 3.23858 11 6C11 8.76142 8.76142 11 6 11C3.23858 11 1 8.76142 1 6ZM6 0C2.68629 0 0 2.68629 0 6C0 9.31371 2.68629 12 6 12C9.31371 12 12 9.31371 12 6C12 2.68629 9.31371 0 6 0ZM6.5 4C6.5 3.72386 6.27614 3.5 6 3.5C5.72386 3.5 5.5 3.72386 5.5 4V6.22228C5.5 6.38948 5.58357 6.54561 5.7227 6.63834L7.38983 7.74948C7.61961 7.90263 7.93004 7.8405 8.08319 7.61072C8.23634 7.38094 8.17422 7.07051 7.94443 6.91736L6.5 5.95465V4Z" fill="#939393"/>
                                                    </svg> {{$course->chapter_count->count()}} &nbsp; <span>{{__('Chapters')}}</span> </p>  
                                                <p>{{$course->level}}</p>                                                  
                                            </div>
                                            
                                            @if(Auth::guard('students')->check())
                                                @if(in_array($course->id,Auth::guard('students')->user()->purchasedCourse()))
                                                    <div class="card-price">
                                                    </div>
                                                    <a class="btn cart-btn cart-btn" href="{{route('store.fullscreen',[$store->slug,Crypt::encrypt($course->id),''])}}">
                                                        {{__('Get Started')}}
                                                        <svg viewBox="0 0 10 5">
                                                            <path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                @else
                                                    <div class="card-price">
                                                        @if($course->has_discount == 'on')
                                                            <ins> {{ ($course->is_free == 'on')? __('Free') : Utility::priceFormat($course->price)}}</ins>
                                                        @else
                                                            <ins> {{ ($course->is_free == 'on')? __('Free') : Utility::priceFormat($course->price)}}</ins>
                                                        @endif
                                                    </div>
                                                    <div class="add_to_cart" data-id="{{$course->id}}">                                                        
                                                        @if($key !== false)
                                                            <a id="cart-btn-{{$course->id}}" class="btn cart-btn cart-btn">{{__('Already in Cart')}} <svg viewBox="0 0 10 5">
                                                                <path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z">
                                                                </path>
                                                            </svg></a>
                                                        @else
                                                            <a id="cart-btn-{{$course->id}}" class="btn cart-btn">{{__('Add in Cart')}} <svg viewBox="0 0 10 5">
                                                                <path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z">
                                                                </path>
                                                            </svg></a>
                                                        @endif
                                                    </div>
                                                @endif
                                            @else
                                                <div class="card-price">
                                                    @if($course->has_discount == 'on')
                                                        <ins>{{ ($course->is_free == 'on')? __('Free') : Utility::priceFormat($course->price)}}</ins>
                                                    @else
                                                        <ins>{{ ($course->is_free == 'on')? __('Free') : Utility::priceFormat($course->price)}}</ins>
                                                    @endif
                                                </div>
                                                <div class="add_to_cart" data-id="{{$course->id}}">                                                        
                                                    @if($key !== false)
                                                        <a id="cart-btn-{{$course->id}}" class="btn cart-btn">{{__('Already in Cart')}}</a>
                                                    @else
                                                        <a id="cart-btn-{{$course->id}}" class="btn cart-btn">{{__('Add in Cart')}}</a>
                                                    @endif
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    @endif

    <!-- CATEGORIES -->
    @php
        $main_homepage_category_key = array_search('Home-Categories',array_column($getStoreThemeSetting, 'section_name'));
        $category_enable = 'off';
        $homepage_category_title = 'Categories';
        $homepage_category_description = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy.Lorem Ipsum is simply dummy text of the printing and typesetting industry.';
        $homepage_category_button = 'Find More Categories';

        if(!empty($getStoreThemeSetting[$main_homepage_category_key])) {
            $homepage_category_header = $getStoreThemeSetting[$main_homepage_category_key];
            $category_enable = $homepage_category_header['section_enable'];

            $homepage_title_key = array_search('Title',array_column($homepage_category_header['inner-list'], 'field_name'));
            $homepage_category_title = $homepage_category_header['inner-list'][$homepage_title_key]['field_default_text'];

            $homepage_description_key = array_search('Description',array_column($homepage_category_header['inner-list'], 'field_name'));
            $homepage_category_description = $homepage_category_header['inner-list'][$homepage_description_key]['field_default_text'];
            
            $homepage_button_key = array_search('Button',array_column($homepage_category_header['inner-list'], 'field_name'));
            $homepage_category_button = $homepage_category_header['inner-list'][$homepage_button_key]['field_default_text'];
        }       
    @endphp
    @if($category_enable == 'on')
        @if($categories->count()>0)
            <section class="course-category-section border-bottom padding-top padding-bottom">
                <div class="container">
                    <div class="section-title d-flex align-items-start justify-content-between">
                        <div class="section-title-left">
                            <h2>{{ $homepage_category_title }}</h2>
                            <p>{{ $homepage_category_description }}</p>
                        </div>
                        <a href="{{route('store.search',[$store->slug])}}" class="btn">{{ $homepage_category_button }} <svg viewBox="0 0 10 5">
                            <path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z">
                            </path>
                        </svg></a>
                    </div>
                    <div class="row  justify-content-center row-gap">
                        @foreach($categories as $category)
                            <div class="col-md-4 col-lg-3 co-sm-6 col-12 category-card">
                                <div class="category-card-inner">
                                    <div class="category-img">
                                        <a href="#">
                                            @if(!empty($category->category_image))
                                                <img alt="Image placeholder" src="{{asset(Storage::url('uploads/category_image/'.$category->category_image))}}">
                                            @else
                                                <img src="{{asset('assets/themes/theme3/images/product-2.jpg')}}" alt="business" class="img-fluid">
                                            @endif 
                                        </a>
                                    </div>
                                    <div class="category-content">
                                        <h4>
                                            <a href="{{route('store.search',[$store->slug,Crypt::encrypt($category->id)])}}">{{$category->name}}</a>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    @endif

    <!-- COURSE/SALE -->
    @php
        $main_homepage_onsale_course_key = array_search('Home-On Sale',array_column($getStoreThemeSetting, 'section_name'));
        $onsale_course_enable = 'off';
        $homepage_onsale_course_title = 'On Sale';
        $homepage_onsale_course_button = 'Show More';

        if(!empty($getStoreThemeSetting[$main_homepage_onsale_course_key])) {
            $homepage_onsale_course_header = $getStoreThemeSetting[$main_homepage_onsale_course_key];
            $onsale_course_enable = $homepage_onsale_course_header['section_enable'];

            $homepage_onsale_course_title_key = array_search('Title',array_column($homepage_onsale_course_header['inner-list'], 'field_name'));            
            $homepage_onsale_course_title = $homepage_onsale_course_header['inner-list'][$homepage_onsale_course_title_key]['field_default_text'];
            
            $homepage_onsale_course_button_key = array_search('Button',array_column($homepage_onsale_course_header['inner-list'], 'field_name'));
            $homepage_onsale_course_button = $homepage_onsale_course_header['inner-list'][$homepage_onsale_course_button_key]['field_default_text'];
        }       
    @endphp
    @if($onsale_course_enable == 'on') 
        <section class="modern-skills-section border-bottom padding-top padding-bottom">
            <div class="container">
                <div class="section-title d-flex align-items-center justify-content-between">
                    <div class="section-title-left">
                        <h2>{{ $homepage_onsale_course_title }}</h2>
                    </div>
                    <a href="{{route('store.search',[$store->slug])}}" class="btn">{{ $homepage_onsale_course_button }} <svg viewBox="0 0 10 5">
                        <path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z">
                        </path>
                    </svg></a>
                </div>
                <div class="row align-items-center">
                    <div class="col-lg-12 col-md-12 col-12">
                        <div class="row product-row">
                            @foreach($courses as $course)                               
                                @if(!empty($course->discount))
                                    <div class="col-md-3 col-sm-3 col-12 product-card course-skill">
                                        <div class="product-card-inner">
                                            <div class="product-img">
                                                <a href="{{route('store.viewcourse',[$store->slug,\Illuminate\Support\Facades\Crypt::encrypt($course->id)])}}">
                                                    @if(!empty($course->thumbnail))
                                                        <img alt="Image placeholder" src="{{asset(Storage::url('uploads/thumbnail/'.$course->thumbnail))}}">
                                                    @else
                                                        <img src="{{ asset('assets/themes/theme3/images/google-ux.png') }}" alt="card">
                                                    @endif
                                                </a>
                                                
                                                <div class="subtitle-top d-flex align-items-center justify-content-between">
                                                    <span class="badge">{{!empty($course->category_id)? $course->category_id->name:'-'}}</span>
                                                    <span class="badge">{{ __('Sale') }}</span>
                                                    {{-- <a href="#" class="wishlist-btn">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M15.1335 2.95108C16.73 4.16664 16.9557 6.44579 15.6274 7.93897L8.99983 15.3894L2.37233 7.93977C1.04381 6.44646 1.26946 4.167 2.86616 2.95128C4.50032 1.70704 6.87275 2.10393 7.99225 3.80885L8.36782 4.38082C8.59267 4.72325 9.05847 4.82238 9.40821 4.60224C9.51777 4.53328 9.60294 4.44117 9.66134 4.33666L10.0076 3.80914C11.1268 2.10394 13.4993 1.70679 15.1335 2.95108ZM8.99998 2.653C7.31724 0.526225 4.15516 0.102335 1.94184 1.78754C-0.33726 3.52284 -0.659353 6.77651 1.23696 8.90805L8.4334 16.9972C8.7065 17.3041 9.18204 17.3362 9.49557 17.0688C9.53631 17.0341 9.57231 16.996 9.60351 16.9553L16.7628 8.90721C18.6589 6.77579 18.3367 3.52246 16.0579 1.78734C13.8446 0.102142 10.6825 0.526185 8.99998 2.653Z" fill="white"></path>
                                                        </svg>
                                                    </a> --}}                                           

                                                    @if(Auth::guard('students')->check())
                                                        @if(sizeof($course->student_wl)>0)
                                                            @foreach($course->student_wl as $student_wl)
                                                                <a href="#" class="wishlist-btn wishlist_btn add_to_wishlist" data-placement="top">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M15.1335 2.95108C16.73 4.16664 16.9557 6.44579 15.6274 7.93897L8.99983 15.3894L2.37233 7.93977C1.04381 6.44646 1.26946 4.167 2.86616 2.95128C4.50032 1.70704 6.87275 2.10393 7.99225 3.80885L8.36782 4.38082C8.59267 4.72325 9.05847 4.82238 9.40821 4.60224C9.51777 4.53328 9.60294 4.44117 9.66134 4.33666L10.0076 3.80914C11.1268 2.10394 13.4993 1.70679 15.1335 2.95108ZM8.99998 2.653C7.31724 0.526225 4.15516 0.102335 1.94184 1.78754C-0.33726 3.52284 -0.659353 6.77651 1.23696 8.90805L8.4334 16.9972C8.7065 17.3041 9.18204 17.3362 9.49557 17.0688C9.53631 17.0341 9.57231 16.996 9.60351 16.9553L16.7628 8.90721C18.6589 6.77579 18.3367 3.52246 16.0579 1.78734C13.8446 0.102142 10.6825 0.526185 8.99998 2.653Z" fill="white"></path>
                                                                    </svg>
                                                                </a>
                                                            @endforeach
                                                        @else
                                                            <a class="wishlist-btn add_to_wishlist" data-id="{{$course->id}}" data-placement="top">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.1335 2.95108C16.73 4.16664 16.9557 6.44579 15.6274 7.93897L8.99983 15.3894L2.37233 7.93977C1.04381 6.44646 1.26946 4.167 2.86616 2.95128C4.50032 1.70704 6.87275 2.10393 7.99225 3.80885L8.36782 4.38082C8.59267 4.72325 9.05847 4.82238 9.40821 4.60224C9.51777 4.53328 9.60294 4.44117 9.66134 4.33666L10.0076 3.80914C11.1268 2.10394 13.4993 1.70679 15.1335 2.95108ZM8.99998 2.653C7.31724 0.526225 4.15516 0.102335 1.94184 1.78754C-0.33726 3.52284 -0.659353 6.77651 1.23696 8.90805L8.4334 16.9972C8.7065 17.3041 9.18204 17.3362 9.49557 17.0688C9.53631 17.0341 9.57231 16.996 9.60351 16.9553L16.7628 8.90721C18.6589 6.77579 18.3367 3.52246 16.0579 1.78734C13.8446 0.102142 10.6825 0.526185 8.99998 2.653Z" fill="white"></path>
                                                                </svg>
                                                            </a>
                                                        @endif
                                                    @else
                                                        <a class="wishlist-btn add_to_wishlist" data-id="{{$course->id}}" data-placement="top">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M15.1335 2.95108C16.73 4.16664 16.9557 6.44579 15.6274 7.93897L8.99983 15.3894L2.37233 7.93977C1.04381 6.44646 1.26946 4.167 2.86616 2.95128C4.50032 1.70704 6.87275 2.10393 7.99225 3.80885L8.36782 4.38082C8.59267 4.72325 9.05847 4.82238 9.40821 4.60224C9.51777 4.53328 9.60294 4.44117 9.66134 4.33666L10.0076 3.80914C11.1268 2.10394 13.4993 1.70679 15.1335 2.95108ZM8.99998 2.653C7.31724 0.526225 4.15516 0.102335 1.94184 1.78754C-0.33726 3.52284 -0.659353 6.77651 1.23696 8.90805L8.4334 16.9972C8.7065 17.3041 9.18204 17.3362 9.49557 17.0688C9.53631 17.0341 9.57231 16.996 9.60351 16.9553L16.7628 8.90721C18.6589 6.77579 18.3367 3.52246 16.0579 1.78734C13.8446 0.102142 10.6825 0.526185 8.99998 2.653Z" fill="white"></path>
                                                            </svg>
                                                        </a>
                                                    @endif  

                                                </div>
                                            </div>
                                            <div class="product-content">
                                                <div class="product-content-top">
                                                <div class="university-logo">
                                                    <img src="{{ asset('assets/themes/theme3/images/Google_ G _Logo.png') }}" alt="">
                                                </div>
                                                    <h4>
                                                        <a href="{{route('store.viewcourse',[$store->slug,\Illuminate\Support\Facades\Crypt::encrypt($course->id)])}}">{{$course->title}}</a>
                                                    </h4>
                                                    <p>{{ __('Lorem Ipsum is simply dummy text of the printing and typesetting industry.') }}</p>
                                                </div>
                                                <div class="review-star d-flex align-items-center">
                                                    @if($store->enable_rating == 'on')                                                
                                                        @for($i =1;$i<=5;$i++)
                                                            @php
                                                                $icon = 'fa-star';
                                                                $color = '';
                                                                $newVal1 = ($i-0.5);
                                                                if($course->course_rating() < $i && $course->course_rating() >= $newVal1)
                                                                {
                                                                    $icon = 'fa-star-half-alt';
                                                                }
                                                                if($course->course_rating() >= $newVal1)
                                                                {
                                                                    $color = 'text-warning';
                                                                }
                                                            @endphp
                                                            <i class="fas {{$icon .' '. $color}}"></i>
                                                        @endfor                                                
                                                    @endif
                                                </div>
                                                <div class="product-content-bottom">
                                                    <div class="course-detail">
                                                        <p>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                                                                <g clip-path="url(#clip0_19_26)">
                                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M5.89035 0.981725C3.17939 0.981725 0.981725 3.17939 0.981725 5.89035C0.981725 7.16364 1.46654 8.3237 2.26163 9.196C2.67034 8.3076 3.38394 7.61471 4.24934 7.22454C3.75002 6.77528 3.43604 6.12405 3.43604 5.39949C3.43604 4.04401 4.53487 2.94518 5.89035 2.94518C7.24583 2.94518 8.34466 4.04401 8.34466 5.39949C8.34466 6.11254 8.04058 6.75457 7.55502 7.20298C8.43552 7.56901 9.18412 8.23342 9.54281 9.16977C10.3238 8.30051 10.799 7.15092 10.799 5.89035C10.799 3.17939 8.60131 0.981725 5.89035 0.981725ZM9.69387 10.3882C10.9703 9.30774 11.7807 7.69368 11.7807 5.89035C11.7807 2.6372 9.1435 0 5.89035 0C2.6372 0 0 2.6372 0 5.89035C0 7.69366 0.810351 9.30769 2.08677 10.3882C2.12022 10.426 2.15982 10.459 2.20478 10.4855C3.21384 11.2959 4.49547 11.7807 5.89035 11.7807C7.28519 11.7807 8.5668 11.2959 9.57584 10.4856C9.62075 10.4591 9.66038 10.4261 9.69387 10.3882ZM8.74856 9.88145L8.66025 9.61652C8.29993 8.53556 7.14229 7.8538 5.89035 7.8538C4.6233 7.8538 3.49161 8.64647 3.0586 9.83723L3.04038 9.88735C3.84386 10.4613 4.82767 10.799 5.89035 10.799C6.95667 10.799 7.94357 10.459 8.74856 9.88145ZM5.89035 6.87208C6.70364 6.87208 7.36294 6.21278 7.36294 5.39949C7.36294 4.5862 6.70364 3.9269 5.89035 3.9269C5.07706 3.9269 4.41776 4.5862 4.41776 5.39949C4.41776 6.21278 5.07706 6.87208 5.89035 6.87208Z" fill="#8A94A6"></path>
                                                                </g>
                                                                <defs>
                                                                    <clipPath id="">
                                                                        <rect width="11.7807" height="11.7807" fill="white">
                                                                        </rect>
                                                                    </clipPath>
                                                                </defs>
                                                            </svg>{{$course->student_count->count()}} &nbsp; <span>{{__('Students')}}</span>
                                                        </p>
                                                        <p><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M1 6C1 3.23858 3.23858 1 6 1C8.76142 1 11 3.23858 11 6C11 8.76142 8.76142 11 6 11C3.23858 11 1 8.76142 1 6ZM6 0C2.68629 0 0 2.68629 0 6C0 9.31371 2.68629 12 6 12C9.31371 12 12 9.31371 12 6C12 2.68629 9.31371 0 6 0ZM6.5 4C6.5 3.72386 6.27614 3.5 6 3.5C5.72386 3.5 5.5 3.72386 5.5 4V6.22228C5.5 6.38948 5.58357 6.54561 5.7227 6.63834L7.38983 7.74948C7.61961 7.90263 7.93004 7.8405 8.08319 7.61072C8.23634 7.38094 8.17422 7.07051 7.94443 6.91736L6.5 5.95465V4Z" fill="#939393"></path>
                                                            </svg> {{$course->chapter_count->count()}} &nbsp; <span>{{__('Chapters')}}</span> </p>
                                                        <p>{{$course->level}}</p>     
                                                    </div>

                                                    <!-- CART -->
                                                    @if(Auth::guard('students')->check())
                                                        @if(in_array($course->id,Auth::guard('students')->user()->purchasedCourse()))
                                                            <div class="price">
                                                            </div>
                                                            <a class="btn cart-btn" href="{{route('store.fullscreen',[$store->slug,Crypt::encrypt($course->id),''])}}">
                                                                {{__('Get Started')}}
                                                                <svg viewBox="0 0 10 5">
                                                                    <path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z">
                                                                    </path>
                                                                </svg>
                                                            </a>
                                                        @else
                                                            <div class="price">
                                                                @if($course->has_discount == 'on')
                                                                    <ins> {{ ($course->is_free == 'on')? __('Free') : Utility::priceFormat($course->price)}}</ins>
                                                                    <sub>
                                                                        <del> {{ Utility::priceFormat($course->discount)}} </del>
                                                                    </sub>
                                                                @else
                                                                    <ins> {{ ($course->is_free == 'on')? __('Free') : Utility::priceFormat($course->price)}}</ins>
                                                                    <sub>
                                                                        <del> {{ Utility::priceFormat($course->discount)}} </del>
                                                                    </sub>
                                                                @endif
                                                            </div>
                                                            <div class="add_to_cart" data-id="{{$course->id}}">
                                                                @if($key !== false)
                                                                    <a id="cart-btn-{{$course->id}}" class="btn cart-btn">{{__('Already in Cart')}}</a>
                                                                @else
                                                                    <a id="cart-btn-{{$course->id}}" class="btn cart-btn">{{__('Add in Cart')}}</a>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    @else
                                                        <div class="price">
                                                            @if($course->has_discount == 'on')
                                                                <ins>{{ ($course->is_free == 'on')? __('Free') : Utility::priceFormat($course->price)}}</ins>
                                                                <sub>
                                                                    <del> {{ Utility::priceFormat($course->discount)}} </del>
                                                                </sub>
                                                            @else
                                                                <h3>{{ ($course->is_free == 'on')? __('Free') : Utility::priceFormat($course->price)}}</h3>
                                                                <sub>
                                                                    <del> {{ Utility::priceFormat($course->discount)}} </del>
                                                                </sub>
                                                            @endif
                                                        </div>
                                                        <div class="add_to_cart" data-id="{{$course->id}}">
                                                            @if($key !== false)
                                                                <a id="cart-btn-{{$course->id}}" class="btn cart-btn">{{__('Already in Cart')}}</a>
                                                            @else
                                                                <a id="cart-btn-{{$course->id}}" class="btn cart-btn">{{__('Add in Cart')}}</a>
                                                            @endif
                                                        </div>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Email Subscriber -->
    @php
        $main_homepage_email_subscriber_key = array_search('Home-Email-subscriber',array_column($getStoreThemeSetting, 'section_name'));        
        $email_subscriber_enable = 'off';
        $homepage_email_subscriber_title = 'Improve Your Skills with ModernCourse';
        $homepage_email_subscriber_subtext = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy.';
        $homepage_email_subscriber_button = 'Subscribe';

        if(!empty($getStoreThemeSetting[$main_homepage_email_subscriber_key])) {
            $homepage_subscriber_header = $getStoreThemeSetting[$main_homepage_email_subscriber_key];
            $email_subscriber_enable = $homepage_subscriber_header['section_enable'];

            $homepage_email_subscriber_title_key = array_search('Title',array_column($homepage_subscriber_header['inner-list'], 'field_name'));            
            $homepage_email_subscriber_title = $homepage_subscriber_header['inner-list'][$homepage_email_subscriber_title_key]['field_default_text'];

            $homepage_email_subscriber_subtext_key = array_search('Sub Text',array_column($homepage_subscriber_header['inner-list'], 'field_name'));
            $homepage_email_subscriber_subtext = $homepage_subscriber_header['inner-list'][$homepage_email_subscriber_subtext_key]['field_default_text'];

            $homepage_email_subscriber_button_key = array_search('Button',array_column($homepage_subscriber_header['inner-list'], 'field_name'));
            $homepage_email_subscriber_button = $homepage_subscriber_header['inner-list'][$homepage_email_subscriber_button_key]['field_default_text'];
        }
    @endphp
    @if($email_subscriber_enable == 'on') 
        <section class="newsletter-section padding-bottom padding-top border-top">
            <div class="container">
                <div class="newsletter-container">
                    <div class="section-title">
                        <h2>{{ $homepage_email_subscriber_title }}</h2>
                        <p>{{ $homepage_email_subscriber_subtext }}</p>
                    </div>
                    <div class="newsletter-form">
                        {{ Form::open(array('route' => array('subscriptions.store_email', $store->id),'method' => 'POST')) }}
                            <div class="input-wrapper">
                                {{ Form::email('email',null,array('aria-label'=>'Enter your email address','placeholder'=>__('Type your email address....'))) }}
                                <button type="submit" class="btn">{{ $homepage_email_subscriber_button }} <svg viewBox="0 0 10 5">
                                    <path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z">
                                    </path>
                                </svg></button>
                            </div>
                            <div class="checkbox-custom">
                                <input type="checkbox" class="" id="newslettercheckbox">
                                <label for="newslettercheckbox">{{ $homepage_email_subscriber_subtext }}</label>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        
        </section>
    @endif

@endsection


