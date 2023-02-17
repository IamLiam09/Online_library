@extends('layouts.theme2.shopfront')
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
    <div class="home-wrapper">
        <!-- header -->
        @php
            $main_homepage_header_text_key = array_search('Home-Header',array_column($getStoreThemeSetting, 'section_name'));        
            $header_enable = 'off';
            $homepage_header_title = 'Improve Your<br> Skills with <br> ModernCourse.';
            $homepage_header_Sub_text = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.';
            $homepage_header_button = 'Show More';

            if(!empty($getStoreThemeSetting[$main_homepage_header_text_key])) {
                $homepage_header = $getStoreThemeSetting[$main_homepage_header_text_key];
                $header_enable = $homepage_header['section_enable'];

                $homepage_header_title_key = array_search('Title',array_column($homepage_header['inner-list'], 'field_name'));
                $homepage_header_title = $homepage_header['inner-list'][$homepage_header_title_key]['field_default_text'];
                
                $homepage_header_Sub_text_key = array_search('Sub Title',array_column($homepage_header['inner-list'], 'field_name'));
                $homepage_header_Sub_text = $homepage_header['inner-list'][$homepage_header_Sub_text_key]['field_default_text'];

                $homepage_header_button_key = array_search('Button',array_column($homepage_header['inner-list'], 'field_name'));
                $homepage_header_button = $homepage_header['inner-list'][$homepage_header_button_key]['field_default_text'];
            }


            $sub_title[0] = 'Data Analyst';
            $sub_title[1] = 'UX/UI Designer';
            $sub_title[2] = 'Data Analyst';
            $sub_title[3] = 'Data Engineer';
            $sub_subtitle[0] = __('Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the.');
            $sub_subtitle[1] = __('Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the.');
            $sub_subtitle[2] = __('Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the.');
            $sub_subtitle[3] = __('Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the.');
            $a[0] = '#';
            $a[1] = '#';
            $a[2] = '#';
            $a[3] = '#';
            $img[0] = 'theme2/images/data-analyst.jpg';
            $img[1] = 'theme2/images/data-analyst.jpg';
            $img[2] = 'theme2/images/data-analyst.jpg';
            $img[3] = 'theme2/images/data-analyst.jpg';

            if($header_enable == 'on') {
                if(!empty($getStoreThemeSetting[1])) {
                    $homepage_header1 = $getStoreThemeSetting[1];
                    foreach ($homepage_header1['inner-list'] as $key1 => $value1) {
                        $sl = $value1['field_slug'];
                        for ($i=0; $i < $homepage_header1['loop_number']; $i++) {                             
                            if(!empty($homepage_header1[$sl][$i])) {
                                if($sl == "homepage-thumbnail-innerbox-1-title") {
                                    $sub_title[$i] = $homepage_header1[$sl][$i];
                                }
                                if($sl == "homepage-thumbnail-innerbox-1-sub-text") {
                                    $sub_subtitle[$i] = $homepage_header1[$sl][$i];
                                }
                                if($sl == "homepage-quick-link") {
                                    $a[$i] = $homepage_header1[$sl][$i];
                                }
                                if($sl == "homepage-thumbnail-innerbox-thumbnail") {                                    
                                    $img[$i] = $homepage_header1[$sl][$i]['field_prev_text'];
                                }
                            }  else {                            
                                if($sl == "homepage-thumbnail-innerbox-1-title") {
                                    $sub_title[$i] = $value1['field_default_text'];
                                }
                                if($sl == "homepage-thumbnail-innerbox-1-sub-text") {
                                    $sub_subtitle[$i] = $value1['field_default_text'];
                                }
                                if($sl == "homepage-quick-link") {
                                    $a[$i] = $value1['field_default_text'];
                                }
                                if($sl == "homepage-thumbnail-innerbox-thumbnail") {
                                    $img[$i] = $value1['field_default_text'];
                                }
                            }
                        }
                    }
                }
            }
        @endphp
        @if($header_enable == 'on')
            <section class="main-home-first-section padding-top">
                <div class="banner-image">
                    {{-- <img src="{{ asset('assets/themes/theme2/images/banner-image1.png') }}" alt=""> --}}
                    @php
                        $data=explode(".",$store->store_theme);                               
                    @endphp

                    @if($data[0]=='dark-blue-color')
                        <img src="{{ asset('assets/themes/theme2/images/banner-image1.png') }}" alt="">
                    @elseif($data[0]=='dark-green-color')
                        <img src="{{ asset('assets/themes/theme2/images/banner-image2.png') }}" alt="">
                    @else
                        <img src="{{ asset('assets/themes/theme2/images/banner-image3.png') }}" alt="">
                    @endif
                </div>
                <div class="container">
                    <div class="row align-items-center">
                        @if($header_enable == 'on')
                            <div class="col-lg-6  col-12">
                                <div class="home-banner-content">
                                    <div class="home-banner-content-inner">
                                        <h2 class="h1">{!! $homepage_header_title !!}</h2>
                                        <p>{!! $homepage_header_Sub_text !!} </p>
                                        <div class="btn-group d-flex">
                                            <a href="{{route('store.search',[$store->slug])}}" class="btn">{{ $homepage_header_button }} <svg viewBox="0 0 10 5">
                                                <path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z">
                                                </path>
                                            </svg></a>
                                        </div>
                                    </div>
                                </div>
                            </div>                   
                            <div class="col-lg-6  col-12">
                                <div class="banner-content-left">
                                    <div class="banner-category-wrap">
                                        <div class="banner-category-list">
                                            <ul class="category-list">
                                                <li>
                                                    <div class="category-card">
                                                        <div class="cateory-card-inner">
                                                            <div class="category-image">
                                                                <img src="{{ asset(Storage::url('uploads/'.$img[0])) }}" alt="">          
                                                                {{-- <img src="{{ asset('assets/themes/theme2/images/data-analyst.jpg') }}" alt="">  --}}
                                                            </div>
                                                            <div class="cateory-caption">
                                                                <h5>
                                                                    <a href="{{$a[0]}}">{{ $sub_title[0] }}</a>
                                                                </h5>
                                                                <p>{{ $sub_subtitle[0] }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="category-card">
                                                        <div class="cateory-card-inner">
                                                            <div class="category-image">
                                                                <img src="{{ asset(Storage::url('uploads/'.$img[1])) }}" alt=""> 
                                                                {{-- <img src="{{ asset('assets/themes/theme2/images/Designer.jpg') }}" alt=""> --}}
                                                            </div>
                                                            <div class="cateory-caption">
                                                                <h5>
                                                                    <a href="{{$a[1]}}">{{ $sub_title[1] }}</a>
                                                                </h5>
                                                                <p>{{ $sub_subtitle[1] }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="category-card">
                                                        <div class="cateory-card-inner">
                                                            <div class="category-image">
                                                                <img src="{{ asset(Storage::url('uploads/'.$img[2])) }}" alt=""> 
                                                                {{-- <img src="{{ asset('assets/themes/theme2/images/data-analyst.jpg') }}" alt=""> --}}
                                                            </div>
                                                            <div class="cateory-caption">
                                                                <h5>
                                                                    <a href="{{$a[2]}}">{{ $sub_title[2] }}</a>
                                                                </h5>
                                                                <p>{{ $sub_subtitle[2] }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="category-card">
                                                        <div class="cateory-card-inner">
                                                            <div class="category-image">
                                                                <img src="{{ asset(Storage::url('uploads/'.$img[3])) }}" alt=""> 
                                                                {{-- <img src="{{ asset('assets/themes/theme2/images/data-Engineer.jpg') }}" alt=""> --}}
                                                            </div>
                                                            <div class="cateory-caption">
                                                                <h5>
                                                                    <a href="{{$a[3]}}">{{ $sub_title[3] }}</a>
                                                                </h5>
                                                                <p>{{ $sub_subtitle[3] }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>

                                            </ul>
                                        </div>
                                        <div class="btn-wrapper">
                                            <a href="{{route('store.search',[$store->slug])}}" class="btn-degrees">
                                                {{ __('More') }}<br> {{ __('Courses') }}
                                                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21"
                                                viewBox="0 0 21 21" fill="none">
                                                <g clip-path="url(#clip0_2_13577)">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M3.07558 17.9246C2.73387 17.5829 2.73387 17.0288 3.07558 16.6871L15.1937 4.56902L7.40677 4.56888C6.92352 4.56887 6.53177 4.17711 6.53178 3.69386C6.53179 3.21062 6.92355 2.81887 7.4068 2.81888L17.3061 2.81905C17.7894 2.81906 18.1811 3.2108 18.1811 3.69404L18.1813 13.5934C18.1813 14.0766 17.7895 14.4684 17.3063 14.4684C16.823 14.4684 16.4313 14.0766 16.4313 13.5934L16.4311 5.80645L4.31301 17.9246C3.97131 18.2663 3.41729 18.2663 3.07558 17.9246Z"
                                                        fill="black" />
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_2_13577">
                                                        <rect width="21" height="21" fill="white" />
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        @endif

        <!-- Branding Logo -->
        <section class="client-logo-section"> 
            <div class="container offset-left">
                <div class="row align-items-center">
                    <div class="col-md-6 col-lg-5 col-12">
                        <div class="client-desk-inner-left">
                            <div class="section-title">
                                <h2>{{ __('Improve Your Skills with ModernCourse') }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-7 col-12">
                        <div class="client-logo-slider">
                            <div class="client-logo-item">
                                <a href="#">
                                    <img src="{{ asset('assets/themes/'.$store->theme_dir.'/images/ModernCourse.png') }}" alt="">
                                </a>
                            </div>
                            <div class="client-logo-item">
                                <a href="#">
                                    <img src="{{ asset('assets/themes/'.$store->theme_dir.'/images/ModernCourse.png') }}" alt="">
                                </a>
                            </div>
                            <div class="client-logo-item">
                                <a href="#">
                                    <img src="{{ asset('assets/themes/'.$store->theme_dir.'/images/ModernCourse.png') }}" alt="">
                                </a>
                            </div>
                            <div class="client-logo-item">
                                <a href="#">
                                    <img src="{{ asset('assets/themes/'.$store->theme_dir.'/images/ModernCourse.png') }}" alt="">
                                </a>
                            </div>

                            <div class="client-logo-item">
                                <a href="#">
                                    <img src="{{ asset('assets/themes/'.$store->theme_dir.'/images/ModernCourse.png') }}" alt="">
                                </a>
                            </div>
                            <div class="client-logo-item">
                                <a href="#">
                                    <img src="{{ asset('assets/themes/'.$store->theme_dir.'/images/ModernCourse.png') }}" alt="">
                                </a>
                            </div>
                            <div class="client-logo-item">
                                <a href="#">
                                    <img src="{{ asset('assets/themes/'.$store->theme_dir.'/images/ModernCourse.png') }}" alt="">
                                </a>
                            </div>
                            <div class="client-logo-item">
                                <a href="#">
                                    <img src="{{ asset('assets/themes/'.$store->theme_dir.'/images/ModernCourse.png') }}" alt="">
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Course -->
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
                <section class="earn-degree-section padding-bottom padding-top">
                    <div class="container">
                        <div class="section-title d-flex align-items-center justify-content-between">
                            {{-- <h2><b>Earn Your Degree</b> <br> with ModernCourse</h2> --}}
                            <h2>{{ $homepage_featured_course_title }}</h2>
                            <a href="{{route('store.search',[$store->slug])}}" class="btn gradient-style">{{ $homepage_featured_course_button }} <svg viewBox="0 0 10 5">
                                <path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z">
                                </path>
                            </svg></a>
                        </div>
                        <div class="row product-row course-degree">
                            @foreach($featured_course as $course)                        
                                <div class="col-md-4 col-sm-6 col-12 product-card course-degree">
                                    <div class="product-card-inner">
                                        <div class="product-img">
                                            <a href="{{route('store.search',[$store->slug])}}">
                                                @if(!empty($course->thumbnail))
                                                    <img alt="card" src="{{asset(Storage::url('uploads/thumbnail/'.$course->thumbnail))}}" class="img-fluid">
                                                @else
                                                    <img src="{{ asset('assets/themes/theme2/images/bachelor-of-sci.jpg') }}" alt="">
                                                @endif
                                            </a>
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
                                            
                                            <div class="subtitle-top d-flex align-items-center justify-content-between">
                                                <span class="badge">{{!empty($course->category_id)?$course->category_id->name:'-'}}</span>
                                                @if(Auth::guard('students')->check())
                                                    @if(sizeof($course->student_wl)>0)                                                   
                                                        <a class="wishlist-btn add_to_wishlist" data-id="{{$course->id}}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M15.1335 2.95108C16.73 4.16664 16.9557 6.44579 15.6274 7.93897L8.99983 15.3894L2.37233 7.93977C1.04381 6.44646 1.26946 4.167 2.86616 2.95128C4.50032 1.70704 6.87275 2.10393 7.99225 3.80885L8.36782 4.38082C8.59267 4.72325 9.05847 4.82238 9.40821 4.60224C9.51777 4.53328 9.60294 4.44117 9.66134 4.33666L10.0076 3.80914C11.1268 2.10394 13.4993 1.70679 15.1335 2.95108ZM8.99998 2.653C7.31724 0.526225 4.15516 0.102335 1.94184 1.78754C-0.33726 3.52284 -0.659353 6.77651 1.23696 8.90805L8.4334 16.9972C8.7065 17.3041 9.18204 17.3362 9.49557 17.0688C9.53631 17.0341 9.57231 16.996 9.60351 16.9553L16.7628 8.90721C18.6589 6.77579 18.3367 3.52246 16.0579 1.78734C13.8446 0.102142 10.6825 0.526185 8.99998 2.653Z" fill="white"/>
                                                            </svg>
                                                        </a>
                                                    @else
                                                        <a class="wishlist-btn add_to_wishlist" data-id="{{$course->id}}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M15.1335 2.95108C16.73 4.16664 16.9557 6.44579 15.6274 7.93897L8.99983 15.3894L2.37233 7.93977C1.04381 6.44646 1.26946 4.167 2.86616 2.95128C4.50032 1.70704 6.87275 2.10393 7.99225 3.80885L8.36782 4.38082C8.59267 4.72325 9.05847 4.82238 9.40821 4.60224C9.51777 4.53328 9.60294 4.44117 9.66134 4.33666L10.0076 3.80914C11.1268 2.10394 13.4993 1.70679 15.1335 2.95108ZM8.99998 2.653C7.31724 0.526225 4.15516 0.102335 1.94184 1.78754C-0.33726 3.52284 -0.659353 6.77651 1.23696 8.90805L8.4334 16.9972C8.7065 17.3041 9.18204 17.3362 9.49557 17.0688C9.53631 17.0341 9.57231 16.996 9.60351 16.9553L16.7628 8.90721C18.6589 6.77579 18.3367 3.52246 16.0579 1.78734C13.8446 0.102142 10.6825 0.526185 8.99998 2.653Z" fill="white"/>
                                                            </svg>
                                                        </a>
                                                    @endif
                                                @else
                                                    <a class="wishlist-btn add_to_wishlist" data-id="{{$course->id}}">
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
                                                <img src="{{ asset('assets/themes/theme2/images/oxford.png') }}" alt="">
                                            </div>
                                                <h4>
                                                    <a href="{{route('store.viewcourse',[$store->slug,\Illuminate\Support\Facades\Crypt::encrypt($course->id)])}}">{{$course->title}}</a>
                                                </h4>
                                                <p>{{ __('Lorem Ipsum is simply dummy text of the printing and typesetting industry.') }}</p>
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
                                                        </svg> {{$course->student_count->count()}} &nbsp; <span>{{__('Students')}}</span>
                                                    </p>
                                                    <p><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M1 6C1 3.23858 3.23858 1 6 1C8.76142 1 11 3.23858 11 6C11 8.76142 8.76142 11 6 11C3.23858 11 1 8.76142 1 6ZM6 0C2.68629 0 0 2.68629 0 6C0 9.31371 2.68629 12 6 12C9.31371 12 12 9.31371 12 6C12 2.68629 9.31371 0 6 0ZM6.5 4C6.5 3.72386 6.27614 3.5 6 3.5C5.72386 3.5 5.5 3.72386 5.5 4V6.22228C5.5 6.38948 5.58357 6.54561 5.7227 6.63834L7.38983 7.74948C7.61961 7.90263 7.93004 7.8405 8.08319 7.61072C8.23634 7.38094 8.17422 7.07051 7.94443 6.91736L6.5 5.95465V4Z" fill="#939393"/>
                                                        </svg> {{$course->chapter_count->count()}} &nbsp; <span>{{ __('Chapters') }}</span> </p>  
                                                    <p>{{$course->level}}</p>                                               
                                                </div>

                                                <!-- Cart -->
                                                @if(Auth::guard('students')->check())
                                                    @if(in_array($course->id,Auth::guard('students')->user()->purchasedCourse()))
                                                        <div class="card-price">
                                                        </div>
                                                        <div class="add-cart">
                                                            <a href="{{route('store.fullscreen',[$store->slug,Crypt::encrypt($course->id),''])}}" class="btn cart-btn">{{ __('Get Started') }} <svg viewBox="0 0 10 5">
                                                                <path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z">
                                                                </path>
                                                            </svg></a>
                                                        </div>
                                                    @else
                                                        <div class="card-price">
                                                            @if($course->has_discount == 'on')
                                                                <h3> {{ ($course->is_free == 'on')? __('Free') : Utility::priceFormat($course->price)}}</h3>
                                                            @else
                                                                <h3> {{ ($course->is_free == 'on')? __('Free') : Utility::priceFormat($course->price)}}</h3>
                                                            @endif
                                                        </div>
                                                        <div class="add_to_cart" data-id="{{$course->id}}">
                                                            @if($key !== false)
                                                                <a  id="cart-btn-{{$course->id}}" class="btn cart-btn">{{__('Already in Cart')}}</a>
                                                            @else
                                                                <a id="cart-btn-{{$course->id}}" class="btn cart-btn">{{__('Add in Cart')}}</a>
                                                            @endif
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="card-price">
                                                        @if($course->has_discount == 'on')
                                                            <h3>{{ ($course->is_free == 'on')? __('Free') : Utility::priceFormat($course->price)}}</h3>
                                                        @else
                                                            <h3>{{ ($course->is_free == 'on')? __('Free') : Utility::priceFormat($course->price)}}</h3>
                                                        @endif
                                                    </div>
                                                    <div class="add_to_cart" data-id="{{$course->id}}">
                                                        @if($key !== false)
                                                            <a id="cart-btn-{{$course->id}}" class="btn cart-btn">{{__('Already in Cart')}}</a>
                                                        @else
                                                            <a id="cart-btn-{{$course->id}}" class="cart-btn btn">{{__('Add in Cart')}}</a>
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

        <!-- Category -->
        @php
            $main_homepage_category_key = array_search('Home-Categories',array_column($getStoreThemeSetting, 'section_name'));
            $category_enable = 'off';
            $homepage_category_title = 'Improve Your Skills with ModernCourse';
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
            <section class="testimonial-section padding-bottom padding-top">
                <div class="container">
                    <!-- Category Section -->
                    @if($demoStoreThemeSetting['enable_categories'] == 'on')
                        @if($categories->count()>0)
                            <div class="row align-items-center">
                                <div class="col-lg-5 col-md-12 col-12">
                                    <div class="testimonial-left-side">
                                        <div class="section-title">
                                            <h2>{{ $homepage_category_title }}</h2>
                                        </div>
                                        <p>{{ $homepage_category_description }} </p>
                                        <a href="{{route('store.search',[$store->slug])}}" class="btn"> {{ $homepage_category_button }}<svg viewBox="0 0 10 5">
                                            <path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z">
                                            </path>
                                        </svg></a>
                                    </div>
                                </div>
                                <div class="col-lg-7 col-md-12 col-12">
                                    <div class="customers-review">
                                        @foreach($categories as $category)
                                            <div class="testimonial-card">
                                                <div class="testimonial-card-inner">
                                                    <div class="user-img">
                                                        @if(!empty($category->category_image))
                                                            <img alt="Image placeholder" src="{{asset(Storage::url('uploads/category_image/'.$category->category_image))}}" class="myimage img-fluid">
                                                        @else
                                                            {{-- <img src="{{asset('assets/img/business.svg')}}" alt="business" class="img-fluid"> --}}
                                                            <img src="{{ asset('assets/themes/theme2/images/data-analyst.jpg') }}" alt="">
                                                        @endif
                                                    </div>
                                                    <div class="reviews-words">
                                                        <a href="{{route('store.search',[$store->slug,Crypt::encrypt($category->id)])}}">
                                                            <h5>{{$category->name}}</h5>
                                                        </a>
                                                        <p>{{ __('Lorem Ipsum is simply dummy text of the printing and.') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </section>
        @endif

        <!-- Sale -->
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
            <section class="home-category-section padding-bottom">
                <div class="container">
                    <div class="section-title d-flex align-items-center justify-content-between">
                        {{-- <h2><b>Earn Your Degree</b> <br> with ModernCourse</h2> --}}
                        <h2>{{ $homepage_onsale_course_title }}</h2>
                        <a href="{{route('store.search',[$store->slug])}}" class="btn gradient-style">{{ $homepage_onsale_course_button }} <svg viewBox="0 0 10 5">
                            <path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z">
                            </path>
                        </svg></a>
                    </div>
                    <div class="row home-category-row">
                        @foreach($courses as $course)
                            @if(!empty($course->discount))
                                <div class="col-lg-4 col-sm-6 col-md-6 col-12 category-itm">
                                    <div class="category-itm-inner">
                                        <div class="category-content-top">
                                            <div class="category-img">
                                                @if(!empty($course->thumbnail))
                                                    <img alt="Image placeholder" src="{{asset(Storage::url('uploads/thumbnail/'.$course->thumbnail))}}" class="img-fluid">
                                                @else
                                                    <img src="{{ asset('assets/themes/theme2/images/category-1.jpg') }}" alt="">
                                                @endif
                                            </div>
                                            <div class="category-content-inner">
                                                <h5>{{!empty($course->category_id)? $course->category_id->name:'-'}}</h5>
                                                <span class="sale">{{__('Sale')}}</span>
                                            </div>
                                        </div>
                                        <div class="category-content-main">
                                            <h3>
                                                <a href="{{route('store.viewcourse',[$store->slug,\Illuminate\Support\Facades\Crypt::encrypt($course->id)])}}"> {{$course->title}}</a>
                                            </h3>
                                            <p>{{ __('Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy.') }}</p>
                                            
                                            <div class="card-price-section">
                                                @if(Auth::guard('students')->check())
                                                    @if(in_array($course->id,Auth::guard('students')->user()->purchasedCourse()))
                                                        <div class="card-price">
                                                        </div>
                                                        <div class="add-cart">
                                                            {{-- <a href="{{route('store.fullscreen',[$store->slug,Crypt::encrypt($course->id),''])}}">
                                                                {{__('Start Watching')}}
                                                            </a> --}}
                                                            <a href="{{route('store.fullscreen',[$store->slug,Crypt::encrypt($course->id),''])}}" class="btn cart-btn">{{ __('Get Started') }} <svg viewBox="0 0 10 5">
                                                                <path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z">
                                                                </path>
                                                            </svg></a>
                                                        </div>
                                                    @else
                                                        <div class="card-price">
                                                            @if($course->has_discount == 'on')
                                                                <h3> {{ ($course->is_free == 'on')? __('Free') : Utility::priceFormat($course->price)}}</h3>
                                                                <sub>
                                                                    <del> {{ Utility::priceFormat($course->discount)}} </del>
                                                                </sub>
                                                            @else
                                                                <h3> {{ ($course->is_free == 'on')? __('Free') : Utility::priceFormat($course->price)}}</h3>
                                                                <sub>
                                                                    <del> {{ Utility::priceFormat($course->discount)}} </del>
                                                                </sub>
                                                            @endif
                                                        </div>
                                                        <div class="add_to_cart" data-id="{{$course->id}}">
                                                            @if($key !== false)
                                                                <a  id="cart-btn-{{$course->id}}" class="btn">{{__('Already in Cart')}}</a>
                                                            @else
                                                                <a id="cart-btn-{{$course->id}}" class="btn">{{__('Add in Cart')}}</a>
                                                            @endif
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="card-price">
                                                        @if($course->has_discount == 'on')
                                                            <h3>{{ ($course->is_free == 'on')? __('Free') : Utility::priceFormat($course->price)}}</h3>
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
                                                            <a id="cart-btn-{{$course->id}}" class="btn">{{__('Already in Cart')}}</a>
                                                        @else
                                                            <a id="cart-btn-{{$course->id}}" class="cart-btn btn">{{__('Add in Cart')}}</a>
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
                <div class="shape-image">
                    {{-- <img src="{{ asset('assets/themes/theme2/images/section-shape-left.png') }}" alt=""> --}}
                    @php
                        $data=explode(".",$store->store_theme);                               
                    @endphp

                    @if($data[0]=='dark-blue-color')
                        <img src="{{ asset('assets/themes/theme2/images/section-shape-left.png') }}" alt="">
                    @elseif($data[0]=='dark-green-color')
                        <img src="{{ asset('assets/themes/theme2/images/section-shape-left2.png') }}" alt="">
                    @else
                        <img src="{{ asset('assets/themes/theme2/images/section-shape-left3.png') }}" alt="">
                    @endif

                </div>
            </section>
        @endif

        <!--  Subscriber -->
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
            <section class="testimonial-section padding-bottom padding-top">
                <div class="container">
                    <div class="newsletter-container">
                        <div class="section-title">
                            <h2>{{ $homepage_email_subscriber_title }}</h2>
                            <p>{{ $homepage_email_subscriber_subtext }}</p>
                        </div>
                        <div class="newsletter-form">
                            <form action="">
                                <div class="input-wrapper">
                                    <input type="email" placeholder="Type your email address....">
                                    <button type="submit" class="btn btn-white">{{ $homepage_email_subscriber_button }} <svg viewBox="0 0 10 5">
                                        <path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z">
                                        </path>
                                    </svg></button>
                                </div>
                                <div class="checkbox-custom">
                                    <input type="checkbox" class="" id="newslettercheckbox">
                                    <label for="newslettercheckbox">{{ $homepage_email_subscriber_subtext }}</label>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
             
            </section> 
        @endif
    </div>
@endsection


