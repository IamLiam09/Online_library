@extends('layouts.admin')
@section('page-title')
    {{__('Order')}}
@endsection
@section('title')
    {{__('Orders')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">{{ __('Order') }}</a></li>
    <li class="breadcrumb-item">{{ __('show') }}</li>
@endsection
@section('action-btn')
    <a href="#" id="{{env('APP_URL').'/'.$store->slug.'/order/'.$order_id}}" class="btn btn-sm btn-primary btn-icon m-1" onclick="copyToClipboard(this)" title="Copy link" data-bs-toggle="tooltip" data-original-title="{{__('Click to Copy Link')}}"><i class="ti ti-link text-white"></i></a>
@endsection
@section('filter')
@endsection
@section('content')

    <div class="mt-4">
        <div id="printableArea">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card card-fluid">
                        <div class="card-header ">
                            <h6 class="mb-0">{{__('Order')}} {{$order->order_id}}</h6>
                        </div>
                        <div class="card-body">
                            <h6 class="mb-4">{{__('Shipping Information')}}</h6>
                            <address class="mb-0 text-sm">
                                <dl class="row mt-4 align-items-center">
                                    <dt class="col-sm-3 h6 text-sm">{{__('Name')}}</dt>
                                    <dd class="col-sm-9 text-sm"> {{ !empty($student_data->name) ? $student_data->name : ''}}</dd>
                                    <dt class="col-sm-3 h6 text-sm">{{__('E-mail')}}</dt>
                                    <dd class="col-sm-9 text-sm">{{ !empty($student_data->email) ? $student_data->email : ''}}</dd>
                                </dl>
                            </address>
                        </div>
                        <div class="card-footer table-border-style">                                                                
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead class="thead-light">
                                        <tr class="border-top-0">
                                            <th>{{__('Item')}}</th>
                                            <th>{{__('Price')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $sub_tax = 0;
                                            $total = 0;
                                        @endphp     
                                        {{-- @if (!empty($order_products)) --}}
                                            @foreach($order_products as $key=>$product)
                                                <tr>
                                                    <td class="total">
                                                    <span class="h6 text-sm">
                                                        @if(isset($product->product_name))
                                                            {{$product->product_name}}
                                                        @else
                                                            {{$product->name}}
                                                        @endif
                                                    </span>
                                                        @php
                                                            $total_tax = 0
                                                        @endphp
                                                    </td>
                                                    <td>
                                                        {{ Utility::priceFormat($product->price)}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        {{-- @endif --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card card-fluid">
                        <div class="card-header border-0">
                            <h6 class="mb-0">{{__('Items from Order '). $order->order_id}}</h6>
                        </div>
                        <div class="card-body table-border-style">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>{{__('Description')}}</th>
                                            <th>{{__('Price')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{__('Grand Total')}} :</td>
                                            <td>{{ Utility::priceFormat($sub_total)}}</td>
                                        </tr>
                                        <tr>
                                            <th>{{__('Applied Coupon')}} :</th>
                                            <th>{{(!empty($order->discount_price))?$order->discount_price: Utility::priceFormat(0)}}</th>
                                        </tr>
                                        <tr>
                                            <th>{{__('Total')}} :</th>
                                            <th>{{ Utility::priceFormat($grand_total) }}</th>
                                        </tr>
                                        <tr>    
                                            <th>{{__('Payment Type')}} :</th>
                                            <th>{{ $order['payment_type'] }}</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="card">
            <div class="input-group">
                <input type="text" value="{{env('APP_URL').'/'.$store->slug.'/order/'.$order_id}}" id="myInput" class="form-control d-inline-block" aria-label="Recipient's username" aria-describedby="button-addon2" readonly>
                <div class="input-group-append">
                    <button class="btn btn-outline-primary" type="button" onclick="myFunction()" id="button-addon2"><i class="far fa-copy"></i> {{__('Copy Link')}}</button>
                </div>
            </div>
        </div> --}}
    </div>
@endsection
@push('script-page')
    <script type="text/javascript" src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
    <script>
        var filename = $('#filesname').val();
        function saveAsPDF() {
            var element = document.getElementById('printableArea');
            var opt = {
                margin: 0.3,
                filename: filename,
                image: {type: 'jpeg', quality: 1},
                html2canvas: {scale: 4, dpi: 72, letterRendering: true},
                jsPDF: {unit: 'in', format: 'A2'}
            };
            html2pdf().set(opt).from(element).save();

        }
    </script>
    <script>
        $("#deliver_btn").on('click', '#delivered', function () {
            var status = $('#delivered').attr('data-value');
            var data = {
                delivered: status,
            }
            $.ajax({
                url: '{{ route('orders.update',$order->id) }}',
                method: 'PUT',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    show_toastr('success', data.success, 'success');
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                }
            });
        });
    </script>
    <script>
        function myFunction() {
            var copyText = document.getElementById("myInput");
            copyText.select();
            copyText.setSelectionRange(0, 99999)
            document.execCommand("copy");
            show_toastr('Success', 'Link copied', 'success');
        }
    </script>
    <script>
        function copyToClipboard(element) {
            var copyText = element.id;
            document.addEventListener('copy', function (e) {
                e.clipboardData.setData('text/plain', copyText);
                e.preventDefault();
            }, true);

            document.execCommand('copy');
            show_toastr('Success', 'Url copied to clipboard', 'success');
        }
    </script>
@endpush
