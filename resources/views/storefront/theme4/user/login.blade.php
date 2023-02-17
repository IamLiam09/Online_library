@extends('storefront.theme4.user.user')
@section('page-title')
    {{__('Login')}} - {{($store->tagline) ?  $store->tagline : config('APP_NAME', ucfirst($store->name))}}
@endsection
@push('css-page')
@endpush
@section('head-title')
    {{__('Student Login')}}
@endsection
@section('content')
<div class="wrapper">
    <section class="login-page padding-bottom padding-top">
        <div class="banner-image">
            <img src="{{ asset('assets/themes/theme4/images/login-page-img.png') }}" alt="">
        </div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-12">
                    <div class="login-left-side">
                        <div class="section-title">
                            <h2>{{ __('Students Login') }}</h2>
                        </div>
                        <p>{{ __('Lorem Ipsum is simply dummy text of the printing and typesetting industry.') }}</p>
                        <div class="form-wrapper">
                            {!! Form::open(['route' => ['student.login', $slug, !empty($is_cart) && $is_cart == true ? $is_cart : false],'class' => 'login-form',],['method' => 'POST'],) !!}
                                <div class="row">
                                    <div class="col-md-6 col-12 form-group">
                                        <label>{{ __('Username') }}<sup aria-hidden="true">*</sup>:</label>
                                        {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter Your Email')))}}
                                    </div>
                                    <div class="col-md-6 col-12 form-group">
                                        <label>{{ __('Password') }}<sup aria-hidden="true">*</sup>:</label>
                                        {{Form::password('password',array('class'=>'form-control','id'=>'exampleInputPassword1','placeholder'=>__('Enter Your Password')))}}
                                    </div>
                                    <div class="col-12 form-footer d-flex align-items-center mobile-direction-column">
                                        <button type="submit" class="btn submit-btn">{{ __('Login') }} 
                                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="6" viewBox="0 0 6 6" fill="none">
                                                <path d="M0.599975 3.6C0.268617 3.6 -1.1665e-07 3.33137 -1.31134e-07 3C-1.45619e-07 2.66863 0.268617 2.4 0.599975 2.4L3.95156 2.4L2.57588 1.02426C2.34157 0.789949 2.34157 0.41005 2.57588 0.175735C2.81018 -0.0585791 3.19007 -0.0585791 3.42437 0.175735L5.82427 2.57574C5.93679 2.68826 6 2.84087 6 3C6 3.15913 5.93679 3.31174 5.82427 3.42426L3.42437 5.82426C3.19007 6.05858 2.81018 6.05858 2.57588 5.82426C2.34157 5.58995 2.34157 5.21005 2.57588 4.97574L3.95156 3.6L0.599975 3.6Z" fill="black"></path>
                                            </svg>
                                        </button>
                                        <p>{{ __('By using the system, you accept the') }} <a href="#">{{ __('Privacy Policy') }}</a> {{ __('and') }} <a href="#">{{ __('System Regulations.') }}</a></p>
                                    </div>
                                    <div class="col-sm-12 col-12 d-flex align-items-center justify-content-center reg-lbl-wrap ">
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
                <div class="col-lg-6 col-12">
                    <div class="login-left">
                        <img src="{{ asset('./assets/themes/theme4/images/login-img.svg') }}" alt="">                            
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


