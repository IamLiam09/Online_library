@extends('storefront.theme2.user.user')
@section('page-title')
    {{__('Login')}} - {{($store->tagline) ?  $store->tagline : config('APP_NAME', ucfirst($store->name))}}
@endsection
@push('css-page')
@endpush
@section('head-title')
    {{__('Student Login')}}
@endsection
@section('content')
<div class="login-page-wrapper">
    <section class="login-page padding-bottom padding-top">
        <div class="banner-image">
            @php
                $data=explode(".",$store->store_theme);                               
            @endphp

            @if($data[0]=='dark-blue-color')
                <img src="{{ asset('assets/themes/theme2/images/banner-image1.png') }}" alt="">
            @elseif($data[0]=='dark-green-color')
                <img src="{{ asset('assets/themes/theme2/images/banner-image2.png') }}" alt="">
            @else
                <img src="{{ asset('assets/themes/theme2/images/banner-image3.png') }}" alt="">
            @endif
        </div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-12">
                    <div class="login-left-side">
                        <div class="section-title">
                            <h2>{{ __('Student Login') }}</h2>
                        </div>
                        <p>{{ __('Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry') }}'{{ __('s standard dummy.') }}</p>
                        <div class="form-wrapper">
                            {!! Form::open(array('route' => array('student.login', $slug,(!empty($is_cart) && $is_cart==true)?$is_cart:false),'class'=>'login-form'),['method'=>'POST']) !!}
                                <div class="row">
                                    <div class="col-md-6 col-12 form-group">
                                        <label for="exampleInputEmail1">{{ __('Username') }}<sup aria-hidden="true">*</sup>:</label>
                                        {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter Your Email')))}}
                                    </div>
                                    <div class="col-md-6 col-12 form-group">
                                        <label>{{ __('Password') }}<sup aria-hidden="true">*</sup>:</label>
                                        {{Form::password('password',array('class'=>'form-control','id'=>'exampleInputPassword1','placeholder'=>__('Enter Your Password')))}}
                                    </div>
                                    <div class="col-12 form-footer d-flex align-items-center mobile-direction-column">
                                        <button type="submit" class="btn submit-btn"> {{ __('Login') }}<svg viewBox="0 0 10 5">
                                            <path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z">
                                            </path>
                                        </svg></button>
                                        <p>{{ __('By using the system, you accept the ') }}<a href="#">{{ __('Privacy Policy') }}</a> {{ __('and') }} <a href="#">{{ __('System Regulations.') }}</a></p>
                                    </div>
                                    <div class="col-sm-12 col-12 d-flex align-items-center justify-content-center reg-lbl-wrap">
                                        <div class="reg-lbl">{{ __('If You Dont Have Account') }}</div> 
                                        <a href="{{route('store.usercreate',$slug)}}" class="btn register-btn" type="submit">
                                            {{ __('Register') }}
                                        </a> 
                                    </div>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
                @php
                    $main_homepage_header_text_key = array_search('Home-Header', array_column($getStoreThemeSetting, 'section_name'));
                    $header_enable = 'off';
                    $homepage_header_title = 'Improve Your<br> Skills with <br> ModernCourse.';
                    $homepage_header_Sub_text = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.';
                    $homepage_header_button = 'Show More';
                    
                    if (!empty($getStoreThemeSetting[$main_homepage_header_text_key])) {
                        $homepage_header = $getStoreThemeSetting[$main_homepage_header_text_key];
                        $header_enable = $homepage_header['section_enable'];
                    
                        $homepage_header_title_key = array_search('Title', array_column($homepage_header['inner-list'], 'field_name'));
                        $homepage_header_title = $homepage_header['inner-list'][$homepage_header_title_key]['field_default_text'];
                    
                        $homepage_header_Sub_text_key = array_search('Sub Title', array_column($homepage_header['inner-list'], 'field_name'));
                        $homepage_header_Sub_text = $homepage_header['inner-list'][$homepage_header_Sub_text_key]['field_default_text'];
                    
                        $homepage_header_button_key = array_search('Button', array_column($homepage_header['inner-list'], 'field_name'));
                        $homepage_header_button = $homepage_header['inner-list'][$homepage_header_button_key]['field_default_text'];
                    }
            
                    $sub_title[0] = 'Data Analyst';
                    $sub_title[1] = 'UX/UI Designer';
                    $sub_title[2] = 'Data Analyst';
                    $sub_title[3] = 'Data Engineer';
                    $sub_subtitle[0] = __('Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the.');
                    $sub_subtitle[1] = __('Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the.');
                    $sub_subtitle[2] = __('Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the.');
                    $sub_subtitle[3] = __('Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the.');
                    $a[0] = '#';
                    $a[1] = '#';
                    $a[2] = '#';
                    $a[3] = '#';
                    $img[0] = 'theme2/images/data-analyst.jpg';
                    $img[1] = 'theme2/images/data-analyst.jpg';
                    $img[2] = 'theme2/images/data-analyst.jpg';
                    $img[3] = 'theme2/images/data-analyst.jpg';
                        
                    if ($header_enable == 'on') {
                        if (!empty($getStoreThemeSetting[1])) {
                            $homepage_header1 = $getStoreThemeSetting[1];
                            foreach ($homepage_header1['inner-list'] as $key1 => $value1) {
                                $sl = $value1['field_slug'];
                                for ($i = 0; $i < $homepage_header1['loop_number']; $i++) {
                                    if (!empty($homepage_header1[$sl][$i])) {
                                        if ($sl == 'homepage-thumbnail-innerbox-1-title') {
                                            $sub_title[$i] = $homepage_header1[$sl][$i];
                                        }
                                        if ($sl == 'homepage-thumbnail-innerbox-1-sub-text') {
                                            $sub_subtitle[$i] = $homepage_header1[$sl][$i];
                                        }
                                        if ($sl == 'homepage-quick-link') {
                                            $a[$i] = $homepage_header1[$sl][$i];
                                        }
                                        if ($sl == 'homepage-thumbnail-innerbox-thumbnail') {
                                            $img[$i] = $homepage_header1[$sl][$i]['field_prev_text'];
                                        }
                                    } else {
                                        if ($sl == 'homepage-thumbnail-innerbox-1-title') {
                                            $sub_title[$i] = $value1['field_default_text'];
                                        }
                                        if ($sl == 'homepage-thumbnail-innerbox-1-sub-text') {
                                            $sub_subtitle[$i] = $value1['field_default_text'];
                                        }
                                        if ($sl == 'homepage-quick-link') {
                                            $a[$i] = $value1['field_default_text'];
                                        }
                                        if ($sl == 'homepage-thumbnail-innerbox-thumbnail') {
                                            $img[$i] = $value1['field_default_text'];
                                        }
                                    }
                                }
                            }
                        }
                    }
                @endphp
                <div class="col-lg-6 col-12">
                    <div class="banner-category-list">
                        @if($header_enable == 'on')
                            <ul class="category-list">
                                <li>
                                    <div class="category-card">
                                        <div class="cateory-card-inner">
                                            <div class="category-image">
                                                <img src="{{ asset(Storage::url('uploads/' . $img[0])) }}" alt="">
                                                {{-- <img src="{{ asset('assets/themes/theme2/images/data-analyst.jpg') }}" alt="">  --}}
                                            </div>
                                            <div class="cateory-caption">
                                                <h5>
                                                    <a href="{{ $a[0] }}">{{ $sub_title[0] }}</a>
                                                </h5>
                                                <p>{{ $sub_subtitle[0] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="category-card">
                                        <div class="cateory-card-inner">
                                            <div class="category-image">
                                                <img src="{{ asset(Storage::url('uploads/' . $img[1])) }}" alt="">
                                                {{-- <img src="{{ asset('assets/themes/theme2/images/Designer.jpg') }}" alt=""> --}}
                                            </div>
                                            <div class="cateory-caption">
                                                <h5>
                                                    <a href="{{ $a[1] }}">{{ $sub_title[1] }}</a>
                                                </h5>
                                                <p>{{ $sub_subtitle[1] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="category-card">
                                        <div class="cateory-card-inner">
                                            <div class="category-image">
                                                <img src="{{ asset(Storage::url('uploads/' . $img[2])) }}" alt="">
                                                {{-- <img src="{{ asset('assets/themes/theme2/images/data-analyst.jpg') }}" alt=""> --}}
                                            </div>
                                            <div class="cateory-caption">
                                                <h5>
                                                    <a href="{{ $a[2] }}">{{ $sub_title[2] }}</a>
                                                </h5>
                                                <p>{{ $sub_subtitle[2] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="category-card">
                                        <div class="cateory-card-inner">
                                            <div class="category-image">
                                                <img src="{{ asset(Storage::url('uploads/' . $img[3])) }}" alt="">
                                                {{-- <img src="{{ asset('assets/themes/theme2/images/data-Engineer.jpg') }}" alt=""> --}}
                                            </div>
                                            <div class="cateory-caption">
                                                <h5>
                                                    <a href="{{ $a[3] }}">{{ $sub_title[3] }}</a>
                                                </h5>
                                                <p>{{ $sub_subtitle[3] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
@push('script-page')
    <script>
        if ('{!! !empty($is_cart) && $is_cart==true !!}') {
            show_toastr('Error', 'You need to login!', 'error');
        }
    </script>
@endpush


