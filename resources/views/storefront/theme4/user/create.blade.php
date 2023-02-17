@extends('storefront.theme4.user.user')
@section('page-title')
    {{__('Register')}} - {{($store->tagline) ?  $store->tagline : config('APP_NAME', ucfirst($store->name))}}
@endsection
@push('css-page')
@endpush
@section('head-title')
    {{__('Student Register')}}
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
                        {!! Form::open(['route' => ['store.userstore', $slug], 'class' => 'login-form'], ['method' => 'post']) !!}
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label>{{ __('Full Name') }}<sup aria-hidden="true">*</sup>:</label>
                                        <input type="text" class="form-control" name="name" placeholder="{{ __('Full Name') }}" required="required">
                                    </div>
                                    @error('name')
                                        <span class="error invalid-email text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div> 
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label>{{ __('Email') }}<sup aria-hidden="true">*</sup>:</label>
                                        <input type="email" class="form-control" name="email" placeholder="{{ __('Enter Your Email') }}" required="required">
                                    </div>
                                    @error('email')
                                        <span class="error invalid-email text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label>{{ __('Number') }}<sup aria-hidden="true">*</sup>:</label>
                                        <input type="text" name="phone_number" class="form-control" placeholder="Number" required="required">
                                    </div>
                                    @error('number')
                                        <span class="error invalid-email text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label>{{ __('Password') }}<sup aria-hidden="true">*</sup>:</label>
                                        <input type="password" name="password" class="form-control" placeholder="{{ __('Enter Your Password') }}" required="required">
                                    </div>
                                    @error('password')
                                        <span class="error invalid-email text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div> 
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label>{{ __('Confirm Password') }}<sup aria-hidden="true">*</sup>:</label>
                                        <input type="password" name="password_confirmation" class="form-control" placeholder="{{ __('Enter Your Password') }}" required="required">
                                    </div>
                                    @error('password_confirmation')
                                        <span class="error invalid-email text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div> 
                                <div class="col-12 form-footer d-flex align-items-center mobile-direction-column">
                                    <button type="submit" class="btn submit-btn">{{ __('Register') }} <svg viewBox="0 0 10 5">
                                        <path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z">
                                        </path>
                                    </svg></button>
                                    <p>{{ __('By using the system, you accept the') }} <a href="#">{{ __('Privacy Policy') }}</a> {{ __('and') }} <a href="#">{{ __('System Regulations.') }}</a></p>
                                </div>
                                <div class="col-sm-12 col-12 d-flex align-items-center justify-content-center reg-lbl-wrap">
                                    <div class="reg-lbl">{{ __('Already registered ?') }}</div>
                                    <a href="{{ route('student.loginform', $slug) }}" class="btn register-btn" type="submit">
                                        {{ __('Login') }}
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
@endpush
