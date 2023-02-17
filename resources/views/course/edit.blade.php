@extends('layouts.admin')
@section('page-title')
    {{__('Course')}}
@endsection
@section('title')
    {{__('Edit Course')}}
@endsection
@section('breadcrumb')

    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('course.index') }}">{{ __('Course') }}</a></li>
    <li class="breadcrumb-item">{{ __('Edit') }}</li>

@endsection
@section('action-btn')
    <a href="{{ route('course.index') }}" class="btn btn-sm btn-white btn-icon-only rounded-circle">
        <i class="fa fa-arrow-left"></i>
    </a>
@endsection
@section('filter')
@endsection
@push('css-page')
    <link rel="stylesheet" href="{{asset('libs/summernote/summernote-bs4.css')}}">
    <style>
        .dropdown-toggle::after {
            content: none;
        }

        .dropdown-toggle {
            cursor: pointer;
        }

        .margin-r {
            margin-right: 44px;
        }
    </style>
@endpush
@push('script-page')
    <script src="{{asset('libs/summernote/summernote-bs4.js')}}"></script>
    {{--Switch--}}
    <script>
        $(document).ready(function () {
            type();
            $(document).on('click', '.code', function (e) {
                var type = $(this).val();
                if (type == 'Quiz') {
                    $('#quiz-content-1').removeClass('d-none');
                    $('#quiz-content-1').addClass('d-block');

                    $('#course-content-2').removeClass('d-block');
                    $('#course-content-2').addClass('d-none');
                    $('#course-content-3').removeClass('d-block');
                    $('#course-content-3').addClass('d-none');
                    $('#course-content-4').removeClass('d-block');
                    $('#course-content-4').addClass('d-none');

                    $('#subcategory').removeAttr('required');

                } else {
                    $('#course-content-2').removeClass('d-none');
                    $('#course-content-2').addClass('d-block');
                    $('#course-content-3').removeClass('d-none');
                    $('#course-content-3').addClass('d-block');
                    $('#course-content-4').removeClass('d-none');
                    $('#course-content-4').addClass('d-block');

                    $('#subcategory').attr('required');

                    $('#duration').removeClass('col-md-6');
                    $('#duration').addClass('col-md-6');


                    $('#quiz-content-1').removeClass('d-block');
                    $('#quiz-content-1').addClass('d-none');
                }
            });
            $(document).on('click', '#customSwitches', function () {
                if ($(this).is(":checked")) {
                    $('#price').addClass('d-none');
                    $('#price').removeClass('d-block');
                    $('#discount-div').addClass('d-none');
                    $('#discount-div').removeClass('d-block');
                } else {
                    $('#price').addClass('d-block');
                    $('#price').removeClass('d-none');
                    $('#discount-div').addClass('d-block');
                    $('#discount-div').removeClass('d-none');
                }
            });
            $(document).on('click', '#customSwitches2', function () {
                if ($(this).is(":checked")) {
                    $('#discount').addClass('d-block');
                    $('#discount').removeClass('d-none');
                } else {
                    $('#discount').addClass('d-none');
                    $('#discount').removeClass('d-block');
                }
            });

            function type() {
                if ($('#customSwitches3').is(":checked")) {
                    $('#preview_type').addClass('d-block');
                    $('#preview_type').removeClass('d-none');

                    preview();
                } else {
                    $('#preview_type').addClass('d-none');
                    $('#preview_type').removeClass('d-block');

                    $('#preview-iframe-div').addClass('d-none');
                    $('#preview-iframe-div').removeClass('d-block');

                    $('#preview-video-div').addClass('d-none');
                    $('#preview-video-div').removeClass('d-block');

                    $('#preview-image-div').addClass('d-none');
                    $('#preview-image-div').removeClass('d-block');

                }
            }

            $(document).on('click', '#customSwitches3', function () {
                if ($('#customSwitches3').is(":checked")) {
                    $('#preview_type').addClass('d-block');
                    $('#preview_type').removeClass('d-none');

                    preview();
                } else {
                    $('#preview_type').addClass('d-none');
                    $('#preview_type').removeClass('d-block');

                    $('#preview-iframe-div').addClass('d-none');
                    $('#preview-iframe-div').removeClass('d-block');

                    $('#preview-video-div').addClass('d-none');
                    $('#preview-video-div').removeClass('d-block');

                    $('#preview-image-div').addClass('d-none');
                    $('#preview-image-div').removeClass('d-block');

                }
            });

            function preview() {
                $("#preview_type").change(function () {
                    $(this).find("option:selected").each(function () {
                        var optionValue = $(this).attr("value");
                        if (optionValue == 'Image') {

                            $('#preview-image-div').removeClass('d-none');
                            $('#preview-image-div').addClass('d-block');

                            $('#preview-iframe-div').addClass('d-none');
                            $('#preview-iframe-div').removeClass('d-block');

                            $('#preview-video-div').addClass('d-none');
                            $('#preview-video-div').removeClass('d-block');

                        } else if (optionValue == 'iFrame') {

                            $('#preview-image-div').addClass('d-none');
                            $('#preview-image-div').removeClass('d-block');

                            $('#preview-iframe-div').removeClass('d-none');
                            $('#preview-iframe-div').addClass('d-block');

                            $('#preview-video-div').addClass('d-none');
                            $('#preview-video-div').removeClass('d-block');

                        } else if (optionValue == 'Video File') {

                            $('#preview-image-div').addClass('d-none');
                            $('#preview-image-div').removeClass('d-block');

                            $('#preview-iframe-div').addClass('d-none');
                            $('#preview-iframe-div').removeClass('d-block');


                            $('#preview-video-div').removeClass('d-none');
                            $('#preview-video-div').addClass('d-block');
                        }
                    });
                }).change();
            }
        });
    </script>
    {{--Subcategory--}}
    <script>
        function getSubcategory(cid) {
            $.ajax({
                url: '{{route('course.getsubcategory')}}',
                type: 'POST',
                data: {
                    "category_id": cid, "_token": "{{ csrf_token() }}",
                },
                success: function (data) {
                    $('#subcategory').empty();
                    $('#subcategory').append('<option value="" disabled>{{__('Select Subcategory')}}</option>');

                    $.each(data, function (key, value) {
                        var select = '';
                        if (key == '{{ $course->sub_category }}') {
                            select = 'selected';
                        }
                        $('#subcategory').append('<option value="' + key + '"' + select + '>' + value + '</option>');
                    });
                }
            });
        }

        $(document).on('change', '#category_id', function () {
            var category_id = $(this).val();
            getSubcategory(category_id);
        });
    </script>
    {{--Dropzone--}}
    <script>
        var Dropzones = function () {
            var e = $('[data-toggle="dropzone1"]'), t = $(".dz-preview");
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            e.length && (Dropzone.autoDiscover = !1, e.each(function () {
                var e, a, n, o, i;
                e = $(this), a = void 0 !== e.data("dropzone-multiple"), n = e.find(t), o = void 0, i = {
                    url: "{{route('course.practicesfiles',[$course_id])}}",
                    headers: {
                        'x-csrf-token': CSRF_TOKEN,
                    },
                    thumbnailWidth: null,
                    thumbnailHeight: null,
                    previewsContainer: n.get(0),
                    previewTemplate: n.html(),
                    maxFiles: 10,
                    parallelUploads: 10,
                    autoProcessQueue: true,
                    uploadMultiple: true,
                    acceptedFiles: a ? null : "image/*",
                    success: function (file, response) {

                       
                        if (response.status == "success") {
                            show_toastr('success', response.success, 'success');
                            setInterval('location.reload()', 1200);
                        } else {
                            show_toastr('Error', response.error, 'error');
                        }
                    },
                    error: function (file, response) {
                        if (response.error) {
                            show_toastr('Error', response.error, 'error');
                        } else {
                            show_toastr('Error', response.error, 'error');
                        }
                    },
                    init: function () {
                        var myDropzone = this;
                    }

                }, n.html(""), e.dropzone(i)
            }))
        }();

        /*FILE DELETE*/
        $(".deleteRecord").click(function () {
            var id = $(this).data("id");
            var token = $("meta[name='csrf-token']").attr("content");

            $.ajax(
            {
                url: '{{ route('practices.file.delete', '_id') }}'.replace('_id', id),
                type: 'DELETE',
                data: {
                    "id": id,
                    "_token": token,
                },
                success: function (response) {
                    show_toastr('Success', response.success, 'success');
                    $('.product_Image[data-id="' + response.id + '"]').remove();
                }, error: function (response) {
                    show_toastr('Error', response.error, 'error');
                }

            });
        });
    </script>

    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300,
        })

        $(".list-group-item").click(function(){
            $('.list-group-item').filter(function(){
                return this.href == id;
            }).parent().removeClass('text-primary');
        });

        function check_theme(color_val) {
            $('#theme_color').prop('checked', false);
            $('input[value="'+color_val+'"]').prop('checked', true);
        }
    </script>

    {{-- <script src="{{ asset('assets/js/plugins/tinymce/tinymce.min.js') }}"></script>
    <script>
        if ($(".pc-tinymce-2").length) {
            tinymce.init({
                selector: '.pc-tinymce-2',
                height: "400",
                content_style: 'body { font-family: "Inter", sans-serif; }'
            });
        }
    </script> --}}

@endpush
@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-xl-3">
                <div class="card sticky-top" style="top:30px">
                    <div class="list-group list-group-flush" id="useradd-sidenav">
                        <a href="#headerstab" class="list-group-item list-group-item-action border-0 active">{{ __('Create Content') }}</a>
                        <a href="#coursetab" class="list-group-item list-group-item-action border-0">{{ __('Edit Course') }}</a>
                        <a href="#practicestab" class="list-group-item list-group-item-action border-0">{{ __('Practice') }}</a>
                        <a href="#faqstab" class="list-group-item list-group-item-action border-0">{{ __('FAQs') }}</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-9">
                {{--HEADER--}}
                <div id="headerstab" class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h5 class="mb-0">{{ __('Create Content') }}</h5>
                            </div>

                            <div class="col-6 text-end">
                                <a href="#" data-url="{{route('headers.create',$course_id)}}" class="mx-3 btn btn-sm align-items-center action-btn bg-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Create Header')}}" data-ajax-popup="true" data-size="lg" data-title="{{__('Create Header')}}"><i class="ti ti-plus text-white"></i></a>
                            </div> 
                        </div>
                    </div>
                    <div class="card-body">
                        {{-- <form method="POST" action="{{route('headers.create',$course_id)}}" accept-charset="UTF-8">                            
                            
                            @csrf --}}
                            <div class="container-fluid">
                                @if(!empty($headers) && count($headers) > 0)
                                    @foreach ($headers as $header)
                                        <div class="card mt-2">
                                            <div class="card-header">
                                                <div class="row">
                                                    <div class="col-md-10">
                                                        <b>{{ $header->title }}</b>
                                                    </div>
                                                    <div class="col-md-2 actions">
                                                        <div class="row">
                                                            <div class="action-btn bg-primary ms-2">
                                                                <a href="{{route('chapters.create',[$course_id,$header->id])}}" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Create Chapter')}}" data-ajax-popup="true" data-size="lg" data-title="{{__('Create Chapter')}}"><i class="ti ti-plus text-white"></i></a>
                                                            </div>

                                                            
                                                            <div class="action-btn bg-info ms-2">
                                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Edit Header')}}" data-ajax-popup="true" data-size="lg" data-title="{{__('Edit Header')}}" data-url="{{route('headers.edit',[$header->id,$course_id])}}"><i class="ti ti-edit text-white"></i></a>
                                                            </div> 
    
                                                            <div class="action-btn bg-danger ms-2">
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['headers.destroy', [$header->id,$course_id] ]]) !!}
                                                                <a href="#!" class="mx-3 btn btn-sm align-items-center show_confirm" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Delete')}}">
                                                                    <i class="ti ti-trash text-white"></i>
                                                                </a>
                                                                {!! Form::close() !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if(!empty($header->chapters_data))
                                                <div class="card-body ml-5 mt-3">
                                                    @foreach($header->chapters_data as $chapters)
                                                        <div class="row mb-2">
                                                            <div class="col-6">
                                                                <i class="fa fa-play-circle"></i>
                                                                <span class="ml-3">{{$chapters->name}}</span>
                                                            </div>
                                                            <div class="col-6 text-end">
                                                                <div class="action-btn bg-info">
                                                                    <a href="{{route('chapters.edit',[$course_id,$chapters->id,$header->id])}}" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Edit Chapter')}}" data-title="{{__('Edit Chapter')}}"><i class="ti ti-edit text-white"></i></a>
                                                                </div>
        
                                                                <div class="action-btn bg-danger ms-2">
                                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['chapters.destroy', [$chapters->id,$course_id,$header->id] ]]) !!}
                                                                        <a href="#!" class="mx-3 btn btn-sm  align-items-center show_confirm" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Delete')}}">
                                                                            <i class="ti ti-trash text-white"></i>
                                                                        </a>
                                                                    {!! Form::close() !!}
                                                                </div>

                                                            </div>
                                                        </div>   
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <tbody>
                                    <tr>
                                        <td>
                                            <div class="text-center">
                                                <i class="fas fa-folder-open text-primary" style="font-size: 48px;"></i>
                                                <h2>{{__('Opps')}}...</h2>
                                                <h6>{{__('No data Found')}}. </h6>
                                                <h6>{{__('Please Create New Header')}}. </h6>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                @endif
                            </div>
                        {{-- </form> --}}
                    </div>
                </div>

                {{--EDIT COURSE--}}
                <div id="coursetab" class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Edit Course') }}</h5>
                    </div>
                    <div class="card-body">
                        {{Form::model($course,array('route' => array('course.update', $course->id), 'method' => 'PUT','enctype'=>'multipart/form-data')) }}
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-12">
                                    {{Form::label('title',__('Topic Title'),['class'=>'form-label'])}}
                                    {{Form::text('title',null,array('class'=>'form-control font-style','required'=>'required'))}}
                                </div>
                                <div class="form-group col-md-12 col-lg-12">
                                    {{Form::label('course_requirements',__('Course Requirements'),['class'=>'form-label'])}}
                                    {!! Form::textarea('course_requirements',null,array('class'=>'form-control summernote-simple','rows'=>15,)) !!}
                                    {{-- {!! Form::textarea('course_requirements',null,array('class'=>'form-control pc-tinymce-2','rows'=>15,)) !!} --}}
                                </div>
                                <div class="form-group col-md-12 col-lg-12">
                                    {{Form::label('course_description',__('Course Description'),['class'=>'form-label'])}}
                                    {!! Form::textarea('course_description',null,array('class'=>'form-control summernote-simple','rows'=>15,)) !!}
                                    {{-- {!! Form::textarea('course_description',null,array('class'=>'form-control pc-tinymce-2','rows'=>15,)) !!} --}}
                                </div>

                                <div class="form-group col-md-6 {{ ($course->type == 'Quiz')? '' :'d-none'}} " id="quiz-content-1">
                                    {{Form::label('quiz',__('Select Quiz'),['class'=>'form-label'])}}
                                    {!!Form::select('quiz[]', $quiz, explode(',',$course->quiz),array('class' => 'form-control','data-toggle'=>'select','multiple')) !!}
                                </div>

                                <div class="form-group col-md-6 {{ ($course->type == 'Course')? '' :'d-none'}}" id="course-content-2">
                                    {{Form::label('category',__('Select Category'),['class'=>'form-label'])}}
                                    {!! Form::select('category',$category,null,array('class'=>'form-control','id'=>'category_id')) !!}
                                </div>
                                <div class="form-group col-md-6 {{ ($course->type == 'Course')? '' :'d-none'}}" id="course-content-3">
                                    {{Form::label('subcategory',__('Select Subcategory'),['class'=>'form-label'])}}
                                    {!!Form::select('subcategory[]', $sub_category, explode(',',$course->sub_category),array('class' => 'form-control','data-toggle'=>'select','multiple','id'=>'subcategory')) !!}

                                </div>
                                <div class="form-group col-md-6">
                                    {{Form::label('level',__('Select Level'),['class'=>'form-label'])}}
                                    {!! Form::select('level',$level,null,array('class'=>'form-control')) !!}
                                </div>
                                <div class="form-group col-md-6">
                                    {{Form::label('lang',__('Language'),['class'=>'form-label'])}}
                                    {{Form::text('lang',null,array('class'=>'form-control font-style','required'=>'required'))}}
                                </div>
                                <div class="form-group col-md-6 {{ ($course->type == 'Quiz')? 'd-none' :''}}" id="duration">
                                    {{Form::label('duration',__('Duration'),['class'=>'form-label'])}}
                                    {{Form::text('duration',null,array('class'=>'form-control font-style'))}}
                                </div>
                                <div class="form-group col-md-6">
                                    {{Form::label('status',__('Status'),['class'=>'form-label'])}}
                                    {!! Form::select('status',$status,null,array('class'=>'form-control ' )) !!}
                                </div>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="custom-control form-group col-md-6 mt-5 ml-3 custom-switch">
                                            {{-- <input type="checkbox" class="custom-control-input" id="customSwitches" name="is_free" {{ ($course->is_free == 'on')? 'checked' :''}}>
                                            {{Form::label('customSwitches',__('This is free'),['class'=>'custom-control-label form-label'])}} --}}

                                            <div class="form-check form-check form-switch custom-control-inline">
                                                <input type="checkbox" class="form-check-input" role="switch" id="customSwitches"  name="is_free" {{ ($course->is_free == 'on')? 'checked' :''}}>
                                                {{Form::label('customSwitches',__('This is free'),['class'=>'form-check-label'])}}
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6 ml-auto {{ ($course->is_free == 'on')? 'd-none' :''}}" id="price">
                                            {{Form::label('price',__('Price'),['class'=>'form-label'])}}
                                            {{Form::text('price',null,array('class'=>'form-control font-style'))}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 {{ ($course->is_free == 'on')? 'd-none' :''}}" id="discount-div">
                                    <div class="row">
                                        <div class="custom-control form-group col-md-6 mt-5 ml-3 custom-switch">
                                            {{-- <input type="checkbox" class="custom-control-input" id="customSwitches2" name="has_discount" {{ ($course->has_discount == 'on')? 'checked' :''}}>
                                            {{Form::label('customSwitches2',__('Discount'),['class'=>'custom-control-label form-label'])}} --}}

                                            <div class="form-check form-check form-switch custom-control-inline">
                                                <input type="checkbox" class="form-check-input" role="switch" id="customSwitches2"  name="has_discount" {{ ($course->has_discount == 'on')? 'checked' :''}}>
                                                {{Form::label('customSwitches2',__('Discount'),['class'=>'form-check-label'])}}
                                            </div>

                                        </div>
                                        <div class="form-group col-md-6 ml-auto {{ ($course->has_discount == 'on')? '' :'d-none'}}" id="discount">
                                            {{Form::label('discount',__('Discount'),['class'=>'form-label'])}}
                                            {{Form::text('discount',null,array('class'=>'form-control font-style'))}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="custom-control form-group col-md-12 mt-5 ml-3 custom-switch">
                                            {{-- <input type="checkbox" class="custom-control-input" id="customSwitches4" name="featured_course" {{ ($course->featured_course == 'on')? 'checked' :''}}>
                                            {{Form::label('customSwitches4',__('Featured Course'),['class'=>'custom-control-label form-label'])}} --}}
                                            <div class="form-check form-check form-switch custom-control-inline">
                                                <input type="checkbox" class="form-check-input" role="switch" id="customSwitches4"  name="featured_course" {{ ($course->featured_course == 'on')? 'checked' :''}}>
                                                {{Form::label('customSwitches4',__('Featured Course'),['class'=>'form-check-label'])}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="custom-control form-group col-md-6 mt-5 ml-3 custom-switch">

                                            <div class="form-check form-check form-switch custom-control-inline">
                                                <input type="checkbox" class="form-check-input" role="switch" id="customSwitches3"  name="is_preview" {{ ($course->is_preview == 'on')? 'checked' :''}}>
                                                {{Form::label('customSwitches3',__('Preview'),['class'=>'form-check-label'])}}
                                            </div>

                                        </div>
                                        <div class="form-group col-md-6 ml-auto {{ ($course->is_preview == 'on')? '' :'d-none'}}" id="preview_type">
                                            {{Form::label('preview_type',__('Preview Type'),['class'=>'form-label'])}}
                                            {{Form::select('preview_type',$preview_type,null,array('class'=>'form-control font-style','id'=>'preview_type'))}}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-lg-6 mt-4">
                                    <div class="col-12">

                                        <div class="form-file mb-3">
                                            <label for="thumbnail" class="form-label">{{ __('Upload thumbnail') }}</label>
                                            <input type="file" class="form-control" name="thumbnail" id="thumbnail" aria-label="file example">
                                            <a href="{{asset(Storage::url('uploads/thumbnail/'.$course->thumbnail))}}" target="_blank">
                                                <img src="{{asset(Storage::url('uploads/thumbnail/'.$course->thumbnail))}}" name="thumbnail" id="thumbnail" alt="user-image" class="avatar avatar-lg mt-3">
                                            </a>
                                            <div class="invalid-feedback">{{ __('invalid form file') }}</div>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group col-lg-6 mt-4 d-none" id="preview-video-div">
                                    <div class="col-12">
                                        <div class="form-file mb-3">
                                            <label for="preview_video" class="form-label">{{ __('Preview Video') }}</label>
                                            <input type="file" class="form-control" name="preview_video" id="preview_video" aria-label="file example">
                                            <div class="invalid-feedback">{{ __('invalid form file') }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-lg-6 mt-4 ml-auto d-none" id="preview-iframe-div">
                                    {{Form::label('preview_iframe',__('Preview iFrame'),['class'=>'form-label'])}}
                                    <input class="form-control font-style" name="preview_iframe" type="text" id="preview_iframe" value="{{ ($course->preview_type == 'iFrame')? $course->preview_content :''}}">
                                </div>
                                <div class="form-group col-lg-6 mt-4 d-none" id="preview-image-div">
                                    <div class="col-12">
                                        <div class="form-file mb-3">
                                            <label for="preview_image" class="form-label">{{ __('Preview Image') }}</label>
                                            <input type="file" class="form-control" name="preview_image" id="preview_image" aria-label="file example">
                                            <a href="{{asset(Storage::url('uploads/preview_image/'.$course->preview_content))}}" target="_blank">
                                                <img src="{{asset(Storage::url('uploads/preview_image/'.$course->preview_content))}}" name="preview_image" id="preview_image" alt="user-image" class="avatar avatar-lg mt-3">
                                            </a>
                                            <div class="invalid-feedback">{{ __('invalid form file') }}</div>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group col-md-12 col-lg-12">
                                    {{Form::label('meta_keywords',__('Meta Keywords'),['class'=>'form-label'])}}
                                    {!! Form::textarea('meta_keywords',null,array('class'=>'form-control','rows'=>5,)) !!}
                                </div>

                                <div class="form-group col-md-12 col-lg-12">
                                    {{Form::label('meta_description',__('Meta Description'),['class'=>'form-label'])}}
                                    {!! Form::textarea('meta_description',null,array('class'=>'form-control','rows'=>5,)) !!}
                                </div>

                                {{-- <div class="w-100 text-right">
                                    <button type="submit" class="btn btn-sm btn-primary rounded-pill mr-auto" id="submit-all">{{ __('Save') }}</button>
                                </div> --}}

                                <div class="row mb-4">
                                    <div class="col-lg-12 text-right text-end float-end">
                                        <input type="submit" value="{{ __('Save') }}" class="btn btn-primary btn-submit" id="submit-all">
                                    </div>
                                </div>

                            </div>
                        {{ Form::close() }}
                    </div>
                </div>

                {{-- PRACTICES --}}
                <div id="practicestab" class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Practice') }}</h5>
                    </div>
                    <div class="card-body">
                        {{Form::open(array('method'=>'post','id'=>'frmTarget','enctype'=>'multipart/form-data'))}}

                        
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            {{Form::label('content',__('Practices Files'),array('class'=>'form-control-label')) }}
                                            <div class="dropzone dropzone-multiple" data-toggle="dropzone1" data-dropzone-url="http://" data-dropzone-multiple>
                                                <div class="fallback">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="dropzone-1" name="file" multiple>
                                                        <label class="custom-file-label" for="customFileUpload">{{__('Choose file')}}</label>
                                                    </div>
                                                </div>
                                                <ul class="dz-preview dz-preview-multiple list-group list-group-lg list-group-flush">
                                                    <li class="list-group-item px-0">
                                                        <div class="row align-items-center">
                                                            <div class="col-auto">
                                                                <div class="avatar">
                                                                    <img class="rounded" src="" alt="Image placeholder" data-dz-thumbnail>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <h6 class="text-sm mb-1" data-dz-name>...</h6>
                                                                <p class="small text-muted mb-0" data-dz-size></p>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div class="action-btn bg-danger btn-sm ms-2">
                                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-dz-remove>
                                                                        <i class="ti ti-trash text-white"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="card-wrapper lead-common-box">
                                            @if(!empty($practices_files) && count($practices_files) > 0)
                                                @foreach($practices_files as $practices_file)
                                                    <div class="card mb-3 border shadow-none product_Image" data-id="{{$practices_file->id}}">
                                                        <div class="px-3 py-3">
                                                            <div class="row align-items-center">
                                                                <div class="col ml-n2">
                                                                    <p class="card-text small text-muted">
                                                                        {{$practices_file->file_name}}
                                                                    </p>
                                                                </div>
                                                                <div class="col-auto actions">
                                                                    <div class="action-btn btn-info">
                                                                        <a href="#" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Edit File Name')}}" data-ajax-popup="true" data-size="md" data-title="{{__('Edit File Name')}}" data-url="{{route('practices.filename.edit',[$practices_file])}}"><i class="ti ti-edit text-white"></i></a>
                                                                    </div>
                                                                </div>
                                                                <div class="col-auto actions">
                                                                    <div class="action-btn btn-primary">
                                                                        <a href="{{asset(Storage::url('uploads/practices/'.$practices_file->files))}}" download="" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Download')}}" ><i class="ti ti-download text-white"></i></a>
                                                                    </div>
                                                                </div>
                                                                <div class="col-auto actions">
                                                                    <div class="action-btn btn-danger">
                                                                        <a class="deleteRecord mx-3 btn btn-sm align-items-center" name="deleteRecord" data-id="{{ $practices_file->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Delete')}}" ><i class="ti ti-trash text-white"></i></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>

                {{--FAQS--}}
                <div id="faqstab" class="card">
                    <div class="card-header">
                        <div class="row">
                        <div class="col-6">
                            <h5 class="mb-0">{{ __('FAQs') }}</h5>
                        </div>
                        <div class="col-6 text-end">
                            {{-- <a href="#" data-size="xl" data-url="{{route('faqs.create',$course_id)}}" data-ajax-popup="true" data-title="{{__('Create FAQs')}}" class="btn btn-primary btn-sm bor-radius " style="width:150px;">
                                <i class="fa fa-plus"></i>
                            </a> --}}

                            <div class="btn btn-sm btn-primary btn-icon m-1">
                                <a href="#" class="" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Create FAQs')}}" data-ajax-popup="true" data-size="lg" data-title="{{__('Create FAQs')}}" data-url="{{route('faqs.create',$course_id)}}"><i class="ti ti-plus text-white"></i></a>
                            </div>

                        </div>
                        </div>
                    </div>


                    <div class="card-body">
                        <div id="accordion-2" class="accordion accordion-flush">
                            @if(count($faqs) > 0 && !empty($faqs))
                                @foreach($faqs as $k_f => $faq)
                                    <div class="row">
                                        <div class="col-11">
                                            <div class="accordion-item card">

                                                <h2 class="accordion-header" id="heading-{{ $k_f }}">
                                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{$k_f}}" aria-expanded="{{ $k_f == 0 ? 'true' : 'false' }}" aria-controls="collapse-{{ $k_f }}">
                                                        <span class="d-flex align-items-center">
                                                            <i class="ti ti-info-circle text-primary"></i> {{$faq->question}}
                                                        </span>
                                                    </button>
                                                </h2>
                                                <div id="collapse-{{ $k_f }}" class="accordion-collapse collapse @if ($k_f == 0) show @endif" aria-labelledby="heading-{{ $k_f }}" data-bs-parent="#faq-accordion">
                                                    <div class="accordion-body">
                                                        <p class="mb-0">{{$faq->answer}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-1 d-flex">
                                            <div class="action-btn bg-info">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Edit FAQs')}}" data-ajax-popup="true" data-size="lg" data-title="{{__('Edit FAQs')}}" data-url="{{route('faqs.edit',[$faq->id,$course_id])}}"><i class="ti ti-edit text-white"></i></a>
                                            </div>
                                            <div class="action-btn bg-danger ms-2">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['faqs.destroy', [$faq->id,$course_id] ]]) !!}
                                                    <a href="#!" class="mx-3 btn btn-sm  align-items-center show_confirm" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Delete')}}">
                                                        <i class="ti ti-trash text-white"></i>
                                                    </a>
                                                {!! Form::close() !!}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <tbody>
                                <tr>
                                    <td colspan="7">
                                        <div class="text-center">
                                            <i class="fas fa-folder-open text-primary" style="font-size: 48px;"></i>
                                            <h2>{{__('Opps')}}...</h2>
                                            <h6>{{__('No data Found')}}. </h6>
                                            <h6>{{__('Please Create New FAQs')}}. </h6>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>


@endsection
