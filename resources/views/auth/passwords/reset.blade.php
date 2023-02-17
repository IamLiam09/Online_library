@extends('layouts.auth')
@section('page-title')
    {{__('Reset Password')}}
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
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $request->token }}">
                        <div class="form-group mb-3">
                                {{Form::label('E-Mail',__('E-Mail'),array('class' => 'form-control-label'))}}
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                {{Form::label('Password',__('Password'),array('class' => 'form-control-label'))}}
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror 
                            </div>
                            <div class="form-group mb-3">
                                {{Form::label('password-confirm',__('Confirm Password'),array('class' => 'form-control-label'))}}
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
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
                                <button type="submit" class="btn btn-primary text-white">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>                        
                        </form>
                    </div>             
                </div>
            </div>
            <div class="col-xl-6 img-card-side">
                <div class="auth-img-content">
                    <img src="../assets/images/auth/img-auth-3.svg" alt="" class="img-fluid">
                    <h3 class="text-white mb-4 mt-5">"Attention is the new currency"</h3>
                    <p class="text-white">The more effortless the writing looks, the more effort the writer
                        actually put into the process.</p>
                </div>
            </div>
        </div>
    </div>
@endsection