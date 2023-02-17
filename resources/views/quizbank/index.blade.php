@extends('layouts.admin')
@section('page-title')
    {{__('Quiz')}}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block text-white font-weight-bold mb-0 ">{{__('Quiz Content')}}</h5>
    </div>
@endsection
@section('breadcrumb')
@endsection
@section('action-btn')
    <a href="#" data-size="lg" data-url="{{route('quizbank.create')}}" data-ajax-popup="true" data-title="{{__('Create Quizzes')}}" class="btn btn-sm btn-white btn-icon-only rounded-circle">
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
    <script type='text/javascript'>
        $(document).on('click', '#create_fields', function () {
            addFields();
        });
       /* $(document).on('click', '.delete-options', function () {
            var id = ($(this).attr('id'));
            $("#d-"+id).remove();
            var k = $('.options').length;
            console.log(k);
        });*/

        function addFields() {

            var number = document.getElementById("option_count").value;
            var container = document.getElementById("options_div");
            var place = 0;
            var k = $('.options').length;
            var total_count = parseInt(k) + parseInt(number);

            for (i = k; i < total_count; i++) {
                place = i + 1;
                var div =
                    '<div class="form-group" id="d-'+ i +'">\n' +
                    '    <div class="input-group">\n' +
                    '      <div class="input-group-prepend" style="height:60px;">\n' +
                    '        <div class="input-group-text">\n' +
                    '          <input type="checkbox" class="mt-0" name="answer[' + i + ']">\n' +
                    '        </div>\n' +
                    '      </div>\n' +
                    '      <input type="text" class="form-control options" name="options[' + i + ']" placeholder="Option ' + place + '">\n' +
                    '    </div>\n' +
                    '</div>';
                $(div).appendTo('#options_div');
            }

        }
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
                    <h6 class="d-inline-block mb-0">{!! $quiz_name->title !!}</h6>
                </div>
            </div>
        </div>
        <!-- Table -->
        <div class="table-responsive">
            <table class="table align-items-center">
                <thead>
                <tr>
                    <th scope="col">{{ __('Question')}}</th>
                    <th scope="col" class="text-right">{{ __('Action')}}</th>
                </tr>
                </thead>
                <tbody class="list">
                @foreach($quiz as $quiz)
                    <tr>
                        <td>{{$quiz->question}}</td>
                        <td class="text-right">
                            <!-- Actions -->
                            <div class="actions ml-3">
                                <a href="#" class="action-item mr-2" data-size="lg" data-url="{{route('quizbank.show',$quiz->id)}}" data-ajax-popup="true" data-title="{{__('Quiz Details')}}" data-original-title="Full details" data-toggle="tooltip">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="#" class="action-item mr-2" data-toggle="tooltip" title="Edit" data-size="lg" data-url="{{route('quizbank.edit',$quiz->id)}}" data-ajax-popup="true" data-title="{{__('Edit Quiz')}}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" class="action-item  mr-2 " data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').' | '.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$quiz->id}}').submit();">
                                    <i class="fas fa-trash"></i>
                                </a>
                                {!! Form::open(['method' => 'DELETE', 'route' => ['quizbank.destroy', $quiz->id],'id'=>'delete-form-'.$quiz->id]) !!}
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

