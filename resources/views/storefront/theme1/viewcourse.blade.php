
@extends('layouts.theme1.shopfront')
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
    <script src="{{asset('./js/moovie.js')}}"></script>
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
    <section class="common-banner-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-md-12">
                    <div class="common-banner-content">
                        <a href="{{route('store.slug',$store->slug)}}" class="back-btn">
                            <span class="svg-ic">
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="5" viewBox="0 0 11 5" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M10.5791 2.28954C10.5791 2.53299 10.3818 2.73035 10.1383 2.73035L1.52698 2.73048L2.5628 3.73673C2.73742 3.90636 2.74146 4.18544 2.57183 4.36005C2.40219 4.53467 2.12312 4.53871 1.9485 4.36908L0.133482 2.60587C0.0480403 2.52287 -0.000171489 2.40882 -0.000171488 2.2897C-0.000171486 2.17058 0.0480403 2.05653 0.133482 1.97353L1.9485 0.210321C2.12312 0.0406877 2.40219 0.044729 2.57183 0.219347C2.74146 0.393966 2.73742 0.673036 2.5628 0.842669L1.52702 1.84888L10.1383 1.84875C10.3817 1.84874 10.5791 2.04609 10.5791 2.28954Z" fill="white"></path>
                                </svg>
                            </span>
                            {{__('Back to home')}}
                        </a>
                        <span class="badge">  {{ !empty($course->category_id)?$course->category_id->name:''}}</span>
                        {{-- @dd($course->category_id) --}}
                        <div class="section-title">
                            <h2>{{$course->title}}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="banner-image">
                {{-- <img src="{{ asset('assets/imgs/Male-Running-common.png') }}" alt=""> --}}
                @php
                    $data=explode(".",$store->store_theme);                               
                @endphp

                @if($data[0]=='yellow-style')
                    <img src="{{ asset('assets/imgs/Male-Running-common1.png') }}" alt="">
                @elseif($data[0]=='blue-style')
                    <img src="{{ asset('assets/imgs/Male-Running-common2.png') }}" alt="">
                @else
                    <img src="{{ asset('assets/imgs/Male-Running-common3.png') }}" alt="">
                @endif
            </div>
        </div>
    </section>
    <section class="product-main-section padding-bottom padding-top">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-8 col-12 pdp-left-side ">
                    <div class="pdp-main-itm">
                        <div class="pdp-main-media">
                            @if($course->is_preview == 'on')
                                @if($course->preview_type == 'Video File')
                                    <video id="example" class="preview_video" poster="{{asset('assets/imgs/video-img.png')}}" controls controlsList="nodownload">
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
                    <div class="tabs-wrapper">
                        <div class="pdp-tabs">
                            <ul class="tabs">
                                <li class="tab-link active" data-tab="tab-1">
                                    <a href="javascript:;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0 5.5C0 8.53757 2.46243 11 5.5 11C8.53757 11 11 8.53757 11 5.5C11 2.46243 8.53757 0 5.5 0C2.46243 0 0 2.46243 0 5.5ZM5.5 9C3.567 9 2 7.433 2 5.5C2 3.567 3.567 2 5.5 2C7.433 2 9 3.567 9 5.5C9 7.433 7.433 9 5.5 9ZM0 18.5C0 21.5376 2.46243 24 5.5 24C8.53757 24 11 21.5376 11 18.5C11 15.4624 8.53757 13 5.5 13C2.46243 13 0 15.4624 0 18.5ZM5.5 22C3.567 22 2 20.433 2 18.5C2 16.567 3.567 15 5.5 15C7.433 15 9 16.567 9 18.5C9 20.433 7.433 22 5.5 22ZM18.5 24C15.4624 24 13 21.5376 13 18.5C13 15.4624 15.4624 13 18.5 13C21.5376 13 24 15.4624 24 18.5C24 21.5376 21.5376 24 18.5 24ZM15 18.5C15 20.433 16.567 22 18.5 22C20.433 22 22 20.433 22 18.5C22 16.567 20.433 15 18.5 15C16.567 15 15 16.567 15 18.5ZM13 5.5C13 8.53757 15.4624 11 18.5 11C21.5376 11 24 8.53757 24 5.5C24 2.46243 21.5376 0 18.5 0C15.4624 0 13 2.46243 13 5.5ZM18.5 9C16.567 9 15 7.433 15 5.5C15 3.567 16.567 2 18.5 2C20.433 2 22 3.567 22 5.5C22 7.433 20.433 9 18.5 9Z" fill="#8A94A6"/>
                                        </svg>{{__('Overview')}}</a>
                                </li>
                                <li class="tab-link" data-tab="tab-2">
                                    <a href="javascript:;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M2.56788 5.19093L8 2.58804V19.5552C7.90178 19.5902 7.80494 19.6304 7.70991 19.676L3.43212 21.7257C2.76833 22.0438 2 21.56 2 20.8239V6.09275C2 5.70792 2.22083 5.35723 2.56788 5.19093ZM10.2873 19.6687C10.1931 19.6242 10.0972 19.5849 10 19.5508V2.82556L13.524 5.077C13.9738 5.36438 14.4836 5.52169 15 5.54568V21.8337C14.8907 21.8218 14.7832 21.7917 14.682 21.7439L10.2873 19.6687ZM17 21.2308L21.3338 19.6959C21.7331 19.5545 22 19.1769 22 18.7533V4.17608C22 3.44003 21.2317 2.9562 20.5679 3.27427L17 4.98388V21.2308ZM7.56448 0.578981C8.50035 0.130547 9.6015 0.197627 10.476 0.756341L14.6008 3.39161C14.8923 3.57784 15.2593 3.6002 15.5713 3.45072L19.7036 1.47063C21.695 0.51644 24 1.96792 24 4.17608V18.7533C24 20.024 23.1994 21.1569 22.0015 21.5812L16.1105 23.6676C15.3643 23.9319 14.5438 23.8905 13.828 23.5524L9.43328 21.4772C9.16113 21.3486 8.84557 21.3495 8.57415 21.4796L4.29636 23.5294C2.305 24.4836 0 23.0321 0 20.8239V6.09275C0 4.93825 0.662494 3.88618 1.70364 3.3873L7.56448 0.578981Z" fill="#8A94A6"/>
                                            </svg>
                                            {{__('Syllabus')}}
                                    </a>
                                </li>
                                <li class="tab-link" data-tab="tab-3">
                                    <a href="javascript:;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.47715 2 2 6.47715 2 12C2 14.594 2.98767 16.9573 4.60747 18.7344C5.44011 16.9245 6.89387 15.5129 8.65688 14.7181C7.63965 13.8028 7 12.4761 7 11C7 8.23858 9.23858 6 12 6C14.7614 6 17 8.23858 17 11C17 12.4527 16.3805 13.7606 15.3913 14.6741C17.1851 15.4198 18.7102 16.7734 19.4409 18.6809C21.032 16.91 22 14.5681 22 12C22 6.47715 17.5228 2 12 2ZM19.7487 21.1632C22.3491 18.962 24 15.6738 24 12C24 5.37258 18.6274 0 12 0C5.37258 0 0 5.37258 0 12C0 15.6737 1.65087 18.9619 4.25123 21.1631C4.31937 21.2402 4.40004 21.3074 4.49165 21.3614C6.54732 23.0122 9.15831 24 12 24C14.8416 24 17.4525 23.0123 19.5082 21.3615C19.5997 21.3076 19.6804 21.2404 19.7487 21.1632ZM17.8228 20.1308L17.6429 19.5911C16.9089 17.3889 14.5505 16 12 16C9.41872 16 7.11321 17.6148 6.23108 20.0407L6.19395 20.1428C7.83083 21.312 9.83507 22 12 22C14.1723 22 16.1829 21.3073 17.8228 20.1308ZM12 14C13.6569 14 15 12.6569 15 11C15 9.34315 13.6569 8 12 8C10.3431 8 9 9.34315 9 11C9 12.6569 10.3431 14 12 14Z" fill="#6F5FA7"/>
                                            </svg>
                                            {{__('Tutor')}}
                                    </a>
                                </li>
                                <li class="tab-link" data-tab="tab-4">
                                    <a href="javascript:;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9.57406 2.76901C10.3447 0.410328 13.6553 0.410334 14.4259 2.76901L15.8051 6.99008C15.8535 7.13813 15.9892 7.23936 16.1438 7.24271L20.4962 7.33707C22.8824 7.3888 23.9006 10.4175 22.0372 11.9204L18.4398 14.8219C18.3249 14.9146 18.2765 15.0678 18.317 15.2104L19.5954 19.7059C20.2665 22.0656 17.5912 23.9429 15.6282 22.4897L12.216 19.9637C12.0875 19.8686 11.9125 19.8686 11.784 19.9637L8.37177 22.4897C6.40879 23.9429 3.7335 22.0656 4.40456 19.7059L5.68299 15.2104C5.72354 15.0678 5.67513 14.9146 5.56022 14.8219L1.96284 11.9205C0.0993767 10.4175 1.1176 7.3888 3.50376 7.33707L7.85616 7.24271C8.01078 7.23936 8.14651 7.13813 8.19488 6.99008L9.57406 2.76901ZM12.3466 3.45914C12.2365 3.12218 11.7635 3.12218 11.6534 3.45914L10.2743 7.68021C9.93564 8.71654 8.98557 9.42515 7.90325 9.44862L3.55084 9.54298C3.20996 9.55037 3.0645 9.98303 3.33071 10.1977L6.92809 13.0992C7.73242 13.748 8.07131 14.8202 7.78748 15.8183L6.50904 20.3138C6.41318 20.6509 6.79536 20.9191 7.07579 20.7115L10.488 18.1854C11.3877 17.5194 12.6123 17.5194 13.512 18.1854L16.9242 20.7115C17.2046 20.9191 17.5868 20.6509 17.491 20.3138L16.2125 15.8183C15.9287 14.8202 16.2676 13.748 17.0719 13.0992L20.6693 10.1977C20.9355 9.98303 20.79 9.55037 20.4492 9.54298L16.0968 9.44862C15.0144 9.42515 14.0644 8.71654 13.7257 7.68021L12.3466 3.45914Z" fill="#6F5FA7"/>
                                            </svg>
                                            {{__('Course Review')}}
                                    </a>
                                </li>
                                <li class="tab-link" data-tab="tab-5">
                                    <a href="javascript:;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.47296 4.16957C3.0915 4.53032 3 4.81128 3 5C3 5.18872 3.0915 5.46968 3.47296 5.83043C3.85653 6.19317 4.46523 6.56591 5.30032 6.89995C6.96497 7.56581 9.33323 8 12 8C14.6668 8 17.035 7.56581 18.6997 6.89995C19.5348 6.56591 20.1435 6.19317 20.527 5.83043C20.9085 5.46968 21 5.18872 21 5C21 4.81128 20.9085 4.53032 20.527 4.16957C20.1435 3.80683 19.5348 3.43409 18.6997 3.10005C17.035 2.43419 14.6668 2 12 2C9.33323 2 6.96497 2.43419 5.30032 3.10005C4.46523 3.43409 3.85653 3.80683 3.47296 4.16957ZM21 7.97538C20.5351 8.27022 20.0089 8.53034 19.4425 8.7569C17.4878 9.53876 14.8561 10 12 10C9.14392 10 6.51218 9.53876 4.55754 8.7569C3.99114 8.53034 3.46486 8.27022 3 7.97538V12C3 12.1887 3.0915 12.4697 3.47296 12.8304C3.85653 13.1932 4.46523 13.5659 5.30032 13.9C6.96497 14.5658 9.33323 15 12 15C14.6668 15 17.035 14.5658 18.6997 13.9C19.5348 13.5659 20.1435 13.1932 20.527 12.8304C20.9085 12.4697 21 12.1887 21 12V7.97538ZM23 12V5C23 4.08415 22.5319 3.31282 21.9013 2.71646C21.2728 2.1221 20.417 1.63292 19.4425 1.2431C17.4878 0.461239 14.8561 0 12 0C9.14392 0 6.51218 0.461239 4.55754 1.2431C3.58299 1.63292 2.72723 2.1221 2.09874 2.71646C1.46814 3.31282 1 4.08415 1 5V12V19C1 19.9159 1.46814 20.6872 2.09874 21.2835C2.72723 21.8779 3.58299 22.3671 4.55754 22.7569C6.51218 23.5388 9.14392 24 12 24C14.8561 24 17.4878 23.5388 19.4425 22.7569C20.417 22.3671 21.2728 21.8779 21.9013 21.2835C22.5319 20.6872 23 19.9159 23 19V12ZM21 14.9754C20.5351 15.2702 20.0089 15.5303 19.4425 15.7569C17.4878 16.5388 14.8561 17 12 17C9.14392 17 6.51218 16.5388 4.55754 15.7569C3.99114 15.5303 3.46486 15.2702 3 14.9754V19C3 19.1887 3.0915 19.4697 3.47296 19.8304C3.85653 20.1932 4.46523 20.5659 5.30032 20.9C6.96497 21.5658 9.33323 22 12 22C14.6668 22 17.035 21.5658 18.6997 20.9C19.5348 20.5659 20.1435 20.1932 20.527 19.8304C20.9085 19.4697 21 19.1887 21 19V14.9754Z" fill="#8A94A6"/>
                                            </svg>
                                            {{__('FAQs')}}
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="tabs-container">
                            <div class="tab-content active" id="tab-1">
                                <div class="section-title">
                                    <a href="{{route('store.viewcourse',[$store->slug,\Illuminate\Support\Facades\Crypt::encrypt($course->id)])}}">
                                        <h3>{{$course->title}}</h3>
                                    </a>
                                </div>
                                <p> {!! $course->course_description !!}</p>
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
                            <div class="tab-content" id="tab-3">
                                <div class="user-review-content">
                                    <div class="abt-user">
                                        <div class="abt-usr-image">
                                            <img src="{{asset('assets/img/user.png')}}" alt="user">
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
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M9.57406 2.76901C10.3447 0.410328 13.6553 0.410334 14.4259 2.76901L15.8051 6.99008C15.8535 7.13813 15.9892 7.23936 16.1438 7.24271L20.4962 7.33707C22.8824 7.3888 23.9006 10.4175 22.0372 11.9204L18.4398 14.8219C18.3249 14.9146 18.2765 15.0678 18.317 15.2104L19.5954 19.7059C20.2665 22.0656 17.5912 23.9429 15.6282 22.4897L12.216 19.9637C12.0875 19.8686 11.9125 19.8686 11.784 19.9637L8.37177 22.4897C6.40879 23.9429 3.7335 22.0656 4.40456 19.7059L5.68299 15.2104C5.72354 15.0678 5.67513 14.9146 5.56022 14.8219L1.96284 11.9205C0.0993767 10.4175 1.1176 7.3888 3.50376 7.33707L7.85616 7.24271C8.01078 7.23936 8.14651 7.13813 8.19488 6.99008L9.57406 2.76901ZM12.3466 3.45914C12.2365 3.12218 11.7635 3.12218 11.6534 3.45914L10.2743 7.68021C9.93564 8.71654 8.98557 9.42515 7.90325 9.44862L3.55084 9.54298C3.20996 9.55037 3.0645 9.98303 3.33071 10.1977L6.92809 13.0992C7.73242 13.748 8.07131 14.8202 7.78748 15.8183L6.50904 20.3138C6.41318 20.6509 6.79536 20.9191 7.07579 20.7115L10.488 18.1854C11.3877 17.5194 12.6123 17.5194 13.512 18.1854L16.9242 20.7115C17.2046 20.9191 17.5868 20.6509 17.491 20.3138L16.2125 15.8183C15.9287 14.8202 16.2676 13.748 17.0719 13.0992L20.6693 10.1977C20.9355 9.98303 20.79 9.55037 20.4492 9.54298L16.0968 9.44862C15.0144 9.42515 14.0644 8.71654 13.7257 7.68021L12.3466 3.45914Z" fill="#8A94A6"/>
                                                    </svg>
                                                <span>{{__('Reviews')}} {{$user_count}}</span>
                                                
                                            </li>
                                            <li>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                                    <g clip-path="url(#clip0_517_1166)">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M7.94954 0.235681C8.3526 0.0341516 8.82702 0.0341514 9.23008 0.23568L16.2195 3.73039C17.2747 4.258 17.2747 5.76385 16.2195 6.29146L15.2014 6.80053L16.2195 7.3096C17.2747 7.83721 17.2747 9.34306 16.2195 9.87067L15.2014 10.3797L16.2195 10.8888C17.2747 11.4164 17.2747 12.9223 16.2195 13.4499L9.23008 16.9446C8.82702 17.1461 8.3526 17.1461 7.94954 16.9446L0.960116 13.4499C-0.095104 12.9223 -0.0951028 11.4164 0.960115 10.8888L1.97825 10.3797L0.960116 9.87067C-0.095104 9.34306 -0.0951028 7.83721 0.960115 7.3096L1.97825 6.80053L0.960117 6.29146C-0.0951052 5.76385 -0.0951025 4.258 0.960116 3.73039L7.94954 0.235681ZM3.90655 6.16401C3.9019 6.16163 3.89724 6.15929 3.89254 6.15701L1.60038 5.01093L8.58981 1.51622L15.5792 5.01093L13.287 6.15707C13.2823 6.15931 13.2778 6.16161 13.2732 6.16395L8.58981 8.50564L3.90655 6.16401ZM3.57892 7.60087L1.60038 8.59014L3.89246 9.73617L3.90664 9.74326L8.58981 12.0848L13.2732 9.74318L13.287 9.73626L15.5792 8.59014L13.6007 7.60087L9.23008 9.78618C8.82702 9.98771 8.3526 9.98771 7.94954 9.78618L3.57892 7.60087ZM1.60038 12.1693L3.57892 11.1801L7.94954 13.3654C8.3526 13.5669 8.82702 13.5669 9.23008 13.3654L13.6007 11.1801L15.5792 12.1693L8.58981 15.6641L1.60038 12.1693Z" fill="#8A94A6"/>
                                                    </g>
                                                    <defs>
                                                    <clipPath id="clip0_517_1166">
                                                    <rect width="17.1802" height="17.1802" fill="white"/>
                                                    </clipPath>
                                                    </defs>
                                                </svg>
                                               <span> {{__('Course')}} {{$tutor_course_count->count()}} </span>
                                            </li>
                                            <li>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M5.89035 0.981725C3.17939 0.981725 0.981725 3.17939 0.981725 5.89035C0.981725 7.16364 1.46654 8.3237 2.26163 9.196C2.67034 8.3076 3.38394 7.61471 4.24934 7.22454C3.75002 6.77528 3.43604 6.12405 3.43604 5.39949C3.43604 4.04401 4.53487 2.94518 5.89035 2.94518C7.24583 2.94518 8.34466 4.04401 8.34466 5.39949C8.34466 6.11254 8.04058 6.75457 7.55502 7.20298C8.43552 7.56901 9.18412 8.23342 9.54281 9.16977C10.3238 8.30051 10.799 7.15092 10.799 5.89035C10.799 3.17939 8.60131 0.981725 5.89035 0.981725ZM9.69387 10.3882C10.9703 9.30774 11.7807 7.69368 11.7807 5.89035C11.7807 2.6372 9.1435 0 5.89035 0C2.6372 0 0 2.6372 0 5.89035C0 7.69366 0.810351 9.30769 2.08677 10.3882C2.12022 10.426 2.15982 10.459 2.20478 10.4855C3.21384 11.2959 4.49547 11.7807 5.89035 11.7807C7.28519 11.7807 8.5668 11.2959 9.57584 10.4856C9.62075 10.4591 9.66038 10.4261 9.69387 10.3882ZM8.74856 9.88145L8.66025 9.61652C8.29993 8.53556 7.14229 7.8538 5.89035 7.8538C4.6233 7.8538 3.49161 8.64647 3.0586 9.83723L3.04038 9.88735C3.84386 10.4613 4.82767 10.799 5.89035 10.799C6.95667 10.799 7.94357 10.459 8.74856 9.88145ZM5.89035 6.87208C6.70364 6.87208 7.36294 6.21278 7.36294 5.39949C7.36294 4.5862 6.70364 3.9269 5.89035 3.9269C5.07706 3.9269 4.41776 4.5862 4.41776 5.39949C4.41776 6.21278 5.07706 6.87208 5.89035 6.87208Z" fill="#8A94A6"/>
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
                            <div class="tab-content" id="tab-4">
                                <div class="user-review-content">
                                    <div class="review-rating add-rating">
                                        <span><b>{{$avg_rating}}</b>/5 </span>  <span> {{__('Rating')}}</span>
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
                                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10" fill="none">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M5.00021 0C4.44792 0 4.00021 0.447715 4.00021 1V4H1C0.447715 4 0 4.44772 0 5C0 5.55228 0.447716 6 1 6H4.00021L4.00021 9.00042C4.00021 9.55271 4.44792 10.0004 5.00021 10.0004C5.55249 10.0004 6.00021 9.5527 6.00021 9.00042L6.00021 6L9.00042 6C9.55271 6 10.0004 5.55228 10.0004 5C10.0004 4.44771 9.5527 4 9.00042 4L6.00021 4V1C6.00021 0.447715 5.55249 0 5.00021 0Z" fill="white"/>
                                                </svg>
                                            </a>
                                        @endif                                       
                                    </div>
                                    <div class="product-review">
                                        <ul>
                                            <li>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M9.57406 2.76901C10.3447 0.410328 13.6553 0.410334 14.4259 2.76901L15.8051 6.99008C15.8535 7.13813 15.9892 7.23936 16.1438 7.24271L20.4962 7.33707C22.8824 7.3888 23.9006 10.4175 22.0372 11.9204L18.4398 14.8219C18.3249 14.9146 18.2765 15.0678 18.317 15.2104L19.5954 19.7059C20.2665 22.0656 17.5912 23.9429 15.6282 22.4897L12.216 19.9637C12.0875 19.8686 11.9125 19.8686 11.784 19.9637L8.37177 22.4897C6.40879 23.9429 3.7335 22.0656 4.40456 19.7059L5.68299 15.2104C5.72354 15.0678 5.67513 14.9146 5.56022 14.8219L1.96284 11.9205C0.0993767 10.4175 1.1176 7.3888 3.50376 7.33707L7.85616 7.24271C8.01078 7.23936 8.14651 7.13813 8.19488 6.99008L9.57406 2.76901ZM12.3466 3.45914C12.2365 3.12218 11.7635 3.12218 11.6534 3.45914L10.2743 7.68021C9.93564 8.71654 8.98557 9.42515 7.90325 9.44862L3.55084 9.54298C3.20996 9.55037 3.0645 9.98303 3.33071 10.1977L6.92809 13.0992C7.73242 13.748 8.07131 14.8202 7.78748 15.8183L6.50904 20.3138C6.41318 20.6509 6.79536 20.9191 7.07579 20.7115L10.488 18.1854C11.3877 17.5194 12.6123 17.5194 13.512 18.1854L16.9242 20.7115C17.2046 20.9191 17.5868 20.6509 17.491 20.3138L16.2125 15.8183C15.9287 14.8202 16.2676 13.748 17.0719 13.0992L20.6693 10.1977C20.9355 9.98303 20.79 9.55037 20.4492 9.54298L16.0968 9.44862C15.0144 9.42515 14.0644 8.71654 13.7257 7.68021L12.3466 3.45914Z" fill="#8A94A6"/>
                                                </svg>
                                                <span>{{__('Reviews')}} {{$user_count}}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- edit rating -->
                                <div class="course-reviews-wrap">
                                    @if(!empty($course_ratings))
                                        @foreach($course_ratings as $c_rating_key => $course_rating)
                                            <div class="course-reviews">
                                                <div class="courser-top-content d-flex justify-content-between align-items-center">
                                                    <div class="course-review-name">
                                                        <h3> {{$course_rating->title}} </h3>
                                                    </div>  
                                                    <div class="course-review-star">
                                                        <span class="star-img">
                                                            @for($i =0;$i<5;$i++)
                                                                <i class="fas fa-star {{($course_rating->ratting > $i  ? 'text-warning' : '')}}"></i>
                                                            @endfor
                                                        </span>
                                                        @if(Auth::check())
                                                            <a href="#" class="action-item mr-2" data-size="lg" data-toggle="modal" data-url="{{route('rating.edit',$course_rating->id)}}" data-ajax-popup="true" data-title="{{__('Edit Rating')}}">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <a href="#" class="action-item mr-2 " data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').' | '.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$course_rating->id}}').submit();">
                                                                <i class="fas fa-trash"></i>
                                                            </a>
                                                            {!! Form::open(['method' => 'DELETE', 'route' => ['rating.destroy', $course_rating->id],'id'=>'delete-form-'.$course_rating->id]) !!}
                                                            {!! Form::close() !!}
                                                        @endif
                                                    </div>
                                                </div>
                                                <p>
                                                    {{$course_rating->description}}
                                                </p>
                                                <div class="course-review-profile">
                                                    <div class="course-review-profile-img">
                                                        <img src="{{asset('assets/img/user.png')}}" alt="user">
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
                            <div class="tab-content" id="tab-5">
                                @if(count($faqs) > 0  && !empty($faqs))
                                    @foreach($faqs as $q_i => $faq)
                                        <div class="set has-children">
                                            <a href="javascript:;" class="acnav-label" data-bs-target="#ChapterFour-{{$q_i}}">
                                                {{__('Question')}}:
                                                <b>{{$faq->question}}?</b>
                                            </a>
                                            <div id="ChapterFour-{{$q_i}}" class="acnav-list">
                                                <p>{{$faq->answer}}.
                                                </p>
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
                <div class="col-xl-4 col-lg-4 col-12 pdp-right-side">
                    <div class="pdp-summery">
                        <div class="pdp-summery-top">
                            <div class="pdp-top-content d-flex justify-content-between align-items-center">
                                <span class="badge">{{!empty($course->category_id)?$course->category_id->name:''}}</span>
                                <div class="review-rating add-rating">
                                    <span><b>{{$avg_tutor_rating}}</b>/5 </span>  <span>({{$tutor_count_rating}})</span>
                                    
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
                            </div>
                            <h4><a href="{{route('store.viewcourse',[$store->slug,\Illuminate\Support\Facades\Crypt::encrypt($course->id)])}}">{{$course->title}}</a></h4>
                            <div class="abt-user">
                                <div class="abt-usr-image">
                                    <img src="{{asset('assets/img/user.png')}}" alt="user">
                                </div>
                                <div class="user-info">
                                    <h4>
                                        {{$course->tutor_id->name}}
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <div class="pdp-summery-center">
                            <ul class="course-benefits">
                                <li>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M5.89035 0.981725C3.17939 0.981725 0.981725 3.17939 0.981725 5.89035C0.981725 7.16364 1.46654 8.3237 2.26163 9.196C2.67034 8.3076 3.38394 7.61471 4.24934 7.22454C3.75002 6.77528 3.43604 6.12405 3.43604 5.39949C3.43604 4.04401 4.53487 2.94518 5.89035 2.94518C7.24583 2.94518 8.34466 4.04401 8.34466 5.39949C8.34466 6.11254 8.04058 6.75457 7.55502 7.20298C8.43552 7.56901 9.18412 8.23342 9.54281 9.16977C10.3238 8.30051 10.799 7.15092 10.799 5.89035C10.799 3.17939 8.60131 0.981725 5.89035 0.981725ZM9.69387 10.3882C10.9703 9.30774 11.7807 7.69368 11.7807 5.89035C11.7807 2.6372 9.1435 0 5.89035 0C2.6372 0 0 2.6372 0 5.89035C0 7.69366 0.810351 9.30769 2.08677 10.3882C2.12022 10.426 2.15982 10.459 2.20478 10.4855C3.21384 11.2959 4.49547 11.7807 5.89035 11.7807C7.28519 11.7807 8.5668 11.2959 9.57584 10.4856C9.62075 10.4591 9.66038 10.4261 9.69387 10.3882ZM8.74856 9.88145L8.66025 9.61652C8.29993 8.53556 7.14229 7.8538 5.89035 7.8538C4.6233 7.8538 3.49161 8.64647 3.0586 9.83723L3.04038 9.88735C3.84386 10.4613 4.82767 10.799 5.89035 10.799C6.95667 10.799 7.94357 10.459 8.74856 9.88145ZM5.89035 6.87208C6.70364 6.87208 7.36294 6.21278 7.36294 5.39949C7.36294 4.5862 6.70364 3.9269 5.89035 3.9269C5.07706 3.9269 4.41776 4.5862 4.41776 5.39949C4.41776 6.21278 5.07706 6.87208 5.89035 6.87208Z" fill="#8A94A6"/>
                                    </svg>
                                    <span>{{__('Duration')}} <b>{{$course->duration}}</b></span>
                                </li>
                                <li>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M9.57406 2.76901C10.3447 0.410328 13.6553 0.410334 14.4259 2.76901L15.8051 6.99008C15.8535 7.13813 15.9892 7.23936 16.1438 7.24271L20.4962 7.33707C22.8824 7.3888 23.9006 10.4175 22.0372 11.9204L18.4398 14.8219C18.3249 14.9146 18.2765 15.0678 18.317 15.2104L19.5954 19.7059C20.2665 22.0656 17.5912 23.9429 15.6282 22.4897L12.216 19.9637C12.0875 19.8686 11.9125 19.8686 11.784 19.9637L8.37177 22.4897C6.40879 23.9429 3.7335 22.0656 4.40456 19.7059L5.68299 15.2104C5.72354 15.0678 5.67513 14.9146 5.56022 14.8219L1.96284 11.9205C0.0993767 10.4175 1.1176 7.3888 3.50376 7.33707L7.85616 7.24271C8.01078 7.23936 8.14651 7.13813 8.19488 6.99008L9.57406 2.76901ZM12.3466 3.45914C12.2365 3.12218 11.7635 3.12218 11.6534 3.45914L10.2743 7.68021C9.93564 8.71654 8.98557 9.42515 7.90325 9.44862L3.55084 9.54298C3.20996 9.55037 3.0645 9.98303 3.33071 10.1977L6.92809 13.0992C7.73242 13.748 8.07131 14.8202 7.78748 15.8183L6.50904 20.3138C6.41318 20.6509 6.79536 20.9191 7.07579 20.7115L10.488 18.1854C11.3877 17.5194 12.6123 17.5194 13.512 18.1854L16.9242 20.7115C17.2046 20.9191 17.5868 20.6509 17.491 20.3138L16.2125 15.8183C15.9287 14.8202 16.2676 13.748 17.0719 13.0992L20.6693 10.1977C20.9355 9.98303 20.79 9.55037 20.4492 9.54298L16.0968 9.44862C15.0144 9.42515 14.0644 8.71654 13.7257 7.68021L12.3466 3.45914Z" fill="#8A94A6"/>
                                    </svg>
                                    <span>{{__('Skill Level')}} <b>{{$course->level}} </b></span>
                                </li>
                                <li><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M5.89035 0.981725C3.17939 0.981725 0.981725 3.17939 0.981725 5.89035C0.981725 7.16364 1.46654 8.3237 2.26163 9.196C2.67034 8.3076 3.38394 7.61471 4.24934 7.22454C3.75002 6.77528 3.43604 6.12405 3.43604 5.39949C3.43604 4.04401 4.53487 2.94518 5.89035 2.94518C7.24583 2.94518 8.34466 4.04401 8.34466 5.39949C8.34466 6.11254 8.04058 6.75457 7.55502 7.20298C8.43552 7.56901 9.18412 8.23342 9.54281 9.16977C10.3238 8.30051 10.799 7.15092 10.799 5.89035C10.799 3.17939 8.60131 0.981725 5.89035 0.981725ZM9.69387 10.3882C10.9703 9.30774 11.7807 7.69368 11.7807 5.89035C11.7807 2.6372 9.1435 0 5.89035 0C2.6372 0 0 2.6372 0 5.89035C0 7.69366 0.810351 9.30769 2.08677 10.3882C2.12022 10.426 2.15982 10.459 2.20478 10.4855C3.21384 11.2959 4.49547 11.7807 5.89035 11.7807C7.28519 11.7807 8.5668 11.2959 9.57584 10.4856C9.62075 10.4591 9.66038 10.4261 9.69387 10.3882ZM8.74856 9.88145L8.66025 9.61652C8.29993 8.53556 7.14229 7.8538 5.89035 7.8538C4.6233 7.8538 3.49161 8.64647 3.0586 9.83723L3.04038 9.88735C3.84386 10.4613 4.82767 10.799 5.89035 10.799C6.95667 10.799 7.94357 10.459 8.74856 9.88145ZM5.89035 6.87208C6.70364 6.87208 7.36294 6.21278 7.36294 5.39949C7.36294 4.5862 6.70364 3.9269 5.89035 3.9269C5.07706 3.9269 4.41776 4.5862 4.41776 5.39949C4.41776 6.21278 5.07706 6.87208 5.89035 6.87208Z" fill="#8A94A6"/>
                                    </svg>
                                    <span>{{__('Total Enrolled')}} <b> {{$course->student_count->count()}} </b></span>
                                </li>
                                <li>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                        <g clip-path="url(#clip0_521_1166)">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.94979 0.985742C8.35284 0.784213 8.82726 0.784212 9.23032 0.985741L16.2197 4.48045C17.275 5.00806 17.275 6.51392 16.2197 7.04152L15.2016 7.55059L16.2197 8.05966C17.275 8.58727 17.275 10.0931 16.2197 10.6207L15.2016 11.1298L16.2197 11.6389C17.275 12.1665 17.275 13.6723 16.2197 14.1999L9.23032 17.6947C8.82726 17.8962 8.35284 17.8962 7.94979 17.6947L0.96036 14.1999C-0.0948598 13.6723 -0.0948586 12.1665 0.960359 11.6389L1.97849 11.1298L0.96036 10.6207C-0.0948598 10.0931 -0.0948586 8.58727 0.960359 8.05966L1.9785 7.55059L0.960361 7.04153C-0.094861 6.51392 -0.0948584 5.00806 0.96036 4.48045L7.94979 0.985742ZM3.90679 6.91407C3.90215 6.91169 3.89748 6.90935 3.89279 6.90707L1.60063 5.76099L8.59005 2.26628L15.5795 5.76099L13.2872 6.90713C13.2826 6.90937 13.278 6.91167 13.2734 6.91401L8.59005 9.2557L3.90679 6.91407ZM3.57917 8.35093L1.60063 9.3402L3.8927 10.4862L3.90688 10.4933L8.59005 12.8349L13.2734 10.4932L13.2872 10.4863L15.5795 9.3402L13.6009 8.35093L9.23032 10.5362C8.82726 10.7378 8.35284 10.7378 7.94979 10.5362L3.57917 8.35093ZM1.60063 12.9194L3.57916 11.9301L7.94979 14.1154C8.35284 14.317 8.82726 14.317 9.23032 14.1154L13.6009 11.9301L15.5795 12.9194L8.59005 16.4141L1.60063 12.9194Z" fill="#8A94A6"/>
                                        </g>
                                        <defs>
                                        <clipPath id="clip0_521_1166">
                                        <rect width="17.1802" height="17.1802" fill="white" transform="translate(0 0.75)"/>
                                        </clipPath>
                                        </defs>
                                    </svg>
                                    <span>  {{__('Chapters')}} <b> {{$course->chapter_count->count()}} </b></span>
                                </li>
                                <li>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M15.1616 4.61675L9 7.41029L2.83841 4.61675L9 1.5L15.1616 4.61675ZM15.75 5.99694L9.75 8.71722V12.939V15.019V15.652V15.7515V15.7568V15.7621V15.7672V15.7723V15.7772V15.782V15.7867V15.7914V15.7959V15.8003V15.8046V15.8087V15.8128V15.8168V15.8206V15.8244V15.8693V15.8807V15.8816V15.8824V15.883V15.8836V15.884V15.8843V15.8845L9 15.8845L8.25 15.8845V15.8843V15.884V15.8836V15.883V15.8824V15.8816V15.8807V15.8797V15.8785V15.8773V15.8759V15.8744V15.8728V15.8711V15.8693V15.8244V15.8206V15.8168V15.8128V15.8087V15.8046V15.8003V15.7959V15.7914V15.7867V15.782V15.7772V15.7723V15.7672V15.7621V15.7568V15.7515V15.746V15.7405V15.7291V15.7172V15.7112V15.705V15.6987V15.6923V15.6793V15.6726V15.6659V15.652V15.019V12.939V8.71722L2.25 5.99694V13.0855L8.29601 16.1438C8.26625 16.063 8.25 15.9757 8.25 15.8845H9H9.75C9.75 15.9757 9.73375 16.063 9.70399 16.1438L15.75 13.0855V5.99694ZM1.57294 3.57589C1.0682 3.83121 0.75 4.34875 0.75 4.91439V13.0855C0.75 13.6511 1.0682 14.1687 1.57294 14.424L8.32294 17.8384C8.74863 18.0537 9.25137 18.0537 9.67706 17.8384L16.4271 14.424C16.9318 14.1687 17.25 13.6511 17.25 13.0855V4.91439C17.25 4.34875 16.9318 3.83121 16.4271 3.57589L9.67706 0.161498C9.25137 -0.0538329 8.74863 -0.0538328 8.32294 0.161499L1.57294 3.57589Z" fill="#8A94A6"/>
                                        </svg>
                                        <span>{{__('Language')}}  <b> {{$course->lang}}  </b></span>
                                </li>
                            </ul>
                        </div>
                        <div class="pdp-summery-bottom">
                            <div class="price">
                                <ins> <span class="currency-type">{{ ($course->is_free == 'on')? 'Free' : Utility::priceFormat($course->price)}}</span></ins>
                            </div>
                            <div class="wish-cart d-flex align-items-center">
                               
                                @if(Auth::guard('students')->check())
                                    @if(in_array($course->id,Auth::guard('students')->user()->purchasedCourse()))
                                        <div class="price">
                                        </div>
                                        <div class="add-cart">
                                            <a class="btn btn-secondary" href="{{route('store.fullscreen',[$store->slug,Crypt::encrypt($course->id),''])}}">
                                                {{__('Start Watching')}}
                                            </a>
                                        </div>
                                    @else
                                        <a class="add_to_cart" data-id="{{$course->id}}">
                                            @if($key !== false)
                                                <b id="cart-btn-{{$course->id}}" class="btn btn-secondary">{{__('Already in Cart')}}</b>
                                            @else
                                                <b id="cart-btn-{{$course->id}}" class="btn btn-secondary">{{__('Add in Cart')}}</b>
                                            @endif
                                        </a>
                                    @endif
                                @else
                                    <a class="add_to_cart" data-id="{{$course->id}}">
                                        @if($key !== false)
                                            <b id="cart-btn-{{$course->id}}" class="btn btn-secondary">{{__('Already in Cart')}}</b>
                                        @else
                                            <b id="cart-btn-{{$course->id}}" class="btn btn-secondary">{{__('Add in Cart')}}</b>
                                        @endif
                                    </a>
                                @endif

                                @if(Auth::guard('students')->check())                                
                                    @if(sizeof($course->student_wl)>0)
                                        @foreach($course->student_wl as $student_wl)
                                        <a href="#" class="wishlist-btn add_to_wishlist custom_wish fygyfg_{{$course->id}} active" data-id-2="{{$course->id}}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="14" viewBox="0 0 17 14" fill="none">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M9.18991 3.10164C8.89678 3.37992 8.43395 3.37992 8.14082 3.10164L7.61627 2.60366C7.00231 2.0208 6.17289 1.66491 5.25627 1.66491C3.37348 1.66491 1.84718 3.17483 1.84718 5.03741C1.84718 6.82306 2.82429 8.29753 4.23488 9.50902C5.64667 10.7215 7.33461 11.5257 8.34313 11.9361C8.554 12.0219 8.77673 12.0219 8.9876 11.9361C9.99612 11.5257 11.6841 10.7215 13.0959 9.50901C14.5064 8.29753 15.4835 6.82305 15.4835 5.03741C15.4835 3.17483 13.9572 1.66491 12.0745 1.66491C11.1578 1.66491 10.3284 2.0208 9.71446 2.60366L9.18991 3.10164ZM8.66537 1.52219C7.7806 0.682237 6.57937 0.166016 5.25627 0.166016C2.53669 0.166016 0.332031 2.34701 0.332031 5.03741C0.332031 9.81007 5.61259 12.4457 7.76672 13.3223C8.34685 13.5584 8.98388 13.5584 9.56401 13.3223C11.7181 12.4457 16.9987 9.81006 16.9987 5.03741C16.9987 2.34701 14.794 0.166016 12.0745 0.166016C10.7514 0.166016 9.55013 0.682237 8.66537 1.52219Z" fill="white"></path>
                                            </svg></a>
                                        @endforeach
                                    @else
                                        <a href="#" class="wishlist-btn add_to_wishlist custom_wish fygyfg_{{$course->id}}" data-id-2="{{$course->id}}"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="14" viewBox="0 0 17 14" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9.18991 3.10164C8.89678 3.37992 8.43395 3.37992 8.14082 3.10164L7.61627 2.60366C7.00231 2.0208 6.17289 1.66491 5.25627 1.66491C3.37348 1.66491 1.84718 3.17483 1.84718 5.03741C1.84718 6.82306 2.82429 8.29753 4.23488 9.50902C5.64667 10.7215 7.33461 11.5257 8.34313 11.9361C8.554 12.0219 8.77673 12.0219 8.9876 11.9361C9.99612 11.5257 11.6841 10.7215 13.0959 9.50901C14.5064 8.29753 15.4835 6.82305 15.4835 5.03741C15.4835 3.17483 13.9572 1.66491 12.0745 1.66491C11.1578 1.66491 10.3284 2.0208 9.71446 2.60366L9.18991 3.10164ZM8.66537 1.52219C7.7806 0.682237 6.57937 0.166016 5.25627 0.166016C2.53669 0.166016 0.332031 2.34701 0.332031 5.03741C0.332031 9.81007 5.61259 12.4457 7.76672 13.3223C8.34685 13.5584 8.98388 13.5584 9.56401 13.3223C11.7181 12.4457 16.9987 9.81006 16.9987 5.03741C16.9987 2.34701 14.794 0.166016 12.0745 0.166016C10.7514 0.166016 9.55013 0.682237 8.66537 1.52219Z" fill="white"></path>
                                        </svg></a>
                                    @endif
                                @else
                                    <a href="#" class="wishlist-btn add_to_wishlist custom_wish fygyfg_{{$course->id}}" data-id-2="{{$course->id}}"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="14" viewBox="0 0 17 14" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M9.18991 3.10164C8.89678 3.37992 8.43395 3.37992 8.14082 3.10164L7.61627 2.60366C7.00231 2.0208 6.17289 1.66491 5.25627 1.66491C3.37348 1.66491 1.84718 3.17483 1.84718 5.03741C1.84718 6.82306 2.82429 8.29753 4.23488 9.50902C5.64667 10.7215 7.33461 11.5257 8.34313 11.9361C8.554 12.0219 8.77673 12.0219 8.9876 11.9361C9.99612 11.5257 11.6841 10.7215 13.0959 9.50901C14.5064 8.29753 15.4835 6.82305 15.4835 5.03741C15.4835 3.17483 13.9572 1.66491 12.0745 1.66491C11.1578 1.66491 10.3284 2.0208 9.71446 2.60366L9.18991 3.10164ZM8.66537 1.52219C7.7806 0.682237 6.57937 0.166016 5.25627 0.166016C2.53669 0.166016 0.332031 2.34701 0.332031 5.03741C0.332031 9.81007 5.61259 12.4457 7.76672 13.3223C8.34685 13.5584 8.98388 13.5584 9.56401 13.3223C11.7181 12.4457 16.9987 9.81006 16.9987 5.03741C16.9987 2.34701 14.794 0.166016 12.0745 0.166016C10.7514 0.166016 9.55013 0.682237 8.66537 1.52219Z" fill="white"></path>
                                    </svg></a>
                                @endif
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($more_by_category->count()>0)
        <section class="more-from-tutor padding-bottom">
            <div class="container">
                <div class="section-title d-flex align-items-center justify-content-between">
                    {{-- <h2>{{__('More from')}}  <br> <b>@ {{!empty($category_name)?$category_name->name:''}} </b></h2> --}}
                    <h2>{{__('More from')}} <b> {{!empty($category_name)?$category_name->name:''}} </b> {{__('category')}} </h2>
                    {{-- <h2>{{__('More from')}} <span> {{!empty($category_name)?$category_name->name:''}} </span> {{__('category')}} </h2> --}}

                    <a href="{{route('store.search',[$store->slug])}}" class="btn"> {{__('Learn More')}} </a>
                </div>
                <div class="more-course-slider slider-comman">
                    @foreach($more_by_category as $course_by_cat)
                        <div class="course-widget">
                            <div class="course-widget-inner">
                                <div class="course-media">
                                    <a href="{{route('store.viewcourse',[$store->slug,\Illuminate\Support\Facades\Crypt::encrypt($course_by_cat->id)])}}">
                                        @if(!empty($course_by_cat->thumbnail))
                                            <img src="{{asset(Storage::url('uploads/thumbnail/'.$course_by_cat->thumbnail))}}" alt="card">
                                        @else
                                            <img src="{{asset('assets/img/card-img.svg')}}" alt="card" class="img-fluid">
                                        @endif
                                    </a>
                                </div>

                                <div class="course-caption">
                                    <div class="course-caption-top d-flex align-items-center">
                                        <span class="badge">{{ !empty($course->category_id->name) ? $course->category_id->name : ''}}</span>

                                        @if(Auth::guard('students')->check())
                                            @if(sizeof($course_by_cat->student_wl)>0)
                                                {{-- @foreach($course_by_cat->student_wl as $student_wl) --}}
                                                    <a href="#" class="wishlist-btn wishlist_btn add_to_wishlist" data-id="{{$course_by_cat->id}}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="14"
                                                            viewBox="0 0 17 14" fill="none">
                                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                                d="M9.18991 3.10164C8.89678 3.37992 8.43395 3.37992 8.14082 3.10164L7.61627 2.60366C7.00231 2.0208 6.17289 1.66491 5.25627 1.66491C3.37348 1.66491 1.84718 3.17483 1.84718 5.03741C1.84718 6.82306 2.82429 8.29753 4.23488 9.50902C5.64667 10.7215 7.33461 11.5257 8.34313 11.9361C8.554 12.0219 8.77673 12.0219 8.9876 11.9361C9.99612 11.5257 11.6841 10.7215 13.0959 9.50901C14.5064 8.29753 15.4835 6.82305 15.4835 5.03741C15.4835 3.17483 13.9572 1.66491 12.0745 1.66491C11.1578 1.66491 10.3284 2.0208 9.71446 2.60366L9.18991 3.10164ZM8.66537 1.52219C7.7806 0.682237 6.57937 0.166016 5.25627 0.166016C2.53669 0.166016 0.332031 2.34701 0.332031 5.03741C0.332031 9.81007 5.61259 12.4457 7.76672 13.3223C8.34685 13.5584 8.98388 13.5584 9.56401 13.3223C11.7181 12.4457 16.9987 9.81006 16.9987 5.03741C16.9987 2.34701 14.794 0.166016 12.0745 0.166016C10.7514 0.166016 9.55013 0.682237 8.66537 1.52219Z"
                                                                fill="white"></path>
                                                        </svg>
                                                    </a>
                                                {{-- @endforeach --}}
                                            @else
                                                <a href="#" class="wishlist-btn add_to_wishlist" data-id="{{$course_by_cat->id}}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="14"
                                                        viewBox="0 0 17 14" fill="none">
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M9.18991 3.10164C8.89678 3.37992 8.43395 3.37992 8.14082 3.10164L7.61627 2.60366C7.00231 2.0208 6.17289 1.66491 5.25627 1.66491C3.37348 1.66491 1.84718 3.17483 1.84718 5.03741C1.84718 6.82306 2.82429 8.29753 4.23488 9.50902C5.64667 10.7215 7.33461 11.5257 8.34313 11.9361C8.554 12.0219 8.77673 12.0219 8.9876 11.9361C9.99612 11.5257 11.6841 10.7215 13.0959 9.50901C14.5064 8.29753 15.4835 6.82305 15.4835 5.03741C15.4835 3.17483 13.9572 1.66491 12.0745 1.66491C11.1578 1.66491 10.3284 2.0208 9.71446 2.60366L9.18991 3.10164ZM8.66537 1.52219C7.7806 0.682237 6.57937 0.166016 5.25627 0.166016C2.53669 0.166016 0.332031 2.34701 0.332031 5.03741C0.332031 9.81007 5.61259 12.4457 7.76672 13.3223C8.34685 13.5584 8.98388 13.5584 9.56401 13.3223C11.7181 12.4457 16.9987 9.81006 16.9987 5.03741C16.9987 2.34701 14.794 0.166016 12.0745 0.166016C10.7514 0.166016 9.55013 0.682237 8.66537 1.52219Z"
                                                            fill="white"></path>
                                                    </svg>
                                                </a>
                                            @endif
                                        @else
                                            <a href="#" class="wishlist-btn add_to_wishlist" data-id="{{$course_by_cat->id}}">
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
                                        <a href="{{route('store.viewcourse',[$store->slug,\Illuminate\Support\Facades\Crypt::encrypt($course_by_cat->id)])}}">{{$course_by_cat->title}}</a>
                                    </h6>
                                    <div class="review-star d-flex align-items-center">
                                        @if($store->enable_rating == 'on')
                                            @for($i =1;$i<=5;$i++)
                                                @php
                                                    $icon = 'fa-star';
                                                    $color = '';
                                                    $newVal1 = ($i-0.5);
                                                    if($course_by_cat->course_rating() < $i && $course_by_cat->course_rating() >= $newVal1)
                                                    {
                                                        $icon = 'fa-star-half-alt';
                                                    }
                                                    if($course_by_cat->course_rating() >= $newVal1)
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
                                                </svg>{{$course_by_cat->student_count->count()}} &nbsp; <span>{{__('Students')}}</span></p>
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
                                                </svg> {{$course_by_cat->chapter_count->count()}} &nbsp; <span>{{__('Chapters')}}</span></p>
                                            <p>{{$course_by_cat->level}}</p>
                                        </div>
                                    </div>
                                    <div class="price-addtocart d-flex align-items-center justify-content-between">
                                        <div class="price">
                                            <ins>
                                                <span class="currency-type">
                                                    @if($course_by_cat->has_discount == 'on')
                                                        {{ ($course_by_cat->is_free == 'on')? __('Free') : Utility::priceFormat($course_by_cat->price)}}
                                                        <small>
                                                            <del>{{ Utility::priceFormat( $course_by_cat->discount)}}</del>
                                                        </small>
                                                    @else
                                                        {{ ($course_by_cat->is_free == 'on')? __('Free') : Utility::priceFormat($course_by_cat->price)}}
                                                    @endif
                                                </span>
                                            </ins>
                                        </div>
                                        {{-- <a href="#" class="btn">Add To Cart</a> --}}
                                        @if(Auth::guard('students')->check())
                                            @if(in_array($course_by_cat->id,Auth::guard('students')->user()->purchasedCourse()))
                                                <a class="btn" href="{{route('store.fullscreen',[$store->slug,Crypt::encrypt($course_by_cat->id),''])}}">
                                                    {{__('Start Watching')}}
                                                </a>
                                            @else
                                                <a class="add_to_cart" data-id="{{$course_by_cat->id}}">
                                                    @if($key !== false)
                                                        <b id="cart-btn-{{$course_by_cat->id}}" class="btn btn-secondary">{{__('Already in Cart')}}</b>
                                                    @else
                                                        <b id="cart-btn-{{$course_by_cat->id}}" class="btn btn-secondary">{{__('Add in Cart')}}</b>
                                                    @endif
                                                </a>
                                            @endif
                                        @else
                                            <a class="add_to_cart" data-id="{{$course->id}}">
                                                @if($key !== false)
                                                    <b id="cart-btn-{{$course_by_cat->id}}" class="btn btn-secondary">{{__('Already in Cart')}}</b>
                                                @else
                                                    <b id="cart-btn-{{$course_by_cat->id}}" class="btn btn-secondary">{{__('Add in Cart')}}</b>
                                                @endif
                                            </a>
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
