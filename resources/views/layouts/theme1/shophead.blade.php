<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="Lmsgo">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('meta-data')

    <title>@yield('page-title')</title>
    <meta name="description" content="Lmsgo">
    <meta name="keywords" content="Lmsgo">
    <link rel="shortcut icon" href="assets/images/favicon.png">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('libs/@fortawesome/fontawesome-free/css/all.min.css')}}">
    {{-- <link rel="stylesheet" href="{{asset('./css/main-style.css')}}"> --}}
    <link rel="stylesheet" href="{{asset('css/moovie.css')}}">

    @if(!empty($store->store_theme))
        <link rel="stylesheet" href="{{asset('assets/css/'.$store->store_theme)}}" id="stylesheet">
    @else
        <link rel="stylesheet" href="{{asset('assets/css/yellow-style.css')}}" id="stylesheet">
    @endif
    <link rel="stylesheet" href="{{asset('./css/responsive.css')}}">

</head>



