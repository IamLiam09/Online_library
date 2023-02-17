
@php
    // $logo=asset(Storage::url('uploads/logo/'));
    $logo = \App\Models\Utility::get_file('uploads/logo/');
    $favicon= Utility::getValByName('company_favicon');
    $company_logo = \App\Models\Utility::GetLogo();
    $setting = App\Models\Utility::colorset();
@endphp
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="LMSGo - Learning Management System">
    <meta name="author" content="Rajodiya Infotech">

    <title>@yield('page-title') - {{( Utility::getValByName('title_text')) ? Utility::getValByName('title_text') : config('app.name', 'LMSGo')}}</title>
    <link rel="icon" href="{{$logo.'/'.(isset($favicon) && !empty($favicon)?$favicon:'favicon.png')}}" type="image" sizes="16x16">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- font css -->
    <link rel="stylesheet" href="{{asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{asset('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{asset('assets/fonts/material.css') }}">

    <!-- vendor css -->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/style.css') }}"> 
    <link rel="stylesheet" href="{{ asset('assets/css/landing.css')}}">
   
    @stack('css-page')

    <!-- vendor css -->
    @if ($setting['SITE_RTL'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}">
    @endif
    @if( isset($setting['cust_darklayout']) && $setting['cust_darklayout'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @endif


    <!-- switch button -->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/bootstrap-switch-button.min.css') }}">

    <link rel="stylesheet" href="{{ asset('libs/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{asset('libs/@fancyapps/fancybox/dist/jquery.fancybox.min.css')}}">

    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    
</head>
