
@extends('layouts.theme2.shopfront')
@php
    $profile = asset(Storage::url('uploads/default_avatar/avatar.png'));
@endphp
@section('meta-data')
    <meta name="keywords" content="{{$course->meta_keywords}}">
    <meta name="description" content="{{$course->meta_description}}">
@endsection
@section('page-title')
    {{__('Course Details')}} - {{($store->tagline) ?  $store->tagline : config('APP_NAME', ucfirst($store->name))}}
@endsection
@push('css-page')
<link rel="stylesheet" href="{{asset('themes/theme2/css/moovie.css')}}">
    {{--  <script src="{{asset('js/bootstrap-bundle-min.5.0.1.js')}}"></script>  --}}    
    <style>
        .divider-vertical {
            left: 50%;
            top: 10%;
            bottom: 10%;
            border-left: 1px solid white;
        }
    </style>
@endpush
@push('script-page')
    <script src="{{asset('themes/theme2/js/moovie.js')}}"></script>
    <script>
        // document.addEventListener("DOMContentLoaded", function () {
        //     var demo1 = new Moovie({
        //         selector: "#example",
        //         dimensions: {
        //             width: "100%"
        //         },
        //         config: {
        //             storage: {
        //                 captionOffset: false,
        //                 playrateSpeed: false,
        //                 captionSize: false
        //             }
        //         }
        //     });
        // });

        document.addEventListener("DOMContentLoaded", function () {
         var demo1 = new Moovie({
            selector: "#example",
            dimensions: {
               width: "100%"
            },
            config: {
               storage: {
                  captionOffset: false,
                  playrateSpeed: false,
                  captionSize: false
               }
            },
            icons: {
                path: '{{ asset("/public/assets/imgs") }}/'
            }
         });
         
      });

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
  

    <div class="wrapper">
        <section class="product-main-section padding-top padding-bottom">
            <div class="container">
                <div class="row pdp-summery-row">
                    <div class="col-xl-7 col-lg-7 col-12 pdp-left-side ">
                        <div class="pdp-summery">
                            <div class="pdp-summery-top">
                                <div class="pdp-subtitles d-flex align-items-center">
                                    <span class="subtitle"> {{ !empty($course->category_id)?$course->category_id->name:''}}</span>
                                    <div class="university-logo">
                                        <img src="{{ asset('assets/themes/theme2/images/oxford.png') }}" alt="">
                                    </div>
                                    <div class="review-rating">
                                        <span class="rating-num">{{$avg_rating}}</span>
                                        <div class="review-star d-flex align-items-center">
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
                                        </div>
                                        <span>({{$user_count}})</span>
                                    </div>
                                </div>
                                <div class="section-title">
                                    <h2> {{$course->title}}</h2>
                                </div>
                                <p> 
                                {{ __('Lorem Ipsum is simply dummy text of the printing and typesetting industry.Lorem Ipsum is simply dummy text of the printing and typesetting industry.Lorem Ipsum is simply dummy text of the printing and typesetting industry.Lorem Ipsum is simply dummy text of the printing and typesetting industry.') }}
                                </p>
                                
                                @php
                                    $cart = session()->get($store->slug);
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

                                @if(Auth::guard('students')->check())
                                    @if(in_array($course->id,Auth::guard('students')->user()->purchasedCourse()))
                                        <a class="btn cart-btn cart-btn"  href="{{route('store.fullscreen',[$store->slug,Crypt::encrypt($course->id),''])}}">
                                            {{__('Get Started')}}
                                            <svg viewBox="0 0 10 5">
                                                <path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z">
                                                </path>
                                            </svg>
                                        </a>
                                    @else
                                        <div class="add_to_cart" data-id="{{$course->id}}">                                                        
                                            @if($key !== false)
                                                <a id="cart-btn-{{$course->id}}" class="btn cart-btn">{{__('Already in Cart')}}</a>
                                            @else
                                                <a id="cart-btn-{{$course->id}}" class="btn cart-btn">{{__('Add in Cart')}}</a>
                                            @endif
                                        </div>
                                    @endif
                                @else
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
                        <div class="pdp-tab tabs-wrapper">
                            <ul class="tabs d-flex">
                                <li class="tab-link active" data-tab="tab-1">
                                    <a href="javascript:;">{{ __('Description') }} </a>
                                </li>
                                <li class="tab-link" data-tab="tab-2">
                                    <a href="javascript:;">{{ __('Syllabus') }} </a>
                                </li>
                                {{-- <li class="tab-link" data-tab="tab-3">
                                    <a href="javascript:;">{{ __('Requirements') }}</a>
                                </li> --}}
                                <li class="tab-link" data-tab="tab-4">
                                    <a href="javascript:;">{{ __('Educators') }} </a>
                                </li>
                                <li class="tab-link" data-tab="tab-5">
                                    <a href="javascript:;">{{ __('Learner reviews') }}</a>
                                </li>
                                <li class="tab-link" data-tab="tab-6">
                                    <a href="javascript:;">{{ __('FAQ') }}</a>
                                </li>
                            </ul>
                            <div class="tabs-container">
                                <div class="tab-content active" id="tab-1">
                                    <div class="section-title">
                                        <a href="{{route('store.viewcourse',[$store->slug,\Illuminate\Support\Facades\Crypt::encrypt($course->id)])}}">
                                            <h3>{{$course->title}}</h3>
                                        </a>
                                    </div>
                                    <p>{!! $course->course_description !!}</p>
                                </div>
                                <div class="tab-content" id="tab-2">
                                    @if(count($header) > 0  && !empty($header))
                                        @foreach($header as $i => $header)
                                            <div class="set has-children">
                                                <a href="javascript:;" class="acnav-label">
                                                    <span>{{$header->title}}</span>
                                                </a>
                                                <div class="acnav-list" id="ChapterOne-{{$i}}">
                                                    <ul class="chapter-links">
                                                        @if($header->chapters_data->count() > 0  && !empty($header->chapters_data))
                                                            @foreach($header->chapters_data as $chapters)
                                                                @if($chapters->type == 'Video File')
                                                                    <li>
                                                                        <a href="{{route('store.fullscreen',[$store->slug,\Illuminate\Support\Facades\Crypt::encrypt($course->id),\Illuminate\Support\Facades\Crypt::encrypt($chapters->id)])}}">
                                                                            <span class="link-icon">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="22"
                                                                                    height="22" viewBox="0 0 22 22" fill="none">
                                                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                                                        d="M1.8335 11C1.8335 16.0627 5.93755 20.1667 11.0002 20.1667C16.0628 20.1667 20.1668 16.0627 20.1668 11C20.1668 5.93743 16.0628 1.83337 11.0002 1.83337C5.93755 1.83337 1.8335 5.93743 1.8335 11ZM3.66683 11C3.66683 6.94995 6.95007 3.66671 11.0002 3.66671C15.0503 3.66671 18.3335 6.94995 18.3335 11C18.3335 15.0501 15.0503 18.3334 11.0002 18.3334C6.95007 18.3334 3.66683 15.0501 3.66683 11ZM11.1004 7.64128C10.5379 7.26624 9.81455 7.23127 9.21842 7.5503C8.6223 7.86934 8.25016 8.49058 8.25016 9.16671V12.8334C8.25016 13.5095 8.6223 14.1307 9.21842 14.4498C9.81455 14.7688 10.5379 14.7338 11.1004 14.3588L13.8504 12.5255C14.3605 12.1854 14.6668 11.613 14.6668 11C14.6668 10.3871 14.3605 9.81464 13.8504 9.47462L11.1004 7.64128ZM11.181 12.1017L10.0835 12.8334V11.3701V10.63V9.16671L11.181 9.89834L11.9168 10.3889L12.8335 11L11.9168 11.6112L11.181 12.1017Z"
                                                                                        fill="url(#paint0_linear_514_1165)"></path>
                                                                                    <defs>
                                                                                        <linearGradient id="paint0_linear_514_1165"
                                                                                            x1="2.66181" y1="4.42396" x2="20.5372"
                                                                                            y2="5.42417" gradientUnits="userSpaceOnUse">
                                                                                            <stop stop-color="#6F5FA7"></stop>
                                                                                            <stop offset="1" stop-color="#6F5FA7"></stop>
                                                                                        </linearGradient>
                                                                                    </defs>
                                                                                </svg>
                                                                            </span>
                                                                            <span>{{$chapters->name}}</span> [{{!empty($chapters->duration)?$chapters->duration:''}}]
                                                                        </a>
                                                                    </li>
                                                                @elseif($chapters->type == 'iFrame')
                                                                    <li>
                                                                        <a href="{{route('store.fullscreen',[$store->slug,\Illuminate\Support\Facades\Crypt::encrypt($course->id),\Illuminate\Support\Facades\Crypt::encrypt($chapters->id)])}}">
                                                                            <span class="link-icon">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none">
                                                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M1.8335 11C1.8335 16.0627 5.93755 20.1667 11.0002 20.1667C16.0628 20.1667 20.1668 16.0627 20.1668 11C20.1668 5.93743 16.0628 1.83337 11.0002 1.83337C5.93755 1.83337 1.8335 5.93743 1.8335 11ZM3.66683 11C3.66683 6.94995 6.95007 3.66671 11.0002 3.66671C15.0503 3.66671 18.3335 6.94995 18.3335 11C18.3335 15.0501 15.0503 18.3334 11.0002 18.3334C6.95007 18.3334 3.66683 15.0501 3.66683 11ZM11.1004 7.64128C10.5379 7.26624 9.81455 7.23127 9.21842 7.5503C8.6223 7.86934 8.25016 8.49058 8.25016 9.16671V12.8334C8.25016 13.5095 8.6223 14.1307 9.21842 14.4498C9.81455 14.7688 10.5379 14.7338 11.1004 14.3588L13.8504 12.5255C14.3605 12.1854 14.6668 11.613 14.6668 11C14.6668 10.3871 14.3605 9.81464 13.8504 9.47462L11.1004 7.64128ZM11.181 12.1017L10.0835 12.8334V11.3701V10.63V9.16671L11.181 9.89834L11.9168 10.3889L12.8335 11L11.9168 11.6112L11.181 12.1017Z" fill="url(#paint0_linear_514_1165)"/>
                                                                                    <defs>
                                                                                    <linearGradient id="paint0_linear_514_1165" x1="2.66181" y1="4.42396" x2="20.5372" y2="5.42417" gradientUnits="userSpaceOnUse">
                                                                                    <stop stop-color="#6F5FA7"/>
                                                                                    <stop offset="1" stop-color="#6F5FA7"/>
                                                                                    </linearGradient>
                                                                                    </defs>
                                                                                    </svg>
                                                                            </span>
                                                                            <span>{{$chapters->name}}</span> [{{!empty($chapters->duration)?$chapters->duration:''}}]
                                                                        </a>
                                                                    </li>
                                                                @elseif($chapters->type == 'Text Content')
                                                                    <li>
                                                                        <a href="{{route('store.fullscreen',[$store->slug,\Illuminate\Support\Facades\Crypt::encrypt($course->id),\Illuminate\Support\Facades\Crypt::encrypt($chapters->id)])}}">
                                                                            <span class="link-icon">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none">
                                                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M1.8335 11C1.8335 16.0627 5.93755 20.1667 11.0002 20.1667C16.0628 20.1667 20.1668 16.0627 20.1668 11C20.1668 5.93743 16.0628 1.83337 11.0002 1.83337C5.93755 1.83337 1.8335 5.93743 1.8335 11ZM3.66683 11C3.66683 6.94995 6.95007 3.66671 11.0002 3.66671C15.0503 3.66671 18.3335 6.94995 18.3335 11C18.3335 15.0501 15.0503 18.3334 11.0002 18.3334C6.95007 18.3334 3.66683 15.0501 3.66683 11ZM11.1004 7.64128C10.5379 7.26624 9.81455 7.23127 9.21842 7.5503C8.6223 7.86934 8.25016 8.49058 8.25016 9.16671V12.8334C8.25016 13.5095 8.6223 14.1307 9.21842 14.4498C9.81455 14.7688 10.5379 14.7338 11.1004 14.3588L13.8504 12.5255C14.3605 12.1854 14.6668 11.613 14.6668 11C14.6668 10.3871 14.3605 9.81464 13.8504 9.47462L11.1004 7.64128ZM11.181 12.1017L10.0835 12.8334V11.3701V10.63V9.16671L11.181 9.89834L11.9168 10.3889L12.8335 11L11.9168 11.6112L11.181 12.1017Z" fill="url(#paint0_linear_514_1165)"/>
                                                                                    <defs>
                                                                                    <linearGradient id="paint0_linear_514_1165" x1="2.66181" y1="4.42396" x2="20.5372" y2="5.42417" gradientUnits="userSpaceOnUse">
                                                                                    <stop stop-color="#6F5FA7"/>
                                                                                    <stop offset="1" stop-color="#6F5FA7"/>
                                                                                    </linearGradient>
                                                                                    </defs>
                                                                                    </svg>
                                                                            </span>
                                                                            <span>{{$chapters->name}}</span> [{{!empty($chapters->duration)?$chapters->duration:''}}]
                                                                        </a>
                                                                    </li>
                                                                @elseif($chapters->type == 'Video Url')  
                                                                    <li>
                                                                        <a href="{{route('store.fullscreen',[$store->slug,\Illuminate\Support\Facades\Crypt::encrypt($course->id),\Illuminate\Support\Facades\Crypt::encrypt($chapters->id)])}}">
                                                                            <span class="link-icon">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none">
                                                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M1.8335 11C1.8335 16.0627 5.93755 20.1667 11.0002 20.1667C16.0628 20.1667 20.1668 16.0627 20.1668 11C20.1668 5.93743 16.0628 1.83337 11.0002 1.83337C5.93755 1.83337 1.8335 5.93743 1.8335 11ZM3.66683 11C3.66683 6.94995 6.95007 3.66671 11.0002 3.66671C15.0503 3.66671 18.3335 6.94995 18.3335 11C18.3335 15.0501 15.0503 18.3334 11.0002 18.3334C6.95007 18.3334 3.66683 15.0501 3.66683 11ZM11.1004 7.64128C10.5379 7.26624 9.81455 7.23127 9.21842 7.5503C8.6223 7.86934 8.25016 8.49058 8.25016 9.16671V12.8334C8.25016 13.5095 8.6223 14.1307 9.21842 14.4498C9.81455 14.7688 10.5379 14.7338 11.1004 14.3588L13.8504 12.5255C14.3605 12.1854 14.6668 11.613 14.6668 11C14.6668 10.3871 14.3605 9.81464 13.8504 9.47462L11.1004 7.64128ZM11.181 12.1017L10.0835 12.8334V11.3701V10.63V9.16671L11.181 9.89834L11.9168 10.3889L12.8335 11L11.9168 11.6112L11.181 12.1017Z" fill="url(#paint0_linear_514_1165)"/>
                                                                                    <defs>
                                                                                    <linearGradient id="paint0_linear_514_1165" x1="2.66181" y1="4.42396" x2="20.5372" y2="5.42417" gradientUnits="userSpaceOnUse">
                                                                                    <stop stop-color="#6F5FA7"/>
                                                                                    <stop offset="1" stop-color="#6F5FA7"/>
                                                                                    </linearGradient>
                                                                                    </defs>
                                                                                    </svg>
                                                                            </span>
                                                                            <span>{{$chapters->name}}</span> [{{!empty($chapters->duration)?$chapters->duration:''}}]
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            <p>
                                                                {{__('No Chapters Available!')}}
                                                            </p>
                                                        @endif                                                       
                                                    </ul>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="tab-content" id="tab-4">
                                    <div class="user-review-content">
                                        <div class="abt-user">
                                            <div class="abt-usr-image">
                                                <img src="{{ asset('assets/themes/theme2/images/amelie.png') }}" alt="user">
                                            </div>
                                            <div class="user-info">
                                                <h4>
                                                    <a href="{{route('store.tutor',[$store->slug,\Illuminate\Support\Facades\Crypt::encrypt($tutor_id)])}}">
                                                        {{$course->tutor_id->name}}
                                                    </a>
                                                </h4>
                                                <span>{{$course->tutor_id->about}}</span>
                                                @if($store->enable_rating == 'on')
                                                    <div class="review-rating">
                                                        <span><b>{{$avg_tutor_rating}}</b>/5</span>
                                                        <div class="review-star d-flex align-items-center">
                                                            <span class="star-img">
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                            </span>
                                                        </div>
                                                        <p>({{$tutor_count_rating}})</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="product-review">
                                            <ul>
                                                <li>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none">
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M9.57406 2.76901C10.3447 0.410328 13.6553 0.410334 14.4259 2.76901L15.8051 6.99008C15.8535 7.13813 15.9892 7.23936 16.1438 7.24271L20.4962 7.33707C22.8824 7.3888 23.9006 10.4175 22.0372 11.9204L18.4398 14.8219C18.3249 14.9146 18.2765 15.0678 18.317 15.2104L19.5954 19.7059C20.2665 22.0656 17.5912 23.9429 15.6282 22.4897L12.216 19.9637C12.0875 19.8686 11.9125 19.8686 11.784 19.9637L8.37177 22.4897C6.40879 23.9429 3.7335 22.0656 4.40456 19.7059L5.68299 15.2104C5.72354 15.0678 5.67513 14.9146 5.56022 14.8219L1.96284 11.9205C0.0993767 10.4175 1.1176 7.3888 3.50376 7.33707L7.85616 7.24271C8.01078 7.23936 8.14651 7.13813 8.19488 6.99008L9.57406 2.76901ZM12.3466 3.45914C12.2365 3.12218 11.7635 3.12218 11.6534 3.45914L10.2743 7.68021C9.93564 8.71654 8.98557 9.42515 7.90325 9.44862L3.55084 9.54298C3.20996 9.55037 3.0645 9.98303 3.33071 10.1977L6.92809 13.0992C7.73242 13.748 8.07131 14.8202 7.78748 15.8183L6.50904 20.3138C6.41318 20.6509 6.79536 20.9191 7.07579 20.7115L10.488 18.1854C11.3877 17.5194 12.6123 17.5194 13.512 18.1854L16.9242 20.7115C17.2046 20.9191 17.5868 20.6509 17.491 20.3138L16.2125 15.8183C15.9287 14.8202 16.2676 13.748 17.0719 13.0992L20.6693 10.1977C20.9355 9.98303 20.79 9.55037 20.4492 9.54298L16.0968 9.44862C15.0144 9.42515 14.0644 8.71654 13.7257 7.68021L12.3466 3.45914Z"
                                                            fill="#8A94A6"></path>
                                                    </svg>
                                                    <span>{{ __('Reviews') }} {{$user_count}}</span>

                                                </li>
                                                <li>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                        viewBox="0 0 18 18" fill="none">
                                                        <g clip-path="url(#clip0_517_1166)">
                                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                                d="M7.94954 0.235681C8.3526 0.0341516 8.82702 0.0341514 9.23008 0.23568L16.2195 3.73039C17.2747 4.258 17.2747 5.76385 16.2195 6.29146L15.2014 6.80053L16.2195 7.3096C17.2747 7.83721 17.2747 9.34306 16.2195 9.87067L15.2014 10.3797L16.2195 10.8888C17.2747 11.4164 17.2747 12.9223 16.2195 13.4499L9.23008 16.9446C8.82702 17.1461 8.3526 17.1461 7.94954 16.9446L0.960116 13.4499C-0.095104 12.9223 -0.0951028 11.4164 0.960115 10.8888L1.97825 10.3797L0.960116 9.87067C-0.095104 9.34306 -0.0951028 7.83721 0.960115 7.3096L1.97825 6.80053L0.960117 6.29146C-0.0951052 5.76385 -0.0951025 4.258 0.960116 3.73039L7.94954 0.235681ZM3.90655 6.16401C3.9019 6.16163 3.89724 6.15929 3.89254 6.15701L1.60038 5.01093L8.58981 1.51622L15.5792 5.01093L13.287 6.15707C13.2823 6.15931 13.2778 6.16161 13.2732 6.16395L8.58981 8.50564L3.90655 6.16401ZM3.57892 7.60087L1.60038 8.59014L3.89246 9.73617L3.90664 9.74326L8.58981 12.0848L13.2732 9.74318L13.287 9.73626L15.5792 8.59014L13.6007 7.60087L9.23008 9.78618C8.82702 9.98771 8.3526 9.98771 7.94954 9.78618L3.57892 7.60087ZM1.60038 12.1693L3.57892 11.1801L7.94954 13.3654C8.3526 13.5669 8.82702 13.5669 9.23008 13.3654L13.6007 11.1801L15.5792 12.1693L8.58981 15.6641L1.60038 12.1693Z"
                                                                fill="#8A94A6"></path>
                                                        </g>
                                                        <defs>
                                                            <clipPath id="clip0_517_1166">
                                                                <rect width="17.1802" height="17.1802" fill="white">
                                                                </rect>
                                                            </clipPath>
                                                        </defs>
                                                    </svg>
                                                    <span> {{__('Course')}} {{$tutor_course_count->count()}}</span>
                                                </li>
                                                <li>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        viewBox="0 0 12 12" fill="none">
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M5.89035 0.981725C3.17939 0.981725 0.981725 3.17939 0.981725 5.89035C0.981725 7.16364 1.46654 8.3237 2.26163 9.196C2.67034 8.3076 3.38394 7.61471 4.24934 7.22454C3.75002 6.77528 3.43604 6.12405 3.43604 5.39949C3.43604 4.04401 4.53487 2.94518 5.89035 2.94518C7.24583 2.94518 8.34466 4.04401 8.34466 5.39949C8.34466 6.11254 8.04058 6.75457 7.55502 7.20298C8.43552 7.56901 9.18412 8.23342 9.54281 9.16977C10.3238 8.30051 10.799 7.15092 10.799 5.89035C10.799 3.17939 8.60131 0.981725 5.89035 0.981725ZM9.69387 10.3882C10.9703 9.30774 11.7807 7.69368 11.7807 5.89035C11.7807 2.6372 9.1435 0 5.89035 0C2.6372 0 0 2.6372 0 5.89035C0 7.69366 0.810351 9.30769 2.08677 10.3882C2.12022 10.426 2.15982 10.459 2.20478 10.4855C3.21384 11.2959 4.49547 11.7807 5.89035 11.7807C7.28519 11.7807 8.5668 11.2959 9.57584 10.4856C9.62075 10.4591 9.66038 10.4261 9.69387 10.3882ZM8.74856 9.88145L8.66025 9.61652C8.29993 8.53556 7.14229 7.8538 5.89035 7.8538C4.6233 7.8538 3.49161 8.64647 3.0586 9.83723L3.04038 9.88735C3.84386 10.4613 4.82767 10.799 5.89035 10.799C6.95667 10.799 7.94357 10.459 8.74856 9.88145ZM5.89035 6.87208C6.70364 6.87208 7.36294 6.21278 7.36294 5.39949C7.36294 4.5862 6.70364 3.9269 5.89035 3.9269C5.07706 3.9269 4.41776 4.5862 4.41776 5.39949C4.41776 6.21278 5.07706 6.87208 5.89035 6.87208Z"
                                                            fill="#8A94A6"></path>
                                                    </svg>
                                                    <span>{{__('Students')}} {{$course->student_count->count()}}</span>
                                                </li>
                                            </ul>
                                            <p class="paragraph">
                                                {{$course->tutor_id->description}}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-content" id="tab-5">
                                    <div class="user-review-content">
                                        <div class="review-rating add-rating">
                                            <span><b>{{$avg_rating}}</b>/5 </span> &nbsp; <span>({{$user_count}}&nbsp; {{ __('Rating') }})</span>
                                            <div class="review-star d-flex align-items-center">
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
                                            </div>
                                            @if(Auth::guard('students')->check())
                                                <a href="javascript:;" data-toggle="modal" data-target="#commonModalBlur" class="btn-ic modal-target" data-modal="CourseReview" data-url="{{route('rating',[$store->slug,$course->id])}}" data-size="lg" data-ajax-popup-blur="true" data-title="{{__('Create New Rating')}}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"
                                                        viewBox="0 0 10 10" fill="none">
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M5.00021 0C4.44792 0 4.00021 0.447715 4.00021 1V4H1C0.447715 4 0 4.44772 0 5C0 5.55228 0.447716 6 1 6H4.00021L4.00021 9.00042C4.00021 9.55271 4.44792 10.0004 5.00021 10.0004C5.55249 10.0004 6.00021 9.5527 6.00021 9.00042L6.00021 6L9.00042 6C9.55271 6 10.0004 5.55228 10.0004 5C10.0004 4.44771 9.5527 4 9.00042 4L6.00021 4V1C6.00021 0.447715 5.55249 0 5.00021 0Z"
                                                            fill="white"></path>
                                                    </svg>
                                                </a>
                                            @endif
                                        </div>
                                        <div class="product-review">
                                            <ul>
                                                <li>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none">
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M9.57406 2.76901C10.3447 0.410328 13.6553 0.410334 14.4259 2.76901L15.8051 6.99008C15.8535 7.13813 15.9892 7.23936 16.1438 7.24271L20.4962 7.33707C22.8824 7.3888 23.9006 10.4175 22.0372 11.9204L18.4398 14.8219C18.3249 14.9146 18.2765 15.0678 18.317 15.2104L19.5954 19.7059C20.2665 22.0656 17.5912 23.9429 15.6282 22.4897L12.216 19.9637C12.0875 19.8686 11.9125 19.8686 11.784 19.9637L8.37177 22.4897C6.40879 23.9429 3.7335 22.0656 4.40456 19.7059L5.68299 15.2104C5.72354 15.0678 5.67513 14.9146 5.56022 14.8219L1.96284 11.9205C0.0993767 10.4175 1.1176 7.3888 3.50376 7.33707L7.85616 7.24271C8.01078 7.23936 8.14651 7.13813 8.19488 6.99008L9.57406 2.76901ZM12.3466 3.45914C12.2365 3.12218 11.7635 3.12218 11.6534 3.45914L10.2743 7.68021C9.93564 8.71654 8.98557 9.42515 7.90325 9.44862L3.55084 9.54298C3.20996 9.55037 3.0645 9.98303 3.33071 10.1977L6.92809 13.0992C7.73242 13.748 8.07131 14.8202 7.78748 15.8183L6.50904 20.3138C6.41318 20.6509 6.79536 20.9191 7.07579 20.7115L10.488 18.1854C11.3877 17.5194 12.6123 17.5194 13.512 18.1854L16.9242 20.7115C17.2046 20.9191 17.5868 20.6509 17.491 20.3138L16.2125 15.8183C15.9287 14.8202 16.2676 13.748 17.0719 13.0992L20.6693 10.1977C20.9355 9.98303 20.79 9.55037 20.4492 9.54298L16.0968 9.44862C15.0144 9.42515 14.0644 8.71654 13.7257 7.68021L12.3466 3.45914Z"
                                                            fill="#8A94A6"></path>
                                                    </svg>
                                                    <span>{{__('Reviews')}} {{$user_count}}</span>

                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="course-reviews-wrap">
                                        @if(!empty($course_ratings))
                                            @foreach($course_ratings as $c_rating_key => $course_rating)
                                                <div class="course-reviews">
                                                    <div class="courser-top-content d-flex justify-content-between align-items-center">
                                                        <div class="course-review-name">
                                                            <h3> {{$course_rating->title}} </h3>
                                                        </div>
                                                        <div class="course-review-star">
                                                            <div class="review-star d-flex align-items-center">
                                                                <span class="star-img">
                                                                    {{-- @dd($course_rating->ratting , $i ) --}}
                                                                    @for($i =0;$i<5;$i++)
                                                                        <i class="fas fa-star {{($course_rating->ratting > $i  ? 'text-warning' : '')}}"></i>
                                                                    @endfor
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <p>{{$course_rating->description}}</p>
                                                    <div class="course-review-profile">
                                                        <div class="course-review-profile-img">
                                                            <img src="{{ asset('assets/themes/theme2/images/amelie.png') }}" alt="">
                                                        </div>
                                                        <div class="user-info">
                                                            <h4>
                                                                {{$course_rating->name}}
                                                            </h4>
                                                            <span>{{$course_rating->created_at->diffForHumans()}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                <div class="tab-content" id="tab-6">
                                    @if(count($faqs) > 0  && !empty($faqs))
                                        @foreach($faqs as $q_i => $faq)
                                            <div class="set has-children">
                                                <a href="javascript:;" class="acnav-label" data-bs-target="#ChapterFour-{{$q_i}}">
                                                    {{__('Question')}}:
                                                    <b>{{$faq->question}}??</b>
                                                </a>
                                                <div id="ChapterFour-{{$q_i}}" class="acnav-list">
                                                    <p>{{$faq->answer}}.</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="text-center no-data">
                                            <p>{{__('There are no FAQs!')}}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-5 col-lg-5 col-12 pdp-right-side">
                        <div class="pdp-main-itm">
                            <div class="pdp-main-media">
                                <img src="{{ asset('assets/themes/theme2/images/pdp-main-media.jpg') }}" alt="">
                            </div>
                        </div>
                        <div class="pdp-summery-right">
                            <div class="summery-blocks">
                                <ul>
                                    <li>
                                        <span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12.6812 2.11353C6.84482 2.11353 2.11353 6.84482 2.11353 12.6812C2.11353 15.4224 3.15726 17.9198 4.869 19.7978C5.7489 17.8852 7.28518 16.3935 9.14827 15.5535C8.0733 14.5863 7.39734 13.1843 7.39734 11.6244C7.39734 8.70623 9.76299 6.34058 12.6812 6.34058C15.5993 6.34058 17.965 8.70623 17.965 11.6244C17.965 13.1595 17.3103 14.5417 16.265 15.5071C18.1606 16.2951 19.7722 17.7255 20.5444 19.7413C22.2258 17.8699 23.2488 15.395 23.2488 12.6812C23.2488 6.84482 18.5175 2.11353 12.6812 2.11353ZM20.8696 22.3645C23.6177 20.0384 25.3623 16.5635 25.3623 12.6812C25.3623 5.67755 19.6848 0 12.6812 0C5.67755 0 0 5.67755 0 12.6812C0 16.5634 1.74458 20.0383 4.49254 22.3644C4.56455 22.4459 4.6498 22.5169 4.74661 22.5739C6.91897 24.3185 9.67817 25.3623 12.6812 25.3623C15.6841 25.3623 18.4432 24.3186 20.6155 22.574C20.7122 22.5171 20.7975 22.4461 20.8696 22.3645ZM18.8345 21.2735L18.6444 20.7031C17.8687 18.3759 15.3764 16.9082 12.6812 16.9082C9.95336 16.9082 7.51698 18.6147 6.58477 21.1783L6.54554 21.2862C8.27533 22.5218 10.3933 23.2488 12.6812 23.2488C14.9768 23.2488 17.1015 22.5168 18.8345 21.2735ZM12.6812 14.7947C14.4321 14.7947 15.8514 13.3753 15.8514 11.6244C15.8514 9.87349 14.4321 8.45411 12.6812 8.45411C10.9303 8.45411 9.51087 9.87349 9.51087 11.6244C9.51087 13.3753 10.9303 14.7947 12.6812 14.7947Z" fill="#939393"/>
                                            </svg></span>
                                        <p>{{$course->student_count->count()}} <span>{{ __('Students') }}</span></p>
                                    </li>
                                    <li>
                                        <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M11.7355 0.348131C12.3305 0.0506232 13.0309 0.0506229 13.6259 0.348131L23.9441 5.50721C25.5019 6.2861 25.5019 8.50912 23.9441 9.288L22.4411 10.0395L23.9441 10.791C25.5018 11.5699 25.5019 13.7929 23.9441 14.5718L22.4411 15.3233L23.9441 16.0748C25.5018 16.8537 25.5019 19.0767 23.9441 19.8556L13.6259 25.0147C13.0309 25.3122 12.3305 25.3122 11.7355 25.0147L1.41735 19.8556C-0.14042 19.0768 -0.140418 16.8537 1.41735 16.0748L2.92038 15.3233L1.41735 14.5718C-0.14042 13.7929 -0.140418 11.5699 1.41735 10.791L2.92038 10.0395L1.41735 9.288C-0.140422 8.50912 -0.140418 6.2861 1.41735 5.50721L11.7355 0.348131ZM5.76703 9.09985C5.76018 9.09633 5.75329 9.09288 5.74636 9.08951L2.36255 7.39761L12.6807 2.23853L22.9989 7.39761L19.6149 9.0896C19.6081 9.09291 19.6013 9.0963 19.5946 9.09976L12.6807 12.5567L5.76703 9.09985ZM5.28337 11.221L2.36255 12.6814L5.74623 14.3733L5.76716 14.3837L12.6807 17.8405L19.5945 14.3836L19.6149 14.3734L22.9989 12.6814L20.0781 11.221L13.6259 14.4471C13.0309 14.7446 12.3305 14.7446 11.7355 14.4471L5.28337 11.221ZM2.36255 17.9652L5.28337 16.5048L11.7355 19.7309C12.3305 20.0284 13.0309 20.0284 13.6259 19.7309L20.0781 16.5048L22.9989 17.9652L12.6807 23.1243L2.36255 17.9652Z" fill="#939393"/>
                                                </svg>
                                        </span>
                                        <p>{{$course->chapter_count->count()}} <span>{{__('Chapters')}}</span></p>
                                    </li>
                                    <li>
                                        <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="26" viewBox="0 0 25 26" fill="none">
                                                <g clip-path="url(#clip0_3_562)">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M2.03804 12.9529C2.03804 7.32497 6.60036 2.76265 12.2283 2.76265C17.8562 2.76265 22.4185 7.32497 22.4185 12.9529C22.4185 18.5808 17.8562 23.1431 12.2283 23.1431C6.60036 23.1431 2.03804 18.5808 2.03804 12.9529ZM12.2283 0.724609C5.47478 0.724609 0 6.19939 0 12.9529C0 19.7064 5.47478 25.1811 12.2283 25.1811C18.9817 25.1811 24.4565 19.7064 24.4565 12.9529C24.4565 6.19939 18.9817 0.724609 12.2283 0.724609ZM13.2473 8.87678C13.2473 8.31399 12.791 7.85776 12.2283 7.85776C11.6655 7.85776 11.2092 8.31399 11.2092 8.87678V13.4059C11.2092 13.7466 11.3796 14.0648 11.6631 14.2538L15.0608 16.5184C15.5291 16.8305 16.1618 16.7039 16.4739 16.2356C16.786 15.7673 16.6594 15.1346 16.1911 14.8225L13.2473 12.8604V8.87678Z" fill="#939393"/>
                                                </g>
                                                <defs>
                                                <clipPath id="clip0_3_562">
                                                <rect width="24.4565" height="24.4565" fill="white" transform="translate(0 0.724609)"/>
                                                </clipPath>
                                                </defs>
                                                </svg>
                                        </span>
                                        <p>{{$course->duration}} <span>{{__('Duration')}}</span></p>
                                    </li>
                                    <li>
                                        <span class="icon">
                                            <svg width="25" height="26" viewBox="0 0 25 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <g clip-path="url(#clip0_3_565)">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M12.2283 25.1811C5.47478 25.1811 0 19.7063 0 12.9529C0 6.19939 5.47478 0.724609 12.2283 0.724609C18.9817 0.724609 24.4565 6.19939 24.4565 12.9529C24.4565 19.7063 18.9817 25.1811 12.2283 25.1811ZM22.3682 13.9719C21.8902 18.7862 18.0616 22.6148 13.2473 23.0928V22.1241C13.2473 21.5613 12.7911 21.105 12.2283 21.105C11.6655 21.105 11.2092 21.5613 11.2092 22.1241V23.0928C6.39496 22.6148 2.56635 18.7862 2.08836 13.9719H3.05706C3.61985 13.9719 4.07609 13.5157 4.07609 12.9529C4.07609 12.3901 3.61985 11.9338 3.05706 11.9338H2.08836C2.56635 7.11957 6.39496 3.29096 11.2092 2.81297V3.78167C11.2092 4.34446 11.6655 4.8007 12.2283 4.8007C12.7911 4.8007 13.2473 4.34446 13.2473 3.78167V2.81297C18.0616 3.29096 21.8902 7.11956 22.3682 11.9338H21.3995C20.8367 11.9338 20.3804 12.3901 20.3804 12.9529C20.3804 13.5157 20.8367 13.9719 21.3995 13.9719H22.3682ZM8.15217 12.9529C8.15217 10.7017 9.9771 8.87678 12.2283 8.87678C14.4794 8.87678 16.3043 10.7017 16.3043 12.9529C16.3043 15.204 14.4794 17.029 12.2283 17.029C9.9771 17.029 8.15217 15.204 8.15217 12.9529ZM12.2283 6.83874C8.85152 6.83874 6.11413 9.57613 6.11413 12.9529C6.11413 16.3296 8.85152 19.067 12.2283 19.067C15.605 19.067 18.3424 16.3296 18.3424 12.9529C18.3424 9.57613 15.605 6.83874 12.2283 6.83874Z" fill="#939393"/>
                                                </g>
                                                <defs>
                                                <clipPath id="clip0_3_565">
                                                <rect width="24.4565" height="24.4565" fill="white" transform="translate(0 0.724609)"/>
                                                </clipPath>
                                                </defs>
                                                </svg>
                                        </span>
                                        <p>{{ __('Unlimited') }} <span>{{ __('subscription') }}</span></p>
                                    </li>
                                    <li>
                                        <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28" fill="none">
                                                <g clip-path="url(#clip0_3_570)">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M9.41713 9.39751C10.168 7.69638 11.7885 5.29838 15.2173 5.29838C18.6766 5.29838 21.1956 8.22145 21.1956 11.0637C21.1956 11.6681 21.6823 12.1582 22.2826 12.1582C22.5696 12.1582 23.2921 12.3317 23.9328 12.8241C24.5258 13.2799 25 13.967 25 15.0212C25 15.8587 24.7259 16.7353 24.2235 17.3799C23.7413 17.9985 23.0491 18.412 22.1014 18.412C21.4908 18.412 21.1477 18.4132 20.979 18.4153C20.9369 18.4158 20.9009 18.4163 20.8724 18.4171C20.8589 18.4175 20.8409 18.4181 20.8221 18.4191L20.8076 18.4199C20.7993 18.4204 20.7891 18.4212 20.7778 18.4222C20.7761 18.4223 20.71 18.4272 20.6311 18.445C20.6074 18.4503 20.5606 18.4617 20.5034 18.4822L20.5019 18.4828C20.4662 18.4955 20.3175 18.5489 20.1665 18.6774C20.0852 18.7542 19.9226 18.9737 19.8539 19.1203C19.7985 19.3198 19.8027 19.729 19.8609 19.9243C20.0157 20.3089 20.328 20.4651 20.3713 20.4868L20.3747 20.4885C20.5168 20.5614 20.6458 20.585 20.6549 20.5866L20.6553 20.5867C20.6917 20.5941 20.7208 20.598 20.734 20.5997C20.7776 20.6052 20.8158 20.6073 20.8234 20.6077L20.8244 20.6077C20.8362 20.6084 20.8487 20.6089 20.8611 20.6094C20.8765 20.61 20.8916 20.6104 20.9047 20.6108C21.0048 20.6136 21.1801 20.6163 21.3228 20.6182L21.5119 20.6205L21.5701 20.6212L21.5861 20.6213L21.5904 20.6214L21.5915 20.6214L21.5918 20.6214L21.5918 20.6214L21.592 20.6014H21.5921L21.5919 20.6214C21.6676 20.6222 21.7415 20.6152 21.813 20.6011L22.1014 20.601C23.7787 20.601 25.0792 19.8275 25.9336 18.7314C26.7677 17.6612 27.1739 16.2953 27.1739 15.0212C27.1739 13.1733 26.2893 11.8815 25.2519 11.0841C24.6313 10.6071 23.936 10.2899 23.3065 10.1197C22.8181 6.45156 19.5414 3.10938 15.2173 3.10938C10.9798 3.10938 8.78622 5.85116 7.74472 7.854C5.42372 7.92439 3.76836 8.84965 2.68596 10.1003C1.53389 11.4314 1.08691 13.0652 1.08691 14.2292C1.08691 14.2481 1.0874 14.267 1.08837 14.2859C1.21016 16.6508 2.06391 18.2683 3.22219 19.2859C4.352 20.2784 5.68257 20.6215 6.65757 20.6215C7.03125 20.6215 7.25712 20.6202 7.38366 20.6181C7.43345 20.6173 7.50378 20.616 7.56037 20.6114C7.56523 20.611 7.62918 20.6065 7.70512 20.5902C7.72793 20.5854 7.77099 20.5755 7.82352 20.5579L7.82373 20.5578C7.85751 20.5465 7.98621 20.5034 8.12429 20.4013C8.23417 20.3201 8.6865 19.9336 8.54203 19.2808C8.43635 18.8033 8.07173 18.5975 8.02321 18.5701L8.01992 18.5682C7.91962 18.5105 7.83311 18.4826 7.80215 18.473C7.7612 18.4602 7.72753 18.4524 7.70792 18.4481C7.66872 18.4396 7.63718 18.4351 7.62296 18.4331C7.59211 18.4289 7.56655 18.4267 7.55551 18.4258C7.48781 18.4203 7.37443 18.4171 7.31763 18.4156L7.22047 18.4131L7.18991 18.4125L7.18128 18.4123L7.1789 18.4122L7.17823 18.4122L7.17803 18.4122L7.17796 18.4122L7.17758 18.4312H7.17754L7.17792 18.4122C7.09885 18.4106 7.02157 18.4175 6.94697 18.4321C6.86369 18.4323 6.76774 18.4324 6.65757 18.4324C6.138 18.4324 5.33541 18.2373 4.65136 17.6364C3.99934 17.0636 3.36192 16.0403 3.26097 14.2027C3.26829 13.5179 3.55755 12.425 4.32483 11.5385C5.062 10.6867 6.29978 9.95478 8.37628 10.0464C8.82271 10.0661 9.23564 9.80869 9.41713 9.39751ZM7.17678 18.4684L7.17682 18.4684L7.15575 19.5065L7.17678 18.4684ZM15.2173 23.9054C15.2173 24.5099 14.7307 24.9999 14.1304 24.9999C13.5301 24.9999 13.0434 24.5099 13.0434 23.9054L13.0434 17.7913L11.6381 19.2064C11.2136 19.6338 10.5254 19.6338 10.1009 19.2064C9.67644 18.7789 9.67644 18.0859 10.1009 17.6585L13.3618 14.375C13.5656 14.1697 13.8421 14.0544 14.1304 14.0544C14.4187 14.0544 14.6951 14.1697 14.899 14.375L18.1599 17.6585C18.5843 18.0859 18.5843 18.7789 18.1599 19.2064C17.7354 19.6338 17.0471 19.6338 16.6227 19.2064L15.2173 17.7913L15.2173 23.9054Z" fill="#939393"/>
                                                </g>
                                                <defs>
                                                <clipPath id="clip0_3_570">
                                                <rect width="27.1739" height="26.2681" fill="white" transform="translate(0 0.92041)"/>
                                                </clipPath>
                                                </defs>
                                                </svg>
                                        </span>
                                        <p>{{ __('Offline') }} <span>{{ __('videos') }}</span></p>
                                    </li>
                                    <li>
                                        <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M12 24C5.37258 24 0 18.6274 0 12C0 5.37258 5.37258 0 12 0C18.6274 0 24 5.37258 24 12C24 18.6274 18.6274 24 12 24ZM21.9506 13C21.4816 17.7244 17.7244 21.4816 13 21.9506V21C13 20.4477 12.5523 20 12 20C11.4477 20 11 20.4477 11 21V21.9506C6.27558 21.4816 2.51844 17.7244 2.04938 13H3C3.55228 13 4 12.5523 4 12C4 11.4477 3.55228 11 3 11H2.04938C2.51844 6.27558 6.27558 2.51844 11 2.04938V3C11 3.55228 11.4477 4 12 4C12.5523 4 13 3.55228 13 3V2.04938C17.7244 2.51844 21.4816 6.27558 21.9506 11H21C20.4477 11 20 11.4477 20 12C20 12.5523 20.4477 13 21 13H21.9506ZM17.0812 8.02488C17.2271 7.64841 17.1323 7.22111 16.8409 6.94165C16.5495 6.66219 16.1186 6.5854 15.7486 6.74699L9.8425 9.32622C9.61153 9.42709 9.42716 9.61145 9.32629 9.84243L6.74706 15.7485C6.58547 16.1185 6.66226 16.5494 6.94172 16.8408C7.22118 17.1323 7.64848 17.227 8.02495 17.0811L14.1397 14.7106C14.4018 14.609 14.609 14.4018 14.7106 14.1397L17.0812 8.02488ZM11.0022 11.0022L14.3484 9.54084L13.0053 13.0053L9.5409 14.3484L11.0022 11.0022Z" fill="#939393"/>
                                                </svg>
                                        </span>
                                        <p>{{__('Language')}} <span>{{$course->lang}}</span></p>
                                    </li>
                                    <li>
                                        <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M21.9002 6.66864L13.0002 10.7037L4.10009 6.66864L13.0002 2.16667L21.9002 6.66864ZM22.7502 8.66225L14.0835 12.5915V18.6897V21.6942V22.6085V22.7521V22.7599V22.7674V22.7749V22.7822V22.7893V22.7962V22.8031V22.8097V22.8162V22.8226V22.8288V22.8348V22.8407V22.8465V22.852V22.8575V22.9223V22.9388V22.9401V22.9412V22.9422V22.9429V22.9435V22.944V22.9442L13.0002 22.9443L11.9168 22.9442V22.944V22.9435V22.9429V22.9422V22.9412V22.9401V22.9388V22.9373V22.9357V22.9338V22.9319V22.9297V22.9274V22.9249V22.9223V22.8575V22.852V22.8465V22.8407V22.8348V22.8288V22.8226V22.8162V22.8097V22.8031V22.7962V22.7893V22.7822V22.7749V22.7674V22.7599V22.7521V22.7443V22.7362V22.7197V22.7026V22.6939V22.685V22.6759V22.6667V22.6479V22.6382V22.6285V22.6085V21.6942V18.6897V12.5915L3.25016 8.66225V18.9013L11.9833 23.3188C11.9403 23.2021 11.9168 23.076 11.9168 22.9443H13.0002H14.0835C14.0835 23.076 14.06 23.2021 14.017 23.3188L22.7502 18.9013V8.66225ZM2.27218 5.16518C1.54311 5.53397 1.0835 6.28153 1.0835 7.09857V18.9013C1.0835 19.7183 1.54311 20.4659 2.27218 20.8347L12.0222 25.7666C12.6371 26.0776 13.3633 26.0776 13.9781 25.7666L23.7281 20.8347C24.4572 20.4659 24.9168 19.7183 24.9168 18.9013V7.09857C24.9168 6.28153 24.4572 5.53397 23.7281 5.16518L13.9781 0.233275C13.3633 -0.0777586 12.6371 -0.0777584 12.0222 0.233276L2.27218 5.16518Z" fill="#939393"/>
                                                </svg>
                                        </span>
                                        <p>{{ __('Difficult') }} <span>{{ __('ntroductory') }}</span></p>
                                    </li>
                                    <li>
                                        <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M12 24C5.37258 24 0 18.6274 0 12C0 5.37258 5.37258 0 12 0C18.6274 0 24 5.37258 24 12C24 18.6274 18.6274 24 12 24ZM21.9506 13C21.4816 17.7244 17.7244 21.4816 13 21.9506V21C13 20.4477 12.5523 20 12 20C11.4477 20 11 20.4477 11 21V21.9506C6.27558 21.4816 2.51844 17.7244 2.04938 13H3C3.55228 13 4 12.5523 4 12C4 11.4477 3.55228 11 3 11H2.04938C2.51844 6.27558 6.27558 2.51844 11 2.04938V3C11 3.55228 11.4477 4 12 4C12.5523 4 13 3.55228 13 3V2.04938C17.7244 2.51844 21.4816 6.27558 21.9506 11H21C20.4477 11 20 11.4477 20 12C20 12.5523 20.4477 13 21 13H21.9506ZM17.0812 8.02488C17.2271 7.64841 17.1323 7.22111 16.8409 6.94165C16.5495 6.66219 16.1186 6.5854 15.7486 6.74699L9.8425 9.32622C9.61153 9.42709 9.42716 9.61145 9.32629 9.84243L6.74706 15.7485C6.58547 16.1185 6.66226 16.5494 6.94172 16.8408C7.22118 17.1323 7.64848 17.227 8.02495 17.0811L14.1397 14.7106C14.4018 14.609 14.609 14.4018 14.7106 14.1397L17.0812 8.02488ZM11.0022 11.0022L14.3484 9.54084L13.0053 13.0053L9.5409 14.3484L11.0022 11.0022Z" fill="#939393"></path>
                                                </svg>
                                        </span>
                                        <p>{{ __('Transcript') }} <span>{{$course->lang}}</span></p>
                                    </li>
                                    <li>
                                        <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M18.3759 7.65153C19.7919 9.07948 20.0106 11.2474 19.0322 12.904L13.097 6.96876C14.7536 5.99037 16.9215 6.20913 18.3494 7.62504L18.3626 7.6384L18.3759 7.65153ZM20.5973 6.93577C22.4064 9.47424 22.1722 13.0212 19.8948 15.2986L19.5715 15.6219C19.327 15.8664 18.9305 15.8664 18.686 15.6219L16.8307 13.7665L15.2993 15.2978C14.8763 15.7209 14.1903 15.7209 13.7673 15.2978C13.3442 14.8748 13.3442 14.1888 13.7673 13.7658L15.2986 12.2345L13.7665 10.7024L12.2352 12.2337C11.8121 12.6568 11.1262 12.6568 10.7031 12.2337C10.2801 11.8106 10.2801 11.1247 10.7031 10.7016L12.2345 9.17032L10.3791 7.31498C10.1346 7.07045 10.1346 6.674 10.3791 6.42947L10.7024 6.10619C12.9798 3.82878 16.5267 3.59462 19.0652 5.40371L20.6616 3.80735C21.0846 3.38428 21.7706 3.38428 22.1936 3.80735C22.6167 4.23042 22.6167 4.91635 22.1936 5.33941L20.5973 6.93577ZM3.80811 22.1929C3.38504 21.7698 3.38504 21.0839 3.80811 20.6608L5.40447 19.0644C3.59538 16.526 3.82954 12.9791 6.10695 10.7016L6.43023 10.3784C6.67476 10.1338 7.07121 10.1338 7.31574 10.3784L15.6226 18.6852C15.8671 18.9298 15.8671 19.3262 15.6226 19.5707L15.2993 19.894C13.0219 22.1714 9.475 22.4056 6.93653 20.5965L5.34017 22.1929C4.91711 22.6159 4.23118 22.6159 3.80811 22.1929ZM12.9047 19.0315L6.96952 13.0963C5.98809 14.758 6.21126 16.9342 7.63902 18.362C9.06678 19.7897 11.243 20.0129 12.9047 19.0315Z" fill="#939393"/>
                                                </svg>
                                        </span>
                                        <p>{{ __('Platform') }} <span>{{ __('edX') }}</span></p>
                                    </li>
                                    <li>
                                        <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
                                                <g clip-path="url(#clip0_3_619)">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8.63976 3.01254C7.37055 4.28175 7.37055 6.33953 8.63976 7.60874L10.7014 9.67035C11.9706 10.9396 14.0284 10.9396 15.2976 9.67035L17.3592 7.60874C18.6284 6.33953 18.6284 4.28174 17.3592 3.01254L15.2976 0.950925C14.0284 -0.31828 11.9706 -0.318276 10.7014 0.950927L8.63976 3.01254ZM10.1718 6.07667C9.74875 5.6536 9.74875 4.96767 10.1718 4.54461L12.2334 2.48299C12.6565 2.05992 13.3424 2.05992 13.7655 2.48299L15.8271 4.5446C16.2502 4.96767 16.2502 5.6536 15.8271 6.07667L13.7655 8.13829C13.3424 8.56135 12.6565 8.56135 12.2334 8.13829L10.1718 6.07667ZM0.951902 10.7004C-0.317303 11.9696 -0.317299 14.0274 0.951904 15.2966L3.01352 17.3582C4.28272 18.6274 6.34051 18.6274 7.60971 17.3582L9.67133 15.2966C10.9405 14.0274 10.9405 11.9696 9.67133 10.7004L7.60971 8.63878C6.34051 7.36957 4.28272 7.36958 3.01352 8.63878L0.951902 10.7004ZM2.48397 13.7645C2.0609 13.3415 2.0609 12.6555 2.48397 12.2325L4.54558 10.1708C4.96865 9.74778 5.65458 9.74778 6.07765 10.1708L8.13926 12.2325C8.56233 12.6555 8.56233 13.3415 8.13926 13.7645L6.07765 15.8261C5.65458 16.2492 4.96865 16.2492 4.54558 15.8261L2.48397 13.7645ZM8.63976 22.9844C7.37055 21.7152 7.37055 19.6575 8.63976 18.3882L10.7014 16.3266C11.9706 15.0574 14.0284 15.0574 15.2976 16.3266L17.3592 18.3882C18.6284 19.6574 18.6284 21.7152 17.3592 22.9844L15.2976 25.0461C14.0284 26.3153 11.9706 26.3153 10.7014 25.0461L8.63976 22.9844ZM10.1718 19.9203C9.74875 20.3434 9.74875 21.0293 10.1718 21.4524L12.2334 23.514C12.6565 23.9371 13.3424 23.9371 13.7655 23.514L15.8271 21.4524C16.2502 21.0293 16.2502 20.3434 15.8271 19.9203L13.7655 17.8587C13.3424 17.4356 12.6565 17.4356 12.2334 17.8587L10.1718 19.9203ZM16.3287 10.7004C15.0595 11.9696 15.0595 14.0274 16.3287 15.2966L18.3903 17.3582C19.6595 18.6274 21.7173 18.6274 22.9865 17.3582L25.0481 15.2966C26.3173 14.0274 26.3173 11.9696 25.0481 10.7004L22.9865 8.63878C21.7173 7.36957 19.6595 7.36958 18.3903 8.63878L16.3287 10.7004ZM17.8607 13.7645C17.4377 13.3415 17.4377 12.6555 17.8607 12.2325L19.9223 10.1708C20.3454 9.74778 21.0313 9.74778 21.4544 10.1708L23.516 12.2325C23.9391 12.6555 23.9391 13.3415 23.516 13.7645L21.4544 15.8261C21.0313 16.2492 20.3454 16.2492 19.9223 15.8261L17.8607 13.7645Z" fill="#939393"/>
                                                </g>
                                                <defs>
                                                <clipPath id="clip0_3_619">
                                                <rect width="26" height="26" fill="white"/>
                                                </clipPath>
                                                </defs>
                                                </svg>
                                        </span>
                                        <p>{{ __('Credit') }} <span>{{ __('Add a Verified Certificate for $149') }}</span></p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </section>
        <section class="course-description-section padding-top padding-bottom border-top">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-7 col-12">
                        <div class="description-left-side">
                            <div class="section-title">
                                <h2>{{ __('Course description') }}</h2>
                            </div>
                            <p>{!! $course->course_description !!} </p>
                          
                            {{-- @if(Auth::guard('students')->check())
                                @if(in_array($course->id,Auth::guard('students')->user()->purchasedCourse()))
                                    <a class="btn cart-btn cart-btn"  href="{{route('store.fullscreen',[$store->slug,Crypt::encrypt($course->id),''])}}">
                                        {{__('Get Started')}}
                                        <svg viewBox="0 0 10 5">
                                            <path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z">
                                            </path>
                                        </svg>
                                    </a>
                                @else
                                    <div class="add_to_cart">                                                        
                                        @if($key !== false)
                                            <a id="cart-btn-{{$course->id}}" class="btn cart-btn cart-btn">{{__('Already in Cart')}}</a>
                                        @else
                                            <a id="cart-btn-{{$course->id}}" class="btn cart-btn add_to_cart" data-id="{{$course->id}}">{{__('Add in Cart')}}</a>
                                        @endif
                                    </div>
                                @endif
                            @else
                                <div class="add_to_cart">                                                        
                                    @if($key !== false)
                                        <a id="cart-btn-{{$course->id}}" class="btn cart-btn">{{__('Already in Cart')}}</a>
                                    @else
                                        <a id="cart-btn-{{$course->id}}" class="btn cart-btn add_to_cart" data-id="{{$course->id}}">{{__('Add in Cart')}}</a>
                                    @endif
                                </div>
                            @endif --}}
                        </div>
                    </div>
                    <div class="col-lg-5 col-12">
                        <div class="description-image-right">
                            @if($course->is_preview == 'on')
                                @if($course->preview_type == 'Video File')
                                    <video id="example" class="preview_video" poster="{{ asset('assets/themes/theme2/images/poster.jpg') }}" controls="" controlslist="nodownload" style="display: none;">
                                        <source src="{{asset(Storage::url('uploads/preview_video/'.$course->preview_content))}}" type="video/mp4">
                                        <track kind="captions" label="Portuguese" srclang="pt" src="https://raw.githubusercontent.com/BMSVieira/moovie.js/main/demo-template/subtitles/pt.vtt">
                                        <track kind="captions" label="English" srclang="en" src="https://raw.githubusercontent.com/BMSVieira/moovie.js/main/demo-template/subtitles/en.vtt">
                                        <track kind="captions" label="French" srclang="en" src="https://raw.githubusercontent.com/BMSVieira/moovie.js/main/demo-template/subtitles/french.srt">
                                            {{__('Your browser does not support the video tag')}}.
                                    </video>
                                @elseif($course->preview_type == 'Image')
                                    <div class="container">
                                        <img src="{{asset(Storage::url('uploads/preview_image/'.$course->preview_content))}}">
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="syllabus-section padding-top padding-bottom border-top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-12">
                        <div class="syllabus-left-side">
                            <div class="section-title">
                                <h2>{{ __('Syllabus') }}</h2>
                            </div>
                            <p>This is CS50x , Harvard University's introduction to the intellectual enterprises of
                                computer science and the art of programming for majors and non-majors alike, with or
                                without prior programming experience. An entry-level course taught by David J. Malan,
                                CS50x teaches students how to think algorithmically and solve problems efficiently.
                                Topics include abstraction, algorithms, data structures, encapsulation, resource
                                management, security, software engineering, and web development. Languages include C,
                                Python, SQL, and JavaScript plus CSS and HTML. Problem sets inspired by real-world
                                domains of biology, cryptography, finance, forensics, and gaming. The on-campus version
                                of CS50x , CS50, is Harvard's largest course.
                            </p>
                            <p>Students who earn a satisfactory score on 9 problem sets (i.e., programming assignments)
                                and a final project are eligible for a certificate. This is a self-paced course–you may
                                take CS50x on your own schedule.
                            </p>
                            {{-- @if(Auth::guard('students')->check())
                                @if(in_array($course->id,Auth::guard('students')->user()->purchasedCourse()))
                                    <a class="btn cart-btn cart-btn"  href="{{route('store.fullscreen',[$store->slug,Crypt::encrypt($course->id),''])}}">
                                        {{__('Get Started')}}
                                        <svg viewBox="0 0 10 5">
                                            <path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z">
                                            </path>
                                        </svg>
                                    </a>
                                @else
                                    <div class="add_to_cart">                                                        
                                        @if($key !== false)
                                            <a id="cart-btn-{{$course->id}}" class="btn cart-btn cart-btn">{{__('Already in Cart')}}</a>
                                        @else
                                            <a id="cart-btn-{{$course->id}}" class="btn cart-btn add_to_cart" data-id="{{$course->id}}">{{__('Add in Cart')}}</a>
                                        @endif
                                    </div>
                                @endif
                            @else
                                <div class="add_to_cart">                                                        
                                    @if($key !== false)
                                        <a id="cart-btn-{{$course->id}}" class="btn cart-btn">{{__('Already in Cart')}}</a>
                                    @else
                                        <a id="cart-btn-{{$course->id}}" class="btn cart-btn add_to_cart" data-id="{{$course->id}}">{{__('Add in Cart')}}</a>
                                    @endif
                                </div>
                            @endif --}}
                        </div>
                    </div>
                    <div class="col-lg-6 col-12">
                        <div class="syllabus-list">
                            <div class="sub-title">
                                {{ __('Syllabus') }}
                            </div>
                            <ol>
                                @php
                                    $i = 0;
                                @endphp
                                @foreach ($headers as $key => $header)
                                    <li>
                                        <div class="syllabus-card">
                                            <div class="left-side">
                                                <h5>{{ $header->title }}</h5>
                                                <div class="read-more-content" id="ChapterDetails{{ $key }}">
                                                   
                                                    @foreach ($header->chapters_data as $k => $chapters)                                                    
                                                        {{-- <p>{!! $chapters->chapter_description !!} </p> --}}
                                                        @php                                                            
                                                            $i++;
                                                        @endphp
                                                        
                                                        <div class="right-side">
                                                            <div class="left-side">
                                                                <label for="description{{ $i }}">{{ $i . '. ' . $chapters->name }}</label>
                                                            </div>
                                                            <div class="duration">
                                                                <span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                                                                    <g clip-path="url(#clip0_3_725)">
                                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M1.12581 6.75486C1.12581 3.64602 3.64602 1.12581 6.75486 1.12581C9.8637 1.12581 12.3839 3.64602 12.3839 6.75486C12.3839 9.8637 9.8637 12.3839 6.75486 12.3839C3.64602 12.3839 1.12581 9.8637 1.12581 6.75486ZM6.75486 0C3.02426 0 0 3.02426 0 6.75486C0 10.4855 3.02426 13.5097 6.75486 13.5097C10.4855 13.5097 13.5097 10.4855 13.5097 6.75486C13.5097 3.02426 10.4855 0 6.75486 0ZM7.31777 4.50324C7.31777 4.19236 7.06575 3.94034 6.75486 3.94034C6.44398 3.94034 6.19196 4.19236 6.19196 4.50324V7.00511C6.19196 7.19334 6.28604 7.36911 6.44267 7.47351L8.31955 8.72444C8.57824 8.89686 8.92772 8.82692 9.10014 8.56823C9.27256 8.30954 9.20262 7.96005 8.94393 7.78764L7.31777 6.70381V4.50324Z" fill="#939393"/>
                                                                    </g>
                                                                    <defs>
                                                                    <clipPath id="clip0_3_725">
                                                                    <rect width="13.5097" height="13.5097" fill="white"/>
                                                                    </clipPath>
                                                                    </defs>
                                                                    </svg></span>
                                                                <p>{{ $chapters->duration }} <span>{{ __('Duration') }}</span></p>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <a href="javascript:void(0);" class="read-more" title="Read More">{{ __('Read More') }}</a>
                                            </div>
                                            {{-- @foreach($header->chapters_data as $k => $chapters)
                                                @php
                                                    $i++;
                                                @endphp
                                                <label for="description{{ $i }}">{{ $i . '. ' . $chapters->name }}</label> 
                                                <div  data-id="{{ $chapters->id }}" class="right-side">
                                                    <div class="duration">
                                                        <span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                                                            <g clip-path="url(#clip0_3_725)">
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M1.12581 6.75486C1.12581 3.64602 3.64602 1.12581 6.75486 1.12581C9.8637 1.12581 12.3839 3.64602 12.3839 6.75486C12.3839 9.8637 9.8637 12.3839 6.75486 12.3839C3.64602 12.3839 1.12581 9.8637 1.12581 6.75486ZM6.75486 0C3.02426 0 0 3.02426 0 6.75486C0 10.4855 3.02426 13.5097 6.75486 13.5097C10.4855 13.5097 13.5097 10.4855 13.5097 6.75486C13.5097 3.02426 10.4855 0 6.75486 0ZM7.31777 4.50324C7.31777 4.19236 7.06575 3.94034 6.75486 3.94034C6.44398 3.94034 6.19196 4.19236 6.19196 4.50324V7.00511C6.19196 7.19334 6.28604 7.36911 6.44267 7.47351L8.31955 8.72444C8.57824 8.89686 8.92772 8.82692 9.10014 8.56823C9.27256 8.30954 9.20262 7.96005 8.94393 7.78764L7.31777 6.70381V4.50324Z" fill="#939393"/>
                                                            </g>
                                                            <defs>
                                                            <clipPath id="clip0_3_725">
                                                            <rect width="13.5097" height="13.5097" fill="white"/>
                                                            </clipPath>
                                                            </defs>
                                                            </svg></span>
                                                        <p>{{ $chapters->duration }} <span>{{ __('Duration') }}</span></p>
                                                    </div>
                                                </div>
                                            @endforeach --}}
                                        </div>
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="requirement-section padding-top border-top padding-bottom">
            <div class="container">
                <div class="section-title">
                    <h2>{{ __('Requirements') }}</h2>
                </div>
                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="requirement-left-side">
                            <p>{{ __('This is CS50x , Harvard University')}} ' {{ ('s introduction to the intellectual enterprises of computer science and the art of programming for majors and non-majors alike, with or without prior programming experience. An entry-level course taught by David J. Malan, CS50x teaches students how to think algorithmically and solve problems efficiently.') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="requirement-right-side">
                            <ul>
                                <li>A broad and robust understanding of computer science and programming</li>
                                <li>How to think algorithmically and solve programming problems efficiently</li>
                                <li>Concepts like abstraction, algorithms, data structures, encapsulation, resource management, security, software engineering, and web development</li>
                                <li>Familiarity with a number of languages, including C, Python, SQL, and JavaScript plus CSS and HTML</li>
                                <li>How to engage with a vibrant community of like-minded learners from all levels of experience</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="educators-section padding-top padding-bottom border-top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-12">
                        <div class="educators-left-side">
                            <div class="section-title">
                                <h2>{{ __('Educators') }}</h2>
                            </div>
                            <p>{{ __('This is CS50x , Harvard University')}} ' {{ __('s introduction to the intellectual enterprises of computer science and the art of programming for majors and non-majors alike, with or without prior programming experience. ') }}</p>
                        </div>
                    </div>
                    <div class="col-lg-9 col-12">
                        <div class="row educator-card-row">
                            {{-- @foreach($tutor_ratings as $c_rating_key => $course_rating) --}}
                                <div class="col-md-4 col-sm-6 col-12  educator-card">
                                    <div class="educator-card-inner">
                                        <div class="educator-image">
                                            <img src="{{ asset('assets/themes/theme2/images/poster.jpg') }}" alt="">
                                            <span class="badge">{{ __('Educator') }}</span>
                                        </div>
                                        <div class="educator-caption">
                                            <div class="review-rating">
                                                <span class="rating-num">{{$avg_rating}}</span>
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
                                                <span>({{$user_count}})</span>
                                            </div>
                                            <h4> {{$tutor->name}}</h4>
                                            <div class="educator-bottom">
                                                <p>{{ __('Lorem Ipsum is simply dummy text of the printing and typesetting industry.') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>     
                            {{-- @endforeach --}}
                                                       
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="review-section padding-top padding-bottom border-top">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="review-left-side">
                            <div class="section-title">
                                <h2>{{ __('Reviews') }}</h2>
                            </div>
                            <p>{{ __('This is CS50x , Harvard University')}} ' {{ __('s introduction to the intellectual enterprises of computer science and the art of programming for majors and non-majors alike, with or without prior programming experience. ') }}</p>

                            <div class="review-progress">
                                <div class="review-rating">
                                    <h2 class="rating-num">{{$avg_rating}}</h2>
                                    <div class="review-counter">
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
                                        {{-- @dd($user_count) --}}
                                        <span>({{$user_count}})</span>
                                    </div>
                                </div>
                               
                                <div class="star-progress">
                                    <span>{{ __('5 stars') }}</span>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: {{ !empty($course->star_rating($course->id)) ? $course->star_rating($course->id)['ratting5'] : '0' }}%;"></div>
                                    </div>
                                    {{-- @dd($course->star_rating($course->id)['ratting5']) --}}
                                    {{-- <div class="progress-bar" style="width: {{ !empty($progress) ? $progress : '0' }}%;"></div> --}}
                                    <span>  {{ $course->star_rating($course->id)['ratting5'] }}%</span>
                                </div>
                                <div class="star-progress">
                                    <span>{{ __('4 stars') }}</span>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: {{ !empty($course->star_rating($course->id)) ? $course->star_rating($course->id)['ratting4'] : '0' }}%;"></div>
                                    </div>
                                    <span> {{ $course->star_rating($course->id)['ratting4'] }}%</span>
                                </div>
                                <div class="star-progress">
                                    <span>{{ __('3 stars') }}</span>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: {{ !empty($course->star_rating($course->id)) ? $course->star_rating($course->id)['ratting3'] : '0' }}%;"></div>
                                    </div>
                                    <span>{{ $course->star_rating($course->id)['ratting3'] }}%</span>
                                </div>
                                <div class="star-progress">
                                    <span>{{ __('2 stars') }}</span>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: {{ !empty($course->star_rating($course->id)) ? $course->star_rating($course->id)['ratting2'] : '0' }}%;"></div>
                                    </div>
                                    <span> {{ $course->star_rating($course->id)['ratting2'] }}%</span>
                                </div>
                                <div class="star-progress">
                                    <span>{{ __('1 stars') }}</span>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: {{ !empty($course->star_rating($course->id)) ? $course->star_rating($course->id)['ratting1'] : '0' }}%;"></div>
                                    </div>
                                    <span> {{ $course->star_rating($course->id)['ratting1'] }} %</span>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="review-right-side">
                            <div class="sub-title">
                                {{ __('Top Reviews') }}:
                            </div>
                            @if(!empty($course_ratings))
                                @foreach($course_ratings as $c_rating_key => $course_rating)
                                    <div class="review-card">
                                        <div class="review-card-inner">
                                            <div class="review-rating">
                                                {{-- <span class="rating-num">{{$avg_rating}}</span> --}}
                                                <div class="review-star d-flex align-items-center">
                                                    <span class="star-img">
                                                        @for($i =0;$i<5;$i++)
                                                            <i class="fas fa-star {{($course_rating->ratting > $i  ? 'text-warning' : '')}}"></i>
                                                        @endfor
                                                    </span>
                                                </div>
                                                {{-- <span>({{$user_count}})</span> --}}
                                            </div>
                                            <p>{{$course_rating->description}}</p>
                                            {{-- <p>{{ __('It is a good course to build your critiquing skills and be properly able to understand the intention behind certain design decisions, if a bit simplistic.') }}</p> --}}
                                            <div class="abt-user">
                                                <p> <span><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" fill="none">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M7.5 1.25C4.04822 1.25 1.25 4.04822 1.25 7.5C1.25 9.12124 1.86729 10.5983 2.87967 11.709C3.40007 10.5778 4.30867 9.69557 5.41055 9.19879C4.77478 8.62676 4.375 7.79756 4.375 6.875C4.375 5.14911 5.77411 3.75 7.5 3.75C9.22589 3.75 10.625 5.14911 10.625 6.875C10.625 7.78291 10.2378 8.60039 9.61957 9.17133C10.7407 9.63739 11.6939 10.4834 12.1506 11.6756C13.145 10.5688 13.75 9.10504 13.75 7.5C13.75 4.04822 10.9518 1.25 7.5 1.25ZM12.3429 13.227C13.9682 11.8513 15 9.79613 15 7.5C15 3.35786 11.6421 0 7.5 0C3.35786 0 0 3.35786 0 7.5C0 9.79609 1.03179 11.8512 2.65702 13.2269C2.69961 13.2751 2.75003 13.3171 2.80728 13.3508C4.09208 14.3827 5.72394 15 7.5 15C9.27601 15 10.9078 14.3827 12.1926 13.3509C12.2498 13.3173 12.3003 13.2752 12.3429 13.227ZM11.1393 12.5817L11.0268 12.2444C10.568 10.8681 9.09406 10 7.5 10C5.8867 10 4.44576 11.0093 3.89442 12.5254L3.87122 12.5893C4.89427 13.32 6.14692 13.75 7.5 13.75C8.85771 13.75 10.1143 13.3171 11.1393 12.5817ZM7.5 8.75C8.53553 8.75 9.375 7.91053 9.375 6.875C9.375 5.83947 8.53553 5 7.5 5C6.46447 5 5.625 5.83947 5.625 6.875C5.625 7.91053 6.46447 8.75 7.5 8.75Z" fill="white"></path>
                                                </svg></span> {{$course_rating->name}} </p>  
                                               
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                           
                            {{-- <a href="#" class="show-more link-btn">{{ __('Show More Info') }} <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4" fill="none">
                                <path d="M1.6 0.399983C1.6 0.179078 1.77909 0 2 0C2.22091 0 2.4 0.179078 2.4 0.399983L2.4 2.63437L3.31716 1.71725C3.47337 1.56105 3.72663 1.56105 3.88284 1.71725C4.03905 1.87346 4.03905 2.12671 3.88284 2.28291L2.28284 3.88285C2.20783 3.95786 2.10609 4 2 4C1.89391 4 1.79217 3.95786 1.71716 3.88285L0.117157 2.28291C-0.0390525 2.12671 -0.0390525 1.87346 0.117157 1.71725C0.273367 1.56105 0.526633 1.56105 0.682843 1.71725L1.6 2.63437L1.6 0.399983Z" fill="white"/>
                                </svg></a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="faq-section padding-top padding-bottom border-top">
            <div class="container">
                <div class="section-title">
                    <h2>{{ __('FAQ') }}</h2>
                    <p>{{ __('This is CS50x , Harvard University')}}' {{('s introduction to the intellectual enterprises of computer science and the art of programming for majors and non-majors alike, with or without prior programming experience.')}} </p>
                </div>
                <div class="row">
                    @if(count($faqs) > 0  && !empty($faqs))
                        @foreach($faqs as $q_i => $faq)
                            <div class="col-md-6 col-12">
                                <div class=" set has-children">
                                    <a href="javascript:;" class="acnav-label">
                                        <span>{{$faq->question}}??</span>
                                    </a>
                                    <div id="ChapterFour-{{$q_i}}" class="acnav-list">
                                        <p>{{$faq->answer}}.</p>
                                    </div>
                                </div>
                            </div>                            
                        @endforeach
                    @else
                        <div class="text-center no-data">
                            <p>{{__('There are no FAQs!')}}</p>
                        </div>
                    @endif       
                </div>
            </div>
        </section>
        
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
                                    {{ Form::email('email',null,array('aria-label'=>'Enter your email address','placeholder'=>__('Enter Your Email Address'))) }}
                                    <button type="submit" class="btn btn-white">{{ $homepage_email_subscriber_button }} <svg viewBox="0 0 10 5">
                                        <path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z">
                                        </path>
                                    </svg></button>
                                </div>
                                <div class="checkbox-custom">
                                    <input type="checkbox" class="" id="newslettercheckbox">
                                    <label for="newslettercheckbox">{{ __('Lorem Ipsum is simply dummy text of the printing and typesetting industry.') }}</label>
                                </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>            
            </section> 
        @endif 
    </div>

@endsection
