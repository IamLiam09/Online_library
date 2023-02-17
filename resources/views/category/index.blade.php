@extends('layouts.admin')
@section('page-title')
    {{__('Category')}}
@endsection
@section('title')
    {{__('Categories')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Category') }}</li>
@endsection
@section('action-btn')
    
    <div class="btn btn-sm btn-primary btn-icon m-1">
        <a href="#" class="" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Add Category')}}" data-ajax-popup="true" data-size="lg" data-title="{{__('Add Category')}}" data-url="{{route('category.create')}}"><i class="ti ti-plus text-white"></i></a>
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
@php
    
$category_image=\App\Models\Utility::get_file('uploads/category_image/');

@endphp
@section('content')

    <!-- Listing -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h5>{{__('All Categories')}}</h5>
                </div>
                <!-- Table -->
                <div class="card-body table-border-style">
                    <div class="table-responsive overflow_hidden">
                        <table id="pc-dt-simple" class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('Image')}}</th>
                                    <th scope="col">{{ __('Name')}}</th>
                                    <th scope="col">{{ __('Created at')}}</th>
                                    <th scope="col" class="text-center">{{ __('Action')}}</th>
                                </tr>
                            </thead>
                            @if(count($categorise) > 0 && !empty($categorise))
                                <tbody class="list">
                                    @foreach ($categorise as $category)
                                        <tr>
                                            <td scope="row">
                                                <div class="media align-items-center">
                                                    <div class="cate-img-shadow">
                                                        
                                                        @if(!empty($category->category_image))
                                                            <a href="{{$category_image.$category->category_image}}" target="_blank">
                                                                <img alt="Image placeholder" src="{{$category_image.$category->category_image}}" class="" style="width: 80px;">
                                                            </a>
                                                        @else
                                                            <a href="{{asset(Storage::url('uploads/category_image/default.png'))}}" target="_blank">
                                                                <img alt="Image placeholder" src="{{asset(Storage::url('uploads/category_image/default.png'))}}" class="" style="width: 80px;">
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td> {{ $category->name }} </td>
                                            <td> {{ Utility::dateFormat($category->created_at)}} </td>
                                            <td class="text-center">
                                                <div class="action-btn bg-info ms-2">                                                    
                                                    <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Edit Category')}}" data-ajax-popup="true" data-size="lg" data-title="{{__('Edit Category')}}" data-url="{{route('category.edit',[$category->id])}}"><i class="ti ti-edit text-white"></i></a>
                                                </div>

                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['category.destroy', $category->id] ]) !!}
                                                        <a href="#!" class="mx-3 btn btn-sm  align-items-center show_confirm" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Delete')}}">
                                                            <i class="ti ti-trash text-white"></i>
                                                        </a>
                                                    {!! Form::close() !!}
                                                </div>
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
