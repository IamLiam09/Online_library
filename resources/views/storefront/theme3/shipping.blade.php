@extends('layouts.theme3.storefront')
@section('page-title')
    {{__('Shipping')}}
@endsection
@push('script-page')
@endpush
@push('css-page')
    <link rel="stylesheet" href="{{ asset('libs/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/intlTelInput/intlTelInput.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/intlTelInput/intlTelInput.min.css') }}">
    .form-control:focus {
    background-color: #fff;
    border-color: rgb(39 52 68) !important;
    box-shadow: none;
    }
@endpush
@section('content')
    <div class="container">
        {{Form::model($cust_details,array('route' => array('store.customer',$store->slug), 'method' => 'POST')) }}
        <div class="row row-grid">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <!-- General -->
                        <div class="actions-toolbar py-2 mb-4">
                            <h5 class="mb-1">{{__('Billing information')}}</h5>
                            <p class="text-sm text-muted mb-0">{{__('Fill the form below so we can send you the orders invoice')}}.</p>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{Form::label('name',__('First Name'),array("class"=>"form-control-label")) }}
                                    {{Form::text('name',old('name'),array('class'=>'form-control','placeholder'=>__('Enter Your First Name'),'required'=>'required'))}}

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{Form::label('last_name',__('Last Name'),array("class"=>"form-control-label")) }}
                                    {{Form::text('last_name',old('last_name'),array('class'=>'form-control','placeholder'=>__('Enter Your Last Name'),'required'=>'required'))}}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{Form::label('phone',__('Phone'),array("class"=>"form-control-label")) }}
                                    {{Form::text('phone',old('phone'),array('class'=>'form-control','placeholder'=>'(99) 12345 67890','required'=>'required'))}}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{Form::label('email',__('Email'),array("class"=>"form-control-label")) }}
                                    {{Form::email('email',old('email'),array('class'=>'form-control','placeholder'=>__('Enter Your Email Address'),'required'=>'required'))}}
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{Form::label('billingaddress',__('Address'),array("class"=>"form-control-label")) }}
                                    {{Form::text('billing_address',old('billing_address'),array('class'=>'form-control','placeholder'=>__('Billing Address'),'required'=>'required'))}}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{Form::label('billing_country',__('Country'),array("class"=>"form-control-label")) }}
                                    {{Form::text('billing_country',old('billing_country'),array('class'=>'form-control','placeholder'=>__('Billing Country'),'required'=>'required'))}}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{Form::label('billing_city',__('City'),array("class"=>"form-control-label")) }}
                                    {{Form::text('billing_city',old('billing_city'),array('class'=>'form-control','placeholder'=>__('Billing City'),'required'=>'required'))}}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{Form::label('billing_postalcode',__('Postal Code'),array("class"=>"form-control-label")) }}
                                    {{Form::text('billing_postalcode',old('billing_postalcode'),array('class'=>'form-control','placeholder'=>__('Billing Postal Code')))}}
                                </div>
                            </div>
                            @if($store->enable_shipping == "on")
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{Form::label('location_id',__('Location'),array("class"=>"form-control-label")) }}
                                        {{ Form::select('location_id', $locations, null,array('class' => 'form-control change_location','required'=>'required')) }}
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="actions-toolbar py-2 mb-1">
                            <a class="btn btn-xs small btn-dark text-white rounded-pill mr-auto float-right p-1 px-4" onclick="billing_data()" id="billing_data" data-toggle="tooltip" data-placement="top" title="Same As Billing Address">
                                {{__('Copy Address')}}
                            </a>
                            <h5 class="mb-1">{{__('Shipping information')}}</h5>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{Form::label('shipping_address',__('Address'),array("class"=>"form-control-label")) }}
                                    {{Form::text('shipping_address',old('shipping_address'),array('class'=>'form-control','placeholder'=>__('Shipping Address')))}}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{Form::label('shipping_country',__('Country'),array("class"=>"form-control-label")) }}
                                    {{Form::text('shipping_country',old('shipping_country'),array('class'=>'form-control','placeholder'=>__('Shipping Country')))}}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{Form::label('shipping_city',__('City'),array("class"=>"form-control-label")) }}
                                    {{Form::text('shipping_city',old('shipping_city'),array('class'=>'form-control','placeholder'=>__('Shipping City')))}}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{Form::label('shipping_postalcode',__('Postal Code'),array("class"=>"form-control-label")) }}
                                    {{Form::text('shipping_postalcode',old('shipping_postalcode'),array('class'=>'form-control','placeholder'=>__('Shipping Postal Code')))}}
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 text-right ">
                            <a href="{{route('store.slug',$store->slug)}}" class="btn btn-link bg-white-light text-sm text-dark font-weight-bold">{{__('Return to shop')}}</a>
                            {{Form::submit(__('Next step'),array('class'=>'btn btn-sm btn-success','id'=>'submit-all'))}}
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div id="location_hide" style="display: none">
                    <div class="card">
                        <div class="card-header">
                            <h6>{{__('Select Shipping')}}</h6>
                        </div>
                        <div class="card-body" id="shipping_location_content">
                        </div>
                    </div>
                </div>
                <div data-toggle="sticky" data-sticky-offset="30">
                    <div class="card" id="card-summary">
                        <div class="card-header py-3">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <span class="h6">{{__('Summary')}}</span>
                                </div>
                                <div class="col-6 text-right">
                                    <span class="badge badge-pill badge-soft-success">{{$total_item}} items</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(!empty($products))
                                @php
                                    $total = 0;
                                    $sub_tax = 0;
                                    $sub_total= 0;
                                @endphp
                                @foreach($products as $product)
                                    @if(isset($product['variant_id']) && !empty($product['variant_id']))
                                        <div class="row mb-2 pb-2 delimiter-bottom">
                                            <div class="col-8">
                                                <div class="media align-items-center">
                                                    <img alt="Image placeholder" class="mr-2" src="{{asset($product['image'])}}" style="width: 42px;">
                                                    <div class="media-body">
                                                        <div class="text-limit lh-100">
                                                            <small class="font-weight-bold mb-0">{{$product['product_name'].' - ( ' . $product['variant_name'] .' ) '}}</small>
                                                        </div>
                                                        @php
                                                            $total_tax=0;
                                                        @endphp
                                                        <small class="text-muted">{{$product['quantity']}} x {{ Utility::priceFormat($product['variant_price'])}}
                                                            @if(!empty($product['tax']))
                                                                +
                                                                @foreach($product['tax'] as $tax)
                                                                    @php
                                                                        $sub_tax = ($product['variant_price'] * $product['quantity'] * $tax['tax']) / 100;
                                                                        $total_tax += $sub_tax;
                                                                    @endphp

                                                                    {{ Utility::priceFormat($sub_tax).' ('.$tax['tax_name'].' '.($tax['tax']).'%)'}}
                                                                @endforeach
                                                            @endif
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-4 text-right lh-100">
                                                <small class="text-dark">
                                                    @php
                                                        $totalprice = $product['variant_price'] * $product['quantity'] + $total_tax;
                                                        $subtotal = $product['variant_price'] * $product['quantity'];
                                                        $sub_total += $subtotal;
                                                    @endphp
                                                    {{ Utility::priceFormat($totalprice)}}
                                                </small>
                                                @php

                                                    $total += $totalprice;

                                                @endphp
                                            </div>
                                        </div>
                                    @else
                                        <div class="row mb-2 pb-2 delimiter-bottom">
                                            <div class="col-8">
                                                <div class="media align-items-center">
                                                    <img alt="Image placeholder" class="mr-2" src="{{asset($product['image'])}}" style="width: 42px;">
                                                    <div class="media-body">
                                                        <div class="text-limit lh-100">
                                                            <small class="font-weight-bold mb-0">{{$product['product_name']}}</small>
                                                        </div>
                                                        @php
                                                            $total_tax=0;
                                                        @endphp
                                                        <small class="text-muted">{{$product['quantity']}} x {{ Utility::priceFormat($product['price'])}}
                                                            @if(!empty($product['tax']))
                                                                +
                                                                @foreach($product['tax'] as $tax)
                                                                    @php
                                                                        $sub_tax = ($product['price'] * $product['quantity'] * $tax['tax']) / 100;
                                                                        $total_tax += $sub_tax;
                                                                    @endphp

                                                                    {{ Utility::priceFormat($sub_tax).' ('.$tax['tax_name'].' '.($tax['tax']).'%)'}}
                                                                @endforeach
                                                            @endif
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-4 text-right lh-100">
                                                <small class="text-dark">
                                                    @php
                                                        $totalprice = $product['price'] * $product['quantity'] + $total_tax;
                                                        $subtotal = $product['price'] * $product['quantity'];
                                                        $sub_total += $subtotal;
                                                    @endphp
                                                    {{ Utility::priceFormat($totalprice)}}
                                                </small>
                                                @php($total += $totalprice)
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        <!-- Subtotal -->
                            <div class="row mt-2 pt-2  pb-3">
                                <div class="col-8 text-right">
                                    <small class="font-weight-bold">{{__('Subtotal (Before Tax)')}}:</small>
                                </div>
                                <div class="col-4 text-right">
                                    <span class="text-sm font-weight-bold"> {{ Utility::priceFormat(!empty($sub_total)?$sub_total:0)}}</span>

                                </div>
                            </div>
                            <!-- Shipping -->
                            @foreach($taxArr['tax'] as $k=>$tax)
                                <div class="row mt-2 pt-2 border-top">
                                    <div class="col-8 text-right">
                                        <div class="media align-items-center">
                                            <div class="media-body">
                                                <div class="text-limit lh-100">
                                                    <small class="font-weight-bold mb-0">{{$tax}}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4 text-right">
                                        <span class="text-sm font-weight-bold">{{ Utility::priceFormat($taxArr['rate'][$k])}}</span>
                                    </div>
                                </div>
                            @endforeach
                            @if($store->enable_shipping == "on")
                                <div class="shipping_price_add" style="display: none">
                                    <div class="row mt-3 pt-3 border-top">
                                        <div class="col-8 text-right pt-2">
                                            <div class="media align-items-center">
                                                <div class="media-body">
                                                    <div class="text-limit lh-100 text-sm">{{__('Shipping Price')}}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4 text-right"><span class="text-sm font-weight-bold shipping_price"></span></div>
                                    </div>
                                </div>
                        @endif
                        <!-- Subtotal -->
                            <div class="row mt-3 pt-3 border-top">
                                <div class="col-8 text-right">
                                    <small class="text-uppercase font-weight-bold">{{__('Total')}}:</small>
                                </div>
                                <div class="col-4 text-right">
                                    <span class="text-sm font-weight-bold pro_total_price" data-original="{{ Utility::priceFormat(!empty($total)?$total:0)}}"> {{ Utility::priceFormat(!empty($total)?$total:'0')}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{Form::close()}}
    </div>
@endsection
@push('script-page')
    <script>
        function billing_data() {
            $("[name='shipping_address']").val($("[name='billing_address']").val());
            $("[name='shipping_city']").val($("[name='billing_city']").val());
            $("[name='shipping_state']").val($("[name='billing_state']").val());
            $("[name='shipping_country']").val($("[name='billing_country']").val());
            $("[name='shipping_postalcode']").val($("[name='billing_postalcode']").val());
        }

        $(document).ready(function () {
            $('.change_location').trigger('change');

            setTimeout(function () {
                var shipping_id = $("input[name='shipping_id']:checked").val();
                getTotal(shipping_id);
            }, 200);
        });

        $(document).on('change', '.shipping_mode', function () {
            var shipping_id = this.value;
            getTotal(shipping_id);
        });

        function getTotal(shipping_id) {
            var pro_total_price = $('.pro_total_price').attr('data-original');
            if (shipping_id == undefined) {
                $('.shipping_price_add').hide();
                return false
            } else {
                $('.shipping_price_add').show();
            }
            $.ajax({
                url: '{{ route('user.shipping', [$store->slug,'_shipping'])}}'.replace('_shipping', shipping_id),
                data: {
                    "pro_total_price": pro_total_price,
                    "_token": "{{ csrf_token() }}",
                },
                method: 'POST',
                context: this,
                dataType: 'json',

                success: function (data) {
                    var price = data.price + pro_total_price;
                    $('.shipping_price').html(data.price);
                    $('.pro_total_price').html(data.total_price);
                }
            });
        }

        $(document).on('change', '.change_location', function () {
            var location_id = $('.change_location').val();

            if (location_id == 0) {
                $('#location_hide').hide();

            } else {
                $('#location_hide').show();

            }

            $.ajax({
                url: '{{ route('user.location', [$store->slug,'_location_id'])}}'.replace('_location_id', location_id),
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                method: 'POST',
                context: this,
                dataType: 'json',

                success: function (data) {
                    var html = '';
                    var shipping_id = '{{(isset($cust_details['shipping_id']) ? $cust_details['shipping_id'] : '')}}';
                    $.each(data.shipping, function (key, value) {
                        var checked = '';
                        if (shipping_id != '' && shipping_id == value.id) {
                            checked = 'checked';
                        }

                        html += '<div class="shipping_location"><input type="radio" name="shipping_id" data-id="' + value.price + '" value="' + value.id + '" id="shipping_price' + key + '" class="shipping_mode" ' + checked + '>' +
                            ' <label name="shipping_label" for="shipping_price' + key + '" class="shipping_label"> ' + value.name + '</label></div>';

                    });
                    $('#shipping_location_content').html(html);
                }
            });
        });
    </script>

@endpush

