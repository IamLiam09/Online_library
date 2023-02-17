@extends('layouts.admin')
@section('page-title')
    {{__('Content')}}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block text-white font-weight-bold mb-0 ">{{__('Subcategory-Content')}}</h5>
    </div>
@endsection
@section('breadcrumb')
@endsection
@section('action-btn')
    <a href="#" data-size="lg" data-url="{{route('subcontent.create')}}" data-ajax-popup="true" data-title="{{__('Create Content')}}" class="btn btn-sm btn-white btn-icon-only rounded-circle">
        <i class="fa fa-plus"></i>
    </a>
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
    <!-- Listing -->
    <div class="card">
        <!-- Card header -->
        <div class="card-header actions-toolbar">
            <div class="row justify-content-between align-items-center">
                <div class="col">
                    <h6 class="d-inline-block mb-0">Content of : {{$sub_name->name}}</h6>
                </div>
            </div>
        </div>
        <!-- Table -->
        <div class="table-responsive">
            <table class="table align-items-center">
                <thead>
                <tr>
                    <th scope="col">{{ __('Chapter name')}}</th>
                    <th scope="col">{{ __('Content count')}}</th>
                    <th scope="col">{{ __('Created at')}}</th>
                    <th scope="col" class="text-right">{{ __('Action')}}</th>
                </tr>
                </thead>
                <tbody class="list">
                @foreach ($content as $content)
                    <tr>
                        <td>{{ $content->title }}</td>
                        <td>{{($content->content_id != null)?$content->content_id->count():'0'}}</td>
                        <td> {{ Utility::dateFormat($content->created_at)}}</td>
                        <td class="text-right">
                            <a href="#" class="action-item" data-size="xl" data-ajax-popup="true" data-toggle="tooltip" data-original-title="{{__('Edit')}}" data-url="{{route('subcontent.edit',[$content->id])}}">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="#" class="action-item" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').' | '.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$content->id}}').submit();">
                                <i class="fas fa-trash"></i>
                            </a>
                            </a>
                            {!! Form::open(['method' => 'DELETE', 'route' => ['subcontent.destroy', $content->id],'id'=>'delete-form-'.$content->id]) !!}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

