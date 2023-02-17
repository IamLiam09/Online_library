@extends('storefront.theme1.user.user')
@section('page-title')
    {{__('Register')}} - {{($store->tagline) ?  $store->tagline : config('APP_NAME', ucfirst($store->name))}}
@endsection
@push('css-page')
@endpush
@section('head-title')
    {{__('Student Register')}}
@endsection
@section('content')

    <section class="register-page padding-bottom padding-top">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7 col-12">  
                    <div class="section-title text-center">
                        <h2>{{__('Student')}} <b>{{__('Register')}}</b></h2> 
                    </div> 
                    <div class="form-wrapper">
                        {!! Form::open(array('route' => array('store.userstore', $slug),'class'=>'login-form-main py-5'), ['method' => 'post']) !!}
                        {{-- <form class="login-form"> --}}
                            <div class="form-container">
                                <div class="form-heading">
                                    <h4>{{__('Register')}}</h4>
                                </div>
                            </div>
                            <div class="form-container">
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
                                            <label>{{__('Confirm Password')}}<sup aria-hidden="true">*</sup>:</label>
                                            <input type="password" name="password_confirmation" class="form-control" placeholder="{{ __('Enter Your Password') }}" required="required">
                                        </div>
                                        @error('password_confirmation')
                                            <span class="error invalid-email text-danger" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div> 
                                    
                                </div> 
                            </div> 
                            <div class="form-container">  
                                <div class="row align-items-center form-footer justify-content-end"> 
                                    <div class="col-lg-12 col-12 d-flex align-items-center justify-content-end mobile-direction-column">  
                                        <p>{{ __('By using the system, you accept the')}} <a href="#"> {{__('Privacy Policy')}} </a>  {{__('and')}} <a href="#"> {{__('System Regulations')}}. </a></p>
                                        <button class="btn submit-btn" type="submit">
                                            {{__('Register')}}
                                        </button> 
                                    </div>
                                </div>
                            </div>
                            <div class="form-container">
                                <div class="row align-items-center"> 
                                    <div class="col-sm-12 col-12 d-flex align-items-center justify-content-center">
                                        <div class="reg-lbl">{{__('Already registered ?')}}</div> 
                                        <a href="{{route('student.loginform',$slug)}}" class="btn register-btn" type="submit">
                                            {{__('Login')}}                                            
                                        </a> 
                                    </div>
                                </div>
                            </div>
                        {{-- </form> --}}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('script-page')
@endpush
