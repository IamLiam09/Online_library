@extends('layouts.admin')
@section('page-title')
    {{__('Custom Page')}}
@endsection
@section('title')
    {{__('Custom Page')}}
@endsection
@push('css-page')
    <link rel="stylesheet" href="{{asset('libs/summernote/summernote-bs4.css')}}">
@endpush
@push('script-page')
    <script src="{{asset('libs/summernote/summernote-bs4.js')}}"></script>
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Custom-Page') }}</li>
@endsection


@section('action-btn')
    {{-- <a href="#" data-size="lg" data-url="{{ route('custom-page.create') }}" data-ajax-popup="true" data-title="{{__('Create New Page')}}" class="btn btn-sm btn-white btn-icon-only rounded-circle">
        <i class="fa fa-plus"></i>
    </a> --}}

    <div class="btn btn-sm btn-primary btn-icon m-1">
        <a href="#" class="" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Create New Page')}}" data-ajax-popup="true" data-size="lg" data-title="{{__('Create New Page')}}" data-url="{{route('custom-page.create')}}"><i class="ti ti-plus text-white"></i></a>
    </div>

@endsection
@section('filter')
@endsection
@section('content')
<div class="row">
    <div class="col-sm-12">   
        <div class="card">
            <div class="card-header">
                <h5>{{__('All Pages')}}</h5>
            </div>
                    
            <!-- Table -->
            <div class="card-body table-border-style">
                <div class="table-responsive overflow_hidden">
                    <table id="pc-dt-simple" class="table employee_tableese">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="sort" data-sort="name">{{__('Name')}}</th>
                                <th scope="col" class="sort" data-sort="name">{{__('Page Slug')}}</th>
                                <th scope="col" class="sort" data-sort="name">{{__('Header')}}</th>
                                <th scope="col" class="sort" data-sort="name">{{__('Footer')}}</th>
                                <th class="text-right">{{__('Action')}}</th>
                            </tr>
                        </thead>
                        @if(count($pageoptions) > 0 && !empty($pageoptions))
                            <tbody>
                                @foreach($pageoptions as $pageoption)
                                    <tr data-name="{{$pageoption->name}}">
                                        <td class="sorting_1">{{$pageoption->name}}</td>
                                        @if($store && $store->enable_domain == 'on')
                                            <td class="sorting_1">{{$store->domains . '/page/' . $pageoption->slug}}</td>
                                        @elseif($sub_store && $sub_store->enable_subdomain == 'on')
                                            <td class="sorting_1">{{$sub_store->subdomain . '/page/' . $pageoption->slug}}</td>
                                        @else
                                            <td class="sorting_1">{{env('APP_URL') . '/page/' . $pageoption->slug}}</td>
                                        @endif
                                        <td class="sorting_1">{{ucfirst(($pageoption->enable_page_header == 'on')?$pageoption->enable_page_header:'Off')}}</td>
                                        <td class="sorting_1">{{ucfirst(($pageoption->enable_page_footer == 'on')?$pageoption->enable_page_footer:'Off')}}</td>
                                        <td class="action text-right">
                                            <div class="action-btn bg-info ms-2">                                                    
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Edit Custom Page')}}" data-ajax-popup="true" data-size="lg" data-title="{{__('Edit Custom Page')}}" data-url="{{route('custom-page.edit',$pageoption->id)}}"><i class="ti ti-edit text-white"></i></a>
                                            </div>

                                            <div class="action-btn bg-danger ms-2">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['custom-page.destroy', $pageoption->id] ]) !!}
                                                    <a href="#!" class="mx-3 btn btn-sm align-items-center show_confirm" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Delete')}}">
                                                        <i class="ti ti-trash text-white"></i>
                                                    </a>
                                                {!! Form::close() !!}
                                            </div>

                                            {{-- <a href="#" data-size="lg" data-url="{{ route('custom-page.edit',$pageoption->id) }}" data-ajax-popup="true" data-title="{{__('Edit Custom Page')}}" class="action-item">
                                                <i class="far fa-edit"></i>
                                            </a>
                                            <a href="#" class="action-item" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').' | '.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$pageoption->id}}').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['custom-page.destroy', $pageoption->id],'id'=>'delete-form-'.$pageoption->id]) !!}
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
@push('script-page')
    <script>
        $(document).ready(function () {
            $(document).on('keyup', '.search-user', function () {
                var value = $(this).val();
                $('.employee_tableese tbody>tr').each(function (index) {
                    var name = $(this).attr('data-name');
                    if (name.includes(value)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>
@endpush

