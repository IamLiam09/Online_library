{{Form::model($subcontent,array('id'=>'frmTarget','enctype'=>'multipart/form-data')) }}
<div class="row">
    <div class="form-group col-md-12">
        {{Form::label('title',__('Chapter Title'),['class'=>'form-control-label'])}}
        {{Form::text('title',null,array('class'=>'form-control font-style','required'=>'required'))}}
    </div>
    <div class="form-group col-md-12 col-lg-12">
        {{Form::label('description',__('Chapter mini description'),['class'=>'form-control-label'])}}
        {{Form::textarea('description',null,array('class'=>'form-control summernote-simple','id'=>'exampleFormControlTextarea1','required'=>'required'))}}
    </div>
    <div class="form-group col-md-6">
        {{Form::label('subcategory',__('Select Subcategory'),['class'=>'form-control-label'])}}
        {!! Form::select('subcategory',$subcategory,null,array('class'=>'form-control' )) !!}
    </div>
    <div class="form-group col-md-6">
        {{Form::label('url',__('Video URL'),['class'=>'form-control-label'])}}
        {{Form::text('url',null,array('class'=>'form-control font-style'))}}
    </div>
    <div class="col-12 border-0 mt-3">
        <div class="row">
            <div class="col-12">
                <h5 class="mb-0">{{__('Content')}}</h5>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    {{Form::label('content',__('Upload File'),array('class'=>'form-control-label')) }}
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
                                        {{-- <a href="#" class="dropdown-item" data-dz-remove>
                                            <i class="fas fa-trash-alt"></i>
                                        </a> --}}

                                        <div class="action-btn bg-danger ms-2">
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
                <div class="card-wrapper p-3 lead-common-box">
                    @foreach($file as $file)
                        <div class="card mb-3 border shadow-none product_Image" data-id="{{$file->id}}">
                            <div class="px-3 py-3">
                                <div class="row align-items-center">
                                    <div class="col ml-n2">
                                        <p class="card-text small text-muted">
                                            <img class="rounded" src=" {{asset(Storage::url('uploads/contents/'.$file->content))}}" width="70px" alt="Image placeholder" data-dz-thumbnail>
                                        </p>
                                    </div>
                                    <div class="col-auto actions">
                                        <a class="action-item" href=" {{asset(Storage::url('uploads/contents/'.$file->content))}}" download="" data-toggle="tooltip" data-original-title="{{__('Download')}}">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>

                                    <div class="col-auto actions">
                                        <a name="deleteRecord" class="action-item deleteRecord" data-id="{{ $file->id }}">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="w-100 text-right">
        <button type="button" class="btn btn-sm btn-primary rounded-pill mr-auto" id="submit-all">{{ __('Save') }}</button>
    </div>
</div>
{{ Form::close() }}
<script>
    var Dropzones = function () {
        var e = $('[data-toggle="dropzone1"]'), t = $(".dz-preview");
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        e.length && (Dropzone.autoDiscover = !1, e.each(function () {
            var e, a, n, o, i;
            e = $(this), a = void 0 !== e.data("dropzone-multiple"), n = e.find(t), o = void 0, i = {
                url: "{{route('subcontent.update',$subcontent->id)}}",
                headers: {
                    'x-csrf-token': CSRF_TOKEN,
                },
                thumbnailWidth: null,
                thumbnailHeight: null,
                previewsContainer: n.get(0),
                previewTemplate: n.html(),
                maxFiles: 10,
                parallelUploads: 10,
                autoProcessQueue: false,
                uploadMultiple: true,
                acceptedFiles: a ? null : "image/*",
                success: function (file, response) {
                    if (response.flag == "success") {
                        show_toastr('Success', response.msg, 'success');
                        setInterval('location.reload()', 1500);
                    } else {
                        show_toastr('Error', response.msg, 'error');
                    }
                },
                error: function (file, response) {
                    // Dropzones.removeFile(file);
                    if (response.error) {
                        show_toastr('Error', response.msg, 'error');
                    } else {
                        show_toastr('Error', response.msg, 'error');
                    }
                },
                init: function () {
                    var myDropzone = this;

                    this.on("addedfile", function (e) {
                        !a && o && this.removeFile(o), o = e
                    })
                }
            }, n.html(""), e.dropzone(i)
        }))
    }()

    $('#submit-all').on('click', function () {

        var fd = new FormData();

        var files = $('[data-toggle="dropzone1"]').get(0).dropzone.getAcceptedFiles();
        $.each(files, function (key, file) {
            fd.append('multiple_files[' + key + ']', $('[data-toggle="dropzone1"]')[0].dropzone.getAcceptedFiles()[key]); // attach dropzone image element
        });
        var other_data = $('#frmTarget').serializeArray();
        $.each(other_data, function (key, input) {
            fd.append(input.name, input.value);
        });

        $.ajax({
            url: "{{route('subcontent.update',$subcontent->id)}}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: fd,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data) {
                if (data.flag == "success") {
                    show_toastr('Success', data.msg, 'success');
                    setInterval('location.reload()', 1500);

                } else {
                    show_toastr('Error', data.msg, 'error');
                }
            },
            error: function (data) {
                // Dropzones.removeFile(file);
                if (data.error) {
                    show_toastr('Error', data.error, 'error');
                } else {
                    show_toastr('Error', data, 'error');
                }
            },
        });
    });

    $(".deleteRecord").click(function () {

        var id = $(this).data("id");
        var token = $("meta[name='csrf-token']").attr("content");

        $.ajax(
            {
                url: '{{ route('subcontent.file.delete', '_id') }}'.replace('_id', id),
                type: 'DELETE',
                data: {
                    "id": id,
                    "_token": token,
                },
                success: function (response) {
                        show_toastr('Success', response.success, 'success');
                        $('.product_Image[data-id="' + response.id + '"]').remove();
                    },error: function (response) {
                        show_toastr('Error', response.error, 'error');
                    }

            });
    });
</script>
