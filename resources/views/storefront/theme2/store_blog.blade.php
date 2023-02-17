@extends('layouts.theme2.shopfront')
@section('page-title')
    {{ __('Blog') }} - {{($store->tagline) ?  $store->tagline : config('APP_NAME', ucfirst($store->name))}}
@endsection
@push('css-page')
@endpush
@section('head-title')
    {{ __('Blog') }}
@endsection
@section('content')
  
    <div class="wrapper">
        <section class="common-banner-section"  style="background-image:url({{ asset('assets/themes/theme2/images/comman-banner.png') }});">
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
                                <h2>{{__('Blog')}}</h2>
                            </div>
                            <p>{{ __('Lorem Ipsum is simply dummy text of the printing and typesetting industry.Lorem Ipsum is simply dummy text of the printing and typesetting industry.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="blog-listing-section padding-bottom padding-top">
            <div class="container">
                <div class="blog-list-row row">
                    <div class="blog-filter-right-column col-lg-12 col-md-12 col-12">
                        <div class="row">
                            @foreach($blogs as $blog)
                                <div class="col-lg-4 col-md-4 col-sm-6 col-12 blog-widget">
                                    <div class="blog-widget-inner">
                                        <div class="blog-media">
                                            <a href="{{route('store.store_blog_view',[$store->slug,\Illuminate\Support\Facades\Crypt::encrypt($blog->id)])}}">
                                                {{-- <img src="assets/images/blog-image.jpg" alt=""> --}}
                                                {{-- @if(!empty($blog->blog_cover_image) && file_exists(asset(Storage::url('uploads/store_logo/'.$blog->blog_cover_image)))) --}}
                                                @if(!empty($blog->blog_cover_image) )
                                                    <img src="{{asset(Storage::url('uploads/store_logo/'.$blog->blog_cover_image))}}" alt="card" class="img-fluid">
                                                @else
                                                    <img src="{{ asset('assets/themes/theme2/images/blog-image.jpg') }}" alt="">
                                                @endif
                                            </a>
                                        </div>
                                        <div class="blog-caption">
                                            <div class="captio-top d-flex justify-content-between align-items-center">
                                                <span class="badge">{{__('Articles')}}</span>
                                                <span class="date">{{ Utility::dateFormat($blog->created_at)}}</span>
                                            </div>

                                            {{-- <p class="auth-name"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" fill="none">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M7.5 1.25C4.04822 1.25 1.25 4.04822 1.25 7.5C1.25 9.12124 1.86729 10.5983 2.87967 11.709C3.40007 10.5778 4.30867 9.69557 5.41055 9.19879C4.77478 8.62676 4.375 7.79756 4.375 6.875C4.375 5.14911 5.77411 3.75 7.5 3.75C9.22589 3.75 10.625 5.14911 10.625 6.875C10.625 7.78291 10.2378 8.60039 9.61957 9.17133C10.7407 9.63739 11.6939 10.4834 12.1506 11.6756C13.145 10.5688 13.75 9.10504 13.75 7.5C13.75 4.04822 10.9518 1.25 7.5 1.25ZM12.3429 13.227C13.9682 11.8513 15 9.79613 15 7.5C15 3.35786 11.6421 0 7.5 0C3.35786 0 0 3.35786 0 7.5C0 9.79609 1.03179 11.8512 2.65702 13.2269C2.69961 13.2751 2.75003 13.3171 2.80728 13.3508C4.09208 14.3827 5.72394 15 7.5 15C9.27601 15 10.9078 14.3827 12.1926 13.3509C12.2498 13.3173 12.3003 13.2752 12.3429 13.227ZM11.1393 12.5817L11.0268 12.2444C10.568 10.8681 9.09406 10 7.5 10C5.8867 10 4.44576 11.0093 3.89442 12.5254L3.87122 12.5893C4.89427 13.32 6.14692 13.75 7.5 13.75C8.85771 13.75 10.1143 13.3171 11.1393 12.5817ZM7.5 8.75C8.53553 8.75 9.375 7.91053 9.375 6.875C9.375 5.83947 8.53553 5 7.5 5C6.46447 5 5.625 5.83947 5.625 6.875C5.625 7.91053 6.46447 8.75 7.5 8.75Z" fill="white"></path>
                                            </svg> @johndoe</p> --}}
                                            <h4>
                                                <a href="{{route('store.store_blog_view',[$store->slug,\Illuminate\Support\Facades\Crypt::encrypt($blog->id)])}}">{{ $blog->title }}</a>
                                            </h4>
                                            <p>{{ __('Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum is simply dummy text of the printing and typesetting industry.') }}</p>
                                            
                                            <a class="btn blog-btn" href="{{route('store.store_blog_view',[$store->slug,\Illuminate\Support\Facades\Crypt::encrypt($blog->id)])}}">
                                                {{__('Read More')}}
                                                <svg viewBox="0 0 10 5">
                                                    <path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z">
                                                    </path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
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
@push('script-page')
    <script>
        $(document).ready(function () {
            var blog = {{sizeof($blogs)}};
            if (blog < 1) {
                window.location.href = "{{route('store.slug',$slug)}}";
            }
        });
    </script>
@endpush
