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
   
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
        <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('libs/@fortawesome/fontawesome-free/css/all.min.css')}}">
    {{-- <link rel="stylesheet" href="{{asset('./css/main-style.css')}}"> --}}
    <link rel="stylesheet" href="{{asset('assets/css/yellow-style.css')}}" id="stylesheet">   
    <link rel="stylesheet" href="{{asset('css/moovie.css')}}">

    @if(!empty($store->store_theme))
        <link rel="stylesheet" href="{{asset('assets/css/'.$store->store_theme)}}" id="stylesheet">
    @else
        <link rel="stylesheet" href="{{asset('assets/css/yellow-style.css')}}" id="stylesheet">
    @endif
    <link rel="stylesheet" href="{{asset('./css/responsive.css')}}">

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
    {{-- @dd($getStoreThemeSetting[6]) --}}
    @if(!empty($getStoreThemeSetting[6]) && $getStoreThemeSetting[6]['section_enable'] == 'on')
        <div class="footer footer-dark pt-4 pb-2">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="copyright text-sm font-weight-bold text-center text-md-left rtl-right pt-1">
                            {{$store->footer_note}}
                        </div>
                        {{-- <ul class="nav mt-3 mt-md-0">
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
                        </ul> --}}

                      
                        <ul class="nav mt-3 mt-md-0">
                            @for ($i = 0 ;  $i < $getStoreThemeSetting[8]['loop_number'] ; $i++)
                                @php 
                                    $icon = '';
                                    $link = '#';
                                @endphp
                                @foreach ($getStoreThemeSetting[8]['inner-list'] as $item_key => $item)
                                    @php
                                        if ($item_key == 0) {
                                            if(!empty($getStoreThemeSetting[8][$item['field_slug']])) {
                                                $icon = $getStoreThemeSetting[8][$item['field_slug']][$i];
                                            } else {
                                                $icon = $item['field_default_text'];
                                            }
                                        }
                                            
                                        if ($item_key == 1) {
                                            if(!empty($getStoreThemeSetting[8][$item['field_slug']])) {
                                                $link = $getStoreThemeSetting[8][$item['field_slug']][$i];
                                            } else {
                                                $link = $item['field_default_text'];
                                            }                                            
                                        }
                                    @endphp
                                @endforeach                                        
                                <li>
                                    <a href="{{ $link }}" target="_blank">
                                        {!! $icon !!}
                                    </a>
                                </li>
                            @endfor
                        </ul>   
                        <ul class="nav mt-3 mt-md-0">
                            @for($i = 0; $i< $getStoreThemeSetting[8]['loop_number']; $i++)
                                @php
                                    $icon = '';
                                    $link = '#';
                                @endphp
                                @foreach ($getStoreThemeSetting[8]['inner-list'] as )
                                    
                                @endforeach
                            @endfor
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
    @endif
</footer>



{{-- @if(!empty($getStoreThemeSetting[6]) && $getStoreThemeSetting[6]['section_enable'] == 'on')        
        <footer class="site-footer">
            <div class="container {{($demoStoreThemeSetting['enable_footer_note'] != 'on')?'pt-1':'' }}">                
                <div class="footer-row">                    
                    @if($demoStoreThemeSetting['enable_footer_note'] == 'on')                    
                        <div class="footer-logo footer-col">
                            <a href="{{route('store.slug',$store->slug)}}">
                                @if(!empty($getStoreThemeSetting[6]['inner-list'][0]['field_default_text']))
                                    <img src="{{asset(Storage::url('uploads/'.$getStoreThemeSetting[6]['inner-list'][0]['field_default_text']))}}" alt="lmsgo-logo">                             
                                @else                                
                                    <img src="{{asset(Storage::url('uploads/theme1/footer/lmsgo-logo.svg'))}}" alt="Footer logo">
                                @endif
                            </a>
                        </div>
                        <div class="footer-col footer-link footer-link-1">
                            <div class="footer-widget">
                                <ul>
                                    @for ($i = 0 ;  $i < $getStoreThemeSetting[7]['loop_number'] ; $i++)
                                        @php 
                                            $a = '#';
                                            $footer1_name = '';
                                        @endphp
                                        @foreach ($getStoreThemeSetting[7]['inner-list'] as $item_key => $item)
                                            @php
                                            $footer1_name = '';
                                            $a = '';

                                            if ($item_key == 0) {
                                                if(!empty($getStoreThemeSetting[7][$item['field_slug']])) {
                                                    $footer1_name = $getStoreThemeSetting[7][$item['field_slug']][$i];
                                                } else {
                                                    $footer1_name = $item['field_default_text'];
                                                }
                                            }
                                                
                                            if ($item_key == 1) {
                                                if(!empty($getStoreThemeSetting[7][$item['field_slug']])) {
                                                    $a = $getStoreThemeSetting[7][$item['field_slug']][$i];
                                                } else {
                                                    $a = $item['field_default_text'];
                                                }                                            
                                            }
                                            @endphp
                                            <li>
                                                <a href="{{$a}}">{{ucfirst($footer1_name)}}</a>                                                
                                            </li>
                                        @endforeach
                                    @endfor
                                </ul>
                            </div>
                        </div>
                        <div class="footer-col footer-link footer-link-2 delimiter-top mt-2">
                            <div class="footer-widget">
                                <ul class="share-links">
                                    @for ($i = 0 ;  $i < $getStoreThemeSetting[8]['loop_number'] ; $i++)
                                        @php 
                                            $icon = '';
                                            $link = '#';
                                        @endphp
                                        @foreach ($getStoreThemeSetting[8]['inner-list'] as $item_key => $item)
                                            @php
                                                if ($item_key == 0) {
                                                    if(!empty($getStoreThemeSetting[8][$item['field_slug']])) {
                                                        $icon = $getStoreThemeSetting[8][$item['field_slug']][$i];
                                                    } else {
                                                        $icon = $item['field_default_text'];
                                                    }
                                                }
                                                    
                                                if ($item_key == 1) {
                                                    if(!empty($getStoreThemeSetting[8][$item['field_slug']])) {
                                                        $link = $getStoreThemeSetting[8][$item['field_slug']][$i];
                                                    } else {
                                                        $link = $item['field_default_text'];
                                                    }                                            
                                                }
                                            @endphp
                                        @endforeach                                        
                                        <li>
                                            <a href="{{ $link }}" target="_blank">
                                                {!! $icon !!}
                                            </a>
                                        </li>
                                    @endfor
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="footer-bottom">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            
                            <p>{{$store->footer_note}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    @endif --}}





<script src="{{ asset('libs/frontjs/jquery/jquery.min.js')}}"></script>
<script src="{{ asset('libs/frontjs/bootstrap/bootstrap.min.js')}}"></script>  

<script src="{{ asset('js/slick.min.js')}}" defer="defer"></script>
<script src="{{ asset('libs/bootstrap-notify/bootstrap-notify.min.js')}}"></script>
<script src="{{ asset('js/lmscustom.js')}}" defer="defer"></script>
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

