@extends('layouts.auth')
@section('page-title')
    {{__('Reset Password')}}
@endsection

@section('language-bar')
        <li class="nav-item bth-primary">
            <select name="language" id="language" class="form-control btn-primary mr-2 my-2 me-2" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                @foreach( Utility::languages() as $language)
                    <option @if($lang == $language) selected @endif value="{{ route('password.request',$language) }}">{{Str::upper($language)}}</option>
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
                    <h2 class="mb-3 f-w-600">{{ __('Password Reset') }}</h2>
                </div>
                <div class="">
                    @if (session('status'))
                        <small class="text-muted">{{ session('status') }}</small>
                    @endif
                    {{Form::open(array('route'=>'password.email','method'=>'post','id'=>'loginForm'))}}
                        <div class="form-group mb-3">
                            {{Form::label('email',__('Email'),array('class' => 'form-label'))}}
                            {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter Your Email')))}}

                            @error('email')
                                <span class="invalid-email text-danger" role="alert">
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
                            {{Form::submit( __('Forgot Password'),array('class'=>'btn btn-primary btn-block mt-2','id'=>'saveBtn'))}}

                            <div class="d-block mt-2 px-md-5"><small>{{__('Back to')}}?</small>
                                <a href="{{ route('login',$lang) }}" class="small font-weight-bold">{{ __('Login') }}</a>
                            </div>
                        </div>
                        
                    {{Form::close()}}
                </div>             
            </div>
        </div>
        <div class="col-xl-6 img-card-side">
            <div class="auth-img-content">
                <img src="../assets/images/auth/img-auth-3.svg" alt="" class="img-fluid">
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
