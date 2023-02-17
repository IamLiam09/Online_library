@extends('layouts.admin')
@php
    $logo=\App\Models\Utility::get_file('uploads/logo/');
    $logo_img=\App\Models\Utility::getValByName('company_logo');
    $logo_light = \App\Models\Utility::getValByName('company_light_logo');
    $s_logo=\App\Models\Utility::get_file('uploads/store_logo/');
    // $logo = asset(Storage::url('uploads/logo/'));
    // $dark_logo = Utility::getValByName('company_logo_dark');
    // $light_logo = Utility::getValByName('company_logo_light');
    $company_logo = \App\Models\Utility::GetLogo();
    $company_favicon = Utility::getValByName('company_favicon');
    $store_logo = asset(Storage::url('uploads/store_logo/'));
    $lang = Utility::getValByName('default_language');
    if (Auth::user()->type == 'Owner') {
        $store_lang = $store->lang;
    }
@endphp

@section('page-title')
    {{ __('Store Theme Settings') }}
@endsection
@section('title')
   
    {{ __('Store Theme Settings') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('settings') }}">{{ __('Settings') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Store Theme Settings') }}</li>
@endsection

@section('action-btn')
@endsection

@push('css-page')
    <link rel="stylesheet" href="{{ asset('libs/summernote/summernote-bs4.css') }}">
    <style>
        hr {
            margin: 8px;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-3">
                    <div class="card sticky-top" style="top:30px">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            @if (Auth::user()->type == 'Owner')
                                @if ($theme == 'theme1' || $theme == 'theme2' || $theme == 'theme3' || $theme == 'theme4')                                 
                                    <a href="#Header_Setting" id="Header_Setting_tab"
                                        class="list-group-item list-group-item-action border-0">{{ __('Home Header') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                    <a href="#Features_Setting" id="Features_Setting_tab"
                                        class="list-group-item list-group-item-action border-0">{{ __('Home Featured Course') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                    <a href="#Categories" id="Categories_tab"
                                        class="list-group-item list-group-item-action border-0">{{ __('Home Categories') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                    <a href="#Sale_Setting" id="On_Sale_tab"
                                        class="list-group-item list-group-item-action border-0">{{ __('Home On Sale') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                    <a href="#Email_Subscriber_Setting" id="Email_Subscriber_Setting_tab"
                                        class="list-group-item list-group-item-action border-0">{{ __('Home Email Subscriber') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                    <a href="#Footer_1" id="Footer_1_tab"
                                        class="list-group-item list-group-item-action border-0">{{ __('Home Footer 1') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                    <a href="#Footer_2" id="Footer_2_tab"
                                        class="list-group-item list-group-item-action border-0">{{ __('Home Footer 2') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                    <a href="#Footer_3" id="Footer_3_tab"
                                        class="list-group-item list-group-item-action border-0">{{ __('Home Footer 3') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>                             
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                @if (Auth::user()->type == 'Owner')

                    <div class="col-xl-9">
                        <div class="col-lg-12 col-sm-12 col-md-12">                            
                            <div class="row">
                                {{ Form::open(['route' => ['store.storeeditproducts', [$store->slug, $theme]], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                                    @foreach ($getStoreThemeSetting as $json_key => $section)
                                        @php
                                            $id = '';
                                            if ($section['section_name'] == 'Home-Header') {
                                                $id = 'Header_Setting';
                                            }
                                            if ($section['section_name'] == 'Home-Featured Course') {
                                                $id = 'Features_Setting';
                                            }
                                            if ($section['section_name'] == 'Home-Categories') {
                                                $id = 'Categories';
                                            }
                                            if ($section['section_name'] == 'Home-On Sale') {
                                                $id = 'Sale_Setting';
                                            }
                                            if ($section['section_name'] == 'Home-Email-subscriber') {
                                                $id = 'Email_Subscriber_Setting';
                                            } 
                                            if ($section['section_name'] == 'Home-Footer') {
                                                $id = 'Footer_1';
                                            }
                                            if ($section['section_name'] == 'Home-Footer') {
                                                $id = 'Footer_2';
                                            }
                                            if ($section['section_name'] == 'Home-Footer') {
                                                $id = 'Footer_3';
                                            }

                                        @endphp
                                        <input type="hidden" name="array[{{ $json_key }}][section_name]" value="{{ $section['section_name'] }}">
                                        <input type="hidden" name="array[{{ $json_key }}][section_slug]" value="{{ $section['section_slug'] }}">
                                        <input type="hidden" name="array[{{ $json_key }}][array_type]" value="{{ $section['array_type'] }}">
                                        <input type="hidden" name="array[{{ $json_key }}][loop_number]" value="{{ $section['loop_number'] }}">
                                        @php
                                            $loop = 1;
                                            $section = (array) $section;
                                        @endphp
                                        @if($json_key == 0 || $json_key-1 > -1 && $getStoreThemeSetting[$json_key-1]['section_slug'] != $section['section_slug'])
                                            <div class="card " id="{{ $id }}" >
                                                <div class="card-header d-flex justify-content-between">
                                                    <div> <h5> {{ $section['section_name'] }} </h5> </div>
                                                    <div class="text-end">
                                                        <div class="form-check form-switch form-switch-right">
                                                            <input type="hidden" name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                                value="off">
                                                            <input type="checkbox" class="form-check-input mx-2 off switch" data-toggle="switchbutton"
                                                                name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                                id="array[{{ $json_key }}]{{ $section['section_slug'] }}"
                                                                {{ $section['section_enable'] == 'on' ? 'checked' : '' }}>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                <div class="card-body">
                                                    @if ($section['array_type'] == 'multi-inner-list')
                                                        @php
                                                            $loop = (int) $section['loop_number'];
                                                        @endphp
                                                    @endif
                                                    @for ($i = 0; $i < $loop; $i++)
                                                        <div class="row">
                                                            <?php
                                                                foreach ($section['inner-list'] as $inner_list_key => $field) {
                                                                $field = (array)$field;
                                                            ?>

                                                            @php
                                                            $text_class = '';
                                                            if (in_array($field['field_slug'],['homepage-email-subscriber-title', 'homepage-email-subscriber-sub-text','homepage-email-subscriber-thumbnail']))
                                                            {                                                                
                                                                $text_class = 'subscriber_box';
                                                            }
                                                            @endphp  
                                                            <input type="hidden" name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_name]" value="{{ $field['field_name'] }}">
                                                            <input type="hidden" name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_slug]" value="{{ $field['field_slug'] }}">
                                                            <input type="hidden" name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_help_text]" value="{{ $field['field_help_text'] }}">
                                                            <input type="hidden" name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]" value="{{ $field['field_default_text'] }}">
                                                            <input type="hidden" name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_type]" value="{{ $field['field_type'] }}">

                                                            @if ($field['field_type'] == 'text')
                                                                <div class="col-sm-6  {{ $text_class }}">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                        @php
                                                                            $checked1 = $field['field_default_text'];
                                                                            if (!empty($section[$field['field_slug']][$i])) {
                                                                                $checked1 = $section[$field['field_slug']][$i];
                                                                            }
                                                                        @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <input type="text"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                                class="form-control" value="{{ $checked1 }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @else                                                                 
                                                                            <input type="text"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]" class="form-control"
                                                                                value="{{ $field['field_default_text'] }}"
                                                                                placeholder="{{ $field['field_help_text'] }}" id="">
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            @if ($field['field_type'] == 'text area')
                                                                <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                        @php
                                                                            $checked1 = $field['field_default_text'];
                                                                            if (!empty($section[$field['field_slug']][$i])) {
                                                                                $checked1 = $section[$field['field_slug']][$i];
                                                                            }
                                                                        @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <textarea class="form-control" name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                                rows="3" placeholder="{{ $field['field_help_text'] }}">{{ $checked1 }}</textarea>
                                                                        @else
                                                                            <textarea class="form-control" name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]" {{-- name="array[{{ $section['section_slug'] }}][{{ $field['field_slug'] }}]" --}}
                                                                                rows="3" placeholder="{{ $field['field_help_text'] }}">{{ $field['field_default_text'] }}</textarea>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            @if ($field['field_type'] == 'photo upload')
                                                                <div class="col-sm-6 {{ $text_class }}">
                                                                    @if ($section['array_type'] == 'multi-inner-list')
                                                                        @php
                                                                            $checked2 = $field['field_default_text'];
                                                                            if (!empty($section[$field['field_slug']])) {
                                                                                $checked2 = $section[$field['field_slug']][$i];
                                                                                if (is_array($checked2)) {
                                                                                    $checked2 = $checked2['field_prev_text'];
                                                                                }
                                                                            }
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label class="form-label">{{ $field['field_name'] }}</label>
                                                                            <input type="hidden" name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][field_prev_text]"
                                                                                value="{{ $checked2 }}">
                                                                            <input type="file" name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][image]"
                                                                                class="form-control" placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        @if (isset($checked2) && !is_array($checked2))
                                                                            <img src="{{ asset(Storage::url('uploads/' . $checked2)) }}" style="width: auto; max-height: 80px;" class="cate-img-shadow">
                                                                        @else
                                                                            <img src="{{ asset(Storage::url('uploads/' . $field['field_default_text'])) }}" style="width: auto; max-height: 80px;" class="cate-img-shadow">
                                                                        @endif
                                                                    @else
                                                                        <div class="form-group">
                                                                            <label class="form-label">{{ $field['field_name'] }}</label>
                                                                            <input type="hidden" name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_prev_text]" value="{{ $field['field_default_text'] }}">
                                                                            <input type="file" name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]" class="form-control"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>

                                                                        <img src="{{ asset(Storage::url('uploads/' . $field['field_default_text'])) }}" class="cate-img-shadow"
                                                                            @if ($field['field_slug'] == 'homepage-footer-logo') style="width: auto; height: 80px;" @else style="width: 200px; height: 200px;" @endif>
                                                                    @endif
                                                                </div>
                                                            @endif

                                                            @if ($field['field_type'] == 'button')
                                                                <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                        <input type="text"
                                                                            name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                            class="form-control"
                                                                            value="{{ $field['field_default_text'] }}"
                                                                            placeholder="{{ $field['field_help_text'] }}">
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @php
                                                                $checked = '';
                                                                if ($field['field_slug'] == 'homepage-email-subscriber-display') {
                                                                    $checked = $field['field_default_text'] == 'on' ? 'checked' : '';
                                                                    // dd($checked);
                                                                }
                                                                if ($field['field_slug'] == 'homepage-email-subscriber-display') {
                                                                    // echo $field['field_default_text'];
                                                                    $checked = $field['field_default_text'] == 'on' ? 'checked' : '';
                                                                    // dd($checked);
                                                                }
                                                            @endphp

                                                            @if ($field['field_type'] == 'checkbox')
                                                                <div class="col-sm-6">
                                                                    <label class="form-label">{{ $field['field_name'] }}</label> 
                                                                    <div class="form-check form-switch form-switch-right mb-2">
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            @php
                                                                                $checked1 = '';
                                                                                if (!empty($section[$field['field_slug']][$i]) && $section[$field['field_slug']][$i] == 'on') {
                                                                                    $checked1 = 'checked';
                                                                                }
                                                                            @endphp
                                                                            <input type="hidden" name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]" value="off">
                                                                            <input type="checkbox" class="form-check-input mx-2"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                                id="array[{{ $section['section_slug'] }}][{{ $field['field_slug'] }}]"
                                                                                {{ $checked1 }}>
                                                                        @else
                                                                        
                                                                            <input type="hidden" name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]" value="off">
                                                                            <input type="checkbox" class="form-check-input mx-2 enable_subscriber"
                                                                                
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                id=" array[{{ $section['section_slug'] }}][{{ $field['field_slug'] }}] "
                                                                                {{ $checked }}>
                                                                        @endif

                                                                        <label class="form-check-label" for="array[ {{ $section['section_slug'] }}][{{ $field['field_slug'] }}]"></label>
                                                                    </div>
                                                                </div>
                                                            @endif                                                          

                                                            @if($field['field_type'] == 'multi file upload')
                                                                <div class="form-group">
                                                                    <label class="form-label">{{ $field['field_name'] }}</label>
                                                                    <input type="hidden" name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_prev_text][]" value="">
                                                                    <input type="file" name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text][]"
                                                                    id="file" class="form-control custom-input-file" multiple>
                                                                </div>
                                                                <div id="img-count" class="badge badge-success rounded-pill"></div>
                                                                <div class="col-12">
                                                                    <div class="card-wrapper p-3 lead-common-box">
                                                                        @if(isset($getStoreThemeSetting['brand_logo']) && $getStoreThemeSetting['brand_logo'] != null)
                                                                            @foreach(explode(',',$getStoreThemeSetting['brand_logo']) as $file)
                                                                                <div class="card mb-3 border shadow-none product_Image" data-value="{{$file}}">
                                                                                    <div class="px-3 py-3">
                                                                                        <div class="row align-items-center">
                                                                                            <div class="col ml-n2">
                                                                                                <p class="card-text small text-muted">
                                                                                                    <img class="rounded" src=" {{asset(Storage::url('uploads/store_logo/'.$file))}}" width="70px" alt="Image placeholder" data-dz-thumbnail>
                                                                                                </p>
                                                                                            </div>
                                                                                            <div class="col-auto actions">
                                                                                                <a class="action-item" href=" {{asset(Storage::url('uploads/store_logo/'.$file))}}" download="" data-toggle="tooltip" data-original-title="{{__('Download')}}">
                                                                                                    <i class="fas fa-download"></i>
                                                                                                </a>
                                                                                            </div>
                                                                                            <div class="col-auto actions">
                                                                                                <a name="deleteRecord" class="action-item deleteRecord" data-name="{{$file}}">
                                                                                                    <i class="fas fa-trash"></i>
                                                                                                </a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            <?php } ?>
                                                        </div>
                                                    @endfor

                                                </div>
                                                @if($json_key+1 <= count($getStoreThemeSetting)-1)
                                                    @if($getStoreThemeSetting[$json_key+1]['section_slug'] != $section['section_slug'])
                                            </div>
                                                    @endif
                                                @endif
                                    @endforeach  
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12 col-md-12">
                                            <div class="card-footer">
                                                <div class="col-sm-12">
                                                    <div class="text-end">
                                                        {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-xs btn-primary py-3 px-3']) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                {{ Form::close() }}   
                            </div>                                                      
                        </div>
                    </div>
                @endif

            </div>
            <!-- [ sample-page ] end -->
        </div>
    </div>
@endsection
@push('script-page')

    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"> </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js"></script>

    <script>
         var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300
        })
    </script>
    <script> 
        $(document).ready(function() {
            $('.repeater').repeater({
                initEmpty: false,
                show: function() {
                    $(this).slideDown();
                },
                hide: function(deleteElement) {
                    if (confirm('Are you sure you want to delete this element?')) {
                        $(this).slideUp(deleteElement);
                    }
                },
                isFirstItemUndeletable: true
            })
        });

        $(".deleteRecord").click(function() {
            var name = $(this).data("name");
            var token = $("meta[name='csrf-token']").attr("content");
            $.ajax({
                url: '{{ route('brand.file.delete', [$store->slug, $theme, '_name']) }}'.replace('_name',
                    name),
                type: 'DELETE',
                data: {
                    "name": name,
                    "_token": token,
                },
                success: function(response) {
                    show_toastr('Success', response.success, 'success');
                    $('.product_Image[data-value="' + response.name + '"]').remove();
                },
                error: function(response) {
                    show_toastr('Error', response.error, 'error');
                }
            });
        });
    </script>
    <script src="{{ asset('libs/summernote/summernote-bs4.js') }}"></script>
    <script>        
        var Dropzones = function() {
            var e = $('[data-toggle="dropzone1"]'),
                t = $(".dz-preview");
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            e.length && (Dropzone.autoDiscover = !1, e.each(function() {
                var e, a, n, o, i;
                e = $(this), a = void 0 !== e.data("dropzone-multiple"), n = e.find(t), o = void 0, i = {
                    url: "{{ route('store.storeeditproducts', [$store->slug, $theme]) }}",
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
                    success: function(file, response) {
                        if (response.status == "success") {
                            show_toastr('success', response.success, 'success');
                            // window.location.href = "{{route('product.index')}}"; 
                        } else {
                            show_toastr('Error', response.msg, 'error');
                        }
                    },
                    error: function(file, response) {
                        // Dropzones.removeFile(file);
                        if (response.error) {
                            show_toastr('Error', response.error, 'error');
                        } else {
                            show_toastr('Error', response, 'error');
                        }
                    },
                    init: function() {
                        var myDropzone = this;
                    }

                }, n.html(""), e.dropzone(i)
            }))
        }()

        $("#eventBtn").click(function() {
            $("#BigButton").clone(true).appendTo("#fileUploadsContainer").find("input").val("").end();
        });
        $("#testimonial_eventBtn").click(function() {
            $("#BigButton2").clone(true).appendTo("#fileUploadsContainer2").find("input").val("").end();
        });

        $(document).on('click', '#remove', function() {
            var qq = $('.BigButton').length;

            if (qq > 1) {
                var dd = $(this).attr('data-id');

                $(this).parents('#BigButton').remove();
            }
        });
               
        $("input[type='file']").on("change", function() {
            var numFiles = $(this).get(0).files.length
            $('#img-count').html(numFiles + ' Images selected');
        })
    </script>
   
@endpush