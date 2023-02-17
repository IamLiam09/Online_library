@extends('layouts.theme2.shopfront')
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
    <div class="wrapper" id="wrap">        
        <section class="common-banner-section tutor-banner" style="background-image:url({{ asset('assets/themes/theme2/images/comman-banner.png') }}) ;">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-12">
                        <div class="common-banner-content">
                            <a href="{{route('store.slug',$store->slug)}}" class="back-btn">
                                <span class="svg-ic">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="11" height="5" viewBox="0 0 11 5" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M10.5791 2.28954C10.5791 2.53299 10.3818 2.73035 10.1383 2.73035L1.52698 2.73048L2.5628 3.73673C2.73742 3.90636 2.74146 4.18544 2.57183 4.36005C2.40219 4.53467 2.12312 4.53871 1.9485 4.36908L0.133482 2.60587C0.0480403 2.52287 -0.000171489 2.40882 -0.000171488 2.2897C-0.000171486 2.17058 0.0480403 2.05653 0.133482 1.97353L1.9485 0.210321C2.12312 0.0406877 2.40219 0.044729 2.57183 0.219347C2.74146 0.393966 2.73742 0.673036 2.5628 0.842669L1.52702 1.84888L10.1383 1.84875C10.3817 1.84874 10.5791 2.04609 10.5791 2.28954Z" fill="white"></path>
                                    </svg>
                                </span>
                                {{ __('Back to Home') }}
                            </a>
                          <div class="user-profile">
                              <div class="user-image">
                                    @if(!empty($tutor->avatar))
                                        <img src="{{asset(Storage::url('uploads/profile/'.$tutor->avatar))}}" alt="user">
                                    @else
                                        <img src="{{ asset('assets/themes/theme2/images/amelie.png') }}" alt="">
                                    @endif
                              </div>
                              <div class="user-info-main">
                                <div class="review-rating">
                                    <span class="rating-num">{{$avg_rating}}</span>
                                    <div class="review-star d-flex align-items-center">
                                        @for($i =1;$i<=5;$i++)
                                            @php
                                                $icon = 'fa-star';
                                                $color = '';
                                                $newVal1 = ($i-0.5);
                                                if($avg_rating < $i && $avg_rating >= $newVal1)
                                                {
                                                    $icon = 'fa-star-half-alt';
                                                }
                                                if($avg_rating >= $newVal1)
                                                {
                                                    $color = 'text-warning';
                                                }
                                            @endphp
                                            <i class="fas {{$icon .' '. $color}}"></i>
                                        @endfor
                                    </div>
                                    <span>({{$user_count}})</span>
                                </div>
                                  <h2> {{$tutor->name}}</h2>
                                  <ul class="user-tags">
                                        <li>{{ __('Featured in') }} :</li>                                       
                                        <li><span class="badge">{{$tutor_course->category_id->name}}</span></li>
                                  </ul>
                              </div>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="about-tutor-section  border-bottom ">
            <div class="container padding-bottom">
                <div class="row">
                    <div class="col-lg-7 col-xl-6 col-12">
                        <div class="tutor-left-side">
                            <ul class="tutor-sub d-flex justify-content-between">
                                <li>
                                    <span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12.6812 2.11353C6.84482 2.11353 2.11353 6.84482 2.11353 12.6812C2.11353 15.4224 3.15726 17.9198 4.869 19.7978C5.7489 17.8852 7.28518 16.3935 9.14827 15.5535C8.0733 14.5863 7.39734 13.1843 7.39734 11.6244C7.39734 8.70623 9.76299 6.34058 12.6812 6.34058C15.5993 6.34058 17.965 8.70623 17.965 11.6244C17.965 13.1595 17.3103 14.5417 16.265 15.5071C18.1606 16.2951 19.7722 17.7255 20.5444 19.7413C22.2258 17.8699 23.2488 15.395 23.2488 12.6812C23.2488 6.84482 18.5175 2.11353 12.6812 2.11353ZM20.8696 22.3645C23.6177 20.0384 25.3623 16.5635 25.3623 12.6812C25.3623 5.67755 19.6848 0 12.6812 0C5.67755 0 0 5.67755 0 12.6812C0 16.5634 1.74458 20.0383 4.49254 22.3644C4.56455 22.4459 4.6498 22.5169 4.74661 22.5739C6.91897 24.3185 9.67817 25.3623 12.6812 25.3623C15.6841 25.3623 18.4432 24.3186 20.6155 22.574C20.7122 22.5171 20.7975 22.4461 20.8696 22.3645ZM18.8345 21.2735L18.6444 20.7031C17.8687 18.3759 15.3764 16.9082 12.6812 16.9082C9.95336 16.9082 7.51698 18.6147 6.58477 21.1783L6.54554 21.2862C8.27533 22.5218 10.3933 23.2488 12.6812 23.2488C14.9768 23.2488 17.1015 22.5168 18.8345 21.2735ZM12.6812 14.7947C14.4321 14.7947 15.8514 13.3753 15.8514 11.6244C15.8514 9.87349 14.4321 8.45411 12.6812 8.45411C10.9303 8.45411 9.51087 9.87349 9.51087 11.6244C9.51087 13.3753 10.9303 14.7947 12.6812 14.7947Z" fill="#939393"></path>
                                        </svg></span>
                                    <p>{{ !empty($course->student_count) ? $course->student_count->count() : '0'}} <span>{{ __('Students') }}</span></p>
                                </li>                               
                                <li>
                                    <span class="icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.7355 0.348131C12.3305 0.0506232 13.0309 0.0506229 13.6259 0.348131L23.9441 5.50721C25.5019 6.2861 25.5019 8.50912 23.9441 9.288L22.4411 10.0395L23.9441 10.791C25.5018 11.5699 25.5019 13.7929 23.9441 14.5718L22.4411 15.3233L23.9441 16.0748C25.5018 16.8537 25.5019 19.0767 23.9441 19.8556L13.6259 25.0147C13.0309 25.3122 12.3305 25.3122 11.7355 25.0147L1.41735 19.8556C-0.14042 19.0768 -0.140418 16.8537 1.41735 16.0748L2.92038 15.3233L1.41735 14.5718C-0.14042 13.7929 -0.140418 11.5699 1.41735 10.791L2.92038 10.0395L1.41735 9.288C-0.140422 8.50912 -0.140418 6.2861 1.41735 5.50721L11.7355 0.348131ZM5.76703 9.09985C5.76018 9.09633 5.75329 9.09288 5.74636 9.08951L2.36255 7.39761L12.6807 2.23853L22.9989 7.39761L19.6149 9.0896C19.6081 9.09291 19.6013 9.0963 19.5946 9.09976L12.6807 12.5567L5.76703 9.09985ZM5.28337 11.221L2.36255 12.6814L5.74623 14.3733L5.76716 14.3837L12.6807 17.8405L19.5945 14.3836L19.6149 14.3734L22.9989 12.6814L20.0781 11.221L13.6259 14.4471C13.0309 14.7446 12.3305 14.7446 11.7355 14.4471L5.28337 11.221ZM2.36255 17.9652L5.28337 16.5048L11.7355 19.7309C12.3305 20.0284 13.0309 20.0284 13.6259 19.7309L20.0781 16.5048L22.9989 17.9652L12.6807 23.1243L2.36255 17.9652Z" fill="#939393"></path>
                                            </svg>
                                    </span>
                                    <p>{{$courses->count()}}  <span> {{ __('Courses') }}</span></p>
                                </li>
                                <li>
                                    <span class="icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12 24C5.37258 24 0 18.6274 0 12C0 5.37258 5.37258 0 12 0C18.6274 0 24 5.37258 24 12C24 18.6274 18.6274 24 12 24ZM21.9506 13C21.4816 17.7244 17.7244 21.4816 13 21.9506V21C13 20.4477 12.5523 20 12 20C11.4477 20 11 20.4477 11 21V21.9506C6.27558 21.4816 2.51844 17.7244 2.04938 13H3C3.55228 13 4 12.5523 4 12C4 11.4477 3.55228 11 3 11H2.04938C2.51844 6.27558 6.27558 2.51844 11 2.04938V3C11 3.55228 11.4477 4 12 4C12.5523 4 13 3.55228 13 3V2.04938C17.7244 2.51844 21.4816 6.27558 21.9506 11H21C20.4477 11 20 11.4477 20 12C20 12.5523 20.4477 13 21 13H21.9506ZM17.0812 8.02488C17.2271 7.64841 17.1323 7.22111 16.8409 6.94165C16.5495 6.66219 16.1186 6.5854 15.7486 6.74699L9.8425 9.32622C9.61153 9.42709 9.42716 9.61145 9.32629 9.84243L6.74706 15.7485C6.58547 16.1185 6.66226 16.5494 6.94172 16.8408C7.22118 17.1323 7.64848 17.227 8.02495 17.0811L14.1397 14.7106C14.4018 14.609 14.609 14.4018 14.7106 14.1397L17.0812 8.02488ZM11.0022 11.0022L14.3484 9.54084L13.0053 13.0053L9.5409 14.3484L11.0022 11.0022Z" fill="#939393"></path>
                                            </svg>
                                    </span>
                                    <p> {{ __('Language') }} <span>{{$tutor_course->lang}}</span></p>
                                </li>
                                <li>
                                    <span class="icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M18.3759 7.65153C19.7919 9.07948 20.0106 11.2474 19.0322 12.904L13.097 6.96876C14.7536 5.99037 16.9215 6.20913 18.3494 7.62504L18.3626 7.6384L18.3759 7.65153ZM20.5973 6.93577C22.4064 9.47424 22.1722 13.0212 19.8948 15.2986L19.5715 15.6219C19.327 15.8664 18.9305 15.8664 18.686 15.6219L16.8307 13.7665L15.2993 15.2978C14.8763 15.7209 14.1903 15.7209 13.7673 15.2978C13.3442 14.8748 13.3442 14.1888 13.7673 13.7658L15.2986 12.2345L13.7665 10.7024L12.2352 12.2337C11.8121 12.6568 11.1262 12.6568 10.7031 12.2337C10.2801 11.8106 10.2801 11.1247 10.7031 10.7016L12.2345 9.17032L10.3791 7.31498C10.1346 7.07045 10.1346 6.674 10.3791 6.42947L10.7024 6.10619C12.9798 3.82878 16.5267 3.59462 19.0652 5.40371L20.6616 3.80735C21.0846 3.38428 21.7706 3.38428 22.1936 3.80735C22.6167 4.23042 22.6167 4.91635 22.1936 5.33941L20.5973 6.93577ZM3.80811 22.1929C3.38504 21.7698 3.38504 21.0839 3.80811 20.6608L5.40447 19.0644C3.59538 16.526 3.82954 12.9791 6.10695 10.7016L6.43023 10.3784C6.67476 10.1338 7.07121 10.1338 7.31574 10.3784L15.6226 18.6852C15.8671 18.9298 15.8671 19.3262 15.6226 19.5707L15.2993 19.894C13.0219 22.1714 9.475 22.4056 6.93653 20.5965L5.34017 22.1929C4.91711 22.6159 4.23118 22.6159 3.80811 22.1929ZM12.9047 19.0315L6.96952 13.0963C5.98809 14.758 6.21126 16.9342 7.63902 18.362C9.06678 19.7897 11.243 20.0129 12.9047 19.0315Z" fill="#939393"></path>
                                            </svg>
                                    </span>
                                    <p>{{__('Master of')}} <span>{{ !empty($tutor->degree) ? $tutor->degree : '-'}}</span></p>
                                </li>
                            </ul>
                            <div class="about-tutor">
                               <div class="section-title">
                                   <h2>{{ __('About') }}</h2>
                               </div>
                                <p>{{ __('Lorem Ipsum is simply dummy text of the printing and typesetting industry.Lorem Ipsum is simply dummy text of the printing and typesetting industry.Lorem Ipsum is simply dummy text of the printing and typesetting industry.Lorem Ipsum is simply dummy text of the printing and typesetting industry.') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 col-xl-6 col-12">
                        <div class="tutor-right-side">
                            <div class="tutor-course-slider product-row">
                                @foreach ($courses as $course )
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
                                    <div class="product-card">
                                        <div class="product-card-inner">
                                            <div class="product-img">
                                                <a href="{{route('store.viewcourse',[$store->slug,\Illuminate\Support\Facades\Crypt::encrypt($course->id)])}}">
                                                    <img src="{{ asset('assets/themes/theme2/images/product-1.jpg') }}" alt="">
                                                </a>
                                                <div class="subtitle-top d-flex align-items-center justify-content-between">
                                                    <span class="badge">{{ !empty($course->category_id->name) ? $course->category_id->name : ''}}</span>
                                                    @if(Auth::guard('students')->check())
                                                        @if(sizeof($course->student_wl)>0)
                                                            <a href="#" class="wishlist-btn wishlist_btn add_to_wishlist" data-id="{{$course->id}}" data-toggle="tooltip" data-placement="bottom" title="{{__('Already in Wishlist')}}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.1335 2.95108C16.73 4.16664 16.9557 6.44579 15.6274 7.93897L8.99983 15.3894L2.37233 7.93977C1.04381 6.44646 1.26946 4.167 2.86616 2.95128C4.50032 1.70704 6.87275 2.10393 7.99225 3.80885L8.36782 4.38082C8.59267 4.72325 9.05847 4.82238 9.40821 4.60224C9.51777 4.53328 9.60294 4.44117 9.66134 4.33666L10.0076 3.80914C11.1268 2.10394 13.4993 1.70679 15.1335 2.95108ZM8.99998 2.653C7.31724 0.526225 4.15516 0.102335 1.94184 1.78754C-0.33726 3.52284 -0.659353 6.77651 1.23696 8.90805L8.4334 16.9972C8.7065 17.3041 9.18204 17.3362 9.49557 17.0688C9.53631 17.0341 9.57231 16.996 9.60351 16.9553L16.7628 8.90721C18.6589 6.77579 18.3367 3.52246 16.0579 1.78734C13.8446 0.102142 10.6825 0.526185 8.99998 2.653Z" fill="white"></path>
                                                                </svg>
                                                            </a>
                                                        @else
                                                            <a href="#" class="wishlist-btn add_to_wishlist" data-id="{{$course->id}}" data-toggle="tooltip" data-placement="bottom" title="{{__('Add to Wishlist')}}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.1335 2.95108C16.73 4.16664 16.9557 6.44579 15.6274 7.93897L8.99983 15.3894L2.37233 7.93977C1.04381 6.44646 1.26946 4.167 2.86616 2.95128C4.50032 1.70704 6.87275 2.10393 7.99225 3.80885L8.36782 4.38082C8.59267 4.72325 9.05847 4.82238 9.40821 4.60224C9.51777 4.53328 9.60294 4.44117 9.66134 4.33666L10.0076 3.80914C11.1268 2.10394 13.4993 1.70679 15.1335 2.95108ZM8.99998 2.653C7.31724 0.526225 4.15516 0.102335 1.94184 1.78754C-0.33726 3.52284 -0.659353 6.77651 1.23696 8.90805L8.4334 16.9972C8.7065 17.3041 9.18204 17.3362 9.49557 17.0688C9.53631 17.0341 9.57231 16.996 9.60351 16.9553L16.7628 8.90721C18.6589 6.77579 18.3367 3.52246 16.0579 1.78734C13.8446 0.102142 10.6825 0.526185 8.99998 2.653Z" fill="white"></path>
                                                                </svg>
                                                            </a>
                                                        @endif
                                                    @else
                                                        <a href="#" class="wishlist-btn add_to_wishlist" data-id="{{$course->id}}" data-toggle="tooltip" title="{{__('Add to Wishlist')}}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M15.1335 2.95108C16.73 4.16664 16.9557 6.44579 15.6274 7.93897L8.99983 15.3894L2.37233 7.93977C1.04381 6.44646 1.26946 4.167 2.86616 2.95128C4.50032 1.70704 6.87275 2.10393 7.99225 3.80885L8.36782 4.38082C8.59267 4.72325 9.05847 4.82238 9.40821 4.60224C9.51777 4.53328 9.60294 4.44117 9.66134 4.33666L10.0076 3.80914C11.1268 2.10394 13.4993 1.70679 15.1335 2.95108ZM8.99998 2.653C7.31724 0.526225 4.15516 0.102335 1.94184 1.78754C-0.33726 3.52284 -0.659353 6.77651 1.23696 8.90805L8.4334 16.9972C8.7065 17.3041 9.18204 17.3362 9.49557 17.0688C9.53631 17.0341 9.57231 16.996 9.60351 16.9553L16.7628 8.90721C18.6589 6.77579 18.3367 3.52246 16.0579 1.78734C13.8446 0.102142 10.6825 0.526185 8.99998 2.653Z" fill="white"></path>
                                                            </svg>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="product-content">
                                                <div class="product-content-top">
                                                    <div class="review-rating">
                                                        <span class="rating-num">{{$avg_rating}}</span>
                                                        <div class="review-star d-flex align-items-center">
                                                            @for($i =1;$i<=5;$i++)
                                                                @php
                                                                    $icon = 'fa-star';
                                                                    $color = '';
                                                                    $newVal1 = ($i-0.5);
                                                                    if($avg_rating < $i && $avg_rating >= $newVal1)
                                                                    {
                                                                        $icon = 'fa-star-half-alt';
                                                                    }
                                                                    if($avg_rating >= $newVal1)
                                                                    {
                                                                        $color = 'text-warning';
                                                                    }
                                                                @endphp
                                                                <i class="fas {{$icon .' '. $color}}"></i>
                                                            @endfor
                                                        </div>
                                                        <span>({{$user_count}})</span>
                                                    </div>
                                                    <h4>
                                                        <a href="{{route('store.viewcourse',[$store->slug,\Illuminate\Support\Facades\Crypt::encrypt($course->id)])}}">{{$course->title}}</a>
                                                    </h4>
                                                    <p>{{ __('Lorem Ipsum is simply dummy text of the printing and typesetting industry.') }}</p>
                                                </div>
                                                <div class="product-content-bottom">
                                                    <div class="course-detail">
                                                        <p>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                                viewBox="0 0 12 12" fill="none">
                                                                <g clip-path="url(#clip0_19_26)">
                                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                                        d="M5.89035 0.981725C3.17939 0.981725 0.981725 3.17939 0.981725 5.89035C0.981725 7.16364 1.46654 8.3237 2.26163 9.196C2.67034 8.3076 3.38394 7.61471 4.24934 7.22454C3.75002 6.77528 3.43604 6.12405 3.43604 5.39949C3.43604 4.04401 4.53487 2.94518 5.89035 2.94518C7.24583 2.94518 8.34466 4.04401 8.34466 5.39949C8.34466 6.11254 8.04058 6.75457 7.55502 7.20298C8.43552 7.56901 9.18412 8.23342 9.54281 9.16977C10.3238 8.30051 10.799 7.15092 10.799 5.89035C10.799 3.17939 8.60131 0.981725 5.89035 0.981725ZM9.69387 10.3882C10.9703 9.30774 11.7807 7.69368 11.7807 5.89035C11.7807 2.6372 9.1435 0 5.89035 0C2.6372 0 0 2.6372 0 5.89035C0 7.69366 0.810351 9.30769 2.08677 10.3882C2.12022 10.426 2.15982 10.459 2.20478 10.4855C3.21384 11.2959 4.49547 11.7807 5.89035 11.7807C7.28519 11.7807 8.5668 11.2959 9.57584 10.4856C9.62075 10.4591 9.66038 10.4261 9.69387 10.3882ZM8.74856 9.88145L8.66025 9.61652C8.29993 8.53556 7.14229 7.8538 5.89035 7.8538C4.6233 7.8538 3.49161 8.64647 3.0586 9.83723L3.04038 9.88735C3.84386 10.4613 4.82767 10.799 5.89035 10.799C6.95667 10.799 7.94357 10.459 8.74856 9.88145ZM5.89035 6.87208C6.70364 6.87208 7.36294 6.21278 7.36294 5.39949C7.36294 4.5862 6.70364 3.9269 5.89035 3.9269C5.07706 3.9269 4.41776 4.5862 4.41776 5.39949C4.41776 6.21278 5.07706 6.87208 5.89035 6.87208Z"
                                                                        fill="#8A94A6"></path>
                                                                </g>
                                                                <defs>
                                                                    <clipPath id="">
                                                                        <rect width="11.7807" height="11.7807" fill="white">
                                                                        </rect>
                                                                    </clipPath>
                                                                </defs>
                                                            </svg>{{$course->student_count->count()}} {{__('Students')}}
                                                        </p>
                                                        <p>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                                viewBox="0 0 12 12" fill="none">
                                                                <g clip-path="url(#clip0_19_28)">
                                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                                        d="M5.45116 0.161627C5.72754 0.0234357 6.05285 0.0234355 6.32924 0.161627L11.122 2.558C11.8456 2.91979 11.8456 3.95237 11.122 4.31416L10.4238 4.66324L11.122 5.01231C11.8456 5.3741 11.8456 6.40669 11.122 6.76848L10.4238 7.11755L11.122 7.46663C11.8456 7.82842 11.8456 8.861 11.122 9.22279L6.32924 11.6192C6.05286 11.7574 5.72754 11.7574 5.45116 11.6192L0.658407 9.22279C-0.0651723 8.861 -0.0651715 7.82842 0.658406 7.46663L1.35656 7.11755L0.658407 6.76848C-0.0651723 6.40669 -0.0651715 5.3741 0.658406 5.01231L1.35656 4.66324L0.658407 4.31416C-0.0651731 3.95237 -0.0651713 2.91979 0.658407 2.558L5.45116 0.161627ZM2.67882 4.22677C2.67563 4.22513 2.67243 4.22353 2.66921 4.22197L1.09745 3.43608L5.8902 1.03971L10.6829 3.43608L9.1111 4.22201C9.10793 4.22355 9.10479 4.22512 9.10166 4.22673L5.8902 5.83246L2.67882 4.22677ZM2.45416 5.21204L1.09745 5.8904L2.66915 6.67625L2.67888 6.68111L5.8902 8.28677L9.10163 6.68105L9.11112 6.67631L10.6829 5.8904L9.32623 5.21204L6.32924 6.71054C6.05286 6.84873 5.72754 6.84873 5.45116 6.71054L2.45416 5.21204ZM1.09745 8.34471L2.45416 7.66635L5.45116 9.16485C5.72754 9.30304 6.05286 9.30304 6.32924 9.16485L9.32623 7.66635L10.6829 8.34471L5.8902 10.7411L1.09745 8.34471Z"
                                                                        fill="#8A94A6"></path>
                                                                </g>
                                                                <defs>
                                                                    <clipPath id="">
                                                                        <rect width="11.7807" height="11.7807" fill="white">
                                                                        </rect>
                                                                    </clipPath>
                                                                </defs>
                                                            </svg> {{$course->chapter_count->count()}} {{__('Chapters')}}
                                                        </p>
                                                        <p>{{$course->level}}</p>
                                                    </div>
                                                    {{-- <a href="#" class="btn cart-btn">Get Started <svg viewBox="0 0 10 5">
                                                        <path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z">
                                                        </path>
                                                    </svg></a> --}}

                                                    <div class="price">
                                                        <ins> <span class="currency-type">{{ ($course->is_free == 'on')? __('Free') : Utility::priceFormat($course->price)}}</span></ins>
                                                    </div>
                                                    @if(Auth::guard('students')->check())
                                                        @if(in_array($course->id,Auth::guard('students')->user()->purchasedCourse()))
                                                            <a class="btn cart-btn" href="{{route('store.fullscreen',[$store->slug,Crypt::encrypt($course->id),''])}}">
                                                                {{__('Get Started')}}<svg viewBox="0 0 10 5">
                                                                    <path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z">
                                                                    </path>
                                                                </svg>
                                                            </a>
                                                        @else
                                                            <a class="add_to_cart btn cart-btn" data-id="{{$course->id}}">
                                                                @if($key !== false)
                                                                    <b id="cart-btn-{{$course->id}}">{{__('Already in Cart')}}</b>
                                                                @else
                                                                    <b id="cart-btn-{{$course->id}}">{{__('Add in Cart')}}</b>
                                                                @endif
                                                            </a>
                                                        @endif
                                                    @else
                                                        <a class="add_to_cart btn cart-btn" data-id="{{$course->id}}">
                                                            @if($key !== false)
                                                                <b id="cart-btn-{{$course->id}}">{{__('Already in Cart')}}</b>
                                                            @else
                                                                <b id="cart-btn-{{$course->id}}">{{__('Add in Cart')}}</b>
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
                    </div>
                </div>
            </div>
        </section>

        <section class="review-section padding-top padding-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-12">
                        <div class="review-left-side">
                            <div class="section-title">
                                <h2>{{ __('Reviews') }}</h2>
                            </div>
                            <p>{{ __('This is CS50x , Harvard University')}}'{{ __('s introduction to the intellectual enterprises of computer science and the art of programming for majors and non-majors alike, with or without prior programming experience.') }} </p>

                            <div class="review-progress">
                                <div class="review-rating">
                                    <h2 class="rating-num">{{$avg_rating}}</h2>
                                    <div class="review-counter">
                                        <div class="review-star d-flex align-items-center">
                                            @for($i =1;$i<=5;$i++)
                                                @php
                                                    $icon = 'fa-star';
                                                    $color = '';
                                                    $newVal1 = ($i-0.5);
                                                    if($avg_rating < $i && $avg_rating >= $newVal1)
                                                    {
                                                        $icon = 'fa-star-half-alt';
                                                    }
                                                    if($avg_rating >= $newVal1)
                                                    {
                                                        $color = 'text-warning';
                                                    }
                                                @endphp
                                                <i class="fas {{$icon .' '. $color}}"></i>
                                            @endfor
                                        </div>
                                        <span>({{$user_count}})</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 col-12">
                        <div class="review-right-side">
                            <div class="sub-title">
                                {{ __('Top Reviews') }}:
                            </div>
                            @if(!empty($tutor_ratings))
                                @foreach($tutor_ratings as $c_rating_key => $course_rating)
                                    <div class="review-card">
                                        <div class="review-card-inner">
                                            <div class="review-rating">
                                                <div class="review-star d-flex align-items-center">
                                                    <span class="star-img">
                                                        @for($i =0;$i<5;$i++)
                                                            <i class="fas fa-star {{($course_rating->ratting > $i  ? 'text-warning' : '')}}"></i>
                                                        @endfor
                                                    </span>
                                                </div>
                                            </div>
                                            <p>{{$course_rating->description}}</p>                                            
                                            <div class="abt-user">
                                                <p> <span><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" fill="none">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M7.5 1.25C4.04822 1.25 1.25 4.04822 1.25 7.5C1.25 9.12124 1.86729 10.5983 2.87967 11.709C3.40007 10.5778 4.30867 9.69557 5.41055 9.19879C4.77478 8.62676 4.375 7.79756 4.375 6.875C4.375 5.14911 5.77411 3.75 7.5 3.75C9.22589 3.75 10.625 5.14911 10.625 6.875C10.625 7.78291 10.2378 8.60039 9.61957 9.17133C10.7407 9.63739 11.6939 10.4834 12.1506 11.6756C13.145 10.5688 13.75 9.10504 13.75 7.5C13.75 4.04822 10.9518 1.25 7.5 1.25ZM12.3429 13.227C13.9682 11.8513 15 9.79613 15 7.5C15 3.35786 11.6421 0 7.5 0C3.35786 0 0 3.35786 0 7.5C0 9.79609 1.03179 11.8512 2.65702 13.2269C2.69961 13.2751 2.75003 13.3171 2.80728 13.3508C4.09208 14.3827 5.72394 15 7.5 15C9.27601 15 10.9078 14.3827 12.1926 13.3509C12.2498 13.3173 12.3003 13.2752 12.3429 13.227ZM11.1393 12.5817L11.0268 12.2444C10.568 10.8681 9.09406 10 7.5 10C5.8867 10 4.44576 11.0093 3.89442 12.5254L3.87122 12.5893C4.89427 13.32 6.14692 13.75 7.5 13.75C8.85771 13.75 10.1143 13.3171 11.1393 12.5817ZM7.5 8.75C8.53553 8.75 9.375 7.91053 9.375 6.875C9.375 5.83947 8.53553 5 7.5 5C6.46447 5 5.625 5.83947 5.625 6.875C5.625 7.91053 6.46447 8.75 7.5 8.75Z" fill="white"></path>
                                                </svg></span>  {{$course_rating->name}} </p>
                                            </div>                                            
                                        </div>
                                    </div>
                                @endforeach
                            @endif
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
                                    {{ Form::email('email',null,array('aria-label'=>'Enter your email address','placeholder'=>__('Enter Your Email Address'))) }}
                                    <button type="submit" class="btn btn-white">{{ $homepage_email_subscriber_button }} <svg viewBox="0 0 10 5">
                                        <path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z">
                                        </path>
                                    </svg></button>
                                </div>
                                <div class="checkbox-custom">
                                    <input type="checkbox" class="" id="newslettercheckbox">
                                    <label for="newslettercheckbox">{{ __('Lorem Ipsum is simply dummy text of the printing and typesetting industry.') }}</label>
                                </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>            
            </section> 
        @endif       
    </div>
@endsection
