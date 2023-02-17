@extends('layouts.theme3.storefront')
@section('page-title')
    {{__('Payment')}}
@endsection
@push('script-page')
    <script src="https://js.stripe.com/v3/"></script>
    <script type="text/javascript">
        var stripe = Stripe('{{ $store->stripe_key }}');
        var elements = stripe.elements();

        // Custom styling can be passed to options when creating an Element.
        var style = {
            base: {
                // Add your base input styles here. For example:
                fontSize: '14px',
                color: '#32325d',
            },
        };

        // Create an instance of the card Element.
        var card = elements.create('card', {style: style});

        // Add an instance of the card Element into the `card-element` <div>.
        card.mount('#card-element');

        // Create a token or display an error when the form is submitted.
        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            stripe.createToken(card).then(function (result) {
                if (result.error) {
                    $("#card-errors").html(result.error.message);
                    show_toastr('Error', result.error.message, 'error');
                } else {
                    // Send the token to your server.
                    stripeTokenHandler(result.token);
                }
            });
        });

        function stripeTokenHandler(token) {
            // Insert the token ID into the form so it gets submitted to the server
            var form = document.getElementById('payment-form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);

            form.submit();
        }
    </script>
    <script>
        $(document).on('click', '#owner-whatsapp', function () {
            var product_array = '{{$encode_product}}';
            var product = JSON.parse(product_array.replace(/&quot;/g, '"'));
            var order_id = '{{$order_id = '#'.str_pad(!empty($order->id) ? $order->id + 1 : 0 + 1, 4, "100", STR_PAD_LEFT)}}';

            var total_price = $('#Subtotal .total_price').attr('data-value');
            var coupon_id = $('.hidden_coupon').attr('data_id');
            var dicount_price = $('.dicount_price').html();

            var data = {
                coupon_id: coupon_id,
                dicount_price: dicount_price,
                total_price: total_price,
                product: product,
                order_id: order_id,
                wts_number: $('#wts_number').val()
            }
            $.ajax({
                url: '{{ route('user.whatsapp',$store->slug) }}',
                method: 'POST',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data.status == 'success') {

                        show_toastr(data["success"], '{!! session('+data["status"]+') !!}', data["status"]);

                        setTimeout(function () {
                            var url = '{{ route('store-complete.complete', [$store->slug, ":id"]) }}';
                            url = url.replace(':id', data.order_id);

                            window.location.href = url;
                        }, 1000);

                        var append_href = '{!! $url !!}' + '{{route('user.order',[$store->slug,Crypt::encrypt(!empty($order->id) ? $order->id + 1 : 0 + 1)])}}';
                        window.open(append_href, '_blank');

                    } else {
                        show_toastr("Error", data.success, data["status"]);
                    }
                }
            });
        });
        $(document).on('click', '#cash_on_delivery', function () {
            var product_array = '{{$encode_product}}';
            var product = JSON.parse(product_array.replace(/&quot;/g, '"'));
            var order_id = '{{$order_id = '#'.str_pad(!empty($order->id) ? $order->id + 1 : 0 + 1, 4, "100", STR_PAD_LEFT)}}';

            var total_price = $('#Subtotal .total_price').attr('data-value');
            var coupon_id = $('.hidden_coupon').attr('data_id');
            var dicount_price = $('.dicount_price').html();

            var data = {
                coupon_id: coupon_id,
                dicount_price: dicount_price,
                total_price: total_price,

                product: product,
                order_id: order_id,
            }
            $.ajax({
                url: '{{ route('user.cod',$store->slug) }}',
                method: 'POST',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data.status == 'success') {

                        show_toastr(data["success"], '{!! session('+data["status"]+') !!}', data["status"]);

                        setTimeout(function () {
                            var url = '{{ route('store-complete.complete', [$store->slug, ":id"]) }}';
                            url = url.replace(':id', data.order_id);
                            window.location.href = url;
                        }, 1000);

                    } else {
                        show_toastr("Error", data.success, data["status"]);
                    }
                }
            });
        });
        $(document).on('click', '#bank_transfer', function () {
            var product_array = '{{$encode_product}}';
            var product = JSON.parse(product_array.replace(/&quot;/g, '"'));
            var order_id = '{{$order_id = '#'.str_pad(!empty($order->id) ? $order->id + 1 : 0 + 1, 4, "100", STR_PAD_LEFT)}}';

            var total_price = $('#Subtotal .total_price').attr('data-value');
            var coupon_id = $('.hidden_coupon').attr('data_id');
            var dicount_price = $('.dicount_price').html();

            var data = {
                coupon_id: coupon_id,
                dicount_price: dicount_price,
                total_price: total_price,
                product: product,
                order_id: order_id,
            }
            $.ajax({
                url: '{{ route('user.bank_transfer',$store->slug) }}',
                method: 'POST',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data.status == 'success') {

                        show_toastr(data["success"], '{!! session('+data["status"]+') !!}', data["status"]);

                        setTimeout(function () {
                            var url = '{{ route('store-complete.complete', [$store->slug, ":id"]) }}';
                            url = url.replace(':id', data.order_id);

                            window.location.href = url;
                        }, 1000);

                    } else {
                        show_toastr("Error", data.success, data["status"]);
                    }
                }
            });
        });
    </script>
    <script>
        // Apply Coupon
        $(document).on('click', '.apply-coupon', function (e) {
            e.preventDefault();
            var ele = $(this);
            var coupon = ele.closest('.row').find('.coupon').val();
            var hidden_field = $('.hidden_coupon').val();
            var price = $('#card-summary .product_total').val();
            var shipping_price = $('#card-summary .shipping_price').attr('data-value');

            if (coupon == hidden_field) {
                show_toastr('Error', 'Coupon Already Used', 'error');
            } else {
                if (coupon != '') {
                    $.ajax({
                        url: '{{route('apply.productcoupon')}}',
                        datType: 'json',
                        data: {
                            price: price,
                            shipping_price: shipping_price,
                            store_id: {{$store->id}},
                            coupon: coupon
                        },
                        success: function (data) {
                            console.log(data);
                            $('#stripe_coupon, #paypal_coupon').val(coupon);
                            if (data.is_success) {
                                $('.hidden_coupon').val(coupon);
                                $('.hidden_coupon').attr(data);

                                $('.dicount_price').html(data.discount_price);

                                var html = '';
                                html += '<span class="text-sm font-weight-bold total_price" data-value="' + data.final_price_data_value + '">' + data.final_price + '</span>'
                                $('.final_total_price').html(html);
                                show_toastr('Success', data.message, 'success');
                            } else {
                                show_toastr('Error', data.message, 'error');
                            }
                        }
                    })
                } else {
                    show_toastr('Error', '{{__('Invalid Coupon Code.')}}', 'error');
                }
            }

        });
    </script>
@endpush
@section('content')

    <div class="container">
        <div class="row row-grid">
            <div class="col-lg-8">
                <!-- Add money using COD -->
                @if($store['enable_cod'] == 'on')
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-lg-8 col-md-8">
                                    <div>
                                        <i class="fas fa-shipping-fast"></i>
                                        <label class="h6 lh-180" for="radio-payment-cod">{{__('Cash On Delivery')}}</label>
                                    </div>
                                    <label class="text_sm">{{__('Cash on delivery is a type of transaction in which payment for a good is made at the time of delivery')}}.</label>
                                </div>
                                <div class="col-12 col-lg-4 col-md-4 text-right">
                                    <img alt="Image placeholder" src="{{asset('assets/img/cod.png')}}" width="70">
                                    <form class="w3-container w3-display-middle w3-card-4" method="POST" action="{{ route('user.cod',$store->slug) }}">
                                        @csrf
                                        <input type="hidden" name="product_id">
                                        <div class="form-group mt-3">
                                            <div class="text-sm-right ">
                                                <button type="button" class="btn btn-dark btn-sm float-right" id="cash_on_delivery">{{__('Complete Order')}}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                @endif
            <!-- Add money using Stripe -->
                @if(isset($store_payments['is_stripe_enabled']) && $store_payments['is_stripe_enabled'] == 'on')
                    <form role="form" action="{{ route('stripe.post',$store->slug) }}" method="post" class="require-validation" id="payment-form">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-12 col-lg-8 col-md-8">
                                        <div>
                                            <label class="h6 mb-0 lh-180" for="radio-payment-card">{{__('Credit Card (Strip)')}}</label>
                                        </div>
                                        <p class="text-muted mt-2 mb-0 text_sm">{{__('Safe money transfer using your bank account. We support Mastercard, Visa, Maestro and Skrill')}}.</p>
                                    </div>
                                    <div class="col-12 col-lg-4 col-md-4 text-right">
                                        <img alt="Image placeholder" src="{{asset('assets/img/mastercard.png')}}" width="40" class="mr-2">
                                        <img alt="Image placeholder" src="{{asset('assets/img/visa.png')}}" width="40" class="mr-2">
                                        <img alt="Image placeholder" src="{{asset('assets/img/skrill.png')}}" width="40">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="card-name-on">{{__('Name on card')}}</label>
                                            <input type="text" name="name" id="card-name-on" class="form-control required" placeholder="Enter Your Name">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div id="card-element"></div>
                                        <div id="card-errors" role="alert"></div>
                                    </div>
                                    <div class="col-md-10">
                                        <br>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="error" style="display: none;">
                                            <div class='alert-danger alert text_sm'>{{__('Please correct the errors and try again.')}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <div class="text-sm-right mr-2">
                                                <input type="hidden" name="plan_id">
                                                <button class="btn btn-dark btn-sm" type="submit">
                                                    <i class="mdi mdi-cash-multiple mr-1"></i> {{__('Pay Now')}}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                @endif
            <!-- Add money using PayPal -->
                @if(isset($store_payments['is_paypal_enabled']) && $store_payments['is_paypal_enabled'] == 'on')
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-lg-8 col-md-8">
                                    <div>
                                        <label class="h6 mb-0 lh-180" for="radio-payment-paypal">{{__('PayPal')}}</label>
                                    </div>
                                    <p class="text_sm text-muted mt-2 mb-0">
                                        {{__('Pay your order using the most known and secure platform for online money transfers. You will be redirected to PayPal to finish complete your purchase')}}.
                                    </p>
                                </div>
                                <div class="col-12 col-lg-4 col-md-4 text-right">
                                    <img alt="Image placeholder" src="{{asset('assets/img/paypal-256x160.png')}}" width="70">
                                    <form class="w3-container w3-display-middle w3-card-4" method="POST" action="{{ route('pay.with.paypal',$store->slug) }}">
                                        @csrf
                                        <input type="hidden" name="product_id">
                                        <div class="form-group mt-3">
                                            <div class="text-sm-right ">
                                                <button class="btn btn-dark btn-sm float-right" type="submit">
                                                    <i class="mdi mdi-cash-multiple mr-1"></i> {{__('Pay Now')}}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                @endif
            <!-- Add money using whatsapp -->
                @if($store['enable_whatsapp'] == 'on')
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-lg-8 col-md-8">
                                    <div>
                                        <i class="fab fa-whatsapp"></i>
                                        <label class="h6 lh-180" for="radio-payment-whatsapp">{{__('Whatsapp')}}</label>
                                    </div>
                                    <label class="text_sm">{{__('Click to chat. The click to chat feature lets customers click an URL in order to directly start a chat with another person or business via WhatsApp. ...')}}</label>
                                    <label class="text_sm">{{__('QR code. As you know, having to add a phone number to your contacts in order to start up a WhatsApp message can take a little while. ...')}}.</label>
                                    <input type="text" name="wts_number" id="wts_number" class="form-control input-mask mt-2" data-mask="+00 0000000000" placeholder="Enter Your Phone Number">
                                </div>
                                <div class="col-12 col-lg-4 col-md-4 text-right">
                                    <img alt="Image placeholder" src="{{asset('assets/img/whatsapp.png')}}" width="70">
                                    <form class="w3-container w3-display-middle w3-card-4" method="POST" action="{{ route('user.whatsapp',$store->slug) }}">
                                        @csrf
                                        <input type="hidden" name="product_id">
                                        <div class="form-group mt-3">
                                            <div class="text-sm-right ">
                                                <button type="button" class="btn btn-dark btn-sm float-right" id="owner-whatsapp">{{__('Complete Order')}}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                @endif
            <!-- Add money using Bank Transfer -->
                @if($store['enable_bank'] == 'on')
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-lg-8 col-md-8">
                                    <div>
                                        <i class="fas fa-university"></i>
                                        <label class="h6 lh-180" for="bank-transfer">{{__('Bank Transfer')}}</label>
                                    </div>
                                    <p class="white_space text_sm">{{$store->bank_number}}</p>
                                </div>
                                <div class="col-12 col-lg-4 col-md-4 text-right">
                                    <img alt="Image placeholder" src="{{asset('assets/img/bank.png')}}" width="70">
                                    <form class="w3-container w3-display-middle w3-card-4" method="POST" action="{{ route('user.bank_transfer',$store->slug) }}">
                                        @csrf
                                        <input type="hidden" name="product_id">
                                        <div class="form-group mt-3">
                                            <div class="text-sm-right ">
                                                <button type="button" class="btn btn-dark btn-sm float-right" id="bank_transfer">{{__('Complete Order')}}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(isset($store_payments['is_paystack_enabled']) && $store_payments['is_paystack_enabled']=='on')
                    <script src="https://js.paystack.co/v1/inline.js"></script>
                    {{-- PAYSTACK JAVASCRIPT FUNCTION --}}
                    <script>
                        function payWithPaystack() {
                            var paystack_callback = "{{ url('/paystack') }}";
                            var order_id = '{{$order_id = str_pad(!empty($order->id) ? $order->id + 1 : 0 + 1, 4, "100", STR_PAD_LEFT)}}';
                            var slug = '{{$store->slug}}';
                            var handler = PaystackPop.setup({
                                key: '{{ $store_payments['paystack_public_key']  }}',
                                email: '{{$cust_details['email']}}',
                                amount: $('.total_price').data('value') * 100,
                                currency: '{{$store['currency_code']}}',
                                ref: 'pay_ref_id' + Math.floor((Math.random() * 1000000000) +
                                    1
                                ), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
                                metadata: {
                                    custom_fields: [{
                                        display_name: "Mobile Number",
                                        variable_name: "mobile_number",
                                        value: "{{$cust_details['phone']}}"
                                    }]
                                },

                                callback: function (response) {
                                    console.log(response.reference, order_id);
                                    window.location.href = paystack_callback + '/' + slug + '/' + response.reference + '/' + {{$order_id}};
                                },
                                onClose: function () {
                                    alert('window closed');
                                }
                            });
                            handler.openIframe();
                        }

                    </script>
                    {{-- /PAYSTACK JAVASCRIPT FUNCTION --}}
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-lg-8 col-md-8">
                                    <div>
                                        <label class="h6 mb-0 lh-180" for="radio-payment-paypal">{{__('Paystack')}}</label>
                                    </div>
                                    <p class="text_sm text-muted mt-2 mb-0">
                                        {{__('Pay your order using the most known and secure platform for online money transfers. You will be redirected to Paystack to finish complete your purchase')}}.
                                    </p>
                                </div>
                                <div class="col-12 col-lg-4 col-md-4 text-right">
                                    <img alt="Image placeholder" src="{{asset('assets/img/paystack-logo.jpg')}}" width="110">
                                    <input type="hidden" name="product_id">
                                    <div class="form-group mt-3">
                                        <div class="text-sm-right ">
                                            <button class="btn btn-dark btn-sm float-right" type="button" onclick="payWithPaystack()">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{__('Pay Now')}}
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                @endif

                @if(isset($store_payments['is_flutterwave_enabled']) && $store_payments['is_flutterwave_enabled']=='on')

                    <script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
                    {{-- Flutterwave JAVASCRIPT FUNCTION --}}
                    <script>

                        function payWithRave() {
                            var API_publicKey = '{{ $store_payments['flutterwave_public_key']  }}';
                            var nowTim = "{{ date('d-m-Y-h-i-a') }}";
                            var flutter_callback = "{{ url('/flutterwave') }}";
                            var x = getpaidSetup({
                                PBFPubKey: API_publicKey,
                                customer_email: '{{$cust_details['email']}}',
                                amount: $('.total_price').data('value'),
                                customer_phone: '{{$cust_details['phone']}}',
                                currency: '{{$store['currency_code']}}',
                                txref: nowTim + '__' + Math.floor((Math.random() * 1000000000)) + 'fluttpay_online-' +
                                {{ date('Y-m-d') }},
                                meta: [{
                                    metaname: "payment_id",
                                    metavalue: "id"
                                }],
                                onclose: function () {
                                },
                                callback: function (response) {

                                    var txref = response.tx.txRef;

                                    if (
                                        response.tx.chargeResponseCode == "00" ||
                                        response.tx.chargeResponseCode == "0"
                                    ) {
                                        window.location.href = flutter_callback + '/{{$store->slug}}/' + txref + '/' + {{$order_id}};
                                    } else {
                                        // redirect to a failure page.
                                    }
                                    x.close(); // use this to close the modal immediately after payment.
                                }
                            });
                        }
                    </script>
                    {{-- /PAYSTACK JAVASCRIPT FUNCTION --}}
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-lg-8 col-md-8">
                                    <div>
                                        <label class="h6 mb-0 lh-180" for="radio-payment-paypal">{{__('Flutterwave')}}</label>
                                    </div>
                                    <p class="text_sm text-muted mt-2 mb-0">
                                        {{__('Pay your order using the most known and secure platform for online money transfers. You will be redirected to Flutterwave to finish complete your purchase')}}.
                                    </p>
                                </div>
                                <div class="col-12 col-lg-4 col-md-4 text-right">
                                    <img alt="Image placeholder" src="{{asset('assets/img/flutterwave_logo.png')}}" width="110">
                                    <input type="hidden" name="product_id">
                                    <div class="form-group mt-3">
                                        <div class="text-sm-right ">
                                            <button class="btn btn-dark btn-sm float-right" type="button" onclick="payWithRave()">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{__('Pay Now')}}
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                @endif

                @if(isset($store_payments['is_razorpay_enabled']) && $store_payments['is_razorpay_enabled'] == 'on')
                    @php
                        $logo         =asset(Storage::url('uploads/logo/'));
                        $company_logo = Utility::getValByName('company_logo');
                    @endphp
                    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
                    {{-- Flutterwave JAVASCRIPT FUNCTION --}}
                    <script>

                        function payRazorPay() {

                            var getAmount = $('.total_price').data('value');
                            var product_id = '{{$order_id}}';
                            var useremail = '{{$cust_details['email']}}';
                            var razorPay_callback = '{{url('razorpay')}}';
                            var totalAmount = getAmount * 100;
                            var product_array = '{{$encode_product}}';
                            var product = JSON.parse(product_array.replace(/&quot;/g, '"'));
                            var order_id = '{{$order_id = str_pad(!empty($order->id) ? $order->id + 1 : 0 + 1, 4, "100", STR_PAD_LEFT)}}';

                            var coupon_id = $('.hidden_coupon').attr('data_id');
                            var dicount_price = $('.dicount_price').html();

                            var options = {
                                "key": "{{ $store_payments['razorpay_public_key']  }}", // your Razorpay Key Id
                                "amount": totalAmount,
                                "name": product,
                                "currency": '{{$store['currency_code']}}',
                                "description": "Order Id : " + order_id,
                                "image": "{{$logo.'/'.(isset($company_logo) && !empty($company_logo)?$company_logo:'logo.png')}}",
                                "handler": function (response) {
                                    window.location.href = razorPay_callback + '/{{$store->slug}}/' + response.razorpay_payment_id + '/' + order_id;
                                },
                                "theme": {
                                    "color": "#528FF0"
                                }
                            };

                            var rzp1 = new Razorpay(options);
                            rzp1.open();
                        }
                    </script>
                    {{-- /Razerpay JAVASCRIPT FUNCTION --}}
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-lg-8 col-md-8">
                                    <div>
                                        <label class="h6 mb-0 lh-180" for="radio-payment-paypal">{{__('Razorpay')}}</label>
                                    </div>
                                    <p class="text_sm text-muted mt-2 mb-0">
                                        {{__('Pay your order using the most known and secure platform for online money transfers. You will be redirected to Razorpay to finish complete your purchase')}}.
                                    </p>
                                </div>
                                <div class="col-12 col-lg-4 col-md-4 text-right">
                                    <img alt="Image placeholder" src="{{asset('assets/img/razorpay.png')}}" width="110">
                                    <input type="hidden" name="product_id">
                                    <div class="form-group mt-3">
                                        <div class="text-sm-right ">
                                            <button class="btn btn-dark btn-sm float-right" type="button" onclick="payRazorPay()">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{__('Pay Now')}}
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                @endif

                @if(isset($store_payments['is_paytm_enabled']) && $store_payments['is_paytm_enabled'] == 'on')
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-lg-8 col-md-8">
                                    <div>
                                        <label class="h6 mb-0 lh-180" for="radio-payment-paypal">{{__('Paytm')}}</label>
                                    </div>
                                    <p class="text_sm text-muted mt-2 mb-0">
                                        {{__('Pay your order using the most known and secure platform for online money transfers. You will be redirected to Paytm to finish complete your purchase')}}.
                                    </p>
                                </div>
                                <div class="col-12 col-lg-4 col-md-4 text-right">
                                    <img alt="Image placeholder" src="{{asset('assets/img/Paytm.png')}}" width="110">
                                    <form class="w3-container w3-display-middle w3-card-4" method="POST" action="{{ route('paytm.prepare.payments',$store->slug) }}">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ date('Y-m-d') }}-{{ strtotime(date('Y-m-d H:i:s')) }}-payatm">
                                        <input type="hidden" name="order_id" value="{{str_pad(!empty($order->id) ? $order->id + 1 : 0 + 1, 4, "100", STR_PAD_LEFT)}}">
                                        @php
                                            $skrill_data = [
                                                'transaction_id' => md5(date('Y-m-d') . strtotime('Y-m-d H:i:s') . 'user_id'),
                                                'user_id' => 'user_id',
                                                'amount' => 'amount',
                                                'currency' => 'currency',
                                            ];
                                            session()->put('skrill_data', $skrill_data);

                                        @endphp
                                        <div class="form-group mt-3">
                                            <div class="text-sm-right ">
                                                <button type="submit" class="btn btn-dark btn-sm float-right">{{__('Pay Now')}}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>

                @endif

                @if(isset($store_payments['is_mercado_enabled']) && $store_payments['is_mercado_enabled'] == 'on')
                    <script>
                        function payMercado() {
                            
                            var product_array = '{{$encode_product}}';
                            var product = JSON.parse(product_array.replace(/&quot;/g, '"'));
                            var order_id = '{{$order_id = '#'.str_pad(!empty($order->id) ? $order->id + 1 : 0 + 1, 4, "100", STR_PAD_LEFT)}}';

                            var total_price = $('#Subtotal .total_price').attr('data-value');
                            var coupon_id = $('.hidden_coupon').attr('data_id');
                            var dicount_price = $('.dicount_price').html();

                            var data = {
                                coupon_id: coupon_id,
                                dicount_price: dicount_price,
                                total_price: total_price,
                                product: product,
                                order_id: order_id,
                            }
                            $.ajax({
                                url: '{{ route('mercadopago.prepare',$store->slug) }}',
                                method: 'POST',
                                data: data,
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function (data) {
                                    if (data.status == 'success') {
                                        window.location.href = data.url;
                                    } else {
                                        show_toastr("Error", data.success, data["status"]);
                                    }
                                }
                            });
                        }
                    </script>
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-lg-8 col-md-8">
                                    <div>
                                        <label class="h6 mb-0 lh-180" for="radio-payment-paypal">{{__('Mercado Pago')}}</label>
                                    </div>
                                    <p class="text_sm text-muted mt-2 mb-0">
                                        {{__('Pay your order using the most known and secure platform for online money transfers. You will be redirected to Mercado Pago to finish complete your purchase')}}.
                                    </p>
                                </div>
                                <div class="col-12 col-lg-4 col-md-4 text-right">
                                    <img alt="Image placeholder" src="{{asset('assets/img/mercadopago.png')}}" width="110">

                                    <div class="form-group mt-3">
                                        <div class="text-sm-right ">
                                            <button type="button" class="btn btn-dark btn-sm float-right" onclick="payMercado()">{{__('Pay Now')}}</button>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>

                @endif

                @if(isset($store_payments['is_mollie_enabled']) && $store_payments['is_mollie_enabled'] == 'on')
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-lg-8 col-md-8">
                                    <div>
                                        <label class="h6 mb-0 lh-180" for="radio-payment-paypal">{{__('Mercado Pago')}}</label>
                                    </div>
                                    <p class="text_sm text-muted mt-2 mb-0">
                                        {{__('Pay your order using the most known and secure platform for online money transfers. You will be redirected to Mercado Pago to finish complete your purchase')}}.
                                    </p>
                                </div>
                                <div class="col-12 col-lg-4 col-md-4 text-right">
                                    <img alt="Image placeholder" src="{{asset('assets/img/mollie.png')}}" width="100">
                                    <form class="w3-container w3-display-middle w3-card-4" method="POST" action="{{ route('mollie.prepare.payments',$store->slug) }}">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ date('Y-m-d') }}-{{ strtotime(date('Y-m-d H:i:s')) }}-payatm">
                                        <input type="hidden" name="desc" value="{{time()}}">
                                        <div class="form-group mt-3">
                                            <div class="text-sm-right ">
                                                <button type="submit" class="btn btn-dark btn-sm float-right">{{__('Pay Now')}}</button>
                                            </div>
                                        </div>
                                    </form>

                                </div>

                            </div>
                        </div>
                    </div>

                @endif

                @if(isset($store_payments['is_skrill_enabled']) && $store_payments['is_skrill_enabled'] == 'on')
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-lg-8 col-md-8">
                                    <div>
                                        <label class="h6 mb-0 lh-180" for="radio-payment-paypal">{{__('Skrill')}}</label>
                                    </div>
                                    <p class="text_sm text-muted mt-2 mb-0">
                                        {{__('Pay your order using the most known and secure platform for online money transfers. You will be redirected to Mercado Pago to finish complete your purchase')}}.
                                    </p>
                                </div>
                                <div class="col-12 col-lg-4 col-md-4 text-right">
                                    <img alt="Image placeholder" src="{{asset('assets/img/skrill.png')}}" width="100">
                                    <form class="w3-container w3-display-middle w3-card-4" method="POST" action="{{ route('skrill.prepare.payments',$store->slug) }}">
                                        @csrf
                                        <input type="hidden" name="transaction_id" value="{{ date('Y-m-d') . strtotime('Y-m-d H:i:s') . 'user_id' }}">
                                        <input type="hidden" name="desc" value="{{time()}}">
                                        <div class="form-group mt-3">
                                            <div class="text-sm-right ">
                                                <button type="submit" class="btn btn-dark btn-sm float-right">{{__('Pay Now')}}</button>
                                            </div>
                                        </div>
                                    </form>

                                </div>

                            </div>
                        </div>
                    </div>
                @endif

                @if(isset($store_payments['is_coingate_enabled']) && $store_payments['is_coingate_enabled'] == 'on')
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-lg-8 col-md-8">
                                    <div>
                                        <label class="h6 mb-0 lh-180" for="radio-payment-paypal">{{__('CoinGate')}}</label>
                                    </div>
                                    <p class="text_sm text-muted mt-2 mb-0">
                                        {{__('Pay your order using the most known and secure platform for online money transfers. You will be redirected to Mercado Pago to finish complete your purchase')}}.
                                    </p>
                                </div>
                                <div class="col-12 col-lg-4 col-md-4 text-right">
                                    <img alt="Image placeholder" src="{{asset('assets/img/coingate.png')}}" width="100">
                                    <form class="w3-container w3-display-middle w3-card-4" method="POST" action="{{ route('coingate.prepare',$store->slug) }}">
                                        @csrf
                                        <input type="hidden" name="transaction_id" value="{{ date('Y-m-d') . strtotime('Y-m-d H:i:s') . 'user_id' }}">
                                        <input type="hidden" name="desc" value="{{time()}}">
                                        <div class="form-group mt-3">
                                            <div class="text-sm-right ">
                                                <button type="submit" class="btn btn-dark btn-sm float-right">{{__('Pay Now')}}</button>
                                            </div>
                                        </div>
                                    </form>

                                </div>

                            </div>
                        </div>
                    </div>
                @endif

                @if(isset($store_payments['is_paymentwall_enabled']) && $store_payments['is_paymentwall_enabled'] == 'on')
                    <script src="https://checkout.paymentwall.com/v1/checkout.js"></script>

                    <div class="pay-main d-flex w-100 align-items-start justify-content-between mt-0 mx-0 mb-30">
                        <div class="pay-name">
                            <h4>{{__('PaymentWall')}}</h4>
                            <p>{{__('Pay your order using the most known and secure platform for online money transfers. You will be redirected to PayPal to finish complete your purchase')}}.</p>
                        </div>  
                        <div class="pay-type">
                            <div class="pay-type-img">                                
                                <img src="{{asset('assets/img/paymentwall.png')}}" alt="paypal" class="img-fluid">
                            </div>
                            <form method="POST" action="{{ route('paymentwall.callback',$store->slug) }}">
                                @csrf
                                <input type="hidden" name="product" class="customer_product">
                                <input type="hidden" name="desc" value="{{time()}}">
                                <button type="submit" class="pay-btn">{{__('Pay Now')}}</button>
                            </form>
                        </div>
                    </div>
                @endif


                <div class="mt-4 text-right">
                    <a href="{{route('store.slug',$store->slug)}}" class="btn bg-white-light btn-link text-sm text-dark font-weight-bold">{{__('Return to shop')}}</a>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="col-md-10">
                        <br>
                        <div class="form-group">
                            <label for="stripe_coupon">{{__('Coupon')}}</label>
                            <input type="text" id="stripe_coupon" name="coupon" class="form-control coupon" placeholder="{{ __('Enter Coupon Code') }}">
                            <input type="hidden" name="coupon" class="form-control hidden_coupon" value="">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group apply-stripe-btn-coupon">
                            <a href="#" class="btn btn-primary apply-coupon btn-sm">{{ __('Apply') }}</a>
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
                                    @if($product['variant_id'] !=0)
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

                                                                    {{ Utility::priceFormat($sub_tax).' ('.$tax['tax_name'].' '.$tax['tax'].'%)'}}
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
                        <!-- Discount -->
                            <div class="row mt-2 pt-2 border-top">
                                <div class="col-8 text-right">
                                    <div class="media align-items-center">
                                        <div class="media-body">
                                            <div class="text-limit lh-100">
                                                <small class="font-weight-bold mb-0">{{__('Coupon')}}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4 text-right">
                                    <span class="text-sm font-weight-bold dicount_price">0.00</span>
                                </div>
                            </div>
                            @if($store->enable_shipping == 'on')
                                <div class="shipping_price_add">
                                    <div class="row mt-3 pt-3 border-top">
                                        <div class="col-8 text-right pt-2">
                                            <div class="media align-items-center">
                                                <div class="media-body">
                                                    <div class="text-limit lh-100 text-sm">{{__('Shipping Price')}}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4 text-right"><span class="text-sm font-weight-bold shipping_price" data-value="{{$shipping_price}}">{{ Utility::priceFormat(!empty($shipping_price)?$shipping_price:0)}}</span></div>
                                    </div>
                                </div>
                        @endif
                        <!-- Subtotal -->
                            <div class="row mt-3 pt-3 border-top" id="Subtotal">
                                <div class="col-8 text-right">
                                    <small class="text-uppercase font-weight-bold">{{__('Total')}}:</small>
                                </div>
                                <input type="hidden" class="product_total" value="{{$total}}">
                                <div class="col-4 text-right final_total_price">
                                    @if(!empty($shipping_price) && $shipping_price != 0)
                                        <span class="text-sm font-weight-bold total_price" data-value="{{$total+$shipping_price}}"> {{ Utility::priceFormat(!empty($total)?$total+$shipping_price:0)}}</span>
                                    @else
                                        <span class="text-sm font-weight-bold total_price" data-value="{{$total}}"> {{ Utility::priceFormat(!empty($total)?$total:0)}}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script-page')
    <script src="{{asset('libs/jquery-mask-plugin/dist/jquery.mask.min.js')}}"></script>
@endpush
