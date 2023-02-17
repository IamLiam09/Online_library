@php
    // $logo = asset(Storage::url('uploads/logo/'));
    $logo=\App\Models\Utility::get_file('uploads/logo/');
    // $company_logo = \App\Models\Utility::GetLogo();

    if($setting['cust_darklayout'] == 'on'){
        $company_logo = 'logo-light.png';
    }else{
        $company_logo = 'logo-dark.png';
    }
@endphp

@if (isset($setting['cust_theme_bg']) && $setting['cust_theme_bg'] == 'on')
    <nav class="dash-sidebar light-sidebar transprent-bg">
@else
    <nav class="dash-sidebar light-sidebar">
@endif

    <div class="navbar-wrapper">
        <div class="m-header  main-logo">
            <a href="{{ route('dashboard') }}" class="b-brand">
                <!-- ========   change your logo hear   ============ -->
                <img src="{{ $logo . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png') }}"
                    alt="{{ config('app.name', 'LMSGo SaaS') }}" class="logo logo-lg nav-sidebar-logo" />

                    {{-- <img src="{{ $logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png') }}"  alt="{{ config('app.name', 'Storego') }}" alt="logo" class="logo logo-lg nav-sidebar-logo"> --}}
            </a>
        </div>
        <div class="navbar-content">
            <ul class="dash-navbar">
                @if(Auth::user()->type == 'Owner')
                    <li class="dash-item dash-hasmenu {{ (\Request::route()->getName()=='orders.index' || \Request::route()->getName()=='orders.show') ? ' active dash-trigger' : '' }}">
                        <a href="#!" class="dash-link"><span class="dash-micon"><i class="ti ti-layout-2"></i></span><span class="dash-mtext">{{__('Dashboard')}}</span><span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
                        <ul class="dash-submenu">
                            <li class="dash-item">
                                <a class="dash-link" href="{{route('dashboard')}}">{{ __('Dashboard') }}</a>
                            </li>
                            <li class="dash-item">
                                <a class="dash-link" href="{{route('storeanalytic')}}">{{ __('Store Analytics') }}</a>
                            </li>  
                            <li class="dash-item {{ (\Request::route()->getName()=='orders.index' || \Request::route()->getName()=='orders.show') ? ' active' : '' }}">
                                <a class="dash-link" href="{{route('orders.index')}}">{{ __('Orders') }}</a>
                            </li>                          
                        </ul>
                    </li>                 
               
                    <li class="dash-item dash-hasmenu {{ (\Request::route()->getName()=='course.index' || \Request::route()->getName()=='course.create' || \Request::route()->getName()=='course.edit' || \Request::route()->getName()=='chapters.create' || \Request::route()->getName()=='chapters.edit' || \Request::route()->getName()=='category.index' || \Request::route()->getName()=='subcategory.index' || \Request::route()->getName()=='custom-page.index' || \Request::route()->getName()=='blog.index' || \Request::route()->getName()=='subscriptions.index' || \Request::route()->getName()=='product-coupon.index' || \Request::route()->getName()=='product-coupon.show') ? 'active dash-trigger' : '' }}">
                        <a href="#!" class="dash-link"><span class="dash-micon"><i class="ti ti-license"></i></span><span class="dash-mtext">{{__('Shop')}}</span><span class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
                        <ul class="dash-submenu">
                            <li class="dash-item  {{ (\Request::route()->getName()=='course.index' || \Request::route()->getName()=='course.create' || \Request::route()->getName()=='course.edit' || \Request::route()->getName()=='chapters.create' || \Request::route()->getName()=='chapters.edit') ? ' active' : '' }}">
                                <a class="dash-link" href="{{route('course.index')}}"> {{__('Course')}}</a>
                            </li>
                            <li class="dash-item">  
                                <a class="dash-link" href="{{route('category.index')}}">{{__('Category')}}</a>
                            </li>
                            <li class="dash-item">
                                <a class="dash-link" href="{{route('subcategory.index')}}">{{__('Subcategory')}}</a>
                            </li>
                            <li class="dash-item">
                                <a class="dash-link" href="{{route('custom-page.index')}}">{{__('Custom Page')}}</a>
                            </li>
                            <li class="dash-item">
                                <a class="dash-link" href="{{route('blog.index')}}">{{__('Blog')}}</a>
                            </li>
                            <li class="dash-item">
                                <a class="dash-link" href="{{route('subscriptions.index')}}"> {{__('Subscriber')}}</a>
                            </li>
                            <li class="dash-item  {{ (\Request::route()->getName()=='product-coupon.index' || \Request::route()->getName()=='product-coupon.show') ? ' active' : '' }}">
                                <a class="dash-link" href="{{route('product-coupon.index')}}">{{__('Coupons')}}</a>
                            </li>
                        </ul>
                    </li>
                
                  
               

                    {{-- @if(Auth::user()->type == 'super admin')
                        <li class="dash-item {{ (\Request::route()->getName()=='manage.language') ? ' active' : '' }}">
                            <a href="{{route('manage.language',[$currantLang])}}" class="dash-link  {{ (Request::segment(1) == 'manage-language')?'active':''}}"><span class="dash-micon"><i class="ti ti-language"></i></span><span class="dash-mtext">{{__('Language')}}</span></a>
                        </li>
                    @endif   --}}
                    
                    {{-- @if(Auth::user()->type == 'super admin')
                        <li class="dash-item">
                            <a href="{{route('custom_landing_page.index')}}" class="dash-link"><span class="dash-micon"><i class="ti ti-brand-pagekit"></i></span><span class="dash-mtext">{{__('Landing page')}}</span></a>
                        </li>
                    @endif --}}

                    <li class="dash-item {{ (\Request::route()->getName()=='zoom-meeting.index' || \Request::route()->getName()=='zoom-meeting.calender') ? ' active' : '' }}">
                        <a href="{{route('zoom-meeting.index')}}" class="dash-link"><span class="dash-micon"><i class="ti ti-video"></i></span><span class="dash-mtext">{{__('Zoom Meeting')}}</span></a>
                    </li>
                


                    <li class="dash-item {{ (\Request::route()->getName()=='setting.index' || \Request::route()->getName()=='store.editproducts') ? ' active' : '' }}">
                        <a href="{{ route('settings') }}" class="dash-link"><span class="dash-micon"><i class="ti ti-settings"></i></span><span class="dash-mtext"> 
                            @if(Auth::user()->type == 'super admin')
                                {{__('Settings')}}
                            @else
                                {{__('Store Settings')}}
                            @endif
                        </span></a>
                    </li>
                @endif
            </ul>
            
        </div>
    </div>
</nav>





