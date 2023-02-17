@extends('layouts.admin')
@section('page-title')
    {{__('Sub Domain')}}
@endsection
@section('title')
    {{__('Sub-Domain')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('store-resource.index') }}">{{ __('Store') }}</a></li>
    <li class="breadcrumb-item">{{ __('Sub-Domain') }}</li>
@endsection
@section('action-btn')

    
    <div class="btn btn-sm btn-primary btn-icon m-1">
        <a href="{{ route('store.customDomain') }}" class="" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Custom Domain')}}"><i class="ti ti-home-2 text-white"></i></a>
        {{-- {{__('Custom Domain')}} --}}
    </div>
    <div class="btn btn-sm btn-primary btn-icon m-1">
        <a href="{{ route('store.grid') }}" class="" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Grid View')}}"><i class="ti ti-layout-grid text-white"></i></a>
        {{-- {{__('Grid View')}} --}}
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
@push('css-page')
@endpush
@section('content')

    <!-- Listing -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-border-style">             
                    <div class="table-responsive overflow_hidden">
                        <table id="pc-dt-simple" class="table align-items-center">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('Custom Domain Name')}}</th>
                                    <th scope="col">{{ __('Store Name')}}</th>
                                    <th scope="col">{{ __('Email')}}</th>

                                </tr>
                            </thead>
                            <tbody class="list">
                                @if(count($stores) > 0)
                                    @foreach($stores as $store)
                                        <tr>
                                            <td>
                                                {{$store->subdomain}}
                                            </td>
                                            <td>
                                                {{$store->name}}
                                            </td>
                                            <td>
                                                {{($store->email)}}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="font-style">
                                        <td colspan="6" class="text-center"><h6 class="text-center">{{__('No Domain Found.')}}</h6></td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
