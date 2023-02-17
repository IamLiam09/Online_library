@php
// $logo = asset(Storage::url('uploads/logo/'));
$logo = \App\Models\Utility::get_file('uploads/logo/');
// $company_logo = Utility::getValByName('company_logo');
// $company_logo = \App\Models\Utility::GetLogo();

$profile=\App\Models\Utility::get_file('uploads/profile/');

@endphp

@if (isset($setting['cust_theme_bg']) && $setting['cust_theme_bg'] == 'on')
    <header class="dash-header transprent-bg">
@else
    <header class="dash-header">
@endif
    <div class="header-wrapper">
        <div class="me-auto dash-mob-drp">
            <ul class="list-unstyled">
                <li class="dash-h-item mob-hamburger">
                    <a href="#!" class="dash-head-link" id="mobile-collapse">
                        <div class="hamburger hamburger--arrowturn">
                            <div class="hamburger-box">
                                <div class="hamburger-inner"></div>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="dropdown dash-h-item drp-company">
                    <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        {{-- <span class="theme-avtar">c</span> --}}
                        <span class="theme-avtar"><img alt="Image placeholder"  style="width:30px;"
                                src="{{ !empty($users->avatar) ? $profile .  $users->avatar : $profile . '/avatar.png' }}"></span>
                        <span class="hide-mob ms-2">{{__('Hi')}}, {{\Auth::user()->name}}!</span>
                        <i class="ti ti-chevron-down drp-arrow nocolor hide-mob"></i>
                    </a>

                    <div class="dropdown-menu dash-h-dropdown">

                        @foreach(Auth::user()->stores as $store)
                            @if($store->is_active)
                                <a href="@if(Auth::user()->current_store == $store->id)#@else {{ route('change_store',$store->id) }} @endif" title="{{ $store->name }}" class="dropdown-item notify-item">
                                    @if(Auth::user()->current_store == $store->id)
                                        <i class="ti ti-checks text-success"></i>
                                    @endif
                                    <span>{{ $store->name }}</span>
                                </a>
                            @else
                                <a href="#" class="dropdown-item notify-item" title="{{__('Locked')}}">
                                    <i class="fas fa-lock"></i>
                                    <span>{{ $store->name }}</span>
                                    @if(isset($store->pivot->permission))
                                        @if($store->pivot->permission =='Owner')
                                            <span class="badge badge-primary">{{__($store->pivot->permission)}}</span>
                                        @else
                                            <span class="badge badge-secondary">{{__('Shared')}}</span>
                                        @endif
                                    @endif
                                </a>
                            @endif
                            <div class="dropdown-divider"></div>
                        @endforeach
                        
                        @auth('web')
                            @if(Auth::user()->type == 'Owner')
                                <a href="#" data-size="md" data-url="{{ route('store-resource.create') }}" data-ajax-popup="true" data-title="{{__('Create New Store')}}" class="dropdown-item notify-item">
                                    <i class="ti ti-circle-plus"></i><span>{{ __('Create New Store')}}</span>
                                </a>
                                <hr class="dropdown-divider" />
                            @endif                            
                        @endauth
                        
                        <a href="{{ route('profile') }}" class="dropdown-item">
                            <i class="ti ti-user"></i>
                            <span>{{ __('My profile') }}</span>
                        </a>

                        <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
                            <i class="ti ti-power"></i>
                            <span>{{ __('Logout') }}</span>
                        </a>
                        <form id="frm-logout" action="{{ route('logout') }}" method="POST" class="d-none">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </li>                
            </ul>
        </div>
        <div class="ms-auto">
            <ul class="list-unstyled">              
                <li class="dropdown dash-h-item drp-language">
                    <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="ti ti-world nocolor"></i>
                        <span class="drp-text hide-mob">{{Str::upper($currantLang)}}</span>
                        <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown dropdown-menu-end">
                        @foreach ($languages as $language)
                            <a href="{{route('change.language',$language)}}" class="dropdown-item @if ($language == $currantLang) active-language text-primary @endif">
                                <span> {{Str::upper($language)}}</span>
                            </a>
                        @endforeach
                        {{-- @if(Auth::user()->type == 'super admin') --}}
                            <a href="{{route('manage.language',!empty($currantLang) ? $currantLang :'en')}}" class="dropdown-item border-top py-1 text-primary">
                                <span class="small">{{ __('Manage Languages') }}</span>
                            </a> 
                        {{-- @endif --}}
                    </div>
                </li>               
            </ul>
        </div>
    </div>
</header>


