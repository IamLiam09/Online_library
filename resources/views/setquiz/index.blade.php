@extends('layouts.admin')
@section('page-title')
    {{__('Set Quiz')}}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block text-white font-weight-bold mb-0 ">{{__('Set Quiz')}}</h5>
    </div>
@endsection
@section('breadcrumb')
@endsection
@section('action-btn')
    <a href="#" data-size="lg" data-url="{{route('setquiz.create')}}" data-ajax-popup="true" data-title="{{__('Set a quiz')}}" class="btn btn-sm btn-white btn-icon-only rounded-circle">
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

    {{--Subcategory for create--}}
    <script>
        $(document).on('change', '#category_id', function () {
            var category_id = $(this).val();
            getSubcategory(category_id);
        });

        function getSubcategory(cid) {
            /*console.log(cid);
            return false;*/
            $.ajax({
                url: '{{route('course.getsubcategory')}}',
                type: 'POST',
                data: {
                    "category_id": cid, "_token": "{{ csrf_token() }}",
                },
                success: function (data) {
                    $('#subcategory').empty();
                    $('#subcategory').append('<option value="">{{__('Select Subcategory')}}</option>');

                    $.each(data, function (key, value) {
                        $('#subcategory').append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            });
        }
    </script>
    {{--Subcategory for edit--}}
    {{--Switches--}}
    <script>
        $(document).ready(function(){

            $(document).on('click', '#customSwitches', function() {
                if($(this).is(":checked"))
                {
                    $('#total-time').addClass('d-none');
                    $('#total-time').removeClass('d-block');
                    $('#time-count').addClass('d-block');
                    $('#time-count').removeClass('d-none');
                }else{
                    $('#time-count').addClass('d-none');
                    $('#time-count').removeClass('d-block');
                    $('#total-time').addClass('d-block');
                    $('#total-time').removeClass('d-none');
                }
            });

            $(document).on('click', '#customSwitches2', function() {
                if($(this).is(":checked"))
                {
                    $('#show-result').addClass('d-none');
                    $('#show-result').removeClass('d-block');
                }else{
                    $('#show-result').addClass('d-block');
                    $('#show-result').removeClass('d-none');
                }
            });
        });
    </script>
@endpush
@section('content')
    <!-- Listing -->
    <div class="card">
        <!-- Card header -->
        <div class="card-header actions-toolbar">
            <div class="actions-search" id="actions-search">
                <div class="input-group input-group-merge input-group-flush">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-transparent"><i class="far fa-search"></i></span>
                    </div>
                    <input type="text" class="form-control form-control-flush" placeholder="Type and hit enter ...">
                    <div class="input-group-append">
                        <a href="#" class="input-group-text bg-transparent" data-action="search-close" data-target="#actions-search"><i class="far fa-times"></i></a>
                    </div>
                </div>
            </div>
            <div class="row justify-content-between align-items-center">
                <div class="col">
                    <h6 class="d-inline-block mb-0">{{__('Quiz')}}</h6>
                </div>
            </div>
        </div>
        <!-- Table -->
        <div class="table-responsive">
            <table class="table align-items-center">
                <thead>
                <tr>
                    <th scope="col">{{ __('Title')}}</th>
                    <th scope="col">{{ __('Category')}}</th>
                    <th scope="col">{{ __('Subcategory')}}</th>
                    <th scope="col">{{ __('Passing marks')}}</th>
                    <th scope="col">{{ __('Created at')}}</th>
                    <th scope="col" class="text-right">{{ __('Action')}}</th>
                </tr>
                </thead>
                <tbody class="list">
                @foreach($quiz as $quiz)
                    <tr>
                        <td>{{$quiz->title}}</td>
                        <td>{{$quiz->category_id->name}}</td>
                        <td>{{$quiz->subcategory_id->name}}</td>
                        <td>{{$quiz->min_percentage}}</td>
                        <td>{{ Utility::dateFormat( $quiz->created_at)}}</td>
                        <td class="text-right">
                            <!-- Actions -->
                            <div class="actions ml-3">
                                <a href="{{route('quizbank.viewcontent',\Illuminate\Support\Facades\Crypt::encrypt($quiz->id))}}" class="action-item" data-toggle="tooltip" data-original-title="{{__('MCQs')}}">
                                    <i class="fas fa-question-circle"></i>
                                </a>
                                <a href="#" class="action-item" data-size="lg" data-ajax-popup="true" data-toggle="tooltip" data-original-title="{{__('Edit')}}" data-url="{{route('setquiz.edit',$quiz->id)}}" id="edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" class="action-item  mr-2 " data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').' | '.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$quiz->id}}').submit();">
                                    <i class="fas fa-trash"></i>
                                </a>
                                {!! Form::open(['method' => 'DELETE', 'route' => ['setquiz.destroy', $quiz->id],'id'=>'delete-form-'.$quiz->id]) !!}
                                {!! Form::close() !!}
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

