@extends('storefront.theme1.user.user')
@section('page-title')
    {{__('Wishlist')}} - {{($store->tagline) ?  $store->tagline : config('APP_NAME', ucfirst($store->name))}}
@endsection
@push('css-page')
@endpush
@section('head-title')
    {{__('Your Wishlist')}}
@endsection

@section('content')
    
    
    <section class="common-banner-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-6">
                    <div class="common-banner-content">
                        <a href="{{route('store.slug',$store->slug)}}" class="back-btn">
                            <span class="svg-ic">
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="5" viewBox="0 0 11 5" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M10.5791 2.28954C10.5791 2.53299 10.3818 2.73035 10.1383 2.73035L1.52698 2.73048L2.5628 3.73673C2.73742 3.90636 2.74146 4.18544 2.57183 4.36005C2.40219 4.53467 2.12312 4.53871 1.9485 4.36908L0.133482 2.60587C0.0480403 2.52287 -0.000171489 2.40882 -0.000171488 2.2897C-0.000171486 2.17058 0.0480403 2.05653 0.133482 1.97353L1.9485 0.210321C2.12312 0.0406877 2.40219 0.044729 2.57183 0.219347C2.74146 0.393966 2.73742 0.673036 2.5628 0.842669L1.52702 1.84888L10.1383 1.84875C10.3817 1.84874 10.5791 2.04609 10.5791 2.28954Z" fill="white"></path>
                                </svg>
                            </span>
                            {{__('Back to Category')}}
                        </a>
                        <div class="section-title">
                            <h2>{{__('Wishlist')}}</h2>
                        </div>
                        <p>{{__('Lorem Ipsum is simply dummy text of the printing and typesetting industry.')}}</p>
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
    <section class="padding-top padding-bottom wishlist-section">
        <div class="container">
            <div class="row row-gap">
                @php
                    $count_wishlist = App\Models\Wishlist::wishCount();
                @endphp
                @if(!empty($count_wishlist) && $count_wishlist > 0)
                    @foreach($courses as $key => $course)
                        @if(sizeof($course->student_wl) > 0)
                            @foreach($course->student_wl as $student_wl)
                                <div class=" col-lg-4 col-md-6 col-sm-6 col-12 course-widget">
                                    <div class="course-widget-inner">
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
                                        <div class="course-media">
                                            <a href="product.html">
                                                {{-- <img src="{{ asset('assets/imgs/courses.png') }}" alt=""> --}}
                                                @if(!empty($course->thumbnail))
                                                    <img alt="card" src="{{asset(Storage::url('uploads/thumbnail/'.$course->thumbnail))}}" class="img-fluid">
                                                @else
                                                    <img src="{{asset('assets/img/card-img.svg')}}" alt="card" class="img-fluid">
                                                @endif
                                            </a>
                                        </div>
                                        <div class="course-caption">
                                            <div class="course-caption-top d-flex align-items-center">
                                                <span class="badge">{{!empty($course->category_id)?$course->category_id->name:''}}</span>
                                                <span class="badge sale">{{ __('Sale') }}</span>
                                                @if(Auth::guard('students')->check())
                                                    @if(sizeof($course->student_wl)>0)
                                                   
                                                        <a href="javascript:void(0)" class="wishlist-btn remove_from_wishlist" 
                                                            data-confirm="{{__('Are You Sure?').' | '.__('This action can not be undone. Do you want to continue?')}}" 
                                                            data-confirm-yes="document.getElementById('delete-product-cart-{{$course->id}}').submit();">                                                           
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="14"
                                                                viewBox="0 0 17 14" fill="none">
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                    d="M9.18991 3.10164C8.89678 3.37992 8.43395 3.37992 8.14082 3.10164L7.61627 2.60366C7.00231 2.0208 6.17289 1.66491 5.25627 1.66491C3.37348 1.66491 1.84718 3.17483 1.84718 5.03741C1.84718 6.82306 2.82429 8.29753 4.23488 9.50902C5.64667 10.7215 7.33461 11.5257 8.34313 11.9361C8.554 12.0219 8.77673 12.0219 8.9876 11.9361C9.99612 11.5257 11.6841 10.7215 13.0959 9.50901C14.5064 8.29753 15.4835 6.82305 15.4835 5.03741C15.4835 3.17483 13.9572 1.66491 12.0745 1.66491C11.1578 1.66491 10.3284 2.0208 9.71446 2.60366L9.18991 3.10164ZM8.66537 1.52219C7.7806 0.682237 6.57937 0.166016 5.25627 0.166016C2.53669 0.166016 0.332031 2.34701 0.332031 5.03741C0.332031 9.81007 5.61259 12.4457 7.76672 13.3223C8.34685 13.5584 8.98388 13.5584 9.56401 13.3223C11.7181 12.4457 16.9987 9.81006 16.9987 5.03741C16.9987 2.34701 14.794 0.166016 12.0745 0.166016C10.7514 0.166016 9.55013 0.682237 8.66537 1.52219Z"
                                                                    fill="white"></path>
                                                            </svg>
                                                        </a> 
                                                        {!! Form::open(['method' => 'POST', 'route' => ['student.removeFromWishlist',[$slug,$course->id]],'id'=>'delete-product-cart-'.$course->id]) !!}
                                                        {!! Form::close() !!}
                                                    @else
                                                        <a href="#" class="wishlist-btn add_to_wishlist" data-id="{{$course->id}}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="14"
                                                                viewBox="0 0 17 14" fill="none">
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                    d="M9.18991 3.10164C8.89678 3.37992 8.43395 3.37992 8.14082 3.10164L7.61627 2.60366C7.00231 2.0208 6.17289 1.66491 5.25627 1.66491C3.37348 1.66491 1.84718 3.17483 1.84718 5.03741C1.84718 6.82306 2.82429 8.29753 4.23488 9.50902C5.64667 10.7215 7.33461 11.5257 8.34313 11.9361C8.554 12.0219 8.77673 12.0219 8.9876 11.9361C9.99612 11.5257 11.6841 10.7215 13.0959 9.50901C14.5064 8.29753 15.4835 6.82305 15.4835 5.03741C15.4835 3.17483 13.9572 1.66491 12.0745 1.66491C11.1578 1.66491 10.3284 2.0208 9.71446 2.60366L9.18991 3.10164ZM8.66537 1.52219C7.7806 0.682237 6.57937 0.166016 5.25627 0.166016C2.53669 0.166016 0.332031 2.34701 0.332031 5.03741C0.332031 9.81007 5.61259 12.4457 7.76672 13.3223C8.34685 13.5584 8.98388 13.5584 9.56401 13.3223C11.7181 12.4457 16.9987 9.81006 16.9987 5.03741C16.9987 2.34701 14.794 0.166016 12.0745 0.166016C10.7514 0.166016 9.55013 0.682237 8.66537 1.52219Z"
                                                                    fill="white"></path>
                                                            </svg>
                                                        </a>
                                                    @endif
                                                @else
                                                    <a href="#" class="wishlist-btn add_to_wishlist" data-id="{{$course->id}}">
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
                                                        </svg> {{$course->chapter_count->count()}} &nbsp; <span>{{__('Chapters')}}</span></p>
                                                    <p>{{$course->level}}</p>
                                                </div>
                                            </div>
                                            <div class="price-addtocart d-flex align-items-center justify-content-between">
                                             
                                                @if(Auth::guard('students')->check())
                                                    @if(in_array($course->id,Auth::guard('students')->user()->purchasedCourse()))
                                                        <div class="price">
                                                        </div>
                                                        <div class="add-cart">
                                                            <a class="btn" href="{{route('store.fullscreen',[$store->slug,Crypt::encrypt($course->id),''])}}">
                                                                {{__('Start Watching')}}
                                                            </a>
                                                        </div>
                                                    @else
                                                        <div class="price">
                                                            @if($course->has_discount == 'on')
                                                                <ins> {{ ($course->is_free == 'on')? __('Free') : \App\Models\Utility::priceFormat($course->price)}}</ins>
                                                            @else
                                                                <ins> {{ ($course->is_free == 'on')? __('Free') : \App\Models\Utility::priceFormat($course->price)}}</ins>
                                                            @endif
                                                        </div>
                                                        <div class="add-cart">                                                        
                                                            @if($key !== false)
                                                                <a id="cart-btn-{{$course->id}}" class="btn">{{__('Already in Cart')}}</a>
                                                            @else
                                                                <a id="cart-btn-{{$course->id}}" class="btn add_to_cart" data-id="{{$course->id}}">{{__('Add in Cart')}}</a>
                                                            @endif
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="price">
                                                        @if($course->has_discount == 'on')
                                                            <ins>{{ ($course->is_free == 'on')? __('Free') : \App\Models\Utility::priceFormat($course->price)}}</ins>
                                                        @else
                                                            <ins>{{ ($course->is_free == 'on')? __('Free') : \App\Models\Utility::priceFormat($course->price)}}</ins>
                                                        @endif
                                                    </div>
                                                    <div class="add-cart">
                                                        @if($key !== false)
                                                            <a id="cart-btn-{{$course->id}}" class="btn">{{__('Already in Cart')}}</a>
                                                        @else
                                                            <a id="cart-btn-{{$course->id}}" class="btn add_to_cart" data-id="{{$course->id}}">{{__('Add in Cart')}}</a>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    @endforeach
                @else
                    <div class="col-12 justify-content-center">
                        <div class="empty-detail-wrap">
                            <div class="empty-image">
                                <img alt="Image placeholder" src="{{asset('assets/img/online-shopping.svg')}}">
                            </div>
                            <div class="empty-content">
                                <div class="section-title">
                                    <h2>{{__('Your wishlist is empty')}}.</h6>
                                </div>
                                <h6>
                                    {{__('Your wishlist is currently empty.We have some great courses that you might want to learn')}}.
                                </h6>
                                    <a href="{{route('store.slug',$store->slug)}}" class="btn">
                                    {{__('Return to shop')}}
                                        <svg viewBox="0 0 10 5">
                                            <path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z">
                                            </path>
                                        </svg>
                                    </a>
                            </div>
                        </div>
                    </div>  
                @endif              
            
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
@push('script-page')
    {{--CART--}}
    <script>
        $(document).on('click', '.add_to_cart', function (e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            /* console.log(id);
             return false*/
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

    <script>
        $(document).on('click', '.add_to_wishlist', function (e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            var id_2 = $(this).attr('data-id-2');
            var _url;
            if (id_2 == null) {
                _url = '{{route('student.addToWishlist',[$store->slug, '__course_id'])}}'.replace('__course_id', id);
            } else {
                _url = '{{route('student.addToWishlist', [$store->slug,'__course_id'])}}'.replace('__course_id', id_2);
            }
            $.ajax({
                type: "POST",
                url: _url,
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                context: this,
                success: function (response) {
                    if (response.status == "Success") {
                        show_toastr('Success', response.success, 'success');
                        if (id_2 == null) {
                            $('.fygyfg_' + id).children().attr('src', '{{asset('assets/img/wishlist.svg')}}');
                        } else {
                            $('.fygyfg_' + id_2).children().attr('src', '{{asset('assets/img/wishlist.svg')}}');
                        }
                        // console.log(response.item_count);
                        $('.wishlist_item_count').html(response.item_count);
                        // $(this).toggleClass("wishlist_btn");

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
