@php( $store_logo=asset(Storage::url('uploads/store_logo/')))
@extends('layouts.admin')
@section('page-title')
    {{__('Blog')}}
@endsection
@section('title')
    {{__('Blog')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Blog') }}</li>
@endsection

@push('css-page')
    <link rel="stylesheet" href="{{asset('libs/summernote/summernote-bs4.css')}}">
@endpush
@push('script-page')
    <script src="{{asset('libs/summernote/summernote-bs4.js')}}"></script>
@endpush
@section('action-btn')
    <div class="btn btn-sm btn-primary btn-icon m-1">
        <a href="#" class="" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Social Media')}}" data-ajax-popup="true" data-size="lg" data-title="{{__('Manage Social Blog Button')}}" data-url="{{route('blog.social')}}"><i class="ti ti-social text-white"></i></a>
    </div>

    <div class="btn btn-sm btn-primary btn-icon m-1">
        <a href="#" class="" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Create Blog')}}" data-ajax-popup="true" data-size="lg" data-title="{{__('Create New Blog')}}" data-url="{{route('blog.create')}}"><i class="ti ti-plus text-white"></i></a>
    </div>
@endsection
@section('filter')
@endsection
@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <!-- Table -->
            <div class="card-header">
                <h5>{{__('All Blogs')}}</h5>
            </div>
            <!-- Table -->
            <div class="card-body table-border-style">
                <div class="table-responsive overflow_hidden">                
                    <table id="pc-dt-simple" class="table">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="sort" data-sort="name">{{__('Blog Cover Image')}}</th>
                                <th scope="col" class="sort" data-sort="name">{{__('Title')}}</th>
                                <th scope="col" class="sort" data-sort="name">{{__('Created At')}}</th>
                                <th class="text-right">{{__('Action')}}</th>
                            </tr>
                        </thead>
                        @if(!empty($blogs) && count($blogs) >0)
                            <tbody>
                                @foreach($blogs as $blog)
                                    <tr data-name="{{$blog->title}}">
                                        <td scope="row">
                                            <div class="media align-items-center">
                                                <div>
                                                    <a href="{{$store_logo}}/{{$blog->blog_cover_image}}" target="_blank">
                                                        <img alt="Image placeholder" src="{{$store_logo}}/{{$blog->blog_cover_image}}" class="rounded-circle" style="width: 80px; height: 60px;">
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        </td>
                                        <td class="sorting_1">{{$blog->title}}</td>
                                        <td class="sorting_1">{{ Utility::dateFormat($blog->created_at)}}</td>
                                        <td class="action text-right">
                                            <div class="action-btn bg-info ms-2">                                                    
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Edit Blog')}}" data-ajax-popup="true" data-size="lg" data-title="{{__('Edit Blog')}}" data-url="{{route('blog.edit',[$blog->id])}}"><i class="ti ti-edit text-white"></i></a>
                                            </div>
                                            <div class="action-btn bg-danger ms-2">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['blog.destroy', $blog->id] ]) !!}
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
                                            <h6>{{__('Please Upload Practices Files')}}. </h6>
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

