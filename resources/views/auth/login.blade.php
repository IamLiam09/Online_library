@extends('layouts.auth')
@section('page-title')
    {{__('Login')}}
@endsection

@section('language-bar')
    <li class="nav-item bth-primary">
        <select name="language" id="language" class="form-control btn-primary mr-2 my-2 me-2" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
            @foreach( Utility::languages() as $language)
                <option @if($lang == $language) selected @endif value="{{ route('login',$language) }}">{{Str::upper($language)}}</option>
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
                    <h2 class="mb-3 f-w-600">{{ __('Login') }}</h2>
                </div>
                {{Form::open(array('route'=>'login','method'=>'post','id'=>'loginForm'))}}
                    <div class="">
                        <div class="form-group mb-3">
                            {{Form::label('email',__('Email'),array('class' => 'form-label','id'=>'email'))}}                              
                            {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter Your Email address')))}}
                            
                            @error('email')
                                <span class="invalid-email text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">                                
                            {{Form::label('password',__('Password'),array('class' => 'form-label','id'=>'password'))}}
                            {{Form::password('password',array('class'=>'form-control','placeholder'=>__('Enter Your Password')))}}
                                
                            @error('password')
                                <span class="invalid-password text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="d-grid">   
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request',$lang) }}" class="d-block mt-2">
                                    <small>{{ __('Forgot Your Password?') }}</small>
                                </a>
                            @endif
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

                        <div class="d-grid ">
                            {{Form::submit(__('Login'),array('class'=>'btn btn-primary btn-block mt-2','id'=>'saveBtn'))}}
                            {{-- @if(Utility::getValByName('signup')=='on')
                                <div class="d-block mt-2 px-md-5"><small>{{__('Not registered')}}?</small>
                                    <a href="{{route('register',$lang)}}" class="small font-weight-bold">{{__('Create account')}}</a>
                                </div>
                            @endif --}}
                        </div>

                    </div>
                {{Form::close()}}
            </div>
        </div>
        <div class="col-xl-6 img-card-side">
            <div class="auth-img-content">
                <img src="{{ asset('assets/images/auth/img-auth-3.svg') }}" alt="" class="img-fluid">
                <h3 class="text-white mb-4 mt-5">“Attention is the new currency”</h3>
                <p class="text-white">{{ __('The more effortless the writing looks, the more effort the writer actually put into the process.') }}</p>
            </div>
        </div>
    </div>
</div>

@endsection
@push('custom-scripts')
    @if(env('RECAPTCHA_MODULE') == 'yes')
        {!! NoCaptcha::renderJs() !!}
    @endif

    <script src="{{asset('libs/jquery/dist/jquery.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $("#loginForm").submit(function (e) {
                $("#saveBtn").attr("disabled", true);
                return true;
            });
        });
    </script>
@endpush
