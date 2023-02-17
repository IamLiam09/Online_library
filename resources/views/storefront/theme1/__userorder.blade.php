<!DOCTYPE html>
<html lang="en" dir="{{env('SITE_RTL') == 'on'?'rtl':''}}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="LMSGo - Learning Management System">
    <meta name="author" content="Rajodiya Infotech">

    <title>{{__('User Order')}} - {{($store->tagline) ?  $store->tagline : config('APP_NAME', ucfirst($store->name))}}</title>
    <link rel="icon" href="{{asset(Storage::url('uploads/logo/').(!empty($settings->value)?$settings->value:'favicon.png'))}}" type="image/png">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('libs/@fortawesome/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('libs/fullcalendar/dist/fullcalendar.min.css')}}">

    <link rel="stylesheet" href="{{ asset('libs/animate.css/animate.min.css')}}" id="stylesheet">
    <link rel="stylesheet" href="{{ asset('libs/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{asset('libs/@fancyapps/fancybox/dist/jquery.fancybox.min.css')}}">
    <link rel="stylesheet" href="{{ asset('css/site-light.css')}}" id="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.dataTables.min.css')}}">

    <link rel="stylesheet" href="{{ asset('assets/css/custom.css')}}" id="stylesheet')}}">
    @if(env('SITE_RTL')=='on')
        <link rel="stylesheet" href="{{ asset('css/bootstrap-rtl.css') }}">
    @endif
    <script type="text/javascript" src="{{asset('libs/jquery/dist/jquery.min.js')}}"></script>

    @stack('css-page')
</head>

<body class="application application-offset">
@php
    if(!empty(session()->get('lang')))
    {
        $currantLang = session()->get('lang');
    }else{
        $currantLang = $store->lang;
    }
    $languages= Utility::languages();
@endphp
<div class="container-fluid container-application">
    <div class="main-content position-relative">
        <div id="navbar-top-main" class="navbar-top  navbar-dark bg-primary border-bottom">
            <div class="container px-0">
                <div class="navbar-nav align-items-center float-left">
                    <div class="d-none d-lg-inline-block">
                        <a class="navbar-brand mr-lg-4 pt-0" href="{{route('store.slug',$store->slug)}}">
                            @if(!empty($store->logo))
                                <img alt="Image placeholder" src="{{asset(Storage::url('uploads/store_logo/'.$store->logo))}}" id="navbar-logo" style="height: 40px;">
                            @else
                                <img alt="Image placeholder" src="{{asset(Storage::url('uploads/store_logo/logo.png'))}}" id="navbar-logo" style="height: 40px;">
                            @endif
                        </a>
                        <div class="d-lg-inline-block">
                            <span class="navbar-text mr-3 pt-3 text-lg align-middle">{{ucfirst($store->name)}}</span>
                        </div>
                    </div>
                </div>
                <div class="float-right">
                    <ul class="nav">
                        <li class="nav-item dropdown">
                            <div class="dropdown-menu dropdown-menu-sm">
                                @foreach($languages as $language)
                                    <a href="{{route('change.languagestore',[$store->slug,$language])}}" class="dropdown-item @if($language == $currantLang) active-language @endif">
                                        <span> {{Str::upper($language)}}</span>
                                    </a>
                                @endforeach
                            </div>
                            <a class="nav-link text-white pt-4" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="text-sm mb-0"><i class="fas fa-globe-asia"></i>
                                    {{Str::upper($currantLang)}}
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <nav class="navbar navbar-main navbar-expand-lg navbar-dark bg-primary " id="navbar-main">
            <div class="container px-lg-0">
                <!-- Logo -->

                <!-- Navbar collapse trigger -->
                <button class="navbar-toggler pr-0" type="button" data-toggle="collapse" data-target="#navbar-main-collapse" aria-controls="navbar-main-collapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbar-main-collapse">
                    <ul class="navbar-nav align-items-lg-center ml-lg-auto">
                        <li class="nav-item dropdown dropdown-animate" data-toggle="hover">
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg dropdown-menu-arrow p-0">
                                <div class="dropdown-menu-links rounded-bottom delimiter-top p-4">
                                    <div class="row">
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="main-content">
            <header class="bg-primary d-flex align-items-end">
                <div class="container">
                    <div class="row float-left">
                        <div class=" col-auto">
                            <div class="row align-items-center ">
                                <h4 class="text-white">{{__('Your Order Details')}}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto text-right">
                        <a href="#" onclick="saveAsPDF();" data-toggle="tooltip" data-title="{{__('Download')}}" id="download-buttons" class="btn btn-sm btn-white btn-icon rounded-pill">
                            <span class="btn-inner--icon text-dark"><i class="fa fa-print"></i></span>
                            <span class="btn-inner--text text-dark">{{__('Print')}}</span>
                        </a>
                    </div>
                </div>
            </header>
            <div class="container">
                <div class="mt-4">
                    <div id="printableArea">
                        <div class="row">
                            <div class=" col-6 pb-2 invoice_logo"></div>
                            <div class="col-lg-8">
                                <div class="card card-fluid">
                                    <div class="card-header ">
                                        <h6 class="mb-0">{{__('Items from Order')}} {{$order->order_id}}</h6>
                                    </div>
                                    <div class="card-body">
                                        <h6 class="mb-4">{{__('Shipping Information')}}</h6>
                                        <address class="mb-0 text-sm">
                                            <dl class="row mt-4 align-items-center">
                                                <dt class="col-sm-3 h6 text-sm">{{__('Name')}}</dt>
                                                <dd class="col-sm-9 text-sm"> {{$student_data->name}}</dd>
                                                <dt class="col-sm-3 h6 text-sm">{{__('E-mail')}}</dt>
                                                <dd class="col-sm-9 text-sm">{{$student_data->email}}</dd>
                                            </dl>
                                        </address>
                                    </div>
                                    <div class="card-footer">
                                        <div class="table-responsive">
                                            <table class="table mb-0">
                                                <thead class="thead-light">
                                                <tr class="border-top-0">
                                                    <th>{{__('Item')}}</th>
                                                    <th>{{__('Price')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @php
                                                    $sub_tax = 0;
                                                    $total = 0;
                                                @endphp
                                                @foreach($order_products as $key=>$product)
                                                    <tr>
                                                        <td class="total">
                                                <span class="h6 text-sm">
                                                    @if(isset($product->product_name))
                                                        {{$product->product_name}}
                                                    @else
                                                        {{$product->name}}
                                                    @endif
                                                </span>
                                                            @php
                                                                $total_tax = 0
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            {{ Utility::priceFormat($product->price)}}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card card-fluid">
                                    <div class="card-header border-0">
                                        <h6 class="mb-0">{{__('Items from Order '). $order->order_id}}</h6>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead class="thead-light">
                                            <tr>
                                                <th>{{__('Description')}}</th>
                                                <th>{{__('Price')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>{{__('Grand Total')}} :</td>
                                                <td>{{ Utility::priceFormat($sub_total)}}</td>
                                            </tr>
                                            <tr>
                                                <th>{{__('Apply Coupon')}} :</th>
                                                <th>{{ (!empty($order->discount_price))?$order->discount_price: Utility::priceFormat(0)}}</th>
                                            </tr>
                                            <tr>
                                                <th>{{__('Total')}} :</th>
                                                <th>{{  Utility::priceFormat($grand_total) }}</th>
                                            </tr>
                                            <tr>
                                                <th>{{__('Payment Type')}} :</th>
                                                <th>{{ $order['payment_type'] }}</th>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="invoice_logo_img" class="d-none">
    <div class="row align-items-center py-2 px-3">
        @if(!empty($store->invoice_logo))
            <img alt="Image placeholder" src="{{asset(Storage::url('uploads/store_logo/'.$store->invoice_logo))}}" id="navbar-logo" style="height: 40px;">
        @else
            <img alt="Image placeholder" src="{{asset(Storage::url('uploads/store_logo/invoice_logo.png'))}}" id="navbar-logo" style="height: 40px;">
        @endif
    </div>
</div>

<script src="{{asset('js/site.core.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/custom.js')}}"></script>
<script src="{{ asset('libs/bootstrap-notify/bootstrap-notify.min.js')}}"></script>
<script src="{{asset('libs/swiper/dist/js/swiper.min.js')}}"></script>
<script src="{{asset('js/site.js')}}"></script>
<script src="{{asset('assets/js/demo.js')}}"></script>
<script type="text/javascript" src="{{ asset('assets/js/html2pdf.bundle.min.js') }}"></script>


@php
    $store_settings = \App\Models\Store::where('slug',$store->slug)->first();
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
    var filename = $('#filesname').val();

    function saveAsPDF() {
        var element = document.getElementById('printableArea');
        var logo_html = $('#invoice_logo_img').html();
        $('.invoice_logo').empty();
        $('.invoice_logo').html(logo_html);

        var opt = {
            margin: 0.3,
            filename: filename,
            image: {type: 'jpeg', quality: 1},
            html2canvas: {scale: 4, dpi: 72, letterRendering: true},
            jsPDF: {unit: 'in', format: 'A2'}
        };

        html2pdf().set(opt).from(element).save();
        setTimeout(function () {
            $('.invoice_logo').empty();
        }, 0);
    }
</script>
</body>
</html>

