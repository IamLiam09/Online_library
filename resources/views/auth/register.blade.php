@extends('layouts.auth')
@section('page-title')
    {{__('Register')}}
@endsection

@section('language-bar')
    <li class="nav-item bth-primary">
        <select name="language" id="language" class="form-control btn-primary mr-2 my-2 me-2" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
            @foreach( Utility::languages() as $language)
                <option @if($lang == $language) selected @endif value="{{ route('register',$language) }}">{{Str::upper($language)}}</option>
            @endforeach
        </select>
    </li>
@endsection

@section('content')


<div class="card">
    <div class="row align-items-center">
        <div class="col-xl-6">
            <div class="card-body">
                <div class="">
                    <h2 class="mb-3 f-w-600">{{__('Create account')}}</h2>
                    <p class="text-muted mb-0">{{__("Don't have an account? Create your account, it takes less than a minute")}}</p>
                </div>
                <div class="mt-3">
                    {{Form::open(array('route'=>'register','method'=>'post','id'=>'loginForm'))}}
                        <div class="form-group mb-3">
                            <label class="form-label">{{__('Name')}}</label>
                            {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Your Name')))}}
                            @error('name')
                                <span class="error invalid-name text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">{{__('Store Name')}}</label>
                            {{Form::text('store_name',null,array('class'=>'form-control','placeholder'=>__('Enter Store Name')))}}
                            @error('store_name')
                                <span class="error invalid-store_name text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">{{__('Email')}}</label>
                            {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter Your Email')))}}
                            @error('email')
                                <span class="error invalid-email text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">{{__('Password')}}</label>
                            {{Form::password('password',array('class'=>'form-control','id'=>'input-password','placeholder'=>__('Enter Your Password')))}}
                            @error('password')
                                <span class="error invalid-password text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">{{__('Confirm password')}}</label>
                            {{Form::password('password_confirmation',array('class'=>'form-control','id'=>'confirm-input-password','placeholder'=>__('Enter Your Confirm Password')))}}
                            @error('password_confirmation')
                                <span class="error invalid-password_confirmation text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        @if(env('RECAPTCHA_MODULE') == 'yes')
                            <div class="form-group col-lg-12 col-md-12 mt-3">
                                {!! NoCaptcha::display() !!}
                                @error('g-recaptcha-response')
                                <span class="small text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        @endif

                        <div class="d-grid">
                            {{Form::submit(__('Create my account'),array('class'=>'btn btn-primary btn-block mt-2','id'=>'saveBtn'))}}
                        </div>

                        <div class="d-block mt-2 px-md-5"><small>{{__('Already have an acocunt?')}}</small>
                            <a href="{{ route('login',$lang) }}" class="small font-weight-bold">{{__('Login')}}</a>
                        </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
        <div class="col-xl-6 img-card-side">
            <div class="auth-img-content">
                <img src="{{ asset('assets/images/auth/img-auth-3.svg') }}" alt="" class="img-fluid">
                <h3 class="text-white mb-4 mt-5">“Attention is the new currency”</h3>
                <p class="text-white">The more effortless the writing looks, the more effort the writer
                    actually put into the process.</p>
            </div>
        </div>
    </div>
</div>
@endsection
@push('custom-scripts')
    @if(env('RECAPTCHA_MODULE') == 'yes')
            {!! NoCaptcha::renderJs() !!}
    @endif
@endpush