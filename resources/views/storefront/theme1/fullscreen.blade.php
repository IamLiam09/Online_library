@extends('layouts.theme1.shopfront')
@section('page-title')
    {{__('Home')}} - {{($store->tagline) ?  $store->tagline : config('APP_NAME', ucfirst($store->name))}}
@endsection
@push('css-page')
    <script src="{{asset('./js/bootstrap-bundle-min.5.0.1.js')}}"></script>
    <link rel="stylesheet" href="{{asset('css/moovie.css')}}">
@endpush
@push('script-page')
    <script src="{{asset('./js/moovie.js')}}"></script>
    <script>
        $(document).on('click', '.topic', function (e) {
            var id = $(this).attr('id');
            collapse(id);
        });

        document.addEventListener("DOMContentLoaded", function () {
            var demo1 = new Moovie({
                selector: "#example",
                dimensions: {
                    width: "100%"
                },
                config: {
                    storage: {
                        captionOffset: false,
                        playrateSpeed: false,
                        captionSize: false
                    }
                }
            });
        });

        $(document).on('click', '.checkbox', function (e) {
            e.preventDefault();
            var id = $(this).attr('data-value');
            var aa = $(this);
            if ($(this).prop("checked") == false) {
                $.ajax({
                    type: "POST",
                    url: '{{route('student.remove.checkbox',[ '__chapter_id',$course_id,$slug])}}'.replace('__chapter_id', id),
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function (response) {
                        if (response.status == "Success") {
                            show_toastr('Success', response.success, 'success');
                            $(aa).prop("checked", false);
                            $('#progress_percent').html('');
                            $('#progress_percent').html(response.progress + '%');
                            $("#green_progress").css("width", response.progress + "%");
                        } else {
                            show_toastr('Error', response.error, 'error');
                        }

                        if (response.progress < 100) {
                            $(".certificate_btn").addClass('d-none');
                            $(".btn_certi").addClass('d-none');
                        } else {
                            $(".certificate_btn").removeClass('d-none');
                        }

                    },
                    error: function (result) {
                    }
                });
            } else {
                $.ajax({
                    type: "POST",
                    url: '{{route('student.checkbox', ['__chapter_id',$course_id,$slug])}}'.replace('__chapter_id', id),
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function (response) {
                        if (response.status == "Success") {
                            show_toastr('Success', response.success, 'success');
                            $(aa).prop("checked", true);
                            $('#progress_percent').html('');
                            $('#progress_percent').html(response.progress + '%');
                            $("#green_progress").css("width", response.progress + "%");
                        } else {
                            show_toastr('Error', response.error, 'error');
                        }

                        if (response.progress < 100) {
                            $(".certificate_btn").addClass('d-none');
                            $(".btn_certi").addClass('d-none');
                        } else {
                            $(".certificate_btn").removeClass('d-none');
                        }
                    },
                    error: function (result) {
                    }
                });
            }
        });

        function collapse(id) {
            var collpase_id = "collapseExample-" + id;
            document.getElementById(collpase_id).classList.toggle("show");
        }

        function myFunction() {
            document.getElementById("fullscreenDropdown").classList.toggle("show");
            document.getElementById("fullscreenContent").classList.toggle("hide");
        }

        $(document).on('click', '.show-hidden-menu', function (e) {
            $('#' + $(this).attr('data-id') + '_hidden_menu').slideToggle("slow");
        });
    </script>
@endpush
@section('content')
    @php
        $userstore  = \App\Models\UserStore::where('store_id', $store->id)->first();
        $settings   =\DB::table('settings')->where('name','company_favicon')->where('created_by', $userstore->user_id)->first();
        $i = 0;
    @endphp
    

    <section class="product-main-section padding-bottom">
        <div class="chapter-head">
            <div class="container">
                <div class="chapter-head-content">
                   <div class="chapter-head-block">
                    <a href="{{route('store.viewcourse',[$slug,\Illuminate\Support\Facades\Crypt::encrypt($course_id)])}}" class="back-btn">
                        <span class="svg-ic">
                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="5" viewBox="0 0 11 5" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M10.5791 2.28954C10.5791 2.53299 10.3818 2.73035 10.1383 2.73035L1.52698 2.73048L2.5628 3.73673C2.73742 3.90636 2.74146 4.18544 2.57183 4.36005C2.40219 4.53467 2.12312 4.53871 1.9485 4.36908L0.133482 2.60587C0.0480403 2.52287 -0.000171489 2.40882 -0.000171488 2.2897C-0.000171486 2.17058 0.0480403 2.05653 0.133482 1.97353L1.9485 0.210321C2.12312 0.0406877 2.40219 0.044729 2.57183 0.219347C2.74146 0.393966 2.73742 0.673036 2.5628 0.842669L1.52702 1.84888L10.1383 1.84875C10.3817 1.84874 10.5791 2.04609 10.5791 2.28954Z" fill="white"></path>
                            </svg>
                        </span>
                        {{ __('Back') }}
                    </a>
                   </div>
                    <div class="chapter-title chapter-head-block">
                        <h6>{{__('Chapter')}} <b>{{$current_chapter->name}}</b></h6>
                    </div>
                    <!-- progress bar start-->
                    <div class="course-progress chapter-head-block">
                        <div class="progress-labels d-flex justify-content-between align-items-center">
                            <span>{{__('Progress')}}</span>
                            <span id="progress_percent">{{!empty($progress)?$progress.'%':'0%'}}</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar"  id="green_progress" style="width: {{!empty($progress)?$progress:'0'}}%;"></div>
                        </div>
                    </div>
                    <!-- progress bar end-->
                    <!-- download button -->
                    <div class="chapter-head-block pagination">
                        @if(count($cs_incomplete) == 0 && $progress == 100)                        
                            <div class="download-btn"> 
                                <a href="{{ route('certificate.pdf', Crypt::encrypt($store->certificate_template)) }}" target="_blank" class="btn btn_certi certificate_dl">{{ __('Download') }}</a>
                            </div>
                        @elseif(count($cs_incomplete) == 1)
                            <div class="download-btn">
                                <a href="{{ route('certificate.pdf', Crypt::encrypt($store->certificate_template)) }}" target="_blank" class="btn d-none">{{ __('Download') }}</a>
                            </div>
                        @endif
                        <div class="download-btn certificate_btn d-none">
                            <a href="{{ route('certificate.pdf', Crypt::encrypt($store->certificate_template)) }}" target="_blank" class="btn">{{ __('Download') }}</a>
                        </div>

                     <!-- END download button -->
                        <div class="chapter-pagination">
                            <ul>
                                @if($current_chapter->id != $last_previous)
                                    <li><a href="{{route('store.fullscreen',[$slug,\Illuminate\Support\Facades\Crypt::encrypt($course_id),\Illuminate\Support\Facades\Crypt::encrypt($current_chapter->id),'previous'])}}" class="previous-course"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <g clip-path="url(#clip0_30_10881)">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8736 1.39356C14.2385 1.39356 14.5343 1.68935 14.5343 2.05421L14.5343 13.9459C14.5343 14.3108 14.2385 14.6066 13.8736 14.6066L1.98195 14.6066C1.61708 14.6066 1.3213 14.3108 1.3213 13.9459L1.3213 2.05422C1.3213 1.68935 1.61708 1.39357 1.98195 1.39357L13.8736 1.39356ZM15.8556 2.05421C15.8556 0.959612 14.9682 0.0722637 13.8736 0.0722638L1.98195 0.0722654C0.887348 0.0722655 1.16362e-07 0.959615 2.59901e-07 2.05422L1.81931e-06 13.9459C1.96285e-06 15.0405 0.887351 15.9279 1.98195 15.9279L13.8736 15.9279C14.9682 15.9279 15.8556 15.0405 15.8556 13.9459L15.8556 2.05421ZM10.5712 8.66071C10.9361 8.66071 11.2319 8.36493 11.2319 8.00006C11.2319 7.6352 10.9361 7.33941 10.5712 7.33941L6.88068 7.33941L7.73483 6.48526C7.99283 6.22726 7.99283 5.80896 7.73483 5.55096C7.47683 5.29296 7.05853 5.29296 6.80053 5.55096L4.81858 7.53291C4.69468 7.65681 4.62508 7.82485 4.62508 8.00006C4.62508 8.17528 4.69468 8.34332 4.81858 8.46721L6.80053 10.4492C7.05853 10.7072 7.47683 10.7072 7.73483 10.4492C7.99283 10.1912 7.99283 9.77286 7.73483 9.51486L6.88068 8.66071L10.5712 8.66071Z" fill="#8A94A6"/>
                                        </g>
                                        <defs>
                                        <clipPath id="clip0_30_10881">
                                        <rect width="15.8556" height="15.8556" fill="white" transform="matrix(1 -8.74228e-08 -8.74228e-08 -1 0 15.9277)"/>
                                        </clipPath>
                                        </defs>
                                        </svg> {{__('Previous Chapter')}}</a></li>
                                @else
                                    <li><a class="previous-course"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <g clip-path="url(#clip0_30_10881)">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8736 1.39356C14.2385 1.39356 14.5343 1.68935 14.5343 2.05421L14.5343 13.9459C14.5343 14.3108 14.2385 14.6066 13.8736 14.6066L1.98195 14.6066C1.61708 14.6066 1.3213 14.3108 1.3213 13.9459L1.3213 2.05422C1.3213 1.68935 1.61708 1.39357 1.98195 1.39357L13.8736 1.39356ZM15.8556 2.05421C15.8556 0.959612 14.9682 0.0722637 13.8736 0.0722638L1.98195 0.0722654C0.887348 0.0722655 1.16362e-07 0.959615 2.59901e-07 2.05422L1.81931e-06 13.9459C1.96285e-06 15.0405 0.887351 15.9279 1.98195 15.9279L13.8736 15.9279C14.9682 15.9279 15.8556 15.0405 15.8556 13.9459L15.8556 2.05421ZM10.5712 8.66071C10.9361 8.66071 11.2319 8.36493 11.2319 8.00006C11.2319 7.6352 10.9361 7.33941 10.5712 7.33941L6.88068 7.33941L7.73483 6.48526C7.99283 6.22726 7.99283 5.80896 7.73483 5.55096C7.47683 5.29296 7.05853 5.29296 6.80053 5.55096L4.81858 7.53291C4.69468 7.65681 4.62508 7.82485 4.62508 8.00006C4.62508 8.17528 4.69468 8.34332 4.81858 8.46721L6.80053 10.4492C7.05853 10.7072 7.47683 10.7072 7.73483 10.4492C7.99283 10.1912 7.99283 9.77286 7.73483 9.51486L6.88068 8.66071L10.5712 8.66071Z" fill="#8A94A6"/>
                                        </g>
                                        <defs>
                                        <clipPath id="clip0_30_10881">
                                        <rect width="15.8556" height="15.8556" fill="white" transform="matrix(1 -8.74228e-08 -8.74228e-08 -1 0 15.9277)"/>
                                        </clipPath>
                                        </defs>
                                        </svg> {{__('Previous Chapter')}}</a></li>
                                @endif
                                @if($current_chapter->id != $last_next->id)
                                    <li><a href="{{route('store.fullscreen',[$slug,\Illuminate\Support\Facades\Crypt::encrypt($course_id),\Illuminate\Support\Facades\Crypt::encrypt($current_chapter->id),'next'])}}" class="next-course">{{__('Next Chapter')}} <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <g clip-path="url(#clip0_30_10881)">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8736 1.39356C14.2385 1.39356 14.5343 1.68935 14.5343 2.05421L14.5343 13.9459C14.5343 14.3108 14.2385 14.6066 13.8736 14.6066L1.98195 14.6066C1.61708 14.6066 1.3213 14.3108 1.3213 13.9459L1.3213 2.05422C1.3213 1.68935 1.61708 1.39357 1.98195 1.39357L13.8736 1.39356ZM15.8556 2.05421C15.8556 0.959612 14.9682 0.0722637 13.8736 0.0722638L1.98195 0.0722654C0.887348 0.0722655 1.16362e-07 0.959615 2.59901e-07 2.05422L1.81931e-06 13.9459C1.96285e-06 15.0405 0.887351 15.9279 1.98195 15.9279L13.8736 15.9279C14.9682 15.9279 15.8556 15.0405 15.8556 13.9459L15.8556 2.05421ZM10.5712 8.66071C10.9361 8.66071 11.2319 8.36493 11.2319 8.00006C11.2319 7.6352 10.9361 7.33941 10.5712 7.33941L6.88068 7.33941L7.73483 6.48526C7.99283 6.22726 7.99283 5.80896 7.73483 5.55096C7.47683 5.29296 7.05853 5.29296 6.80053 5.55096L4.81858 7.53291C4.69468 7.65681 4.62508 7.82485 4.62508 8.00006C4.62508 8.17528 4.69468 8.34332 4.81858 8.46721L6.80053 10.4492C7.05853 10.7072 7.47683 10.7072 7.73483 10.4492C7.99283 10.1912 7.99283 9.77286 7.73483 9.51486L6.88068 8.66071L10.5712 8.66071Z" fill="#8A94A6"></path>
                                        </g>
                                        <defs>
                                        <clipPath id="clip0_30_10881">
                                        <rect width="15.8556" height="15.8556" fill="white" transform="matrix(1 -8.74228e-08 -8.74228e-08 -1 0 15.9277)"></rect>
                                        </clipPath>
                                        </defs>
                                        </svg></a></li>
                                @else
                                    <li><a class="next-course">{{__('Next Chapter')}} <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <g clip-path="url(#clip0_30_10881)">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8736 1.39356C14.2385 1.39356 14.5343 1.68935 14.5343 2.05421L14.5343 13.9459C14.5343 14.3108 14.2385 14.6066 13.8736 14.6066L1.98195 14.6066C1.61708 14.6066 1.3213 14.3108 1.3213 13.9459L1.3213 2.05422C1.3213 1.68935 1.61708 1.39357 1.98195 1.39357L13.8736 1.39356ZM15.8556 2.05421C15.8556 0.959612 14.9682 0.0722637 13.8736 0.0722638L1.98195 0.0722654C0.887348 0.0722655 1.16362e-07 0.959615 2.59901e-07 2.05422L1.81931e-06 13.9459C1.96285e-06 15.0405 0.887351 15.9279 1.98195 15.9279L13.8736 15.9279C14.9682 15.9279 15.8556 15.0405 15.8556 13.9459L15.8556 2.05421ZM10.5712 8.66071C10.9361 8.66071 11.2319 8.36493 11.2319 8.00006C11.2319 7.6352 10.9361 7.33941 10.5712 7.33941L6.88068 7.33941L7.73483 6.48526C7.99283 6.22726 7.99283 5.80896 7.73483 5.55096C7.47683 5.29296 7.05853 5.29296 6.80053 5.55096L4.81858 7.53291C4.69468 7.65681 4.62508 7.82485 4.62508 8.00006C4.62508 8.17528 4.69468 8.34332 4.81858 8.46721L6.80053 10.4492C7.05853 10.7072 7.47683 10.7072 7.73483 10.4492C7.99283 10.1912 7.99283 9.77286 7.73483 9.51486L6.88068 8.66071L10.5712 8.66071Z" fill="#8A94A6"></path>
                                        </g>
                                        <defs>
                                        <clipPath id="clip0_30_10881">
                                        <rect width="15.8556" height="15.8556" fill="white" transform="matrix(1 -8.74228e-08 -8.74228e-08 -1 0 15.9277)"></rect>
                                        </clipPath>
                                        </defs>
                                        </svg></a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-8 col-12 pdp-left-side">
                    <div class="pdp-main-itm">
                        <div class="pdp-main-media chapter-video">
                            @if($current_chapter->type == 'Video File')
                                <video id="example" class="preview_video" height="500" poster="{{asset('assets/img/video-img.png')}}">
                                    <source src="{{asset(Storage::url('uploads/chapters/'.$current_chapter->video_file))}}" type="video/mp4">
                                    {{__('Your browser does not support the video tag.')}}
                                </video>
                            @elseif($current_chapter->type == 'iFrame')
                                @php
                                    if(strpos($current_chapter->iframe, 'src') !== false)
                                    {
                                        preg_match('/src="([^"]+)"/', $current_chapter->iframe, $match);
                                        $url = $match[1];
                                        $iframe_url = str_replace('https://www.youtube.com/embed/','',$url);
                                    }
                                    else
                                    {
                                        $iframe_url = str_replace('https://youtu.be/','', str_replace('https://www.youtube.com/watch?v=','' ,$current_chapter->iframe));
                                    }
                                @endphp
                                <iframe width="1280" height="720" src="https://www.youtube.com/embed/{{$iframe_url}}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

                            @elseif($current_chapter->type == 'Text Content')
                                <article class="ml-5 mt-5" style="margin-top: -59%;">
                                    {!! $current_chapter->text_content !!}
                                </article>
                            @elseif($current_chapter->type == 'Video Url')
                                @php
                                    if(strpos($current_chapter->video_url, 'src') !== false)
                                    {
                                        preg_match('/src="([^"]+)"/', $current_chapter->video_url, $match);
                                        $url = $match[1];
                                        $video_url = str_replace('https://www.youtube.com/embed/','',$url);
                                    }
                                    else
                                    {
                                        $video_url = str_replace('https://youtu.be/','', str_replace('https://www.youtube.com/watch?v=','' ,$current_chapter->video_url));
                                    }
                                @endphp
                                <iframe width="1280" height="720" src="https://www.youtube.com/embed/{{$video_url}}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            @endif

                        </div>
                    </div>
                    <div class="tabs-wrapper">
                        <div class="pdp-tabs">
                            <ul class="tabs">
                                <li class="tab-link active" data-tab="tab-1">
                                    <a href="#overview"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0 5.5C0 8.53757 2.46243 11 5.5 11C8.53757 11 11 8.53757 11 5.5C11 2.46243 8.53757 0 5.5 0C2.46243 0 0 2.46243 0 5.5ZM5.5 9C3.567 9 2 7.433 2 5.5C2 3.567 3.567 2 5.5 2C7.433 2 9 3.567 9 5.5C9 7.433 7.433 9 5.5 9ZM0 18.5C0 21.5376 2.46243 24 5.5 24C8.53757 24 11 21.5376 11 18.5C11 15.4624 8.53757 13 5.5 13C2.46243 13 0 15.4624 0 18.5ZM5.5 22C3.567 22 2 20.433 2 18.5C2 16.567 3.567 15 5.5 15C7.433 15 9 16.567 9 18.5C9 20.433 7.433 22 5.5 22ZM18.5 24C15.4624 24 13 21.5376 13 18.5C13 15.4624 15.4624 13 18.5 13C21.5376 13 24 15.4624 24 18.5C24 21.5376 21.5376 24 18.5 24ZM15 18.5C15 20.433 16.567 22 18.5 22C20.433 22 22 20.433 22 18.5C22 16.567 20.433 15 18.5 15C16.567 15 15 16.567 15 18.5ZM13 5.5C13 8.53757 15.4624 11 18.5 11C21.5376 11 24 8.53757 24 5.5C24 2.46243 21.5376 0 18.5 0C15.4624 0 13 2.46243 13 5.5ZM18.5 9C16.567 9 15 7.433 15 5.5C15 3.567 16.567 2 18.5 2C20.433 2 22 3.567 22 5.5C22 7.433 20.433 9 18.5 9Z" fill="#8A94A6"/>
                                        </svg>{{__('Overview')}}</a>
                                </li>
                                <li class="tab-link" data-tab="tab-2">
                                    <a href="#profile">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M2.56788 5.19093L8 2.58804V19.5552C7.90178 19.5902 7.80494 19.6304 7.70991 19.676L3.43212 21.7257C2.76833 22.0438 2 21.56 2 20.8239V6.09275C2 5.70792 2.22083 5.35723 2.56788 5.19093ZM10.2873 19.6687C10.1931 19.6242 10.0972 19.5849 10 19.5508V2.82556L13.524 5.077C13.9738 5.36438 14.4836 5.52169 15 5.54568V21.8337C14.8907 21.8218 14.7832 21.7917 14.682 21.7439L10.2873 19.6687ZM17 21.2308L21.3338 19.6959C21.7331 19.5545 22 19.1769 22 18.7533V4.17608C22 3.44003 21.2317 2.9562 20.5679 3.27427L17 4.98388V21.2308ZM7.56448 0.578981C8.50035 0.130547 9.6015 0.197627 10.476 0.756341L14.6008 3.39161C14.8923 3.57784 15.2593 3.6002 15.5713 3.45072L19.7036 1.47063C21.695 0.51644 24 1.96792 24 4.17608V18.7533C24 20.024 23.1994 21.1569 22.0015 21.5812L16.1105 23.6676C15.3643 23.9319 14.5438 23.8905 13.828 23.5524L9.43328 21.4772C9.16113 21.3486 8.84557 21.3495 8.57415 21.4796L4.29636 23.5294C2.305 24.4836 0 23.0321 0 20.8239V6.09275C0 4.93825 0.662494 3.88618 1.70364 3.3873L7.56448 0.578981Z" fill="#8A94A6"/>
                                        </svg>  {{ __('Syllabus') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="tabs-container">
                            <div class="tab-content active" id="tab-1">
                                <div class="section-title">
                                    <h3>
                                        {{$courses->title}}: <br>
                                        {{$current_chapter->name}}
                                    </h3>
                                </div>
                                <p> 
                                    {!! $current_chapter->chapter_description!!}
                                </p>
                            </div>
                            <div class="tab-content" id="tab-2">
                                @foreach($headers as $key => $header)
                                    <div class="set has-children">
                                        <a href="javascript:;" class="acnav-label" data-bs-target="#ChapterOne{{$key}}" aria-expanded="true" aria-controls="ChapterOne">
                                            <span> {{$header->title}}</span>
                                        </a>
                                        <div class="acnav-list">
                                            @foreach($header->chapters_data as $k => $chapters)
                                                <div class="chapter-description">
                                                    <p> {!!$chapters->chapter_description!!}  </p>                                                    
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-12 pdp-right-side">
                    <div class="pdp-summery-accordian">
                        @foreach($headers as $key => $header)
                            <div class="set has-children">
                                <a href="javascript:;" class="acnav-label" data-bs-target="#ChapterDetails{{$key}}">
                                    <span>{{$header->title}}</span>
                                </a>
                                <div class="acnav-list" id="ChapterDetails{{$key}}">
                                    @foreach($header->chapters_data as $k => $chapters)
                                        <div class="chapter-topics">
                                            @php
                                                $i++;
                                            @endphp
                                            <div class="checkbox-custom">
                                                @php
                                                    $chapter_status_check = App\Models\Chapters::chapterstatus($chapters->id);
                                                @endphp
                                                @if (isset($chapter_status_check) && $chapter_status_check->status == 'Active')
                                                    <input type="checkbox" class="description{{ $i }} checkbox" id="description{{ $i }}" data-value="{{ $chapters->id }}" checked>
                                                @else
                                                    <input type="checkbox" class="description{{ $i }} checkbox" id="description{{ $i }}" data-value="{{ $chapters->id }}">
                                                @endif
                                                {{-- @if(isset($chapters['chapters_status']) && $chapters['chapters_status']['status'] == 'Active')
                                                    <input type="checkbox"  class="description{{$i}} checkbox" id="description{{$i}}" data-value="{{$chapters->id}}" checked> 
                                                @else
                                                    <input type="checkbox"  class="description{{$i}} checkbox" id="description{{$i}}" data-value="{{$chapters->id}}">
                                                @endif --}}
                                                <label for="description{{$i}}">{{$i.'. '.$chapters->name}}</label>
                                            </div>
                                            <div data-id="{{$chapters->id}}" class="course-time d-flex align-items-center">
                                                <span>[{{$chapters->duration}}]</span>
                                                <a href="{{route('store.fullscreen',[$slug,\Illuminate\Support\Facades\Crypt::encrypt($course_id),\Illuminate\Support\Facades\Crypt::encrypt($chapters->id) ])}}" class="btn-ic"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M1.5 9C1.5 4.85786 4.85786 1.5 9 1.5C13.1421 1.5 16.5 4.85786 16.5 9C16.5 13.1421 13.1421 16.5 9 16.5C4.85786 16.5 1.5 13.1421 1.5 9ZM9 0C4.02944 0 0 4.02944 0 9C0 13.9706 4.02944 18 9 18C13.9706 18 18 13.9706 18 9C18 4.02944 13.9706 0 9 0ZM12.5758 7.04303C12.8412 6.72501 12.7986 6.25205 12.4805 5.98666C12.1625 5.72127 11.6896 5.76394 11.4242 6.08197L7.37843 10.9301L5.76746 9.39461C5.46763 9.10882 4.99289 9.12021 4.70711 9.42004C4.42132 9.71987 4.43271 10.1946 4.73254 10.4804L6.92347 12.5687C7.07367 12.7118 7.27647 12.7864 7.48364 12.7746C7.6908 12.7628 7.88382 12.6656 8.01677 12.5063L12.5758 7.04303Z" fill="white"/>
                                                    </svg></a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                          
                        @if($practices_files->count()>0)

                            <div class="fil-title">
                                <div class="corse-caption-top">
                                    <img src="{{asset('assets/img/project-files.svg')}}" alt="">
                                    <h5>{{__('project-file')}}</h5>
                                </div>                                
                                <div class="inner-cards">
                                    @foreach($practices_files as $practices_file)
                                        <div class="course-download-card" data-id="{{$practices_file->id}}">
                                            <div class="course-image">
                                                <a class="action-item" href="{{asset(Storage::url('uploads/practices/'.$practices_file->files))}}">
                                                    <img src="{{asset('assets/img/ai.svg')}}" alt="ai" class="img-fluid">
                                                </a>
                                            </div>
                                            <div class="corse-caption">
                                                <h5>
                                                    <a class="action-item" href="{{asset(Storage::url('uploads/practices/'.$practices_file->files))}}">
                                                        {{$practices_file->file_name}}
                                                    </a>
                                                </h5>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>                            
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    @php
        $main_homepage_email_subscriber_key = array_search('Home-Email-subscriber',array_column($getStoreThemeSetting, 'section_name'));        
        $email_subscriber_enable = 'off';
        $homepage_email_subscriber_title = 'Always on time';
        $homepage_email_subscriber_subtext = 'Subscription here';
        $homepage_email_subscriber_box = 'on';
        $homepage_email_subscriber_bottom_text = 'We will never spam to you. Only useful info.';
        $homepage_email_subscriber_button = 'SUBSCRIBE';
        $homepage_email_subscriber_Bckground_Image = '';

        if(!empty($getStoreThemeSetting[$main_homepage_email_subscriber_key])) {
            $homepage_subscriber_header = $getStoreThemeSetting[$main_homepage_email_subscriber_key];
            $email_subscriber_enable = $homepage_subscriber_header['section_enable'];

            $homepage_email_subscriber_title_key = array_search('Title',array_column($homepage_subscriber_header['inner-list'], 'field_name'));            
            $homepage_email_subscriber_title = $homepage_subscriber_header['inner-list'][$homepage_email_subscriber_title_key]['field_default_text'];

            $homepage_email_subscriber_subtext_key = array_search('Sub Text',array_column($homepage_subscriber_header['inner-list'], 'field_name'));
            $homepage_email_subscriber_subtext = $homepage_subscriber_header['inner-list'][$homepage_email_subscriber_subtext_key]['field_default_text'];

            $homepage_email_subscriber_box_key = array_search('Display Email Subscriber Box',array_column($homepage_subscriber_header['inner-list'], 'field_name'));
            $homepage_email_subscriber_box = $homepage_subscriber_header['inner-list'][$homepage_email_subscriber_box_key]['field_default_text'];       
            
            $homepage_email_subscriber_bottom_text_key = array_search('Bottom Text',array_column($homepage_subscriber_header['inner-list'], 'field_name'));
            $homepage_email_subscriber_bottom_text = $homepage_subscriber_header['inner-list'][$homepage_email_subscriber_bottom_text_key]['field_default_text'];

            $homepage_email_subscriber_button_key = array_search('Button',array_column($homepage_subscriber_header['inner-list'], 'field_name'));
            $homepage_email_subscriber_button = $homepage_subscriber_header['inner-list'][$homepage_email_subscriber_button_key]['field_default_text'];

            $homepage_email_subscriber_Bckground_Image_key = array_search('Thumbnail',array_column($homepage_subscriber_header['inner-list'], 'field_name'));
            $homepage_email_subscriber_Bckground_Image = $homepage_subscriber_header['inner-list'][$homepage_email_subscriber_Bckground_Image_key]['field_default_text'];

        }
    @endphp
    @if($email_subscriber_enable == 'on')
        <section class="newsletter-section padding-bottom" id="newsletter">
            <div class="container">
                <div class="newsletter-content-wrap">
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="newsletter-left">
                                @if($homepage_email_subscriber_box == 'on')
                                    <div class="section-title">
                                        <h2><b>{{ $homepage_email_subscriber_title }}</b></h2>    
                                    </div>
                                    <p>{{ $homepage_email_subscriber_subtext }}</p>
                                @endif
                                <div class="newsletter-form">
                                    {{ Form::open(array('route' => array('subscriptions.store_email', $store->id),'method' => 'POST')) }}
                                        <div class="input-wrapper">
                                            {{ Form::email('email',null,array('aria-label'=>'Enter your email address','placeholder'=>__('Enter Your Email Address'))) }}
                                                <button type="submit" class="btn btn-secondary ">{{ $homepage_email_subscriber_button }}</button>
                                        </div>
                                        <p>{{ $homepage_email_subscriber_bottom_text }}</p>
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 newsletter-right-side">
                            <div class="newsletter-image">
                                @if($homepage_email_subscriber_box == 'on')
                                    {{-- <img src="assets/images/MaleRunning-newsletter.png" alt=""> --}}
                                    @if($homepage_email_subscriber_Bckground_Image)
                                        <img src="{{asset(Storage::url('uploads/'.$homepage_email_subscriber_Bckground_Image))}}"> 
                                    @else
                                        @if(!empty($store->sub_img))
                                            <img src="{{asset(Storage::url('uploads/store_logo/'.$store->sub_img))}}" alt="newsletter">
                                        @else
                                            <img src="{{asset('assets/'.$store->theme_dir.'/img/newsletter.png')}}" alt="newsletter">
                                        @endif
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

@endsection
