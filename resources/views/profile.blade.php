@extends('layouts.admin')
@php
    // $profile=asset(Storage::url('uploads/profile/'));
    $profile=\App\Models\Utility::get_file('uploads/profile/');
    $default_profile=asset(Storage::url('uploads/default_avatar/avatar.png'));
@endphp
@section('page-title')
    {{__('Profile')}}
@endsection
@section('title')
     {{__('Profile')}}
@endsection
@section('breadcrumb')
     <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{__('Home')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('Profile')}}</li>
@endsection
@section('action-btn')
@endsection
@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-xl-3">
                <div class="card sticky-top" style="top:30px">
                    <div class="list-group list-group-flush" id="useradd-sidenav">                       
                        <a href="#personal-info" id="Personal_Info_tab"
                            class="list-group-item list-group-item-action border-0 active">{{ __('Personal Info') }}
                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#change-password" id="Change_Password_tab"
                            class="list-group-item list-group-item-action border-0">{{ __('Change Password') }}
                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>                          
                    </div>
                </div>
            </div>
            <div class="col-xl-9">
                <div id="personal-info" class="card">
                    <div class="card-header">
                        <h5 class="mb-2">{{ __('Personal Info') }}</h5>
                    </div>                            
                    {{Form::model($userDetail,array('route' => array('update.account'), 'method' => 'put', 'enctype' => "multipart/form-data"))}}                        
                        <div class="card-body py-3">
                            <div class="row row-grid align-items-center">
                                <div class="col-lg-8">
                                    <div class="form-group">   
                                        {{Form::label('file',__('Avatar'),array('class'=>'form-label'))}}
                                        <div class="media align-items-center">                                            
                                            <a href="{{(!empty($userDetail->avatar))? $profile.'/'.$userDetail->avatar : $default_profile}}" class="avatar avatar-lg rounded-circle mr-3" target="_blank">
                                                {{-- <img  src="{{(!empty($userDetail->avatar))? $profile.'/'.$userDetail->avatar : $default_profile}}"> --}}
                                                <img alt="Image placeholder" src="{{ (!empty($userDetail->avatar)) ? $profile.'/'.$userDetail->avatar : $default_profile }}" id="blah" width="100px"/>
                                            </a>    
                                            <div class="choose-files position-sticky ms-4">
                                                <label for="file-1">
                                                    <div class="bg-primary company_logo_update"> <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                    </div>
                                                    <input type="file" name="profile" id="file-1" class="form-control file" data-multiple-caption="{count} files selected"  onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])" multiple>
                                                </label>                                                    
                                            </div>   
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                @if(\Auth::user()->type=='client')
                                    @php $client=$userDetail->clientDetail; @endphp
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{Form::label('name',__('Name'),array('class'=>'form-label'))}}
                                            {{Form::text('name',null,array('class'=>'form-control font-style'))}}
                                            @error('name')
                                                <span class="invalid-name" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        {{Form::label('email',__('Email'),array('class'=>'form-label'))}}
                                        {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter User Email')))}}
                                        @error('email')
                                            <span class="invalid-email" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        {{Form::label('mobile',__('Mobile'),array('class'=>'form-label'))}}
                                        {{Form::number('mobile',$client->mobile,array('class'=>'form-control'))}}
                                        @error('mobile')
                                            <span class="invalid-mobile" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        {{Form::label('address_1',__('Address 1'),array('class'=>'form-label'))}}
                                        {{Form::textarea('address_1', $client->address_1, ['class'=>'form-control','rows'=>'4'])}}
                                        @error('address_1')
                                            <span class="invalid-address_1" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        {{Form::label('address_2',__('Address 2'),array('class'=>'form-label'))}}
                                        {{Form::textarea('address_2', $client->address_2, ['class'=>'form-control','rows'=>'4'])}}
                                        @error('address_2')
                                            <span class="invalid-address_2" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        {{Form::label('city',__('City'),array('class'=>'form-label'))}}
                                        {{Form::text('city',$client->city,array('class'=>'form-control'))}}
                                        @error('city')
                                            <span class="invalid-city" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        {{Form::label('state',__('State'),array('class'=>'form-label'))}}
                                        {{Form::text('state',$client->state,array('class'=>'form-control'))}}
                                        @error('state')
                                            <span class="invalid-state" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        {{Form::label('country',__('Country'),array('class'=>'form-label'))}}
                                        {{Form::text('country',$client->country,array('class'=>'form-control'))}}
                                        @error('country')
                                            <span class="invalid-country" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        {{Form::label('zip_code',__('Zip Code'),array('class'=>'form-label'))}}
                                        {{Form::text('zip_code',$client->zip_code,array('class'=>'form-control'))}}
                                        @error('zip_code')
                                            <span class="invalid-zip_code" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                @else
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{Form::label('name',__('Name'),array('class'=>'form-label'))}}
                                            {{Form::text('name',null,array('class'=>'form-control font-style'))}}
                                            @error('name')
                                                <span class="invalid-name" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{Form::label('degree',__('Degree'),array('class'=>'form-label'))}}
                                            {{Form::text('degree',null,array('class'=>'form-control','placeholder'=>__('')))}}
                                            @error('degree')
                                                <span class="invalid-degree" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{Form::label('email',__('Email'),array('class'=>'form-label'))}}
                                            {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter User Email')))}}
                                            @error('email')
                                                <span class="invalid-email" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {{Form::label('about',__('About'),array('class'=>'form-label'))}}
                                            {{Form::textarea('about',null,array('class'=>'form-control','placeholder'=>__('')))}}
                                            @error('about')
                                                <span class="invalid-about" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {{Form::label('description',__('Description'),array('class'=>'form-label'))}}
                                            {{Form::textarea('description',null,array('class'=>'form-control','placeholder'=>__('')))}}
                                            @error('description')
                                                <span class="invalid-description" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            {{Form::submit(__('Save Changes'),array('class'=>'btn btn-primary'))}}
                        </div>
                    
                    {{Form::close()}}                          
                </div>

                <div id="change-password" class="card">
                    <div class="card-header">
                        <h5 class="mb-2">{{ __('Change Password') }}</h5>
                    </div> 
                    {{Form::model($userDetail,array('route' => array('update.password',$userDetail->id), 'method' => 'put'))}}
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{Form::label('current_password',__('Current Password'))}}
                                        {{Form::password('current_password',array('class'=>'form-control','placeholder'=>__('Enter Current Password')))}}
                                        @error('current_password')
                                            <span class="invalid-current_password" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{Form::label('new_password',__('New Password'))}}
                                        {{Form::password('new_password',array('class'=>'form-control','placeholder'=>__('Enter New Password')))}}
                                        @error('new_password')
                                            <span class="invalid-new_password" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{Form::label('confirm_password',__('Re-type New Password'))}}
                                        {{Form::password('confirm_password',array('class'=>'form-control','placeholder'=>__('Enter Re-type New Password')))}}
                                        @error('confirm_password')
                                            <span class="invalid-confirm_password" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            {{Form::submit(__('Update'),array('class'=>'btn btn-primary'))}}
                        </div>
                    {{Form::close()}}
                </div> 
            </div>
        </div>
    </div>
</div>


@endsection

@push('script-page')
    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300,
        })
        $(".list-group-item").click(function() {
            $('.list-group-item').filter(function() {
                return this.href == id;
            }).parent().removeClass('text-primary');
        });
    </script>
@endpush

