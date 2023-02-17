@php
    // $logo=asset(Storage::url('uploads/logo/'));
    $logo = \App\Models\Utility::get_file('uploads/logo/');
    $users = \Auth::user();
    $currantLang = $users->currentLanguages();
    $languages = Utility::languages();
    $footer_text = isset(Utility::settings()['footer_text']) ? Utility::settings()['footer_text'] : '';
    
    $setting = App\Models\Utility::colorset();
    // $setting_color = App\Models\Utility::colorsetcolor();
    $color = (!empty($setting['color'])) ? $setting['color'] : 'theme-3';

    // if (!empty($setting['color'])) {
    //     $color = $setting['color'];
    // }else{
    //     $color = 'theme-3';
    // }    
@endphp


<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ $setting['SITE_RTL'] == 'on' ? 'rtl' : '' }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('partials.admin.head')

<body class="{{ $color }}">
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    @include('partials.admin.sidebar')

    @include('partials.admin.header')

    <div class="page-content">
        @include('partials.admin.content')
    </div>

    <footer class="dash-footer" id="footer-main">
        <div class="footer-wrapper">
            <div class="py-1">
                <p class="text-sm mb-0">{{ $footer_text }}</p>
            </div>           
        </div>
    </footer> 
    
    <div class="modal fade" id="commonModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modelCommanModelLabel"></h5>
                    <a type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>

    @include('partials.admin.footer')

</body>

</html>
