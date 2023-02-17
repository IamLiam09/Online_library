@extends('layouts.theme4.shopfront')
@section('page-title')
    {{ ucfirst($pageoption->name) }} - {{($store->tagline) ?  $store->tagline : config('APP_NAME', ucfirst($store->name))}}
@endsection
@push('css-page')
@endpush
@section('head-title')
    {{ ucfirst($pageoption->name) }}
@endsection
@section('content')
<div class="wrapper"> 
    <section class="common-banner-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-6">
                    <div class="common-banner-content">
                        <a href="{{route('store.slug',$store->slug)}}" class="back-btn">
                            <span class="svg-ic">
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="5" viewBox="0 0 11 5" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M10.5791 2.28954C10.5791 2.53299 10.3818 2.73035 10.1383 2.73035L1.52698 2.73048L2.5628 3.73673C2.73742 3.90636 2.74146 4.18544 2.57183 4.36005C2.40219 4.53467 2.12312 4.53871 1.9485 4.36908L0.133482 2.60587C0.0480403 2.52287 -0.000171489 2.40882 -0.000171488 2.2897C-0.000171486 2.17058 0.0480403 2.05653 0.133482 1.97353L1.9485 0.210321C2.12312 0.0406877 2.40219 0.044729 2.57183 0.219347C2.74146 0.393966 2.73742 0.673036 2.5628 0.842669L1.52702 1.84888L10.1383 1.84875C10.3817 1.84874 10.5791 2.04609 10.5791 2.28954Z" fill="white"></path>
                                </svg>
                            </span>
                            {{ __('Back to Home') }}
                        </a>
                        <div class="section-title">
                            <h2>{{ ucfirst($pageoption->name) }}</h2>
                        </div>
                        <p>{{ __('Lorem Ipsum is simply dummy text of the printing and typesetting industry.Lorem Ipsum is simply dummy text of the printing and typesetting industry.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="padding-top padding-bottom about-our-shop-section">
        <div class="container">
            <div class="aboutus-content">
                <p> {!! $pageoption->contents !!} </p>
            </div>
        </div>
    </section>
    
    <!-- Email Subscriber -->
    @php
        $main_homepage_email_subscriber_key = array_search('Home-Email-subscriber',array_column($getStoreThemeSetting, 'section_name'));        
        $email_subscriber_enable = 'off';
        $homepage_email_subscriber_title = 'Improve Your Skills with ModernCourse';
        $homepage_email_subscriber_subtext = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy.';
        $homepage_email_subscriber_button = 'Subscribe';
        $homepage_email_subscriber_Bckground_Image = 'theme4/email/side-image-1.png';


        if(!empty($getStoreThemeSetting[$main_homepage_email_subscriber_key])) {
            $homepage_subscriber_header = $getStoreThemeSetting[$main_homepage_email_subscriber_key];
            $email_subscriber_enable = $homepage_subscriber_header['section_enable'];

            $homepage_email_subscriber_title_key = array_search('Title',array_column($homepage_subscriber_header['inner-list'], 'field_name'));            
            $homepage_email_subscriber_title = $homepage_subscriber_header['inner-list'][$homepage_email_subscriber_title_key]['field_default_text'];

            $homepage_email_subscriber_subtext_key = array_search('Sub Text',array_column($homepage_subscriber_header['inner-list'], 'field_name'));
            $homepage_email_subscriber_subtext = $homepage_subscriber_header['inner-list'][$homepage_email_subscriber_subtext_key]['field_default_text'];

            $homepage_email_subscriber_button_key = array_search('Button',array_column($homepage_subscriber_header['inner-list'], 'field_name'));
            $homepage_email_subscriber_button = $homepage_subscriber_header['inner-list'][$homepage_email_subscriber_button_key]['field_default_text'];

            $homepage_email_subscriber_Bckground_Image_key = array_search('Thumbnail',array_column($homepage_subscriber_header['inner-list'], 'field_name'));
            $homepage_email_subscriber_Bckground_Image = $homepage_subscriber_header['inner-list'][$homepage_email_subscriber_Bckground_Image_key]['field_default_text'];
        }
    @endphp
    @if($email_subscriber_enable == 'on') 
        <section class="newsletter-section padding-top padding-bottom">
            <div class="container">
                <div class="newsletter-container">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="section-title">
                                <h2>{{ $homepage_email_subscriber_title }}</h2>
                                <p>{{ $homepage_email_subscriber_subtext }}</p>
                            </div>
                            <div class="newsletter-form">
                                {{ Form::open(array('route' => array('subscriptions.store_email', $store->id),'method' => 'POST')) }}
                                    <div class="input-wrapper">
                                        {{ Form::email('email',null,array('aria-label'=>'Enter your email address','placeholder'=>__('Type your email address....'))) }}
                                        <button type="submit" class="btn"> {{ $homepage_email_subscriber_button }}
                                            <svg width="6" height="6" viewBox="0 0 6 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M0.599975 3.6C0.268617 3.6 -1.1665e-07 3.33137 -1.31134e-07 3C-1.45619e-07 2.66863 0.268617 2.4 0.599975 2.4L3.95156 2.4L2.57588 1.02426C2.34157 0.789949 2.34157 0.41005 2.57588 0.175735C2.81018 -0.0585791 3.19007 -0.0585791 3.42437 0.175735L5.82427 2.57574C5.93679 2.68826 6 2.84087 6 3C6 3.15913 5.93679 3.31174 5.82427 3.42426L3.42437 5.82426C3.19007 6.05858 2.81018 6.05858 2.57588 5.82426C2.34157 5.58995 2.34157 5.21005 2.57588 4.97574L3.95156 3.6L0.599975 3.6Z" fill="black"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="checkbox-custom">
                                        <input type="checkbox" class="" id="newslettercheckbox">
                                        <label for="newslettercheckbox">{{ $homepage_email_subscriber_subtext }}</label>
                                    </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="newsletter-img">
                                {{-- <img src="{{ asset('assets/themes/theme4/images/side-image-1.png') }}" alt=""> --}}
                                @if($homepage_email_subscriber_Bckground_Image)
                                    <img src="{{asset(Storage::url('uploads/'.$homepage_email_subscriber_Bckground_Image))}}"> 
                                @else
                                    @if(!empty($store->sub_img))
                                        <img src="{{asset(Storage::url('uploads/store_logo/'.$store->sub_img))}}" alt="newsletter">
                                    @else
                                        <img src="{{asset('assets/themes/'.$store->theme_dir.'/images/side-image-1.png')}}" alt="newsletter">
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif  
</div>
@endsection
@push('script-page')
@endpush


