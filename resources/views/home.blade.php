@extends('layouts.admin')
@section('page-title')
    {{ __('Dashboard') }}
@endsection

@section('title')
    {{ __('Dashboard') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Home') }}</li>
@endsection

@php
    // $logo = asset(Storage::url('uploads/logo/'));
    $logo=\App\Models\Utility::get_file('uploads/logo/');
    $company_logo = Utility::getValByName('company_logo');
    $profile=\App\Models\Utility::get_file('uploads/profile/');
    $logo1=\App\Models\Utility::get_file('uploads/is_cover_image/');
@endphp

@section('content')
    <div class="page-content">
        <!-- Page title -->
        @if (\Auth::user()->type == 'Owner')
            <div class="row">
                <!-- [ sample-page ] start -->
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-xxl-5">
                            <div class="card">
                                <div class="card-body stats welcome-card">
                                    <div class="row align-items-center mb-4">
                                        <div class="col-xxl-12">
                                            <h3 class="mb-1" id="greetings"></h3>                                            
                                            <h4 class="f-w-400">
                                                <img src="{{ asset(Storage::url('uploads/profile/' . (!empty(Auth::user()->avatar) ? Auth::user()->avatar : 'avatar.png'))) }}"
                                                    alt="user-image" class="wid-35 me-2 img-thumbnail rounded-circle">{{ __(Auth::user()->name) }}
                                            </h4>
                                            <p>{{ __('Have a nice day! Did you know that you can quickly add your favorite course or category to the store?') }}</p>
                                            <div class="dropdown quick-add-btn">
                                                <a class="btn btn-primary btn-q-add dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false"> <i class="ti ti-plus drp-icon"></i>
                                                    <span class="ms-2 me-2">{{ __('Quick add') }}</span>
                                                </a>
                                                <div class="dropdown-menu">
                                                    <a href="{{ route('course.create') }}" class="dropdown-item"><span>{{ __('Add new Course') }}</span></a>
                                                    <a href="#" data-size="md" data-url="{{ route('category.create') }}" data-ajax-popup="true" data-title="{{ __('Create New Category') }}"
                                                        class="dropdown-item" data-bs-placement="top"><span>{{ __('Add new Category') }}</span></a>
                                                    <a href="#" data-size="md" data-url="{{ route('subcategory.create') }}" data-ajax-popup="true" data-title="{{ __('Create New Subcategory') }}" class="dropdown-item" data-bs-placement="top"><span>{{ __('Add new Subcategory') }}</span></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card min-h-390 overflow-auto">
                                <div class="card-header d-flex justify-content-between">
                                    <h5>{{ __('Top Course') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="sort" data-sort="name">{{ __('Course') }}
                                                    </th>
                                                    <th scope="col" class="sort text-right" data-sort="completion">
                                                        {{ __('Price') }}</th>
                                                </tr>
                                            </thead>
                                            @if (count($products) > 0 && !empty($item_id) && !empty($products))
                                                <tbody class="list">
                                                    @foreach ($products as $product)
                                                        @foreach ($item_id as $k => $item)
                                                            @if ($product->id == $item)
                                                                <tr>
                                                                    <th scope="row">
                                                                        <div class="media align-items-center gap-3">
                                                                            <div>
                                                                                @if (!empty($product->thumbnail))
                                                                                    <img alt="Image placeholder"
                                                                                        src="{{ asset(Storage::url('uploads/thumbnail/' . $product->thumbnail)) }}"
                                                                                        width="80px">
                                                                                @else
                                                                                    <img alt="Image placeholder"
                                                                                        src="{{ asset(Storage::url('uploads/thumbnail/default.jpg')) }}"
                                                                                        class="" style="width: 80px;">
                                                                                @endif
                                                                            </div>
                                                                            <div class="media-body ml-4">
                                                                                <span
                                                                                    class="mb-0 h6 text-sm">{{ $product->title }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </th>
                                                                    <td class="text-right">
                                                                        <div>
                                                                            <span
                                                                                class="completion mr-2 text-dark text-right ">{{ Utility::priceFormat($product->price) }}</span>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                </tbody>
                                            @else
                                                <tbody>
                                                    <tr>
                                                        <td colspan="7">
                                                            <div class="text-center">
                                                                <i class="fas fa-folder-open text-primary"
                                                                    style="font-size: 48px;"></i>
                                                                <h2>{{ __('Opps') }}...</h2>
                                                                <h6>{{ __('No data Found') }}. </h6>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-7">
                            <div class="row">
                                <div class="col-lg-3 col-6">
                                    <div class="card">
                                        <div class="card-body stats">
                                            <div class="theme-avtar bg-primary qrcode">
                                                {!! \QrCode::generate($store_id['store_url']) !!}
                                            </div>
                                            <h6 class="mb-3 mt-4 ">{{ $store_id->name }}</h6>
                                            <a href="#" class="btn btn-primary btn-sm text-sm cp_link mb-0" data-link="{{ $store_id['store_url'] }}" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="{{ __('Click to copy link') }}">{{ __('Store Link') }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <div class="card">
                                        <div class="card-body stats">
                                            <div class="theme-avtar bg-info">
                                                <i class="fas fa-cube"></i>
                                            </div>
                                            <h6 class="mb-3 mt-4 ">{{ __('Total Course') }}</h6>
                                            <h4 class="mb-0">{{ $newproduct }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <div class="card">
                                        <div class="card-body stats">
                                            <div class="theme-avtar bg-warning">
                                                <i class="fas fa-cart-plus"></i>
                                            </div>
                                            <h6 class="mb-3 mt-4 ">{{ __('Total Sales') }}</h6>
                                            <h4 class="mb-0">{{ Utility::priceFormat($totle_sale) }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <div class="card">
                                        <div class="card-body stats">
                                            <div class="theme-avtar bg-danger">
                                                <i class="fas fa-shopping-bag"></i>
                                            </div>
                                            <h6 class="mb-3 mt-4 ">{{ __('Total Orders') }}</h6>
                                            <h4 class="mb-0">{{ $totle_order }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card min-h-390 overflow-auto">
                                <div class="card-header">
                                    <h5>{{ __('Orders') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div id="apex-dashborad" data-color="primary" data-height="230"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <h5>{{ __('Recent Orders') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">{{ __('Orders') }}</th>
                                                    <th scope="col" class="sort">{{ __('Date') }}</th>
                                                    <th scope="col" class="sort">{{ __('Name') }}</th>
                                                    <th scope="col" class="sort">{{ __('Value') }}</th>
                                                    <th scope="col" class="sort">{{ __('Payment Type') }}</th>
                                                    <th scope="col" class="text-center">{{ __('Status') }}</th>
                                                    <th scope="col" class="text-end">{{ __('Action') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (!empty($new_orders) && count($new_orders) > 0)
                                                    @foreach ($new_orders as $order)
                                                        @if ($order->status != 'Cancel Order')
                                                            <tr>
                                                                <td>
                                                                    <div class="d-flex align-items-center">
                                                                        <a href="{{ route('orders.show', $order->id) }}"
                                                                            class="btn btn-outline-primary btn-sm text-sm order-badge">
                                                                            <span
                                                                                class="btn-inner--text">{{ $order->order_id }}</span>
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <h6 class="m-0">
                                                                        {{ Utility::dateFormat($order->created_at) }}
                                                                    </h6>
                                                                </td>
                                                                <td>
                                                                    <h6 class="m-0">{{ $order->name }}</h6>
                                                                </td>
                                                                <td>
                                                                    <h6 class="m-0">
                                                                        {{ Utility::priceFormat($order->price) }} <h6>
                                                                </td>
                                                                <td>
                                                                    <h6 class="m-0">{{ $order->payment_type }}<h6>
                                                                </td>
                                                                <td>
                                                                    <div class="actions ml-3">
                                                                        <div class="d-flex row justify-content-center">
                                                                            <button type="button"
                                                                                class="btn btn-sm {{ 
                                                                                $order->payment_status == 'success' || $order->payment_status == 'succeeded' || $order->payment_status == 'approved' ? 'btn-soft-success' : 'btn-soft-info' }} btn-icon rounded-pill">
                                                                                <span class="btn-inner--icon">
                                                                                    @if ($order->payment_status == 'pendding')
                                                                                        <i class="fas fa-check"></i>
                                                                                    @else
                                                                                        <i class="fa fa-check-double"></i>
                                                                                    @endif
                                                                                </span>
                                                                                @if ($order->payment_status == 'pendding')
                                                                                    <span class="btn-inner--text">
                                                                                        {{ __('Pending') }}:
                                                                                        {{ \App\Models\Utility::dateFormat($order->created_at) }}
                                                                                    </span>
                                                                                @else
                                                                                    <span class="btn-inner--text">
                                                                                        {{ __('Delivered') }}:
                                                                                        {{ \App\Models\Utility::dateFormat($order->updated_at) }}
                                                                                    </span>
                                                                                @endif
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="actions ml-3">
                                                                        <div
                                                                            class="d-flex align-items-center justify-content-end">
                                                                            {{-- <button type="button" class="btn btn-sm {{ $order->status == 'pending' ? 'btn-soft-info' : 'btn-soft-success' }} btn-icon rounded-pill">
                                                                                <span class="btn-inner--icon">
                                                                                    @if ($order->status == 'pending')
                                                                                        <i class="fas fa-check soft-info"></i>
                                                                                    @else
                                                                                        <i class="fa fa-check-double soft-success"></i>
                                                                                    @endif
                                                                                </span>
                                                                                @if ($order->payment_status == 'approved' && $order->status == 'pending')
                                                                                    <span class="btn-inner--text">
                                                                                        {{ __('Paid') }}:
                                                                                        {{ \App\Models\Utility::dateFormat($order->created_at) }}
                                                                                    @else
                                                                                    </span><span class="btn-inner--text">
                                                                                        {{ __('Delivered') }}:
                                                                                        {{ \App\Models\Utility::dateFormat($order->updated_at) }}
                                                                                    </span>
                                                                                @endif

                                                                            </button> --}}
                                                                            <div class="action-btn bg-warning ms-2">
                                                                                <a href="{{ route('orders.show', $order->id) }}"
                                                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                                                    data-bs-toggle="tooltip"
                                                                                    data-bs-placement="top"
                                                                                    title="{{ __('Details') }}"><i
                                                                                        class="ti ti-eye text-white"></i></a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- [ sample-page ] end -->
            </div>
        @else
            <div class="row">
                <!-- [ sample-page ] start -->
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-xxl-6">
                            <div class="row">
                                <div class="col-lg-4 col-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-primary">
                                                <i class="fas fa-cube"></i>
                                            </div>
                                            <h6 class="mb-3 mt-3">{{ __('Total Store') }}</h6>
                                            <h4 class="mb-0">{{ $user->total_user }}</h4>
                                            <div class="col-auto">
                                                <h6 class="text-muted mb-1 mt-2">{{ __('Paid Store') }}</h6>
                                                <span
                                                    class="h6 font-weight-bold mb-0 ">{{ $user['total_paid_user'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-warning">
                                                <i class="fas fa-cart-plus"></i>
                                            </div>
                                            <h6 class="mb-3 mt-3">{{ __('Total Orders') }}</h6>
                                            <h4 class="mb-0">{{ $user->total_orders }}</h4>
                                            <div class="col-auto">
                                                <h6 class="text-muted mb-1 mt-2">{{ __('Total Order Amount') }}</h6>
                                                <span
                                                    class="h6 font-weight-bold mb-0 ">{{ env('CURRENCY_SYMBOL') . $user['total_orders_price'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-danger">
                                                <i class="fas fa-shopping-bag"></i>
                                            </div>
                                            <h6 class="mb-3 mt-3">{{ __('Total Plans') }}</h6>
                                            <h4 class="mb-0">{{ $user['total_plan'] }}</h4>
                                            <div class="col-auto">
                                                <h6 class="text-muted mb-1 mt-2">{{ __('Most Purchase Plan') }}</h6>
                                                <span
                                                    class="h6 font-weight-bold mb-0 ">{{ !empty($user['most_purchese_plan']) ? $user['most_purchese_plan'] : '-' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>{{ __('Recent Orders') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div id="plan_order" data-color="primary" data-height="230"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- [ sample-page ] end -->
            </div>
        @endif
    </div>
@endsection

@push('script-page')
    <script>
        var today = new Date()
        var curHr = today.getHours()

        if (curHr < 12) {
            document.getElementById("greetings").innerHTML = "{{ __('Good Morning,') }}";
        } else if (curHr < 18) {
            document.getElementById("greetings").innerHTML = "{{ __('Good Afternoon,') }}";
        } else {
            document.getElementById("greetings").innerHTML = "{{ __('Good Evening,') }}";
        }
    </script>

    @if (\Auth::user()->type == 'Owner')
        <script>
            $(document).ready(function() {
                $('.cp_link').on('click', function() {
                    var value = $(this).attr('data-link');
                    var $temp = $("<input>");
                    $("body").append($temp);
                    $temp.val(value).select();
                    document.execCommand("copy");
                    $temp.remove();
                    show_toastr('Success', '{{ __('Link copied') }}', 'success')
                });
            });

            (function() {
                var options = {
                    chart: {
                        height: 250,
                        type: 'area',
                        toolbar: {
                            show: false,
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        width: 2,
                        curve: 'smooth'
                    },


                    series: [{
                        name: "{{ __('Order') }}",
                        data: {!! json_encode($chartData['data']) !!}
                    }],

                    xaxis: {
                        axisBorder: {
                            show: !1
                        },
                        type: "MMM",
                        categories: {!! json_encode($chartData['label']) !!},
                        title: {
                            text: 'Days'
                        }

                    },
                    colors: ['#e83e8c'],

                    grid: {
                        strokeDashArray: 4,
                    },
                    legend: {
                        show: false,
                    },
                    // markers: {
                    //     size: 4,
                    //     colors: ['#FFA21D'],
                    //     opacity: 0.9,
                    //     strokeWidth: 2,
                    //     hover: {
                    //         size: 7,
                    //     }
                    // },
                    yaxis: {
                        tickAmount: 3,
                        title: {
                            text: 'Amount'
                        }
                    }
                };
                var chart = new ApexCharts(document.querySelector("#apex-dashborad"), options);
                chart.render();
            })();
            var scrollSpy = new bootstrap.ScrollSpy(document.body, {
                target: '#useradd-sidenav',
                offset: 300
            })
        </script>
    @else
        <script>
            (function() {
                var options = {
                    chart: {
                        height: 250,
                        type: 'area',
                        toolbar: {
                            show: false,
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        width: 2,
                        curve: 'smooth'
                    },


                    series: [{
                        name: "Order",
                        data: {!! json_encode($chartData['data']) !!}
                        // data: [10,20,30,40,50,60,70,40,20,50,60,20,50,70]
                    }],

                    xaxis: {
                        axisBorder: {
                            show: !1
                        },
                        type: "MMM",
                        categories: {!! json_encode($chartData['label']) !!},
                        title: {
                            text: 'Days'
                        }

                    },
                    colors: ['#e83e8c'],

                    grid: {
                        strokeDashArray: 4,
                    },
                    legend: {
                        show: false,
                    },
                    // markers: {
                    //     size: 4,
                    //     colors: ['#FFA21D'],
                    //     opacity: 0.9,
                    //     strokeWidth: 2,
                    //     hover: {
                    //         size: 7,
                    //     }
                    // },
                    yaxis: {
                        tickAmount: 3,
                        title: {
                            text: 'Amountsidebar'
                        }

                    }
                };
                var chart = new ApexCharts(document.querySelector("#plan_order"), options);
                chart.render();
            })();
        </script>
    @endif
@endpush
