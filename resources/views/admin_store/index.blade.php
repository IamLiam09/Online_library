@extends('layouts.admin')
@section('page-title')
    {{__('Store')}}
@endsection
@section('title')
    {{__('Store')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('stores') }}</li>
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
        <a href="{{ route('store.grid') }}" class="" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Grid View')}}"><i class="ti ti-layout-grid text-white"></i></a>
        {{-- {{__('Grid View')}} --}}
    </div>
    <div class="btn btn-sm btn-primary btn-icon m-1">
        <a href="#" class="" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Create New Store')}}" data-ajax-popup="true" data-size="md" data-title="{{__('Create New Store')}}" data-url="{{ route('store-resource.create') }}"><i class="ti ti-plus text-white"></i></a>
    </div>

@endsection
@section('filter')
@endsection
@push('css-page')
    <link rel="stylesheet" href="{{asset('libs/summernote/summernote-bs4.css')}}">
@endpush
@push('script-page')
    <script src="{{asset('libs/summernote/summernote-bs4.js')}}"></script>
@endpush



@section('content')


<div class="row">
    
    <!-- [ sample-page ] start -->
    <div class="col-sm-12">
        <div class="card">
            <!-- Card header -->
            <div class="card-header">
                <h5>{{__('All Store')}}</h5>
            </div>
            <!-- Table -->
            <div class="card-body table-border-style">
                <div class="table-responsive overflow_hidden">
                    <table id="pc-dt-simple" class="table align-items-center">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">{{ __('User Name')}}</th>
                                <th scope="col">{{ __('Email')}}</th>
                                <th scope="col">{{ __('Stores')}}</th>
                                <th scope="col">{{ __('Plan')}}</th>
                                <th scope="col">{{ __('Created At')}}</th>
                                <th scope="col" class="text-center">{{ __('Action')}}</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @foreach($users as $usr)
                                <tr>
                                    <td>{{ $usr->name }}</td>
                                    <td>{{ $usr->email }}</td>
                                    <td>{{ $usr->stores->count() }}</td>
                                    <td>{{ !empty($usr->currentPlan->name)?$usr->currentPlan->name:'-' }}</td>
                                    <td>{{ Utility::dateFormat($usr->created_at)}}</td>
                                    <td class="text-center">   
                                        {{-- text-right --}}
                                        <!-- Actions -->
                                        <div class="action-btn bg-info ms-2">
                                            <a href="#" data-size="md" data-url="{{ route('store-resource.edit',$usr->id) }}" data-ajax-popup="true" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-title="{{__('Edit Store')}}"  data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Edit Store')}}" data-original-title="Edit">
                                                <span class="text-white">  <i class="ti ti-edit"></i> </span>
                                            </a>
                                        </div>   
                                            {{-- <a href="#" class="action-item" data-size="lg" data-url="{{ route('plan.upgrade',$usr->id) }}" data-ajax-popup="true" data-toggle="tooltip" data-title="{{__('Upgrade Plan')}}">
                                                <i class="fas fa-trophy"></i>
                                            </a> --}}

                                        <div class="action-btn bg-success ms-2">
                                            <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-size="md" data-url="{{ route('plan.upgrade',$usr->id) }}" data-ajax-popup="true" data-title="{{__('Upgrade Plan')}}" data-bs-toggle="tooltip" title="{{__('Upgrade Plan')}}">
                                                <span class="text-white">  <i class="ti ti-trophy"></i> </span>
                                            </a>
                                        </div> 
                                            {{-- <a href="#" class="action-item" data-size="lg" data-url="{{route('store.reset',\Crypt::encrypt($usr->id))}}" data-ajax-popup="true" data-title="{{__('Reset Password')}}" data-toggle="tooltip" data-original-title="{{__('Reset Password')}}">
                                                <i class="fas fa-key"></i>
                                            </a> --}}
                                            
                                        <div class="action-btn bg-warning ms-2">
                                            <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-size="md" data-url="{{route('store.reset',\Crypt::encrypt($usr->id))}}" data-ajax-popup="true" data-title="{{__('Reset Password')}}" data-bs-toggle="tooltip" title="{{__('Reset Password')}}">
                                                <span class="text-white"> <i class="ti ti-key"></i> </span>
                                            </a>
                                        </div>

                                        <div class="action-btn bg-danger ms-2">
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['store-resource.destroy', $usr->id]]) !!}
                                                <a href="#!" class="mx-3 btn btn-sm align-items-center show_confirm" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Delete')}}">
                                                    <i class="ti ti-trash text-white"></i>
                                                </a>
                                            {!! Form::close() !!}
                                        </div>                                            
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>        
    </div>
    <!-- [ sample-page ] end -->
</div>

@endsection
@push('script-page')
    <script>
        $(document).on('click', '#billing_data', function () {
            $("[name='shipping_address']").val($("[name='billing_address']").val());
            $("[name='shipping_city']").val($("[name='billing_city']").val());
            $("[name='shipping_state']").val($("[name='billing_state']").val());
            $("[name='shipping_country']").val($("[name='billing_country']").val());
            $("[name='shipping_postalcode']").val($("[name='billing_postalcode']").val());
        })
    </script>
@endpush

