@extends('layouts.admin')
@section('page-title')
    {{__('Coupon Detail')}}
@endsection
@section('title')
    {{__('Coupon Detail')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('product-coupon.index') }}">{{ __('Coupon') }}</a></li>
    <li class="breadcrumb-item">{{$productCoupon->code}}</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between w-100">
                        <h4>{{$productCoupon->code}}</h4>
                    </div>
                </div>
                <div class="card-body table-border-style">
                    <div class="table-responsive overflow_hidden">
                        <table id="pc-dt-simple" class="table no-footer" width="100%" role="grid" aria-describedby="selection-datatable_info" style="width: 100%;">
                            <thead class="thead-light">
                                <tr role="row">
                                    <th class="sorting" tabindex="0" aria-controls="selection-datatable" rowspan="1" colspan="1" aria-label=" Coupon: activate to sort column ascending" style="width: 354px;"> Coupon</th>
                                    <th class="sorting" tabindex="0" aria-controls="selection-datatable" rowspan="1" colspan="1" aria-label=" User: activate to sort column ascending" style="width: 411px;"> User</th>
                                    <th class="sorting" tabindex="0" aria-controls="selection-datatable" rowspan="1" colspan="1" aria-label=" Date: activate to sort column ascending" style="width: 642px;"> Date</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($productCoupons as $userCoupon)
                                <tr role="row" class="odd">
                                    <td>{{ !empty($productCoupon->name)?$productCoupon->name:'' }}</td>
                                    <td>{{ !empty($userCoupon->name)?$userCoupon->name:'' }}</td>
                                    <td>{{ $userCoupon->created_at }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
