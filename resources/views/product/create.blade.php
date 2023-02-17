@extends('layouts.admin')
@section('page-title')
    {{__('Product')}}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block text-white font-weight-bold mb-0 ">{{__('Product')}}</h5>
    </div>
@endsection
@section('breadcrumb')
@endsection
@section('action-btn')
@endsection
@section('filter')
@endsection
@push('css-page')
    <link rel="stylesheet" href="{{asset('libs/summernote/summernote-bs4.css')}}">
@endpush
@push('script-page')
    <script src="{{asset('libs/summernote/summernote-bs4.js')}}"></script>
    <script>
        var Dropzones = function () {
            var e = $('[data-toggle="dropzone1"]'), t = $(".dz-preview");
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            e.length && (Dropzone.autoDiscover = !1, e.each(function () {
                var e, a, n, o, i;
                e = $(this), a = void 0 !== e.data("dropzone-multiple"), n = e.find(t), o = void 0, i = {
                    url: "{{route('product.store')}}",
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
                            show_toastr('success', response.msg, 'success');
                            window.location.href = "{{route('product.index')}}";
                        } else {
                            show_toastr('Error', response.msg, 'error');
                        }
                    },
                    error: function (file, response) {
                        // Dropzones.removeFile(file);
                        if (response.error) {
                            show_toastr('Error', response.error, 'error');
                        } else {
                            show_toastr('Error', response, 'error');
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
            var file = document.getElementById('is_cover_image').files[0];
            if (file) {
                fd.append('is_cover_image', file);
            }

            var files = $('[data-toggle="dropzone1"]').get(0).dropzone.getAcceptedFiles();
            $.each(files, function (key, file) {
                fd.append('multiple_files[' + key + ']', $('[data-toggle="dropzone1"]')[0].dropzone.getAcceptedFiles()[key]); // attach dropzone image element
            });
            var other_data = $('#frmTarget').serializeArray();
            $.each(other_data, function (key, input) {
                fd.append(input.name, input.value);
            });

            $.ajax({
                url: "{{route('product.store')}}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: fd,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (data) {
                    if (data.flag == "success") {
                        show_toastr('success', data.msg, 'success');
                        window.location.href = "{{route('product.index')}}";
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

        $(document).on('click', '.get-variants', function (e) {

            $("#commonModal .modal-title").html('{{ __("Add Variants") }}');
            $("#commonModal .modal-dialog").addClass('modal-md');
            $("#commonModal").modal('show');

            $.get('{{ route('product.variants.create') }}', {}, function (data) {
                $('#commonModal .modal-body').html(data);
            });
        });

        $(document).on('click', '.add-variants', function (e) {
            e.preventDefault();

            var form = $(this).parents('form');
            var variantNameEle = $('#variant_name');
            var variantOptionsEle = $('#variant_options');
            var isValid = true;

            if (variantNameEle.val() == '') {
                variantNameEle.focus();
                isValid = false;
            } else if (variantOptionsEle.val() == '') {
                variantOptionsEle.focus();
                isValid = false;
            }

            if (isValid) {
                $.ajax({
                    url: form.attr('action'),
                    datType: 'json',
                    data: {
                        variant_name: variantNameEle.val(),
                        variant_options: variantOptionsEle.val(),
                        hiddenVariantOptions: $('#hiddenVariantOptions').val()
                    },
                    success: function (data) {
                        $('#hiddenVariantOptions').val(data.hiddenVariantOptions);
                        $('.variant-table').html(data.varitantHTML);
                        $("#commonModal").modal('hide');
                    }
                })
            }
        });

        $('#cost').trigger('keyup');
    </script>
@endpush
@section('content')
    <div class="row">
        <div class="col-12">
            <div id="account_edit" class="tabs-card">
                <div class="card ">
                    <div class="card-body">
                        {{Form::open(array('method'=>'post','id'=>'frmTarget','enctype'=>'multipart/form-data'))}}
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    {{Form::label('name',__('Name'),array('class'=>'form-control-label')) }}
                                    {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Name')))}}
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    {{Form::label('product_categorie',__('Product Categories'),array('class'=>'form-control-label')) }}
                                    {!! Form::select('product_categorie[]', $product_categorie, null,array('class' => 'form-control','data-toggle'=>'select','multiple')) !!}
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    {{Form::label('SKU',__('SKU'),array('class'=>'form-control-label')) }}
                                    {{Form::text('SKU',null,array('class'=>'form-control','placeholder'=>__('Enter SKU')))}}
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    {{Form::label('product_tax',__('Product Tax'),array('class'=>'form-control-label')) }}
                                    {{ Form::select('product_tax[]', $product_tax,null, array('class' => 'form-control','data-toggle'=>'select','multiple')) }}
                                </div>
                            </div>
                            <div class="col-6 proprice">
                                <div class="form-group">
                                    {{Form::label('price',__('Price'),array('class'=>'form-control-label')) }}
                                    {{ Form::number('price', null, array('step' => 'any','class' => 'form-control','required'=>'required')) }}
                                </div>
                            </div>
                            <div class="col-6 proprice">
                                <div class="form-group">
                                    {{Form::label('quantity',__('Quantity'),array('class'=>'form-control-label')) }}
                                    {{Form::text('quantity',null,array('class'=>'form-control','placeholder'=>__('Enter Quantity'),'required'=>'required'))}}
                                </div>
                            </div>
                            <div class="form-group col-md-6 py-4">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="product_display" class="custom-control-input" id="product_display">
                                    <label name="product_display" class="custom-control-label form-control-label" for="product_display"></label>
                                    {{Form::label('product_display',__('Product Display'),array('class'=>'form-control-label')) }}
                                </div>
                                @error('product_display')
                                <span class="invalid-product_display" role="alert">
                                    <strong class="text-danger">{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6 py-4">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" name="enable_product_variant" id="enable_product_variant">

                                    <label class="custom-control-label form-control-label" for="enable_product_variant">{{__('Display Variants')}}</label>
                                </div>
                            </div>
                            <div id="productVariant" class="col-md-12">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card my-3">
                                            <div class="card-header">
                                                <div class="row flex-grow-1">
                                                    <div class="col-md d-flex align-items-center">
                                                        <h5 class="card-header-title">{{ __('Product Variants') }}</h5>
                                                    </div>
                                                    <div class="col-md-auto">
                                                        <button type="button" class="btn btn-sm btn-primary get-variants"><i class="ti ti-plus"></i> {{ __('Add Variant') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <input type="hidden" id="hiddenVariantOptions" name="hiddenVariantOptions" value="{}">
                                                <div class="variant-table">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 border-0">
                                <div class="row">
                                    <div class="col-6">
                                        <h5 class="mb-0">{{__('Product Image')}}</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            {{Form::label('sub_images',__('Upload Product Images'),array('class'=>'form-control-label')) }}
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
                                    </div>
                                    <div class="col-6">
                                            <div class="form-group">
                                                <label for="is_cover_image" class="form-control-label">{{ __('Upload Cover Image') }}</label>
                                                <input type="file" name="is_cover_image" id="is_cover_image" class="custom-input-file">
                                                <label for="is_cover_image">
                                                    <i class="fa fa-upload"></i>
                                                    <span>{{__('Choose a file')}}</span>
                                                </label>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 pt-4">
                                <div class="form-group">
                                    {{Form::label('description',__('Product Description'),array('class'=>'form-control-label')) }}
                                    {{Form::textarea('description',null,array('class'=>'form-control summernote-simple','rows'=>2,'placeholder'=>__('Product Description')))}}
                                </div>
                            </div>
                        </div>
                        <div class="w-100 text-right">
                            <button type="button" class="btn btn-sm btn-primary rounded-pill mr-auto" id="submit-all">{{ __('Save') }}</button>
                        </div>
                    </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
    </div>
@endsection
