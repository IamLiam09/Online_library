<!DOCTYPE html>
<html lang="en" dir="{{env('SITE_RTL') == 'on'?'rtl':''}}">
@php
    $userstore = \App\Models\UserStore::where('store_id', $store->id)->first();
    $settings   =\DB::table('settings')->where('name','company_favicon')->where('created_by', $userstore->user_id)->first();
@endphp
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="LMSGo - Learning Management System">
    <meta name="author" content="Rajodiya Infotech">

    <title> @yield('page-title') - {{($store->tagline) ?  $store->tagline : config('APP_NAME', ucfirst($store->name))}} </title>

    <!-- Favicon -->
    <link rel="icon" href="{{asset(Storage::url('uploads/logo/').(!empty($settings->value)?$settings->value:'favicon.png'))}}" type="image/png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Font Awesome 5 -->
    <link rel="stylesheet" href="{{asset('libs/@fortawesome/fontawesome-free/css/all.min.css')}}"><!-- Page CSS -->
    <link rel="stylesheet" href="{{asset('libs/animate.css/animate.min.css')}}">
    <link rel="stylesheet" href="{{asset('libs/swiper/dist/css/swiper.min.css')}}">
    <link rel="stylesheet" href="{{asset('libs/animate.css/animate.min.css')}}">
    <!-- site CSS -->
    <link rel="stylesheet" href="{{asset('assets/css/site.css')}}" id="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css')}}" id="stylesheet')}}">
    <script type="text/javascript" src="{{asset('libs/jquery/dist/jquery.min.js')}}"></script>
    @if(env('SITE_RTL')=='on')
        <link rel="stylesheet" href="{{ asset('css/bootstrap-rtl.css') }}">
    @endif
    <style>
        @stack('css-page')
            @media (min-width: 768px) {
            .header-account-page {
                height: 200px;
            }
        }
    </style>

</head>
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
<header class="header " id="header-main">
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
    <div id="navbar-top-main" class="navbar-top navbar-dark bg-dark border-bottom">
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
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="main-content">
    <header class="pt-3 d-flex align-items-end">
        <!-- Header container -->
        <div class="container">
            <div class="row">
                <div class=" col-lg-12">
                    <!-- Salute + Small stats -->
                    <div class="row align-items-center mb-4 ">
                        <div class="col-md-5 mb-4 mb-md-0">
                            <span class="h2 mb-0 text-dark d-block">{{__('My Cart')}}</span>
                            <span class="text-dark">{{__('Have a nice shopping')}}!</span>
                        </div>
                    </div>
                    <!-- Account navigation -->
                    <div class="d-flex">
                        <div class="btn-group btn-group-nav shadow" role="group" aria-label="Basic example">
                            <div class="btn-group" role="group">
                                <a href="{{route('store.cart',$store->slug)}}" class="btn btn-dark btn-icon border_r {{ (Request::segment(3) == 'cart')? 'active' :'' }}">
                                    <span class="btn-inner--icon"><i class="fas fa-shopping-cart"></i></span>
                                    <span class="btn-inner--text d-none d-md-inline-block">{{__('Cart')}}</span>
                                </a>
                                <a href="{{route('user-address.useraddress',$store->slug)}}" class="btn btn-dark btn-icon border_r {{ (Request::segment(3) == 'useraddress')? 'active' :'' }}">
                                    <span class="btn-inner--icon"><i class="fas fa-user"></i></span>
                                    <span class="btn-inner--text d-none d-md-inline-block">{{__('Customer')}}</span>
                                </a>
                                <a href="{{route('store-payment.payment',$store->slug)}}" class="btn btn-dark btn-icon border_r {{ (Request::segment(3) == 'userpayment')? 'active' :'' }}">
                                    <span class="btn-inner--icon"><i class="fas fa-credit-card"></i></span>
                                    <span class="btn-inner--text d-none d-md-inline-block">{{__('Payment')}}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <section class="slice slice-lg">
        @yield('content')
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
                                <a class="nav-link pl-0" href="mailto:{{$store->email}}" target="_blank">
                                    <i class="fas fa-envelope"></i>
                                </a>
                            </li>
                        @endif
                        @if(!empty($store->whatsapp))
                            <li class="nav-item">
                                <a class="nav-link" href="https://wa.me/{{$store->whatsapp}}" target=”_blank”>
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
                        @if(!empty($page_slug_url))
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
{{-- <div class="modal fade" id="commonModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div>
                <h4 class="h4 font-weight-400 float-left modal-title" id="exampleModalLabel"></h4>
                <a href="#" class="more-text widget-text float-right close-icon" data-dismiss="modal" aria-label="Close">{{__('Close')}}</a>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div> --}}

<div class="modal-popup" id="commonModalBlur" role="dialog">
    <div class="modal-dialog-inner lg-dialog" role="document">
        <div class="popup-content">
            <div class="popup-header">
                <h4 class="modal-title profile-heading" id="modelCommanModelLabel"></h4>
                <div class="close-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 50 50" fill="none">
                        <path d="M27.7618 25.0008L49.4275 3.33503C50.1903 2.57224 50.1903 1.33552 49.4275 0.572826C48.6647 -0.189868 47.428 -0.189965 46.6653 0.572826L24.9995 22.2386L3.33381 0.572826C2.57102 -0.189965 1.3343 -0.189965 0.571605 0.572826C-0.191089 1.33562 -0.191186 2.57233 0.571605 3.33503L22.2373 25.0007L0.571605 46.6665C-0.191186 47.4293 -0.191186 48.666 0.571605 49.4287C0.952952 49.81 1.45285 50.0007 1.95275 50.0007C2.45266 50.0007 2.95246 49.81 3.3339 49.4287L24.9995 27.763L46.6652 49.4287C47.0465 49.81 47.5464 50.0007 48.0463 50.0007C48.5462 50.0007 49.046 49.81 49.4275 49.4287C50.1903 48.6659 50.1903 47.4292 49.4275 46.6665L27.7618 25.0008Z"
                            fill="white"></path>
                    </svg>
                </div>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<!-- Core JS - includes jquery, bootstrap, popper, in-view and sticky-kit -->
{{-- <script src="{{asset('js/site.core.js')}}"></script> --}}
<!-- notify -->
<script type="text/javascript" src="{{ asset('js/custom.js')}}"></script>

<script src="{{ asset('libs/bootstrap-notify/bootstrap-notify.min.js')}}"></script>
<!-- Page JS -->
<script src="{{asset('libs/swiper/dist/js/swiper.min.js')}}"></script>
<!-- site JS -->
{{-- <script src="{{asset('js/site.js')}}"></script> --}}
<!-- Demo JS - remove it when starting your project -->
{{-- <script src="{{asset('assets/js/demo.js')}}"></script> --}}

<script src="{{ asset('assets/themes/theme3/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/themes/theme3/js/slick.min.js') }}"></script>
<script src="{{ asset('assets/themes/theme3/js/custom.js') }}"></script>

@php
    $store_settings = \App\Models\Store::where('slug',$store->slug)->first();
@endphp

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id={{$store_settings->google_analytic}}"></script>
{!! $store_settings->storejs !!}
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }

    gtag('js', new Date());
    gtag('config', '{{ !empty($store_settings->google_analytic) }}');
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
@stack('script-page')
</body>

</html>

