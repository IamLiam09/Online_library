@extends('layouts.theme1.shopfront')
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
        $header_enebme = 'off';
        $homepage_header_text = 'Special offer';
        $homepage_header_offer_description = 'The Data Science Course 2021: Complete Data Science Bootcamp $490.00';
        $homepage_header_title = 'Knowledge from <b> +300 categories </b> in one place.';
        $homepage_header_Sub_text = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.';
        $homepage_header_Bckground_Image = '';

        if(!empty($getStoreThemeSetting[$main_homepage_header_text_key])) {
            $homepage_header = $getStoreThemeSetting[$main_homepage_header_text_key];
            $header_enebme = $homepage_header['section_enable'];

            $homepage_header_text_key = array_search('Offer Text',array_column($homepage_header['inner-list'], 'field_name'));            
            $homepage_header_text = $homepage_header['inner-list'][$homepage_header_text_key]['field_default_text'];

            $homepage_header_offer_description_key = array_search('homepage-header-offer-description',array_column($homepage_header['inner-list'], 'field_slug'));
            $homepage_header_offer_description = $homepage_header['inner-list'][$homepage_header_offer_description_key]['field_default_text'];

            $homepage_header_title_key = array_search('Title',array_column($homepage_header['inner-list'], 'field_name'));
            $homepage_header_title = $homepage_header['inner-list'][$homepage_header_title_key]['field_default_text'];
            
            $homepage_header_Sub_text_key = array_search('Sub Title',array_column($homepage_header['inner-list'], 'field_name'));
            $homepage_header_Sub_text = $homepage_header['inner-list'][$homepage_header_Sub_text_key]['field_default_text'];

            $homepage_header_Bckground_Image_key = array_search('Thumbnail Image',array_column($homepage_header['inner-list'], 'field_name'));
            $homepage_header_Bckground_Image = $homepage_header['inner-list'][$homepage_header_Bckground_Image_key]['field_default_text'];
        }

        $sub_title[0] = '+74';
        $sub_title[1] = '+30';
        $sub_subtitle[0] = __('Reports courses');
        $sub_subtitle[1] = __('Math courses');
        if($header_enebme == 'on') {
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
                        }  else {                            
                            if($sl == "homepage-thumbnail-innerbox-1-title") {
                                $sub_title[$i] = $value1['field_default_text'];
                            }
                            if($sl == "homepage-thumbnail-innerbox-1-sub-text") {
                                $sub_subtitle[$i] = $value1['field_default_text'];
                            }
                        }
                    }
                }
            }
        }
    @endphp        
    @if ($header_enebme == 'on')
        <section class="main-home-first-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-5 col-md-6 col-12">
                        <div class="home-banner-content">
                            <div class="home-banner-content-inner">
                                @if(!empty($special_offer_courses) && isset($special_offer_courses))
                                    <div class="offer-announcement">
                                    
                                        <div class="offer-badge">
                                            {!! $homepage_header_text !!}
                                        </div> 
                                        <div class="offer-desk">
                                            <p>
                                                {{ $homepage_header_offer_description }}
                                                <a href="{{route('store.viewcourse',[$store->slug,\Illuminate\Support\Facades\Crypt::encrypt($special_offer_courses->id)])}}">{{__('Get now')}}</a>
                                                {{-- {{$special_offer_courses->title}} {{ Utility::priceFormat($special_offer_courses->price)}} 
                                                    <a href="{{route('store.viewcourse',[$store->slug,\Illuminate\Support\Facades\Crypt::encrypt($special_offer_courses->id)])}}">{{__('Get now')}}</a> --}}                                                    
                                            </p>
                                        </div>
                                    </div>
                                @endif 
                                
                                <h2 class="h1"> {!! $homepage_header_title !!}. </h2>
                                <p>{!! $homepage_header_Sub_text !!} </p>
                                <div class="search-form">
                                    <form action="{{route('store.search',[$store->slug])}}" method="get">
                                        <div class="input-wrapper">
                                            <input type="search" placeholder="{{__('Search programming, design, math')}}..." name="search" id="search_box">
                                            <button type="submit" aria-hidden="true" class="btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13" fill="none">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M9.47487 10.5131C8.48031 11.2863 7.23058 11.7466 5.87332 11.7466C2.62957 11.7466 0 9.11706 0 5.87332C0 2.62957 2.62957 0 5.87332 0C9.11706 0 11.7466 2.62957 11.7466 5.87332C11.7466 7.23058 11.2863 8.48031 10.5131 9.47487L12.785 11.7465C13.0717 12.0332 13.0717 12.4981 12.785 12.7848C12.4983 13.0715 12.0334 13.0715 11.7467 12.7848L9.47487 10.5131ZM10.2783 5.87332C10.2783 8.30612 8.30612 10.2783 5.87332 10.2783C3.44051 10.2783 1.46833 8.30612 1.46833 5.87332C1.46833 3.44051 3.44051 1.46833 5.87332 1.46833C8.30612 1.46833 10.2783 3.44051 10.2783 5.87332Z"
                                                        fill="#545454"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 banner-left-side col-md-6 col-12">
                        <div class="courses-reports report-box">
                            <span class="svg-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="49" height="49" viewBox="0 0 49 49"
                                    fill="none">
                                    <g clip-path="url(#clip0_475_1165)">
                                        <path
                                            d="M12.3678 21.2906L16.0226 24.5651C15.0077 25.1196 14.2297 26.0054 13.799 27.0484L10.144 23.7737C11.1589 23.2193 11.937 22.3336 12.3678 21.2906Z"
                                            fill="white" fill-opacity="0.5" />
                                        <path
                                            d="M21.0247 24.6834C21.9775 25.2643 22.7361 26.1616 23.1304 27.2675L27.3779 23.8072C26.425 23.2264 25.6665 22.3291 25.2721 21.2233L21.0247 24.6834Z"
                                            fill="white" fill-opacity="0.5" />
                                        <path
                                            d="M34.6034 21.4425L38.2581 24.7171C37.2433 25.2715 36.4653 26.1574 36.0346 27.2004L32.3796 23.9257C33.3945 23.3713 34.1726 22.4856 34.6034 21.4425Z"
                                            fill="white" fill-opacity="0.5" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M2.86481 20.4767C3.46734 23.1716 6.14042 24.8678 8.83531 24.2652C11.5302 23.6627 13.2264 20.9896 12.6239 18.2947C12.0213 15.5998 9.34826 13.9036 6.65337 14.5062C3.95848 15.1087 2.26229 17.7818 2.86481 20.4767ZM6.11783 19.7494C6.31867 20.6477 7.2097 21.2131 8.108 21.0122C9.00629 20.8114 9.57169 19.9203 9.37085 19.022C9.17001 18.1237 8.27898 17.5584 7.38068 17.7592C6.48239 17.96 5.91699 18.8511 6.11783 19.7494Z"
                                            fill="white" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M25.1004 20.6287C25.7029 23.3235 28.376 25.0197 31.0709 24.4172C33.7658 23.8147 35.462 21.1416 34.8595 18.4467C34.2569 15.7518 31.5838 14.0556 28.889 14.6582C26.1941 15.2607 24.4979 17.9338 25.1004 20.6287ZM28.3534 19.9013C28.5543 20.7996 29.4453 21.365 30.3436 21.1642C31.2419 20.9634 31.8073 20.0723 31.6064 19.174C31.4056 18.2757 30.5146 17.7103 29.6163 17.9112C28.718 18.112 28.1526 19.0031 28.3534 19.9013Z"
                                            fill="white" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M35.7785 30.1959C36.3811 32.8908 39.0541 34.587 41.749 33.9845C44.4439 33.382 46.1401 30.7089 45.5376 28.014C44.9351 25.3191 42.262 23.6229 39.5671 24.2254C36.8722 24.828 35.176 27.5011 35.7785 30.1959ZM39.0316 29.4686C39.2324 30.3669 40.1234 30.9323 41.0217 30.7315C41.92 30.5306 42.4854 29.6396 42.2846 28.7413C42.0837 27.843 41.1927 27.2776 40.2944 27.4785C39.3961 27.6793 38.8307 28.5703 39.0316 29.4686Z"
                                            fill="white" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M19.5135 33.8325C16.8186 34.435 14.1455 32.7388 13.543 30.044C12.9404 27.3491 14.6366 24.676 17.3315 24.0735C20.0264 23.4709 22.6995 25.1671 23.302 27.862C23.9045 30.5569 22.2083 33.23 19.5135 33.8325ZM18.7861 30.5795C17.8878 30.7803 16.9968 30.2149 16.796 29.3166C16.5951 28.4183 17.1605 27.5273 18.0588 27.3265C18.9571 27.1256 19.8481 27.691 20.049 28.5893C20.2498 29.4876 19.6844 30.3786 18.7861 30.5795Z"
                                            fill="white" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_475_1165">
                                            <rect width="40" height="40" fill="white"
                                                transform="translate(0.319336 9.09106) rotate(-12.603)" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </span>
                            <div class="inner-report">
                                <h3>{{ $sub_title[0] }}</h3> 
                                <p>{{ $sub_subtitle[0] }}</p>
                            </div>
                        </div>
                        <div class="maths-courses report-box second-style">
                            <span class="svg-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="47" height="47" viewBox="0 0 47 47"
                                    fill="none">
                                    <g clip-path="url(#clip0_478_1166)">
                                        <path
                                            d="M16.3619 27.1264L16.8164 24.5228C17.0717 23.0601 18.968 22.6362 19.8218 23.8511L21.971 26.9091C22.8247 28.124 21.7835 29.7646 20.3208 29.5093L17.7172 29.0549C16.8104 28.8966 16.2036 28.0332 16.3619 27.1264Z"
                                            fill="white" fill-opacity="0.5" />
                                        <path
                                            d="M10.1589 14.2009C9.25217 14.0426 8.38878 14.6494 8.23051 15.5562C8.07224 16.4629 8.67901 17.3263 9.58578 17.4846L12.8695 18.0577C13.7762 18.216 14.6396 17.6092 14.7979 16.7025C14.9562 15.7957 14.3494 14.9323 13.4426 14.774L10.1589 14.2009Z"
                                            fill="white" fill-opacity="0.5" />
                                        <path
                                            d="M8.43947 24.0519C7.5327 23.8937 6.66932 24.5004 6.51105 25.4072C6.35278 26.314 6.95955 27.1774 7.86631 27.3356L11.15 27.9088C12.0568 28.0671 12.9202 27.4603 13.0784 26.5535C13.2367 25.6468 12.6299 24.7834 11.7232 24.6251L8.43947 24.0519Z"
                                            fill="white" fill-opacity="0.5" />
                                        <path
                                            d="M15.9979 38.9062C15.0911 38.7479 14.4844 37.8845 14.6426 36.9777L15.2158 33.6941C15.3741 32.7873 16.2375 32.1805 17.1442 32.3388C18.051 32.4971 18.6578 33.3604 18.4995 34.2672L17.9263 37.5509C17.7681 38.4577 16.9047 39.0644 15.9979 38.9062Z"
                                            fill="white" fill-opacity="0.5" />
                                        <path
                                            d="M24.4937 38.6972C24.3354 39.604 24.9422 40.4674 25.849 40.6256C26.7557 40.7839 27.6191 40.1771 27.7774 39.2704L28.3506 35.9867C28.5088 35.0799 27.902 34.2165 26.9953 34.0583C26.0885 33.9 25.2251 34.5068 25.0669 35.4135L24.4937 38.6972Z"
                                            fill="white" fill-opacity="0.5" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M12.5907 9.96046L8.64814 32.5479C8.48987 33.4547 9.09664 34.318 10.0034 34.4763L32.5908 38.4189C34.0536 38.6742 35.0948 37.0335 34.241 35.8187L15.5961 9.28873C14.7424 8.07388 12.846 8.49774 12.5907 9.96046ZM9.307 9.38731L5.36445 31.9747C4.88963 34.695 6.70996 37.2852 9.43025 37.76L32.0177 41.7026C36.4059 42.4685 39.5295 37.5466 36.9682 33.9021L18.3233 7.37209C15.762 3.72758 10.0729 4.99913 9.307 9.38731Z"
                                            fill="white" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_478_1166">
                                            <rect width="40" height="40" fill="white"
                                                transform="translate(7.52588 0.206299) rotate(9.90101)" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </span>
                            <div class="inner-report">
                                <h3>{{ $sub_title[1] }}</h3>
                                <p>{{ $sub_subtitle[1] }}</p>
                            </div>
                        </div>      
                        <div class="banner-image-left">    
                            @php
                                $data=explode(".",$store->store_theme);                               
                            @endphp
                            @if(!empty($homepage_header_Bckground_Image))
                                <img src="{{asset(Storage::url('uploads/'.$homepage_header_Bckground_Image))}}">                                  
                            @else
                                @if($data[0]=='green-style')
                                    <img src="{{asset('assets/imgs/MaleRunning3.png')}}" alt="">
                                @elseif($data[0]=='blue-style')
                                    <img src="{{asset('assets/imgs/MaleRunning2.png')}}" alt="">
                                @else
                                    <img src="{{asset('assets/imgs/MaleRunning1.png')}}" alt="">
                                @endif
                            @endif                              
                            
                        </div>
                    </div>
                </div>
            </div>
                <div class="scroll-down">
                    <a href="#newsletter" class="scroll-link">
                        <span class="scroll-icon"></span>
                        {{ __('Scroll Down') }}
                    </a>
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
            <section class="eatured-course padding-bottom">                            
                <div class="container">
                    <div class="section-title d-flex align-items-center justify-content-between">
                        {{-- <h2>{{$demoStoreThemeSetting['featured_title']}}</h2> --}}
                        <h2>{{ $homepage_featured_course_title }}</h2>
                        <a href="{{route('store.search',[$store->slug])}}" class="btn"> {{ $homepage_featured_course_button }} </a>
                        {{-- <a href="{{route('store.search',[$store->slug])}}" class="btn"> {{__('Show more')}} </a> --}}
                    </div>                   

                    <div class="latest-course-slider slider-comman">
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
                                
                            <div class="course-widget">
                                <div class="course-widget-inner">
                                    <div class="course-media">

                                        <a href="{{route('store.viewcourse',[$store->slug,\Illuminate\Support\Facades\Crypt::encrypt($course->id)])}}">
                                            @if(!empty($course->thumbnail))
                                                <img alt="card" src="{{asset(Storage::url('uploads/thumbnail/'.$course->thumbnail))}}"
                                                class="img-fluid">
                                            @else
                                                <img src="{{asset('assets/img/card-img.svg')}}" alt="card" class="img-fluid">
                                            @endif
                                        </a>
                                    </div>
                                    <div class="course-caption">
                                        <div class="course-caption-top d-flex align-items-center">
                                            <span class="badge">{{!empty($course->category_id)?$course->category_id->name:'-'}}</span>
                                            @if(Auth::guard('students')->check())
                                                @if(sizeof($course->student_wl)>0)
                                                    {{-- @foreach($course->student_wl as $student_wl) --}}
                                                        <a  class="wishlist-btn wishlist_btn add_to_wishlist" data-id="{{$course->id}}" tabindex="0" data-placement="top">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="14" viewBox="0 0 17 14" fill="none">
                                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M9.18991 3.10164C8.89678 3.37992 8.43395 3.37992 8.14082 3.10164L7.61627 2.60366C7.00231 2.0208 6.17289 1.66491 5.25627 1.66491C3.37348 1.66491 1.84718 3.17483 1.84718 5.03741C1.84718 6.82306 2.82429 8.29753 4.23488 9.50902C5.64667 10.7215 7.33461 11.5257 8.34313 11.9361C8.554 12.0219 8.77673 12.0219 8.9876 11.9361C9.99612 11.5257 11.6841 10.7215 13.0959 9.50901C14.5064 8.29753 15.4835 6.82305 15.4835 5.03741C15.4835 3.17483 13.9572 1.66491 12.0745 1.66491C11.1578 1.66491 10.3284 2.0208 9.71446 2.60366L9.18991 3.10164ZM8.66537 1.52219C7.7806 0.682237 6.57937 0.166016 5.25627 0.166016C2.53669 0.166016 0.332031 2.34701 0.332031 5.03741C0.332031 9.81007 5.61259 12.4457 7.76672 13.3223C8.34685 13.5584 8.98388 13.5584 9.56401 13.3223C11.7181 12.4457 16.9987 9.81006 16.9987 5.03741C16.9987 2.34701 14.794 0.166016 12.0745 0.166016C10.7514 0.166016 9.55013 0.682237 8.66537 1.52219Z" fill="white"></path>
                                                            </svg>
                                                        </a> 
                                                    {{-- @endforeach --}}
                                                @else
                                                    <a href="#" class="wishlist-btn add_to_wishlist" tabindex="0" data-id="{{$course->id}}" data-placement="top">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="14" viewBox="0 0 17 14" fill="none">
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9.18991 3.10164C8.89678 3.37992 8.43395 3.37992 8.14082 3.10164L7.61627 2.60366C7.00231 2.0208 6.17289 1.66491 5.25627 1.66491C3.37348 1.66491 1.84718 3.17483 1.84718 5.03741C1.84718 6.82306 2.82429 8.29753 4.23488 9.50902C5.64667 10.7215 7.33461 11.5257 8.34313 11.9361C8.554 12.0219 8.77673 12.0219 8.9876 11.9361C9.99612 11.5257 11.6841 10.7215 13.0959 9.50901C14.5064 8.29753 15.4835 6.82305 15.4835 5.03741C15.4835 3.17483 13.9572 1.66491 12.0745 1.66491C11.1578 1.66491 10.3284 2.0208 9.71446 2.60366L9.18991 3.10164ZM8.66537 1.52219C7.7806 0.682237 6.57937 0.166016 5.25627 0.166016C2.53669 0.166016 0.332031 2.34701 0.332031 5.03741C0.332031 9.81007 5.61259 12.4457 7.76672 13.3223C8.34685 13.5584 8.98388 13.5584 9.56401 13.3223C11.7181 12.4457 16.9987 9.81006 16.9987 5.03741C16.9987 2.34701 14.794 0.166016 12.0745 0.166016C10.7514 0.166016 9.55013 0.682237 8.66537 1.52219Z" fill="white"></path>
                                                        </svg>
                                                    </a>
                                                @endif
                                            @else
                                                <a href="#" class="wishlist-btn add_to_wishlist" tabindex="0" data-id="{{$course->id}}" data-placement="top">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="14" viewBox="0 0 17 14" fill="none">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M9.18991 3.10164C8.89678 3.37992 8.43395 3.37992 8.14082 3.10164L7.61627 2.60366C7.00231 2.0208 6.17289 1.66491 5.25627 1.66491C3.37348 1.66491 1.84718 3.17483 1.84718 5.03741C1.84718 6.82306 2.82429 8.29753 4.23488 9.50902C5.64667 10.7215 7.33461 11.5257 8.34313 11.9361C8.554 12.0219 8.77673 12.0219 8.9876 11.9361C9.99612 11.5257 11.6841 10.7215 13.0959 9.50901C14.5064 8.29753 15.4835 6.82305 15.4835 5.03741C15.4835 3.17483 13.9572 1.66491 12.0745 1.66491C11.1578 1.66491 10.3284 2.0208 9.71446 2.60366L9.18991 3.10164ZM8.66537 1.52219C7.7806 0.682237 6.57937 0.166016 5.25627 0.166016C2.53669 0.166016 0.332031 2.34701 0.332031 5.03741C0.332031 9.81007 5.61259 12.4457 7.76672 13.3223C8.34685 13.5584 8.98388 13.5584 9.56401 13.3223C11.7181 12.4457 16.9987 9.81006 16.9987 5.03741C16.9987 2.34701 14.794 0.166016 12.0745 0.166016C10.7514 0.166016 9.55013 0.682237 8.66537 1.52219Z" fill="white"></path>
                                                    </svg>
                                                </a>
                                            @endif
                                        </div>
                                        <h6>
                                            <a href="{{route('store.viewcourse',[$store->slug,\Illuminate\Support\Facades\Crypt::encrypt($course->id)])}}">
                                                {{$course->title}}
                                            </a>
                                        </h6>                                       
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
                                        <div class="course-bottom">
                                            <div class="course-detail">
                                                <p>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                                                        <g clip-path="url(#clip0_19_26)">
                                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                                d="M5.89035 0.981725C3.17939 0.981725 0.981725 3.17939 0.981725 5.89035C0.981725 7.16364 1.46654 8.3237 2.26163 9.196C2.67034 8.3076 3.38394 7.61471 4.24934 7.22454C3.75002 6.77528 3.43604 6.12405 3.43604 5.39949C3.43604 4.04401 4.53487 2.94518 5.89035 2.94518C7.24583 2.94518 8.34466 4.04401 8.34466 5.39949C8.34466 6.11254 8.04058 6.75457 7.55502 7.20298C8.43552 7.56901 9.18412 8.23342 9.54281 9.16977C10.3238 8.30051 10.799 7.15092 10.799 5.89035C10.799 3.17939 8.60131 0.981725 5.89035 0.981725ZM9.69387 10.3882C10.9703 9.30774 11.7807 7.69368 11.7807 5.89035C11.7807 2.6372 9.1435 0 5.89035 0C2.6372 0 0 2.6372 0 5.89035C0 7.69366 0.810351 9.30769 2.08677 10.3882C2.12022 10.426 2.15982 10.459 2.20478 10.4855C3.21384 11.2959 4.49547 11.7807 5.89035 11.7807C7.28519 11.7807 8.5668 11.2959 9.57584 10.4856C9.62075 10.4591 9.66038 10.4261 9.69387 10.3882ZM8.74856 9.88145L8.66025 9.61652C8.29993 8.53556 7.14229 7.8538 5.89035 7.8538C4.6233 7.8538 3.49161 8.64647 3.0586 9.83723L3.04038 9.88735C3.84386 10.4613 4.82767 10.799 5.89035 10.799C6.95667 10.799 7.94357 10.459 8.74856 9.88145ZM5.89035 6.87208C6.70364 6.87208 7.36294 6.21278 7.36294 5.39949C7.36294 4.5862 6.70364 3.9269 5.89035 3.9269C5.07706 3.9269 4.41776 4.5862 4.41776 5.39949C4.41776 6.21278 5.07706 6.87208 5.89035 6.87208Z"
                                                                fill="#8A94A6"></path>
                                                        </g>
                                                        <defs>
                                                            <clipPath id="">
                                                                <rect width="11.7807" height="11.7807" fill="white"></rect>
                                                            </clipPath>
                                                        </defs>
                                                    </svg>
                                                    {{$course->student_count->count()}} &nbsp; <span>{{__('Students')}}</span>
                                                </p>
                                                <p>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                                                    <g clip-path="url(#clip0_19_28)">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M5.45116 0.161627C5.72754 0.0234357 6.05285 0.0234355 6.32924 0.161627L11.122 2.558C11.8456 2.91979 11.8456 3.95237 11.122 4.31416L10.4238 4.66324L11.122 5.01231C11.8456 5.3741 11.8456 6.40669 11.122 6.76848L10.4238 7.11755L11.122 7.46663C11.8456 7.82842 11.8456 8.861 11.122 9.22279L6.32924 11.6192C6.05286 11.7574 5.72754 11.7574 5.45116 11.6192L0.658407 9.22279C-0.0651723 8.861 -0.0651715 7.82842 0.658406 7.46663L1.35656 7.11755L0.658407 6.76848C-0.0651723 6.40669 -0.0651715 5.3741 0.658406 5.01231L1.35656 4.66324L0.658407 4.31416C-0.0651731 3.95237 -0.0651713 2.91979 0.658407 2.558L5.45116 0.161627ZM2.67882 4.22677C2.67563 4.22513 2.67243 4.22353 2.66921 4.22197L1.09745 3.43608L5.8902 1.03971L10.6829 3.43608L9.1111 4.22201C9.10793 4.22355 9.10479 4.22512 9.10166 4.22673L5.8902 5.83246L2.67882 4.22677ZM2.45416 5.21204L1.09745 5.8904L2.66915 6.67625L2.67888 6.68111L5.8902 8.28677L9.10163 6.68105L9.11112 6.67631L10.6829 5.8904L9.32623 5.21204L6.32924 6.71054C6.05286 6.84873 5.72754 6.84873 5.45116 6.71054L2.45416 5.21204ZM1.09745 8.34471L2.45416 7.66635L5.45116 9.16485C5.72754 9.30304 6.05286 9.30304 6.32924 9.16485L9.32623 7.66635L10.6829 8.34471L5.8902 10.7411L1.09745 8.34471Z" fill="#8A94A6"></path>
                                                    </g>
                                                    <defs>
                                                        <clipPath id="">
                                                            <rect width="11.7807" height="11.7807" fill="white"></rect>
                                                        </clipPath>
                                                    </defs>
                                                    </svg>
                                                    {{$course->chapter_count->count()}} &nbsp; <span>{{__('Chapters')}}</span>
                                                </p>
                                                <p>{{$course->level}}</p>
                                            </div>
                                        </div>
                                        <div class="price-addtocart d-flex align-items-center justify-content-between">
                                            @if(Auth::guard('students')->check())
                                                @if(in_array($course->id,Auth::guard('students')->user()->purchasedCourse()))
                                                    <div class="price">
                                                    </div>
                                                    <div class="add-cart">
                                                        <a class="btn"  href="{{route('store.fullscreen',[$store->slug,Crypt::encrypt($course->id),''])}}">
                                                            {{__('Start Watching')}}
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="price">
                                                        @if($course->has_discount == 'on')
                                                            <ins> {{ ($course->is_free == 'on')? __('Free') : Utility::priceFormat($course->price)}}</ins>
                                                        @else
                                                            <ins> {{ ($course->is_free == 'on')? __('Free') : Utility::priceFormat($course->price)}}</ins>
                                                        @endif
                                                    </div>
                                                    <div class="add_to_cart" data-id="{{$course->id}}">                                                    
                                                        @if($key !== false)
                                                            <a id="cart-btn-{{$course->id}}" class="btn">{{__('Already in Cart')}}</a>
                                                        @else
                                                            <a id="cart-btn-{{$course->id}}" class="btn">{{__('Add in Cart')}}</a>
                                                        @endif
                                                    </div>
                                                @endif
                                            @else
                                                <div class="price">
                                                    @if($course->has_discount == 'on')
                                                        <ins>{{ ($course->is_free == 'on')? __('Free') : Utility::priceFormat($course->price)}}</ins>
                                                    @else
                                                        <ins>{{ ($course->is_free == 'on')? __('Free') : Utility::priceFormat($course->price)}}</ins>
                                                    @endif
                                                </div>
                                                <div class="add_to_cart"  data-id="{{$course->id}}">
                                                    
                                                    @if($key !== false)
                                                        <a id="cart-btn-{{$course->id}}" class="btn">{{__('Already in Cart')}}</a>
                                                    @else
                                                        <a id="cart-btn-{{$course->id}}" class="btn">{{__('Add in Cart')}}</a>
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
        $homepage_category_tag = 'Categories';
        $homepage_category_title = 'Knowledge from <b> +300 categories </b>  in one place.';
        $homepage_category_description = 'There is only that moment and the incredible certainty that everything under the sun has been written by one hand only..';
        $homepage_category_button = 'Learn More';

        if(!empty($getStoreThemeSetting[$main_homepage_category_key])) {
            $homepage_category_header = $getStoreThemeSetting[$main_homepage_category_key];
            $category_enable = $homepage_category_header['section_enable'];

            $homepage_tag_key = array_search('Tag',array_column($homepage_category_header['inner-list'], 'field_name'));            
            $homepage_category_tag = $homepage_category_header['inner-list'][$homepage_tag_key]['field_default_text'];

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
            <section class="padding-bottom padding-top knowledge-section">
                <div class="container">
                    <div class="section-title d-flex align-items-center justify-content-between">
                        
                        <div class="left-side">
                            <div class="categories">
                                {{-- <span class="badge">{{$demoStoreThemeSetting['categories']}}</span> --}}
                                <span class="badge">{!! $homepage_category_tag !!}</span>
                            </div> 
                            {{-- <h2>{{__('Knowledge from')}}<b> +300 {{__('categories')}} </b> {{__('in one place')}}. </h2> --}}
                            <h2>{!! $homepage_category_title !!} </h2>
                        </div>
                        <div class="right-side">
                            <p>{{$demoStoreThemeSetting['categories_title']}}.</p>
                            <a href="{{route('store.search',[$store->slug])}}" class="btn btn-secondary"> {{ $homepage_category_button }}</a>
                            {{-- <a href="{{route('store.search',[$store->slug])}}" class="btn btn-secondary"> {{__('Learn More')}}</a> --}}
                        </div>
                    </div>
                    <div class="row row-gap knowledge-cards-row">
                        @foreach($categories as $category)

                            <div class="col-md-4 col-sm-6 col-12 knowledge-card">
                                <div class="knowledge-card-inner">
                                    <div class="knowledge-top-content">
                                        <div class="knowledge-icon">
                                            @if(!empty($category->category_image))
                                                <img alt="Image placeholder" src="{{asset(Storage::url('uploads/category_image/'.$category->category_image))}}">
                                            @else
                                                <img src="{{asset('assets/img/business.svg')}}" alt="business" class="img-fluid">
                                            @endif 
                                        </div>

                                        <h4>
                                            <a href="{{route('store.search',[$store->slug,Crypt::encrypt($category->id)])}}">{{$category->name}}</a>
                                        </h4>
                                    </div>
                                    <div class="knowledge-content">
                                        <p>{{__('Lorem Ipsum is simply dummy text of the printing and typesetting industry')}}.</p>
                                        <a href="{{route('store.search',[$store->slug])}}">{{__('Find more courses')}}</a>
                                    </div>
                                </div>
                            </div>

                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    @endif

    <!-- COURSE -->   
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
        @if($courses->count()>0) 
            <section class="on-sale-section padding-top padding-bottom">
                <div class="container">
                    <div class="section-title d-flex align-items-center justify-content-between">
                        {{-- <h2>{{__('On Sale')}}</h2> --}}
                        <h2>{{ $homepage_onsale_course_title }}</h2>
                        <a href="{{route('store.search',[$store->slug])}}" class="btn"> {{ $homepage_onsale_course_button }} </a>
                        {{-- <a href="{{route('store.search',[$store->slug])}}" class="btn"> {{__('Show More')}} </a> --}}
                    </div>
                    <div class="sale-slider slider-comman">
                        @foreach($courses as $course)                               
                            @if(!empty($course->discount))                    
                                <div class="course-widget">
                                    <div class="course-widget-inner">
                                        <div class="course-media">
                                            <a href="{{route('store.viewcourse',[$store->slug,\Illuminate\Support\Facades\Crypt::encrypt($course->id)])}}">
                                                {{-- <img src="assets/images/courses.png" alt=""> --}}
                                                @if(!empty($course->thumbnail))
                                                    <img alt="Image placeholder" src="{{asset(Storage::url('uploads/thumbnail/'.$course->thumbnail))}}">
                                                @else
                                                    <img src="{{asset('assets/img/card-img.svg')}}" alt="card">
                                                @endif
                                            </a>
                                        </div>
                                        <div class="course-caption">
                                            <div class="course-caption-top d-flex align-items-center">
                                                <span class="badge">{{!empty($course->category_id)? $course->category_id->name:'-'}}</span>
                                                <span class="badge sale">{{__('Sale')}}</span>                                      
                                                @if(Auth::guard('students')->check())
                                                    @if(sizeof($course->student_wl)>0)
                                                        @foreach($course->student_wl as $student_wl)
                                                            <a href="#" class="wishlist-btn wishlist_btn add_to_wishlist" data-placement="top">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="14"
                                                                viewBox="0 0 17 14" fill="none">
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                    d="M9.18991 3.10164C8.89678 3.37992 8.43395 3.37992 8.14082 3.10164L7.61627 2.60366C7.00231 2.0208 6.17289 1.66491 5.25627 1.66491C3.37348 1.66491 1.84718 3.17483 1.84718 5.03741C1.84718 6.82306 2.82429 8.29753 4.23488 9.50902C5.64667 10.7215 7.33461 11.5257 8.34313 11.9361C8.554 12.0219 8.77673 12.0219 8.9876 11.9361C9.99612 11.5257 11.6841 10.7215 13.0959 9.50901C14.5064 8.29753 15.4835 6.82305 15.4835 5.03741C15.4835 3.17483 13.9572 1.66491 12.0745 1.66491C11.1578 1.66491 10.3284 2.0208 9.71446 2.60366L9.18991 3.10164ZM8.66537 1.52219C7.7806 0.682237 6.57937 0.166016 5.25627 0.166016C2.53669 0.166016 0.332031 2.34701 0.332031 5.03741C0.332031 9.81007 5.61259 12.4457 7.76672 13.3223C8.34685 13.5584 8.98388 13.5584 9.56401 13.3223C11.7181 12.4457 16.9987 9.81006 16.9987 5.03741C16.9987 2.34701 14.794 0.166016 12.0745 0.166016C10.7514 0.166016 9.55013 0.682237 8.66537 1.52219Z"
                                                                    fill="white"></path>
                                                                </svg> 
                                                            </a>
                                                        @endforeach
                                                    @else
                                                        <a class="wishlist-btn add_to_wishlist" data-id="{{$course->id}}" data-placement="top">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="14" viewBox="0 0 17 14" fill="none">
                                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                                d="M9.18991 3.10164C8.89678 3.37992 8.43395 3.37992 8.14082 3.10164L7.61627 2.60366C7.00231 2.0208 6.17289 1.66491 5.25627 1.66491C3.37348 1.66491 1.84718 3.17483 1.84718 5.03741C1.84718 6.82306 2.82429 8.29753 4.23488 9.50902C5.64667 10.7215 7.33461 11.5257 8.34313 11.9361C8.554 12.0219 8.77673 12.0219 8.9876 11.9361C9.99612 11.5257 11.6841 10.7215 13.0959 9.50901C14.5064 8.29753 15.4835 6.82305 15.4835 5.03741C15.4835 3.17483 13.9572 1.66491 12.0745 1.66491C11.1578 1.66491 10.3284 2.0208 9.71446 2.60366L9.18991 3.10164ZM8.66537 1.52219C7.7806 0.682237 6.57937 0.166016 5.25627 0.166016C2.53669 0.166016 0.332031 2.34701 0.332031 5.03741C0.332031 9.81007 5.61259 12.4457 7.76672 13.3223C8.34685 13.5584 8.98388 13.5584 9.56401 13.3223C11.7181 12.4457 16.9987 9.81006 16.9987 5.03741C16.9987 2.34701 14.794 0.166016 12.0745 0.166016C10.7514 0.166016 9.55013 0.682237 8.66537 1.52219Z"
                                                                fill="white"></path>
                                                        </svg> 
                                                        </a>
                                                    @endif
                                                @else
                                                    <a class="wishlist-btn add_to_wishlist" data-id="{{$course->id}}" data-placement="top">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="14"
                                                                viewBox="0 0 17 14" fill="none">
                                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                                d="M9.18991 3.10164C8.89678 3.37992 8.43395 3.37992 8.14082 3.10164L7.61627 2.60366C7.00231 2.0208 6.17289 1.66491 5.25627 1.66491C3.37348 1.66491 1.84718 3.17483 1.84718 5.03741C1.84718 6.82306 2.82429 8.29753 4.23488 9.50902C5.64667 10.7215 7.33461 11.5257 8.34313 11.9361C8.554 12.0219 8.77673 12.0219 8.9876 11.9361C9.99612 11.5257 11.6841 10.7215 13.0959 9.50901C14.5064 8.29753 15.4835 6.82305 15.4835 5.03741C15.4835 3.17483 13.9572 1.66491 12.0745 1.66491C11.1578 1.66491 10.3284 2.0208 9.71446 2.60366L9.18991 3.10164ZM8.66537 1.52219C7.7806 0.682237 6.57937 0.166016 5.25627 0.166016C2.53669 0.166016 0.332031 2.34701 0.332031 5.03741C0.332031 9.81007 5.61259 12.4457 7.76672 13.3223C8.34685 13.5584 8.98388 13.5584 9.56401 13.3223C11.7181 12.4457 16.9987 9.81006 16.9987 5.03741C16.9987 2.34701 14.794 0.166016 12.0745 0.166016C10.7514 0.166016 9.55013 0.682237 8.66537 1.52219Z"
                                                                fill="white"></path>
                                                        </svg> 
                                                    </a>
                                                @endif                                        
                                            </div>
                                            <h6>
                                                <a href="{{route('store.viewcourse',[$store->slug,\Illuminate\Support\Facades\Crypt::encrypt($course->id)])}}">{{$course->title}}</a>
                                            </h6>
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
                                            <div class="course-bottom">
                                                <div class="course-detail">
                                                    <p><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                            viewBox="0 0 12 12" fill="none">
                                                            <g clip-path="url(#clip0_19_26)">
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                    d="M5.89035 0.981725C3.17939 0.981725 0.981725 3.17939 0.981725 5.89035C0.981725 7.16364 1.46654 8.3237 2.26163 9.196C2.67034 8.3076 3.38394 7.61471 4.24934 7.22454C3.75002 6.77528 3.43604 6.12405 3.43604 5.39949C3.43604 4.04401 4.53487 2.94518 5.89035 2.94518C7.24583 2.94518 8.34466 4.04401 8.34466 5.39949C8.34466 6.11254 8.04058 6.75457 7.55502 7.20298C8.43552 7.56901 9.18412 8.23342 9.54281 9.16977C10.3238 8.30051 10.799 7.15092 10.799 5.89035C10.799 3.17939 8.60131 0.981725 5.89035 0.981725ZM9.69387 10.3882C10.9703 9.30774 11.7807 7.69368 11.7807 5.89035C11.7807 2.6372 9.1435 0 5.89035 0C2.6372 0 0 2.6372 0 5.89035C0 7.69366 0.810351 9.30769 2.08677 10.3882C2.12022 10.426 2.15982 10.459 2.20478 10.4855C3.21384 11.2959 4.49547 11.7807 5.89035 11.7807C7.28519 11.7807 8.5668 11.2959 9.57584 10.4856C9.62075 10.4591 9.66038 10.4261 9.69387 10.3882ZM8.74856 9.88145L8.66025 9.61652C8.29993 8.53556 7.14229 7.8538 5.89035 7.8538C4.6233 7.8538 3.49161 8.64647 3.0586 9.83723L3.04038 9.88735C3.84386 10.4613 4.82767 10.799 5.89035 10.799C6.95667 10.799 7.94357 10.459 8.74856 9.88145ZM5.89035 6.87208C6.70364 6.87208 7.36294 6.21278 7.36294 5.39949C7.36294 4.5862 6.70364 3.9269 5.89035 3.9269C5.07706 3.9269 4.41776 4.5862 4.41776 5.39949C4.41776 6.21278 5.07706 6.87208 5.89035 6.87208Z"
                                                                    fill="#8A94A6" />
                                                            </g>
                                                            <defs>
                                                                <clipPath id="clip0_19_26">
                                                                    <rect width="11.7807" height="11.7807" fill="white" />
                                                                </clipPath>
                                                            </defs>
                                                        </svg>{{$course->student_count->count()}} &nbsp; <span>{{__('Students')}}</span>
                                                    </p>
                                                    <p><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                            viewBox="0 0 12 12" fill="none">
                                                            <g clip-path="url(#clip0_19_28)">
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                    d="M5.45116 0.161627C5.72754 0.0234357 6.05285 0.0234355 6.32924 0.161627L11.122 2.558C11.8456 2.91979 11.8456 3.95237 11.122 4.31416L10.4238 4.66324L11.122 5.01231C11.8456 5.3741 11.8456 6.40669 11.122 6.76848L10.4238 7.11755L11.122 7.46663C11.8456 7.82842 11.8456 8.861 11.122 9.22279L6.32924 11.6192C6.05286 11.7574 5.72754 11.7574 5.45116 11.6192L0.658407 9.22279C-0.0651723 8.861 -0.0651715 7.82842 0.658406 7.46663L1.35656 7.11755L0.658407 6.76848C-0.0651723 6.40669 -0.0651715 5.3741 0.658406 5.01231L1.35656 4.66324L0.658407 4.31416C-0.0651731 3.95237 -0.0651713 2.91979 0.658407 2.558L5.45116 0.161627ZM2.67882 4.22677C2.67563 4.22513 2.67243 4.22353 2.66921 4.22197L1.09745 3.43608L5.8902 1.03971L10.6829 3.43608L9.1111 4.22201C9.10793 4.22355 9.10479 4.22512 9.10166 4.22673L5.8902 5.83246L2.67882 4.22677ZM2.45416 5.21204L1.09745 5.8904L2.66915 6.67625L2.67888 6.68111L5.8902 8.28677L9.10163 6.68105L9.11112 6.67631L10.6829 5.8904L9.32623 5.21204L6.32924 6.71054C6.05286 6.84873 5.72754 6.84873 5.45116 6.71054L2.45416 5.21204ZM1.09745 8.34471L2.45416 7.66635L5.45116 9.16485C5.72754 9.30304 6.05286 9.30304 6.32924 9.16485L9.32623 7.66635L10.6829 8.34471L5.8902 10.7411L1.09745 8.34471Z"
                                                                    fill="#8A94A6" />
                                                            </g>
                                                            <defs>
                                                                <clipPath id="clip0_19_28">
                                                                    <rect width="11.7807" height="11.7807" fill="white" />
                                                                </clipPath>
                                                            </defs>
                                                        </svg> {{$course->chapter_count->count()}} &nbsp; <span>{{__('Chapters')}}</span>
                                                    </p>
                                                    <p> {{$course->level}}</p>
                                                </div>
                                            </div>
                                            <!-- Avatars -->
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
                                            <!-- CART -->
                                            <div class="price-addtocart d-flex align-items-center justify-content-between">                                            
                                                @if(Auth::guard('students')->check())
                                                    @if(in_array($course->id,Auth::guard('students')->user()->purchasedCourse()))
                                                        <div class="price">
                                                        </div>
                                                        <div class="btn add-cart">
                                                            <a href="{{route('store.fullscreen',[$store->slug,Crypt::encrypt($course->id),''])}}">
                                                                {{__('Start Watching')}}
                                                            </a>
                                                        </div>
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
                                                                <a id="cart-btn-{{$course->id}}" class="btn">{{__('Already in Cart')}}</a>
                                                            @else
                                                                <a id="cart-btn-{{$course->id}}" class="btn">{{__('Add in Cart')}}</a>
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
                                                            <a id="cart-btn-{{$course->id}}" class="btn">{{__('Already in Cart')}}</a>
                                                        @else
                                                            <a id="cart-btn-{{$course->id}}" class="btn">{{__('Add in Cart')}}</a>
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
            </section>
        @endif  
    @endif

    <!-- EMAIL SUBSCRIBER -->
    @php
        $main_homepage_email_subscriber_key = array_search('Home-Email-subscriber',array_column($getStoreThemeSetting, 'section_name'));        
        $email_subscriber_enable = 'off';
        $homepage_email_subscriber_title = 'Always on time';
        $homepage_email_subscriber_subtext = 'Subscription here';
        $homepage_email_subscriber_box = 'on';
        $homepage_email_subscriber_bottom_text = 'We will never spam to you. Only useful info.';
        $homepage_email_subscriber_button = 'SUBSCRIBE';
        $homepage_email_subscriber_Bckground_Image = '';

        if(!empty($getStoreThemeSetting[$main_homepage_email_subscriber_key])) {
            $homepage_subscriber_header = $getStoreThemeSetting[$main_homepage_email_subscriber_key];
            $email_subscriber_enable = $homepage_subscriber_header['section_enable'];

            $homepage_email_subscriber_title_key = array_search('Title',array_column($homepage_subscriber_header['inner-list'], 'field_name'));            
            $homepage_email_subscriber_title = $homepage_subscriber_header['inner-list'][$homepage_email_subscriber_title_key]['field_default_text'];

            $homepage_email_subscriber_subtext_key = array_search('Sub Text',array_column($homepage_subscriber_header['inner-list'], 'field_name'));
            $homepage_email_subscriber_subtext = $homepage_subscriber_header['inner-list'][$homepage_email_subscriber_subtext_key]['field_default_text'];

            $homepage_email_subscriber_box_key = array_search('Display Email Subscriber Box',array_column($homepage_subscriber_header['inner-list'], 'field_name'));
            $homepage_email_subscriber_box = $homepage_subscriber_header['inner-list'][$homepage_email_subscriber_box_key]['field_default_text'];       
            
            $homepage_email_subscriber_bottom_text_key = array_search('Bottom Text',array_column($homepage_subscriber_header['inner-list'], 'field_name'));
            $homepage_email_subscriber_bottom_text = $homepage_subscriber_header['inner-list'][$homepage_email_subscriber_bottom_text_key]['field_default_text'];

            $homepage_email_subscriber_button_key = array_search('Button',array_column($homepage_subscriber_header['inner-list'], 'field_name'));
            $homepage_email_subscriber_button = $homepage_subscriber_header['inner-list'][$homepage_email_subscriber_button_key]['field_default_text'];

            $homepage_email_subscriber_Bckground_Image_key = array_search('Thumbnail',array_column($homepage_subscriber_header['inner-list'], 'field_name'));
            $homepage_email_subscriber_Bckground_Image = $homepage_subscriber_header['inner-list'][$homepage_email_subscriber_Bckground_Image_key]['field_default_text'];

        }
    @endphp
    @if($email_subscriber_enable == 'on')
        <section class="newsletter-section padding-bottom" id="newsletter">
            <div class="container">
                <div class="newsletter-content-wrap">
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="newsletter-left">
                                @if($homepage_email_subscriber_box == 'on')
                                    <div class="section-title">
                                        <h2><b>{{ $homepage_email_subscriber_title }}</b></h2>    
                                    </div>
                                    <p>{{ $homepage_email_subscriber_subtext }}</p>
                                @endif
                                <div class="newsletter-form">
                                    {{ Form::open(array('route' => array('subscriptions.store_email', $store->id),'method' => 'POST')) }}
                                        <div class="input-wrapper">
                                            {{ Form::email('email',null,array('aria-label'=>'Enter your email address','placeholder'=>__('Enter Your Email Address'))) }}
                                                <button type="submit" class="btn btn-secondary ">{{ $homepage_email_subscriber_button }}</button>
                                        </div>
                                        <p>{{ $homepage_email_subscriber_bottom_text }}</p>
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 newsletter-right-side">
                            <div class="newsletter-image">
                                @if($homepage_email_subscriber_box == 'on')
                                    @if($homepage_email_subscriber_Bckground_Image)
                                        <img src="{{asset(Storage::url('uploads/'.$homepage_email_subscriber_Bckground_Image))}}"> 
                                    @else
                                        @if(!empty($store->sub_img))
                                            <img src="{{asset(Storage::url('uploads/store_logo/'.$store->sub_img))}}" alt="newsletter">
                                        @else
                                            <img src="{{asset('assets/'.$store->theme_dir.'/img/newsletter.png')}}" alt="newsletter">
                                        @endif
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
   
@endsection
