@extends('layouts.theme3.shopfront')
@section('page-title')
    {{ __('Home') }} - {{ $store->tagline ? $store->tagline : config('APP_NAME', ucfirst($store->name)) }}
@endsection
@push('css-page')
    <script src="{{ asset('./js/bootstrap-bundle-min.5.0.1.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/themes/theme3/css/moovie.css') }}">
@endpush
@push('script-page')
    <script src="{{ asset('assets/themes/theme3/js/moovie.js') }}"></script>
    <script>
        $(document).on('click', '.topic', function(e) {
            var id = $(this).attr('id');
            collapse(id);
        });

        document.addEventListener("DOMContentLoaded", function() {
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
                },
                icons: {
                    path: '{{ asset("/public/assets/imgs") }}/'
                }
            });
        });

        $(document).on('click', '.checkbox', function(e) {

            e.preventDefault();
            var id = $(this).attr('data-value');
            var aa = $(this);
            $(this).attr('checked', 'checked');
            console.log("check");

            if ($(this).prop("checked") == false) {
                $.ajax({
                    type: "POST",
                    url: '{{ route('student.remove.checkbox', ['__chapter_id', $course_id, $slug]) }}'
                        .replace('__chapter_id', id),
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(response) {
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
                    error: function(result) {}
                });
            } else {
                $.ajax({
                    type: "POST",
                    url: '{{ route('student.checkbox', ['__chapter_id', $course_id, $slug]) }}'.replace(
                        '__chapter_id', id),
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(response) {
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
                    error: function(result) {}
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

        $(document).on('click', '.show-hidden-menu', function(e) {
            $('#' + $(this).attr('data-id') + '_hidden_menu').slideToggle("slow");
        });
    </script>
@endpush
@section('content')
    
    @php
        $userstore = \App\Models\UserStore::where('store_id', $store->id)->first();
        $settings = \DB::table('settings')
            ->where('name', 'company_favicon')
            ->where('created_by', $userstore->user_id)
            ->first();
        $i = 0;
    @endphp
    <section class="chapter-main-section padding-bottom">
        <div class="chapter-head">
            <div class="container offset-right">
                <div class="chapter-head-content">
                    <div class="chapter-head-block">
                        <a href="{{ route('store.viewcourse', [$slug, \Illuminate\Support\Facades\Crypt::encrypt($course_id)]) }}" class="back-btn">
                            <span class="svg-ic">
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="5" viewBox="0 0 11 5"
                                    fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M10.5791 2.28954C10.5791 2.53299 10.3818 2.73035 10.1383 2.73035L1.52698 2.73048L2.5628 3.73673C2.73742 3.90636 2.74146 4.18544 2.57183 4.36005C2.40219 4.53467 2.12312 4.53871 1.9485 4.36908L0.133482 2.60587C0.0480403 2.52287 -0.000171489 2.40882 -0.000171488 2.2897C-0.000171486 2.17058 0.0480403 2.05653 0.133482 1.97353L1.9485 0.210321C2.12312 0.0406877 2.40219 0.044729 2.57183 0.219347C2.74146 0.393966 2.73742 0.673036 2.5628 0.842669L1.52702 1.84888L10.1383 1.84875C10.3817 1.84874 10.5791 2.04609 10.5791 2.28954Z"
                                        fill="white"></path>
                                </svg>
                            </span>
                            {{ __('Back to Course Description') }}
                        </a>
                    </div>
                    @if ($current_chapter->id != $last_previous)
                        <div class="chapter-head-block page-btn-block">
                            <a href="{{ route('store.fullscreen', [$slug, \Illuminate\Support\Facades\Crypt::encrypt($course_id), \Illuminate\Support\Facades\Crypt::encrypt($current_chapter->id), 'previous']) }}" class="prev-btn">
                                <span class="svg-ic">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="11" height="5" viewBox="0 0 11 5"
                                        fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M10.5791 2.28954C10.5791 2.53299 10.3818 2.73035 10.1383 2.73035L1.52698 2.73048L2.5628 3.73673C2.73742 3.90636 2.74146 4.18544 2.57183 4.36005C2.40219 4.53467 2.12312 4.53871 1.9485 4.36908L0.133482 2.60587C0.0480403 2.52287 -0.000171489 2.40882 -0.000171488 2.2897C-0.000171486 2.17058 0.0480403 2.05653 0.133482 1.97353L1.9485 0.210321C2.12312 0.0406877 2.40219 0.044729 2.57183 0.219347C2.74146 0.393966 2.73742 0.673036 2.5628 0.842669L1.52702 1.84888L10.1383 1.84875C10.3817 1.84874 10.5791 2.04609 10.5791 2.28954Z"
                                            fill="white"></path>
                                    </svg>
                                </span>
                                <span>{{ __('Previous Chapter') }}:
                                    <b>{{ !empty($previous_chapter) ? $previous_chapter->id : ''}}&nbsp;{{ !empty($previous_chapter) ? $previous_chapter->name : '' }} </b>
                                </span>
                            </a>
                        </div>
                    @else
                        <div class="chapter-head-block page-btn-block">
                            <a class="prev-btn">
                                <span class="svg-ic">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="11" height="5"
                                        viewBox="0 0 11 5" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M10.5791 2.28954C10.5791 2.53299 10.3818 2.73035 10.1383 2.73035L1.52698 2.73048L2.5628 3.73673C2.73742 3.90636 2.74146 4.18544 2.57183 4.36005C2.40219 4.53467 2.12312 4.53871 1.9485 4.36908L0.133482 2.60587C0.0480403 2.52287 -0.000171489 2.40882 -0.000171488 2.2897C-0.000171486 2.17058 0.0480403 2.05653 0.133482 1.97353L1.9485 0.210321C2.12312 0.0406877 2.40219 0.044729 2.57183 0.219347C2.74146 0.393966 2.73742 0.673036 2.5628 0.842669L1.52702 1.84888L10.1383 1.84875C10.3817 1.84874 10.5791 2.04609 10.5791 2.28954Z"
                                            fill="white"></path>
                                    </svg>
                                </span>
                                <span>{{ __('Previous Chapter') }}:
                                    <b>{{ !empty($previous_chapter) ? $previous_chapter->id : ''}}&nbsp;{{ !empty($previous_chapter) ? $previous_chapter->name : '' }}</b>
                                </span>
                            </a>
                        </div>  
                    @endif
                    <!-- progress bar start-->
                    <div class="course-progress chapter-head-block  d-flex justify-content-between align-items-center">
                        <div class="progress-left">
                            <div class="progress-labels">
                                <span>{{ __('Chapter') }} {{ $current_chapter->id }}:
                                    {{ $current_chapter->name }}</span>
                            </div>
                            <div class="progress-labels d-flex justify-content-between align-items-center">
                                <span>{{ __('Progress') }}</span>
                                <span id="progress_percent">{{ !empty($progress) ? $progress . '%' : '0%' }}</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" id="green_progress"
                                    style="width: {{ !empty($progress) ? $progress : '0' }}%;"></div>
                            </div>
                        </div>
                        <div class="progress-right-dw">
                            <!-- Download Button -->
                                @if (count($cs_incomplete) == 0 && $progress == 100)
                                    <div class="download-btn">
                                        <a href="{{ route('certificate.pdf', Crypt::encrypt($store->certificate_template)) }}"
                                            target="_blank" class="btn btn_certi certificate_dl">{{ __('Download') }}
                                            <svg xmlns="http://www.w3.org/2000/svg" id="icons" height="512"
                                                viewBox="0 0 24 24" width="512">
                                                <path d="m21 19h-18a1 1 0 0 0 0 2h18a1 1 0 0 0 0-2z" />
                                                <path d="m12 2a1 1 0 0 0 -1 1v10.59l-3.29-3.3a1 1 0 0 0 -1.42 1.42l5 5a1 1 0 0 0 1.42 0l5-5a1 1 0 0 0 -1.42-1.42l-3.29 3.3v-10.59a1 1 0 0 0 -1-1z" />
                                            </svg>
                                        </a>
                                    </div>
                                @elseif(count($cs_incomplete) == 1)
                                    <div class="download-btn">
                                        <a href="{{ route('certificate.pdf', Crypt::encrypt($store->certificate_template)) }}" target="_blank" class="btn d-none">{{ __('Download') }}  <svg xmlns="http://www.w3.org/2000/svg" id="icons" height="512"
                                            viewBox="0 0 24 24" width="512">
                                            <path d="m21 19h-18a1 1 0 0 0 0 2h18a1 1 0 0 0 0-2z" />
                                            <path d="m12 2a1 1 0 0 0 -1 1v10.59l-3.29-3.3a1 1 0 0 0 -1.42 1.42l5 5a1 1 0 0 0 1.42 0l5-5a1 1 0 0 0 -1.42-1.42l-3.29 3.3v-10.59a1 1 0 0 0 -1-1z" />
                                        </svg></a>
                                    </div>
                                @endif
                                <div class="download-btn certificate_btn d-none">
                                    <a href="{{ route('certificate.pdf', Crypt::encrypt($store->certificate_template)) }}" target="_blank" class="btn">{{ __('Download') }}  <svg xmlns="http://www.w3.org/2000/svg" id="icons" height="512"
                                        viewBox="0 0 24 24" width="512">
                                        <path d="m21 19h-18a1 1 0 0 0 0 2h18a1 1 0 0 0 0-2z" />
                                        <path d="m12 2a1 1 0 0 0 -1 1v10.59l-3.29-3.3a1 1 0 0 0 -1.42 1.42l5 5a1 1 0 0 0 1.42 0l5-5a1 1 0 0 0 -1.42-1.42l-3.29 3.3v-10.59a1 1 0 0 0 -1-1z" />
                                    </svg></a>
                                </div>
                            <!-- END Download Button -->
                        </div>
                        {{-- <div class="radial-progress" data-percentage="60">
                            <span class="progress-left">
                                <span class="progress-bar"></span>
                            </span>
                            <span class="progress-right">
                                <span class="progress-bar"></span>
                            </span>
                            <div class="progress-value">
                                <div>
                                    60%
                                </div>
                            </div>
                        </div> --}}
                    </div>
                    <!-- progress bar end-->
                    @if ($current_chapter->id != $last_next->id)
                        <div class="chapter-head-block page-btn-block">
                            <a href="{{ route('store.fullscreen', [$slug, \Illuminate\Support\Facades\Crypt::encrypt($course_id), \Illuminate\Support\Facades\Crypt::encrypt($current_chapter->id), 'next']) }}"
                                class="next-btn">
                                <span>{{ __('Next Chapter') }}:
                                    <b>{{ !empty($next_chapter) ? $next_chapter->id : ''}}&nbsp;{{ !empty($next_chapter) ? $next_chapter->name : '' }} </b>
                                </span>
                                <span class="svg-ic">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="11" height="5" viewBox="0 0 11 5" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M10.5791 2.28954C10.5791 2.53299 10.3818 2.73035 10.1383 2.73035L1.52698 2.73048L2.5628 3.73673C2.73742 3.90636 2.74146 4.18544 2.57183 4.36005C2.40219 4.53467 2.12312 4.53871 1.9485 4.36908L0.133482 2.60587C0.0480403 2.52287 -0.000171489 2.40882 -0.000171488 2.2897C-0.000171486 2.17058 0.0480403 2.05653 0.133482 1.97353L1.9485 0.210321C2.12312 0.0406877 2.40219 0.044729 2.57183 0.219347C2.74146 0.393966 2.73742 0.673036 2.5628 0.842669L1.52702 1.84888L10.1383 1.84875C10.3817 1.84874 10.5791 2.04609 10.5791 2.28954Z"
                                            fill="white"></path>
                                    </svg>
                                </span>
                            </a>
                        </div>
                    @else
                        <div class="chapter-head-block page-btn-block">
                            <a class="next-btn">
                                <span>{{ __('Next Chapter') }}:
                                    <b>{{ !empty($next_chapter) ? $next_chapter->id : ''}}&nbsp;{{ !empty($next_chapter) ? $next_chapter->name : '' }}</b>
                                </span>
                                <span class="svg-ic">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="11" height="5" viewBox="0 0 11 5" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M10.5791 2.28954C10.5791 2.53299 10.3818 2.73035 10.1383 2.73035L1.52698 2.73048L2.5628 3.73673C2.73742 3.90636 2.74146 4.18544 2.57183 4.36005C2.40219 4.53467 2.12312 4.53871 1.9485 4.36908L0.133482 2.60587C0.0480403 2.52287 -0.000171489 2.40882 -0.000171488 2.2897C-0.000171486 2.17058 0.0480403 2.05653 0.133482 1.97353L1.9485 0.210321C2.12312 0.0406877 2.40219 0.044729 2.57183 0.219347C2.74146 0.393966 2.73742 0.673036 2.5628 0.842669L1.52702 1.84888L10.1383 1.84875C10.3817 1.84874 10.5791 2.04609 10.5791 2.28954Z"
                                            fill="white"></path>
                                    </svg>
                                </span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-12 chapter-video">
                    <div class="chapter-video-media">
                        @if ($current_chapter->type == 'Video File')
                            <video id="example" class="preview_video" height="500"
                                poster="{{ asset('assets/img/video-img.png') }}">
                                <source
                                    src="{{ asset(Storage::url('uploads/chapters/' . $current_chapter->video_file)) }}"
                                    type="video/mp4">
                                {{ __('Your browser does not support the video tag.') }}
                            </video>
                        @elseif($current_chapter->type == 'iFrame')
                            @php
                                if (strpos($current_chapter->iframe, 'src') !== false) {
                                    preg_match('/src="([^"]+)"/', $current_chapter->iframe, $match);
                                    $url = $match[1];
                                    $iframe_url = str_replace('https://www.youtube.com/embed/', '', $url);
                                } else {
                                    $iframe_url = str_replace('https://youtu.be/', '', str_replace('https://www.youtube.com/watch?v=', '', $current_chapter->iframe));
                                }                                    
                            @endphp

                            <iframe width="1280" height="720"
                                src="https://www.youtube.com/embed/{{ $iframe_url }}" title="YouTube video player"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                        @elseif($current_chapter->type == 'Text Content')
                            <article class="ml-5 mt-5" style="margin-top: -59%;">
                                {!! $current_chapter->text_content !!}
                            </article>
                        @elseif($current_chapter->type == 'Video Url')
                            @php
                                if (strpos($current_chapter->video_url, 'src') !== false) {
                                    preg_match('/src="([^"]+)"/', $current_chapter->video_url, $match);
                                    $url = $match[1];
                                    $video_url = str_replace('https://www.youtube.com/embed/', '', $url);
                                } else {
                                    $video_url = str_replace('https://youtu.be/', '', str_replace('https://www.youtube.com/watch?v=', '', $current_chapter->video_url));
                                }
                            @endphp
                            <iframe width="1280" height="720"
                                src="https://www.youtube.com/embed/{{ $video_url }}" title="YouTube video player"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                        @endif
                    </div>
                </div>
                <div class="col-lg-5 col-12">
                    <div class="pdp-summery-accordian">
                        <ul class="pdp-summery-accordian">
                            @foreach ($headers as $key => $header)
                                <li class="set has-children">
                                    <a href="javascript:;" class="acnav-label" data-bs-target="#ChapterDetails{{ $key }}">
                                        <span>{{ $header->title }}</span>
                                    </a>
                                    <div class="acnav-list" id="ChapterDetails{{ $key }}">
                                        @foreach ($header->chapters_data as $k => $chapters)
                                            <div class="chapter-topics">
                                                @php
                                                    $i++;
                                                @endphp
                                                <!-- checked start -->
                                                <div class="checkbox-custom">
                                                    @php
                                                        $chapter_status_check = App\Models\Chapters::chapterstatus($chapters->id);
                                                    @endphp
                                                    @if (isset($chapter_status_check) && $chapter_status_check->status == 'Active')
                                                        <input type="checkbox" class="description{{ $i }} checkbox" id="description{{ $i }}" data-value="{{ $chapters->id }}" checked>
                                                    @else
                                                        <input type="checkbox" class="description{{ $i }} checkbox" id="description{{ $i }}" data-value="{{ $chapters->id }}">
                                                    @endif
                                                    <label for="description{{ $i }}">{{ $i . '. ' . $chapters->name }}</label>
                                                </div>
                                                <div data-id="{{ $chapters->id }}" class="course-time d-flex align-items-center">
                                                    <span>[{{ $chapters->duration }}]</span>
                                                    <a href="{{ route('store.fullscreen', [$slug, \Illuminate\Support\Facades\Crypt::encrypt($course_id), \Illuminate\Support\Facades\Crypt::encrypt($chapters->id)]) }}" class="btn-ic"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M1.5 9C1.5 4.85786 4.85786 1.5 9 1.5C13.1421 1.5 16.5 4.85786 16.5 9C16.5 13.1421 13.1421 16.5 9 16.5C4.85786 16.5 1.5 13.1421 1.5 9ZM9 0C4.02944 0 0 4.02944 0 9C0 13.9706 4.02944 18 9 18C13.9706 18 18 13.9706 18 9C18 4.02944 13.9706 0 9 0ZM12.5758 7.04303C12.8412 6.72501 12.7986 6.25205 12.4805 5.98666C12.1625 5.72127 11.6896 5.76394 11.4242 6.08197L7.37843 10.9301L5.76746 9.39461C5.46763 9.10882 4.99289 9.12021 4.70711 9.42004C4.42132 9.71987 4.43271 10.1946 4.73254 10.4804L6.92347 12.5687C7.07367 12.7118 7.27647 12.7864 7.48364 12.7746C7.6908 12.7628 7.88382 12.6656 8.01677 12.5063L12.5758 7.04303Z" fill="white"></path>
                                                        </svg></a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="chapter-description-section border-top padding-bottom">
        <div class="container">
            <div class="row no-gutters">
                <div class="col-lg-5 col-xl-6 col-12">
                    <div class="pdp-tab tabs-wrapper">
                        <ul class="tabs d-flex">
                            <li class="tab-link active" data-tab="tab-1">
                                <a href="javascript:;">{{ __('Description') }} </a>
                            </li>
                            <li class="tab-link" data-tab="tab-2">
                                <a href="javascript:;">{{ __('Syllabus') }} </a>
                            </li>
                        </ul>
                        <div class="tabs-container">
                            <div class="tab-content active" id="tab-1">
                                <div class="section-title">
                                    <h3>{{ $courses->title }}: <br>
                                        {{ $current_chapter->name }}
                                    </h3>
                                </div>
                                <p>{!! $current_chapter->chapter_description !!}</p>
                                
                            </div>
                            <div class="tab-content" id="tab-2">
                                @foreach ($headers as $key => $header)
                                    <div class="set has-children">
                                        <a href="javascript:;" class="acnav-label" data-bs-target="#ChapterOne{{ $key }}" aria-expanded="true" aria-controls="ChapterOne">
                                            <span>{{ $header->title }}</span>
                                        </a>
                                        <div class="acnav-list" style="display: none;">
                                            @foreach ($header->chapters_data as $k => $chapters)
                                                <div class="chapter-description">
                                                    <p>{!! $chapters->chapter_description !!}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 col-xl-6 col-12">
                    <div class="course-download ms-5">
                        <div class="title-wrap">
                            <h4>{{ __('Project Files') }}:</h4>
                        </div>
                        @foreach ($practices_files as $practices_file)
                            <div class="course-download-card">
                                <div class="course-image">
                                    <img src="{{ asset('assets/themes/theme3/images/pdf-image.png') }}"  alt="">
                                </div>
                                <div class="corse-caption">
                                    <div class="caption-left">
                                        <h5>
                                            <a class="action-item" href="{{ asset(Storage::url('uploads/practices/' . $practices_file->files)) }}"></a>
                                        </h5>
                                        <span>({{ $practices_file->file_name }})</span>
                                    </div>
                                    <div class="btn-wrap">
                                        <a href="{{ asset(Storage::url('uploads/practices/' . $practices_file->files)) }}" class="btn-ic">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="18" viewBox="0 0 22 18" fill="none">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M7.22936 5.30026C7.83007 3.94874 9.12646 2.04357 11.8695 2.04357C14.6369 2.04357 16.6521 4.3659 16.6521 6.62399C16.6521 7.10424 17.0415 7.49356 17.5217 7.49356C17.7513 7.49356 18.3294 7.63139 18.8419 8.02264C19.3163 8.38478 19.6956 8.93063 19.6956 9.76821C19.6956 10.4335 19.4764 11.13 19.0744 11.6422C18.6887 12.1336 18.1349 12.4621 17.3768 12.4621C16.8883 12.4621 16.6138 12.4631 16.4788 12.4647C16.4452 12.4651 16.4163 12.4656 16.3936 12.4662C16.3828 12.4665 16.3684 12.4669 16.3533 12.4677L16.3417 12.4684C16.3351 12.4688 16.3269 12.4694 16.3179 12.4702C16.3166 12.4703 16.2636 12.4742 16.2005 12.4883C16.1816 12.4926 16.1441 12.5016 16.0984 12.5179L16.0971 12.5183C16.0686 12.5285 15.9497 12.5709 15.8289 12.673C15.7638 12.734 15.6337 12.9084 15.5787 13.0248C15.5345 13.1834 15.5378 13.5085 15.5844 13.6636C15.7082 13.9692 15.9581 14.0933 15.9927 14.1105L15.9954 14.1118C16.1091 14.1698 16.2123 14.1885 16.2195 14.1898L16.2199 14.1899C16.249 14.1957 16.2723 14.1988 16.2829 14.2002C16.3177 14.2046 16.3483 14.2062 16.3544 14.2065L16.3551 14.2066C16.3646 14.2071 16.3747 14.2075 16.3845 14.2079C16.3968 14.2084 16.4089 14.2087 16.4194 14.209C16.4995 14.2113 16.6398 14.2134 16.7539 14.2149L16.9051 14.2167L16.9517 14.2173L16.9646 14.2174L16.9679 14.2174L16.9688 14.2174L16.9691 14.2174H16.9691L16.9693 14.2015H16.9693L16.9692 14.2174C17.0297 14.2181 17.0889 14.2125 17.1461 14.2013L17.3768 14.2012C18.7186 14.2012 19.759 13.5867 20.4425 12.7159C21.1098 11.8657 21.4348 10.7804 21.4348 9.76821C21.4348 8.30003 20.7271 7.27377 19.8972 6.64024C19.4007 6.26125 18.8444 6.00926 18.3408 5.87401C17.9501 2.95975 15.3288 0.304443 11.8695 0.304443C8.4795 0.304443 6.72463 2.48275 5.89143 4.07397C4.03463 4.12989 2.71035 4.86499 1.84442 5.85859C0.922766 6.91614 0.565186 8.21418 0.565186 9.13899C0.565186 9.154 0.565575 9.16902 0.566352 9.18402C0.66378 11.0629 1.34678 12.3479 2.27341 13.1564C3.17726 13.9449 4.24171 14.2175 5.02171 14.2175C5.32065 14.2175 5.50135 14.2165 5.60258 14.2148C5.64242 14.2142 5.69868 14.2132 5.74395 14.2095C5.74784 14.2091 5.799 14.2056 5.85975 14.1927C5.878 14.1888 5.91245 14.181 5.95447 14.167L5.95464 14.1669C5.98167 14.1579 6.08462 14.1237 6.19508 14.0426C6.28299 13.978 6.64486 13.671 6.52928 13.1524C6.44474 12.773 6.15304 12.6095 6.11423 12.5877L6.11159 12.5862C6.03135 12.5404 5.96214 12.5182 5.93738 12.5106C5.90461 12.5004 5.87768 12.4942 5.86199 12.4908C5.83063 12.4841 5.8054 12.4805 5.79402 12.4789C5.76934 12.4756 5.74889 12.4738 5.74006 12.4731C5.6859 12.4687 5.5952 12.4662 5.54976 12.465L5.47203 12.463L5.44758 12.4625L5.44068 12.4623L5.43878 12.4623L5.43824 12.4623L5.43808 12.4623L5.43803 12.4623L5.43772 12.4773H5.43768L5.43799 12.4623C5.37473 12.461 5.31291 12.4665 5.25323 12.4781C5.18661 12.4783 5.10985 12.4784 5.02171 12.4784C4.60605 12.4784 3.96398 12.3233 3.41674 11.8459C2.89513 11.3908 2.38519 10.5778 2.30443 9.11786C2.31029 8.57381 2.5417 7.70554 3.15552 7.00122C3.74526 6.32453 4.73548 5.743 6.39668 5.8158C6.75383 5.83146 7.08416 5.62693 7.22936 5.30026ZM5.43708 12.5069L5.43711 12.5069L5.42026 13.3317L5.43708 12.5069ZM11.8695 9.86966C11.8695 9.38941 11.4802 9.0001 11 9.0001C10.5197 9.0001 10.1304 9.38941 10.1304 9.86966L10.1304 14.7272L9.00615 13.603C8.66656 13.2634 8.11598 13.2634 7.7764 13.603C7.43681 13.9426 7.43681 14.4931 7.7764 14.8327L10.3851 17.4414C10.5482 17.6045 10.7693 17.6961 11 17.6961C11.2306 17.6961 11.4518 17.6045 11.6148 17.4414L14.2235 14.8327C14.5631 14.4931 14.5631 13.9426 14.2235 13.603C13.884 13.2634 13.3334 13.2634 12.9938 13.603L11.8695 14.7272L11.8695 9.86966Z" fill="white" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    @php
        $main_homepage_email_subscriber_key = array_search('Home-Email-subscriber',array_column($getStoreThemeSetting, 'section_name'));        
        $email_subscriber_enable = 'off';
        $homepage_email_subscriber_title = 'Improve Your Skills with ModernCourse';
        $homepage_email_subscriber_subtext = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy.';
        $homepage_email_subscriber_button = 'Subscribe';

        if(!empty($getStoreThemeSetting[$main_homepage_email_subscriber_key])) {
            $homepage_subscriber_header = $getStoreThemeSetting[$main_homepage_email_subscriber_key];
            $email_subscriber_enable = $homepage_subscriber_header['section_enable'];

            $homepage_email_subscriber_title_key = array_search('Title',array_column($homepage_subscriber_header['inner-list'], 'field_name'));            
            $homepage_email_subscriber_title = $homepage_subscriber_header['inner-list'][$homepage_email_subscriber_title_key]['field_default_text'];

            $homepage_email_subscriber_subtext_key = array_search('Sub Text',array_column($homepage_subscriber_header['inner-list'], 'field_name'));
            $homepage_email_subscriber_subtext = $homepage_subscriber_header['inner-list'][$homepage_email_subscriber_subtext_key]['field_default_text'];

            $homepage_email_subscriber_button_key = array_search('Button',array_column($homepage_subscriber_header['inner-list'], 'field_name'));
            $homepage_email_subscriber_button = $homepage_subscriber_header['inner-list'][$homepage_email_subscriber_button_key]['field_default_text'];
        }
    @endphp

    @if($email_subscriber_enable == 'on') 
        <section class="newsletter-section padding-bottom padding-top border-top">
            <div class="container">
                <div class="newsletter-container">
                    <div class="section-title">
                        <h2>{{ $homepage_email_subscriber_title }}</h2>
                        <p>{{ $homepage_email_subscriber_subtext }}</p>
                    </div>
                    <div class="newsletter-form">
                        {{ Form::open(array('route' => array('subscriptions.store_email', $store->id),'method' => 'POST')) }}
                            <div class="input-wrapper">
                                {{ Form::email('email',null,array('aria-label'=>'Enter your email address','placeholder'=>__('Type your email address....'))) }}
                                <button type="submit" class="btn">{{ $homepage_email_subscriber_button }} <svg viewBox="0 0 10 5">
                                    <path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z">
                                    </path>
                                </svg></button>
                            </div>
                            <div class="checkbox-custom">
                                <input type="checkbox" class="" id="newslettercheckbox">
                                <label for="newslettercheckbox">{{ $homepage_email_subscriber_subtext }}</label>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        
        </section>
    @endif
    
@endsection
