@extends('storefront.theme1.user.user')
@section('page-title')
    {{__('Login')}} - {{($store->tagline) ?  $store->tagline : config('APP_NAME', ucfirst($store->name))}}
@endsection
@push('css-page')
@endpush
@section('head-title')
    {{__('Student Login')}}
@endsection
@section('content')

    <section class="register-page padding-bottom padding-top">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7 col-12">  
                    <div class="section-title text-center">
                        <h2>{{__('Student')}} <b>{{__('Login')}}</b></h2> 
                    </div> 
                    <div class="form-wrapper">
                        {!! Form::open(array('route' => array('student.login', $slug,(!empty($is_cart) && $is_cart==true)?$is_cart:false),'class'=>'login-form'),['method'=>'POST']) !!}                        
                            <div class="form-container">
                                <div class="form-heading">
                                    <h4>{{__('Login')}}</h4>
                                </div>
                            </div>
                            <div class="form-container">
                                <div class="row"> 
                                  
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">{{ __('USERNAME') }}<sup aria-hidden="true">*</sup>:</label>
                                            {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter Your Email')))}}
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div for="exampleInputPassword1" class="form-group">
                                            <label>{{ __('Password') }}<sup aria-hidden="true">*</sup>:</label>
                                            {{Form::password('password',array('class'=>'form-control','id'=>'exampleInputPassword1','placeholder'=>__('Enter Your Password')))}}
                                        </div>
                                    </div> 
                                    <div class="col-md-12  col-12 d-flex align-items-center justify-content-end mobile-direction-column">  
                                        <button class="btn submit-btn" type="submit">
                                          {{ __('Login') }}
                                        </button> 
                                    </div>
                                </div> 
                            </div>                            
                            <div class="form-container">
                                <div class="row align-items-center"> 
                                    <div class="col-sm-12 col-12 d-flex align-items-center justify-content-center">
                                        <div class="reg-lbl">{{ __('If you dont have account') }}</div> 
                                        <a href="{{route('store.usercreate',$slug)}}" class="btn register-btn" type="submit">
                                            {{ __('Register') }}
                                        </a> 
                                    </div>
                                </div>
                            </div>                       
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('script-page')
    <script>
        if ('{!! !empty($is_cart) && $is_cart==true !!}') {
            show_toastr('Error', 'You need to login!', 'error');
        }
    </script>
@endpush


