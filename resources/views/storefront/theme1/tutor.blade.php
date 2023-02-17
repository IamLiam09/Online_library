@extends('layouts.theme1.shopfront')
@section('page-title')
    {{__('Tutor')}} - {{($store->tagline) ?  $store->tagline : config('APP_NAME', ucfirst($store->name))}}
@endsection
@push('css-page')
@endpush
@push('script-page')
    <script>
        $(document).on('click', '.add_to_cart', function (e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            $.ajax({
                type: "POST",
                url: '{{route('user.addToCart', ['__product_id',$store->slug])}}'.replace('__product_id', id),
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function (response) {
                    if (response.status == "Success") {
                        show_toastr('Success', response.success, 'success');
                        $('#cart-btn-' + id).html('Already in Cart');
                        $('.cart_item_count').html(response.item_count);
                    } else {
                        show_toastr('Error', response.error, 'error');
                    }

                },
                error: function (result) {
                }
            });
        });
    </script>
@endpush
@section('content')
    <div class="over-menu"></div>
    <div id="wrap">
        <section class="common-banner-section tutor-banner">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5 col-12">
                        <div class="common-banner-content">
                            <a href="{{route('store.slug',$store->slug)}}" class="back-btn">
                                <span class="svg-ic">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="11" height="5" viewBox="0 0 11 5" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M10.5791 2.28954C10.5791 2.53299 10.3818 2.73035 10.1383 2.73035L1.52698 2.73048L2.5628 3.73673C2.73742 3.90636 2.74146 4.18544 2.57183 4.36005C2.40219 4.53467 2.12312 4.53871 1.9485 4.36908L0.133482 2.60587C0.0480403 2.52287 -0.000171489 2.40882 -0.000171488 2.2897C-0.000171486 2.17058 0.0480403 2.05653 0.133482 1.97353L1.9485 0.210321C2.12312 0.0406877 2.40219 0.044729 2.57183 0.219347C2.74146 0.393966 2.73742 0.673036 2.5628 0.842669L1.52702 1.84888L10.1383 1.84875C10.3817 1.84874 10.5791 2.04609 10.5791 2.28954Z" fill="white"></path>
                                    </svg>
                                </span>
                                {{__('Back to home')}}
                            </a>
                            <div class="user-profile">
                                <div class="user-image">
                                    {{-- <img src="assets/images/user-6.jpg" alt=""> --}}
                                    @if(!empty($tutor->avatar))
                                        <img src="{{asset(Storage::url('uploads/profile/'.$tutor->avatar))}}" alt="user">
                                    @else
                                        <img src="{{asset('assets/img/user.png')}}" alt="user">
                                    @endif
                                </div>
                                <div class="user-info-main">
                                    <h2>{{$tutor->name}}</h2>
                                    <ul class="user-tags">
                                        <li> {{__('FEATURED IN')}}</li>
                                        <li><span class="badge">{{$tutor_course->category_id->name}}</span></li>
                                    </ul>
                                </div>
                          </div>
                        </div>
                    </div>
                </div>
                <div class="banner-image">
                    {{-- <img src="{{ asset('assets/imgs/Male-Running-common.png') }}" alt=""> --}}

                    @php
                        $data=explode(".",$store->store_theme);                               
                    @endphp

                    @if($data[0]=='yellow-style')
                        <img src="{{ asset('assets/imgs/Male-Running-common1.png') }}" alt="">
                    @elseif($data[0]=='blue-style')
                        <img src="{{ asset('assets/imgs/Male-Running-common2.png') }}" alt="">
                    @else
                        <img src="{{ asset('assets/imgs/Male-Running-common3.png') }}" alt="">
                    @endif

                </div>
            </div>
        </section>
        <section class="about-tutor-section">
            <div class="container">
                <div class="tutor-info-wrap">
                    <ul>
                        <li>
                            <h4>{{__('Rating')}}</h4>
                            <div class="review-rating">
                                <span><b>{{$avg_rating}}</b>/5</span> 
                                <div class="review-star d-flex align-items-center">
                                    <span class="star-icon">
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                    </span>
                                </div>
                                <span>({{$user_count}})</span>
                            </div>
                        </li>
                        <li>
                            <h4>{{__('Total Students')}}</h4>
                            <span>{{'0'}}</span>
                        </li>
                        <li>
                            <h4>{{__('Total Courses')}}</h4>
                            <span>{{$courses->count()}}</span>
                        </li>
                        <li>
                            <h4>{{__('Master of')}}</h4>
                            @if(!empty($tutor->degree))
                                <span>{{$tutor->degree}}</span>
                            @endif
                        </li>
                        <li>
                            <h4>{{__('Language')}}</h4>
                            <span>{{$tutor_course->lang}}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="padding-top padding-bottom tutor-featured-course">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-xl-7 col-md-12">
                        <div class="section-title">
                            <h2>{{$tutor_course['title']}}</h2>
                        </div>
                        <p>{!! $tutor_course['course_description'] !!} </p>
                    </div>
                    <div class="col-lg-6 col-xl-5 col-12">
                        <div class="featured-courses">
                            <div class="box-title">
                                <h4>{{__('Featured Courses')}}</h4>
                            </div>
                            <div class="course-list">
                                <div class="row">
                                    @if(!empty($courses))
                                        @foreach($courses as $course)
                                            <div class="course-widget col-lg-12  col-sm-6 col-12">
                                                <div class="course-widget-inner">
                                                    <div class="course-media">
                                                        <a href="{{route('store.viewcourse',[$store->slug,\Illuminate\Support\Facades\Crypt::encrypt($course->id)])}}" tabindex="0">
                                                            {{-- <img src="assets/images/courses.png" alt=""> --}}
                                                            @if(!empty($course->thumbnail))
                                                                <img src="{{asset(Storage::url('uploads/thumbnail/'.$course->thumbnail))}}" alt="card">
                                                            @else
                                                                <img src="{{asset('assets/img/card-img.svg')}}" alt="card">
                                                            @endif
                                                            <span class="badge">{{ !empty($course->category_id->name) ? $course->category_id->name : ''}}</span>
                                                        </a>
                        
                                                    </div>
                                                    <div class="course-caption">
                                                        <h6>
                                                            <a href="{{route('store.viewcourse',[$store->slug,\Illuminate\Support\Facades\Crypt::encrypt($course->id)])}}"> {{$course->title}}</a>
                                                        </h6>
                                                        <div class="review-rating">
                                                            <span><b>{{$course->course_rating()}}</b></span> 
                                                            <div class="review-star d-flex align-items-center">
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                            </div>
                                                        </div>
                                                        <div class="price-addtocart d-flex align-items-center justify-content-between">
                                                            <div class="price">
                                                                <ins> <span class="currency-type">{{ Utility::priceFormat($course->price)}}</span></ins>
                                                            </div>
                                                            @php
                                                                $cart = session()->get($slug);
                                                                $key = false;
                                                            @endphp
                                                            @if(!empty($cart['products']))
                                                                @foreach($cart['products'] as $k => $value)

                                                                    @if($course->id == $value['product_id'])
                                                                        @php
                                                                            $key = $k
                                                                        @endphp
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                            {{-- <a href="#" class="btn" tabindex="0">Add To Cart</a> --}}
                                                            @if(Auth::guard('students')->check())
                                                                @if(in_array($course->id,Auth::guard('students')->user()->purchasedCourse()))
                                                                    <a class="btn" href="{{route('store.fullscreen',[$store->slug,Crypt::encrypt($course->id),''])}}">
                                                                        {{__('Start Watching')}}
                                                                    </a>
                                                                @else
                                                                    <a class="add_to_cart" data-id="{{$course->id}}">
                                                                        @if($key !== false)
                                                                            <b id="cart-btn-{{$course->id}}" class="btn btn-secondary">{{__('Already in Cart')}}</b>
                                                                        @else
                                                                            <b id="cart-btn-{{$course->id}}" class="btn btn-secondary">{{__('Add in Cart')}}</b>
                                                                        @endif
                                                                    </a>
                                                                @endif
                                                            @else
                                                                <a class="add_to_cart" data-id="{{$course->id}}">
                                                                    @if($key !== false)
                                                                        <b id="cart-btn-{{$course->id}}" class="btn btn-secondary">{{__('Already in Cart')}}</b>
                                                                    @else
                                                                        <b id="cart-btn-{{$course->id}}" class="btn btn-secondary">{{__('Add in Cart')}}</b>
                                                                    @endif
                                                                </a>
                                                            @endif                                                          
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>                     
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="more-from-tutor padding-bottom">
            <div class="container">
                <div class="section-title d-flex align-items-center justify-content-between">
                    <h2>{{__('More from')}}  <br> <b>@ {{ $tutor->name }} </b></h2>
                    <a href="{{route('store.search',[$store->slug])}}" class="btn"> {{__('Learn More')}} </a>
                </div>
                <div class="more-course-slider slider-comman">
                    @foreach($courses as $course)
                        <div class="course-widget">
                            <div class="course-widget-inner">
                                <div class="course-media">
                                    <a href="{{route('store.viewcourse',[$store->slug,\Illuminate\Support\Facades\Crypt::encrypt($course->id)])}}">
                                        {{-- <img src="assets/images/courses.png" alt=""> --}}
                                        @if(!empty($course->thumbnail))
                                            <img src="{{asset(Storage::url('uploads/thumbnail/'.$course->thumbnail))}}" alt="card">
                                        @else
                                            <img src="{{asset('assets/img/card-img.svg')}}" alt="card">
                                        @endif
                                    </a>
                                </div>
                                @php
                                    $cart = session()->get($slug);
                                    $key = false;
                                @endphp
                                @if(!empty($cart['products']))
                                    @foreach($cart['products'] as $k => $value)

                                        @if($course->id == $value['product_id'])
                                            @php
                                                $key = $k
                                            @endphp
                                        @endif
                                    @endforeach
                                @endif
                                <div class="course-caption">
                                    <div class="course-caption-top d-flex align-items-center">
                                        <span class="badge">{{ !empty($course->category_id->name) ? $course->category_id->name : ''}}</span>

                                        @if(Auth::guard('students')->check())
                                            @if(sizeof($course->student_wl)>0)
                                                <a href="#" class="wishlist-btn wishlist_btn add_to_wishlist" data-id="{{$course->id}}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="14"
                                                        viewBox="0 0 17 14" fill="none">
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M9.18991 3.10164C8.89678 3.37992 8.43395 3.37992 8.14082 3.10164L7.61627 2.60366C7.00231 2.0208 6.17289 1.66491 5.25627 1.66491C3.37348 1.66491 1.84718 3.17483 1.84718 5.03741C1.84718 6.82306 2.82429 8.29753 4.23488 9.50902C5.64667 10.7215 7.33461 11.5257 8.34313 11.9361C8.554 12.0219 8.77673 12.0219 8.9876 11.9361C9.99612 11.5257 11.6841 10.7215 13.0959 9.50901C14.5064 8.29753 15.4835 6.82305 15.4835 5.03741C15.4835 3.17483 13.9572 1.66491 12.0745 1.66491C11.1578 1.66491 10.3284 2.0208 9.71446 2.60366L9.18991 3.10164ZM8.66537 1.52219C7.7806 0.682237 6.57937 0.166016 5.25627 0.166016C2.53669 0.166016 0.332031 2.34701 0.332031 5.03741C0.332031 9.81007 5.61259 12.4457 7.76672 13.3223C8.34685 13.5584 8.98388 13.5584 9.56401 13.3223C11.7181 12.4457 16.9987 9.81006 16.9987 5.03741C16.9987 2.34701 14.794 0.166016 12.0745 0.166016C10.7514 0.166016 9.55013 0.682237 8.66537 1.52219Z"
                                                            fill="white"></path>
                                                    </svg>
                                                </a>
                                            @else
                                                <a href="#" class="wishlist-btn add_to_wishlist " data-id="{{$course->id}}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="14"
                                                        viewBox="0 0 17 14" fill="none">
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M9.18991 3.10164C8.89678 3.37992 8.43395 3.37992 8.14082 3.10164L7.61627 2.60366C7.00231 2.0208 6.17289 1.66491 5.25627 1.66491C3.37348 1.66491 1.84718 3.17483 1.84718 5.03741C1.84718 6.82306 2.82429 8.29753 4.23488 9.50902C5.64667 10.7215 7.33461 11.5257 8.34313 11.9361C8.554 12.0219 8.77673 12.0219 8.9876 11.9361C9.99612 11.5257 11.6841 10.7215 13.0959 9.50901C14.5064 8.29753 15.4835 6.82305 15.4835 5.03741C15.4835 3.17483 13.9572 1.66491 12.0745 1.66491C11.1578 1.66491 10.3284 2.0208 9.71446 2.60366L9.18991 3.10164ZM8.66537 1.52219C7.7806 0.682237 6.57937 0.166016 5.25627 0.166016C2.53669 0.166016 0.332031 2.34701 0.332031 5.03741C0.332031 9.81007 5.61259 12.4457 7.76672 13.3223C8.34685 13.5584 8.98388 13.5584 9.56401 13.3223C11.7181 12.4457 16.9987 9.81006 16.9987 5.03741C16.9987 2.34701 14.794 0.166016 12.0745 0.166016C10.7514 0.166016 9.55013 0.682237 8.66537 1.52219Z"
                                                            fill="white"></path>
                                                    </svg>
                                                </a>
                                            @endif
                                        @else
                                            <a href="#" class="wishlist-btn add_to_wishlist " data-id="{{$course->id}}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="14"
                                                    viewBox="0 0 17 14" fill="none">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M9.18991 3.10164C8.89678 3.37992 8.43395 3.37992 8.14082 3.10164L7.61627 2.60366C7.00231 2.0208 6.17289 1.66491 5.25627 1.66491C3.37348 1.66491 1.84718 3.17483 1.84718 5.03741C1.84718 6.82306 2.82429 8.29753 4.23488 9.50902C5.64667 10.7215 7.33461 11.5257 8.34313 11.9361C8.554 12.0219 8.77673 12.0219 8.9876 11.9361C9.99612 11.5257 11.6841 10.7215 13.0959 9.50901C14.5064 8.29753 15.4835 6.82305 15.4835 5.03741C15.4835 3.17483 13.9572 1.66491 12.0745 1.66491C11.1578 1.66491 10.3284 2.0208 9.71446 2.60366L9.18991 3.10164ZM8.66537 1.52219C7.7806 0.682237 6.57937 0.166016 5.25627 0.166016C2.53669 0.166016 0.332031 2.34701 0.332031 5.03741C0.332031 9.81007 5.61259 12.4457 7.76672 13.3223C8.34685 13.5584 8.98388 13.5584 9.56401 13.3223C11.7181 12.4457 16.9987 9.81006 16.9987 5.03741C16.9987 2.34701 14.794 0.166016 12.0745 0.166016C10.7514 0.166016 9.55013 0.682237 8.66537 1.52219Z"
                                                        fill="white"></path>
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                    <h6>
                                        <a href="{{route('store.viewcourse',[$store->slug,\Illuminate\Support\Facades\Crypt::encrypt($course->id)])}}">{{$course->title}}</a>
                                    </h6>
                                    <div class="review-star d-flex align-items-center">

                                        @if($store->enable_rating == 'on')
                                            @for($i =1;$i<=5;$i++)
                                                @php
                                                    $icon = 'fa-star';
                                                    $color = '';
                                                    $newVal1 = ($i-0.5);
                                                    if($course->course_rating() < $i && $course->course_rating() >= $newVal1)
                                                    {
                                                        $icon = 'fa-star-half-alt';
                                                    }
                                                    if($course->course_rating() >= $newVal1)
                                                    {
                                                        $color = 'text-warning';
                                                    }
                                                @endphp
                                                <i class="fas {{$icon .' '. $color}}"></i>
                                            @endfor
                                        @endif
                                                                                
                                    </div>
                                    <div class="course-bottom">
                                        <div class="course-detail">
                                            <p><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                    viewBox="0 0 12 12" fill="none">
                                                    <g clip-path="url(#clip0_19_26)">
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M5.89035 0.981725C3.17939 0.981725 0.981725 3.17939 0.981725 5.89035C0.981725 7.16364 1.46654 8.3237 2.26163 9.196C2.67034 8.3076 3.38394 7.61471 4.24934 7.22454C3.75002 6.77528 3.43604 6.12405 3.43604 5.39949C3.43604 4.04401 4.53487 2.94518 5.89035 2.94518C7.24583 2.94518 8.34466 4.04401 8.34466 5.39949C8.34466 6.11254 8.04058 6.75457 7.55502 7.20298C8.43552 7.56901 9.18412 8.23342 9.54281 9.16977C10.3238 8.30051 10.799 7.15092 10.799 5.89035C10.799 3.17939 8.60131 0.981725 5.89035 0.981725ZM9.69387 10.3882C10.9703 9.30774 11.7807 7.69368 11.7807 5.89035C11.7807 2.6372 9.1435 0 5.89035 0C2.6372 0 0 2.6372 0 5.89035C0 7.69366 0.810351 9.30769 2.08677 10.3882C2.12022 10.426 2.15982 10.459 2.20478 10.4855C3.21384 11.2959 4.49547 11.7807 5.89035 11.7807C7.28519 11.7807 8.5668 11.2959 9.57584 10.4856C9.62075 10.4591 9.66038 10.4261 9.69387 10.3882ZM8.74856 9.88145L8.66025 9.61652C8.29993 8.53556 7.14229 7.8538 5.89035 7.8538C4.6233 7.8538 3.49161 8.64647 3.0586 9.83723L3.04038 9.88735C3.84386 10.4613 4.82767 10.799 5.89035 10.799C6.95667 10.799 7.94357 10.459 8.74856 9.88145ZM5.89035 6.87208C6.70364 6.87208 7.36294 6.21278 7.36294 5.39949C7.36294 4.5862 6.70364 3.9269 5.89035 3.9269C5.07706 3.9269 4.41776 4.5862 4.41776 5.39949C4.41776 6.21278 5.07706 6.87208 5.89035 6.87208Z"
                                                            fill="#8A94A6" />
                                                    </g>
                                                    <defs>
                                                        <clipPath id="clip0_19_26">
                                                            <rect width="11.7807" height="11.7807" fill="white" />
                                                        </clipPath>
                                                    </defs>
                                                </svg>{{$course->student_count->count()}} &nbsp; <span>{{__('Students')}}</span></p>
                                            <p><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                    viewBox="0 0 12 12" fill="none">
                                                    <g clip-path="url(#clip0_19_28)">
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M5.45116 0.161627C5.72754 0.0234357 6.05285 0.0234355 6.32924 0.161627L11.122 2.558C11.8456 2.91979 11.8456 3.95237 11.122 4.31416L10.4238 4.66324L11.122 5.01231C11.8456 5.3741 11.8456 6.40669 11.122 6.76848L10.4238 7.11755L11.122 7.46663C11.8456 7.82842 11.8456 8.861 11.122 9.22279L6.32924 11.6192C6.05286 11.7574 5.72754 11.7574 5.45116 11.6192L0.658407 9.22279C-0.0651723 8.861 -0.0651715 7.82842 0.658406 7.46663L1.35656 7.11755L0.658407 6.76848C-0.0651723 6.40669 -0.0651715 5.3741 0.658406 5.01231L1.35656 4.66324L0.658407 4.31416C-0.0651731 3.95237 -0.0651713 2.91979 0.658407 2.558L5.45116 0.161627ZM2.67882 4.22677C2.67563 4.22513 2.67243 4.22353 2.66921 4.22197L1.09745 3.43608L5.8902 1.03971L10.6829 3.43608L9.1111 4.22201C9.10793 4.22355 9.10479 4.22512 9.10166 4.22673L5.8902 5.83246L2.67882 4.22677ZM2.45416 5.21204L1.09745 5.8904L2.66915 6.67625L2.67888 6.68111L5.8902 8.28677L9.10163 6.68105L9.11112 6.67631L10.6829 5.8904L9.32623 5.21204L6.32924 6.71054C6.05286 6.84873 5.72754 6.84873 5.45116 6.71054L2.45416 5.21204ZM1.09745 8.34471L2.45416 7.66635L5.45116 9.16485C5.72754 9.30304 6.05286 9.30304 6.32924 9.16485L9.32623 7.66635L10.6829 8.34471L5.8902 10.7411L1.09745 8.34471Z"
                                                            fill="#8A94A6" />
                                                    </g>
                                                    <defs>
                                                        <clipPath id="clip0_19_28">
                                                            <rect width="11.7807" height="11.7807" fill="white" />
                                                        </clipPath>
                                                    </defs>
                                                </svg> {{$course->chapter_count->count()}} &nbsp; <span> {{__('Chapters')}}</span></p>
                                            <p>{{$course->level}}</p>
                                        </div>
                                    </div>
                                    <div class="price-addtocart d-flex align-items-center justify-content-between">
                                        <div class="price">
                                            <ins> <span class="currency-type">{{ ($course->is_free == 'on')? __('Free') : Utility::priceFormat($course->price)}}</span></ins>
                                        </div>
                                        {{-- <a href="#" class="btn">Add To Cart</a> --}}
                                        @if(Auth::guard('students')->check())
                                            @if(in_array($course->id,Auth::guard('students')->user()->purchasedCourse()))
                                                <a class="btn" href="{{route('store.fullscreen',[$store->slug,Crypt::encrypt($course->id),''])}}">
                                                    {{__('Start Watching')}}
                                                </a>
                                            @else
                                                <a class="add_to_cart" data-id="{{$course->id}}">
                                                    @if($key !== false)
                                                        <b id="cart-btn-{{$course->id}}" class="btn btn-secondary">{{__('Already in Cart')}}</b>
                                                    @else
                                                        <b id="cart-btn-{{$course->id}}" class="btn btn-secondary">{{__('Add in Cart')}}</b>
                                                    @endif
                                                </a>
                                            @endif
                                        @else
                                            <a class="add_to_cart" data-id="{{$course->id}}">
                                                @if($key !== false)
                                                    <b id="cart-btn-{{$course->id}}" class="btn btn-secondary">{{__('Already in Cart')}}</b>
                                                @else
                                                    <b id="cart-btn-{{$course->id}}" class="btn btn-secondary">{{__('Add in Cart')}}</b>
                                                @endif
                                            </a>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
@endsection
