@extends('layouts.admin')
@section('page-title')
    {{__('Subcategory')}}
@endsection
@section('title')
   {{__('Subcategory')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Subcategory') }}</li>
@endsection
@section('action-btn')
    {{-- <a href="#" data-size="lg" data-url="{{route('subcategory.create')}}" data-ajax-popup="true" data-title="{{__('Add Subcategory')}}" class="btn btn-sm btn-white btn-icon-only rounded-circle">
        <i class="fa fa-plus"></i>
    </a> --}}

    <div class="btn btn-sm btn-primary btn-icon m-1">
        <a href="#" class="" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Add Subcategory')}}" data-ajax-popup="true" data-size="lg" data-title="{{__('Add Subcategory')}}" data-url="{{route('subcategory.create')}}"><i class="ti ti-plus text-white"></i></a>
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
        <div class="col-sm-12">        
            <!-- Listing -->
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h5>{{__('All Subcategories')}}</h5>
                </div>
                <!-- Table -->
                <div class="card-body table-border-style">
                    <div class="table-responsive overflow_hidden">
                        <table id="pc-dt-simple" class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('Name')}}</th>
                                    <th scope="col">{{ __('Category')}}</th>
                                    <th scope="col">{{ __('Created at')}}</th>
                                    <th scope="col" class="text-right">{{ __('Action')}}</th>
                                </tr>
                            </thead>
                            @if(count($subcategorise) > 0 &&  !empty($subcategorise))
                                <tbody class="list">
                                    @foreach ($subcategorise as $subcategory)
                                        <tr>
                                            <td>{{ $subcategory->name }}</td>
                                            <td>{{!empty($subcategory->category_id->name)?$subcategory->category_id->name:''}}</td>
                                            <td> {{ Utility::dateFormat($subcategory->created_at)}}</td>
                                            <td class="text-right">

                                                <div class="action-btn bg-info ms-2">                                                    
                                                    <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Edit Subcategory')}}" data-ajax-popup="true" data-size="lg" data-title="{{__('Edit Subcategory')}}" data-url="{{route('subcategory.edit',[$subcategory->id])}}"><i class="ti ti-edit text-white"></i></a>
                                                </div>

                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['subcategory.destroy', $subcategory->id] ]) !!}
                                                        <a href="#!" class="mx-3 btn btn-sm align-items-center show_confirm" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Delete')}}">
                                                            <i class="ti ti-trash text-white"></i>
                                                        </a>
                                                    {!! Form::close() !!}
                                                </div>

                                                {{-- <a href="#" class="action-item" data-size="lg" data-ajax-popup="true" data-toggle="tooltip" data-original-title="{{__('Edit')}}" data-title="{{__('Edit Category')}}" data-url="{{route('subcategory.edit',[$subcategory->id])}}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="#" class="action-item" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').' | '.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$subcategory->id}}').submit();">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                </a>
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['subcategory.destroy', $subcategory->id],'id'=>'delete-form-'.$subcategory->id]) !!}
                                                {!! Form::close() !!} --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @else
                                <tbody>
                                    <tr>
                                        <td colspan="7">
                                            <div class="text-center">
                                                <i class="fas fa-folder-open text-primary" style="font-size: 48px;"></i>
                                                <h2>{{__('Opps')}}...</h2>
                                                <h6>{{__('No data Found')}}. </h6>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

