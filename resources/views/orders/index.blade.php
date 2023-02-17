@extends('layouts.admin')
@section('page-title')
    {{__('Order')}}
@endsection
@section('title')
    {{__('Orders ')}}
@endsection
@section('action-btn')
    <div class="btn btn-sm btn-primary btn-icon m-1 float-end">
        <a href="{{route('order.export')}}" class="" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Export')}}"><i class="ti ti-file-export text-white"></i></a>
    </div>

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Order') }}</li>
@endsection

@section('filter')
@endsection

@section('content')

<div class="row">
    <div class="col-sm-12">     
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive overflow_hidden">
                    <table id="pc-dt-simple" class="table">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">{{__('Orders')}}</th>
                                <th scope="col" class="sort">{{__('Date')}}</th>
                                <th scope="col" class="sort">{{__('Name')}}</th>
                                <th scope="col" class="sort">{{__('Value')}}</th>
                                <th scope="col" class="sort">{{__('Payment Type')}}</th>
                                <th scope="col" class="sort">{{ __('Receipt') }}</th>
                                <th scope="col" class="text-right">{{__('Action')}}</th>
                            </tr>
                        </thead>
                        @if(!empty($orders) && count($orders) > 0)
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td scope="row">
                                            <a href="{{route('orders.show',$order->id)}}" class="btn btn-sm btn-icon btn-outline-primary order2_badge">
                                                <span class="btn-inner--text">{{$order->order_id}}</span>
                                            </a>
                                        </td>
                                        <td class="order">
                                            <span class="h6 text-sm font-weight-bold mb-0">{{ Utility::dateFormat($order->created_at)}}</span>
                                        </td>
                                        <td>
                                            <span class="client">{{$order->name}}</span>
                                        </td>
                                        <td>
                                            <span class="value text-sm mb-0">{{ Utility::priceFormat($order->price)}}</span>
                                        </td>
                                        <td>
                                            <span class="taxes text-sm mb-0">{{$order->payment_type}}</span>
                                        </td>
                                        <td>
                                            @if ($order->payment_type == 'Bank Transfer')
                                                <a href="{{ asset(Storage::url($order->receipt)) }}" title="Invoice"
                                                    download>
                                                    <i class="fas fa-file-invoice"></i>
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-center">
                                                <!-- Actions -->
                                                <div class="actions ml-3">
                                                    <div class="action-btn bg-warning ms-2">
                                                        <a href="{{route('orders.show',$order->id)}}" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" title="{{ __('Details') }}"> <span class="text-white"> <i class="ti ti-eye"></i></span></a>
                                                    </div>
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['orders.destroy', $order->id]]) !!}
                                                            <a href="#!" class="mx-3 btn btn-sm align-items-center show_confirm" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Delete')}}">
                                                                <i class="ti ti-trash text-white"></i>
                                                            </a>
                                                        {!! Form::close() !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        @else
                            <tbody>
                                <tr>
                                    <td colspan="7">
                                        <div class="text-center">
                                            <i class="fas fa-folder-open text-primary" style="font-size: 48px;"></i>
                                            <h2>{{__('Opps')}}...</h2>
                                            <h6>{{__('No data Found')}}. </h6>
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
</div>
@endsection
