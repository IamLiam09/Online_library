@extends('layouts.admin')
@section('page-title')
    {{__('Store')}}
@endsection
@section('title')
    {{__('Store')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{__('Home')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('User')}}</li>
@endsection
@section('action-btn')

    <div class="btn btn-sm btn-primary btn-icon m-1">
        <a href="{{ route('store.subDomain') }}" class="" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Sub Domain')}}"><i class="ti ti-subtask text-white"></i></a>
        {{-- {{__('Sub Domain')}} --}}
    </div>
    <div class="btn btn-sm btn-primary btn-icon m-1">
        <a href="{{ route('store.customDomain') }}" class="" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Custom Domain')}}"><i class="ti ti-home-2 text-white"></i></a>
        {{-- {{__('Custom Domain')}} --}}
    </div>

    <div class="btn btn-sm btn-primary btn-icon m-1">
        <a href="{{ route('store-resource.index') }}" class="" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('List View')}}"><i class="ti ti-list text-white"></i></a>
        {{-- {{__('List view')}} --}}
    </div> 
    <div class="btn btn-sm btn-primary btn-icon m-1">
        <a href="#" class="" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Create New Store')}}" data-ajax-popup="true" data-size="md" data-title="{{__('Create New Store')}}" data-url="{{ route('store-resource.create') }}"><i class="ti ti-plus text-white"></i></a>
    </div>

@endsection
@section('filter')
@endsection
@section('content')

    @if(\Auth::user()->type = 'super admin')
        <div class="row">
            @foreach($users as $user)
                <div class="col-md-4 col-xxl-3">
                    <div class="card hover-shadow-lg">
                        <div class="card-header border-0 pb-0">
                            <div class="card-header-right">
                                <div class="btn-group card-option">
                                    <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>

                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                        <a href="#" data-size="md" data-url="{{ route('user.edit',$user->id) }}" data-ajax-popup="true" data-size="md" data-title="{{__('Edit User')}}" class="dropdown-item"><i class="ti ti-edit"></i>
                                            <span>{{ __('Edit') }}</span></a>

                                        <a href="#" data-size="md" data-url="{{ route('plan.upgrade',$user->id) }}" data-ajax-popup="true" class="dropdown-item"><i class="ti ti-trophy"></i>
                                            <span>{{ __('Upgrade Plan') }}</span></a>
    
                                        <a class="bs-pass-para dropdown-item trigger--fire-modal-1" href="#"
                                            data-title="{{ __('Delete') }}" data-confirm="{{ __('Are You Sure?') }}"
                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                            data-confirm-yes="delete-form-{{ $user->id }}">
                                            <i class="ti ti-trash"></i><span>{{ __('Delete') }} </span>
                                        </a>
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['user.destroy', $user->id], 'id' => 'delete-form-' . $user->id]) !!}
                                        {!! Form::close() !!}
    
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <div class="avatar-parent-child">
                                <img alt="" src="{{ asset(Storage::url("uploads/profile/")).'/'}}{{ !empty($user->avatar)?$user->avatar:'avatar.png' }}" class="img-fluid rounded-circle card-avatar">
                            </div>
    
                            <h5 class="h6 mt-4 mb-0"> {{$user->name}}</h5>
                            <a href="#" class="d-block text-sm text-muted my-4"> {{$user->email}}</a>
                            <div class="card mb-0 mt-3">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-6">
                                            <h6 class="mb-0">{{$user->countProducts($user->id)}}</h6>
                                            <p class="text-muted text-sm mb-0">{{ __('Products')}}</p>
                                        </div>
                                        <div class="col-6">
                                            <h6 class="mb-0">{{$user->countStores($user->id)}}</h6>
                                            <p class="text-muted text-sm mb-0">{{ __('Stores')}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="actions d-flex justify-content-between">
                                <span class="d-block text-sm text-muted"> {{__('Plan') }} : {{$user->currentPlan->name}}</span>
                            </div>
                            <div class="actions d-flex justify-content-between mt-1">
                                <span class="d-block text-sm text-muted">{{__('Plan Expired') }} : {{!empty($user->plan_expire_date) ? \Auth::user()->dateFormat($user->plan_expire_date):'Unlimited'}}</span>
                            </div>
                        </div>                        
                    </div>
                </div>
            @endforeach

            <div class="col-md-3">
                <a href="#" class="btn-addnew-project"  data-ajax-popup="true" data-size="md" data-title="{{ __('Create New Store') }}" data-url="{{route('store-resource.create')}}">
                    <div class="bg-primary proj-add-icon">
                        <i class="ti ti-plus"></i>
                    </div>
                    <h6 class="mt-4 mb-2">{{ __('New Store') }}</h6>
                    <p class="text-muted text-center">{{ __('Click here to add New Store') }}</p>
                </a>
            </div>

        </div>
    @endif
    
@endsection
