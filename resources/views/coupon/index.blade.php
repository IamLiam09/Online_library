@extends('layouts.admin')
@section('page-title')
    {{__('Coupons')}}
@endsection
@section('title')
    {{__('Coupons')}}
@endsection
@section('action-btn')
    {{-- <a href="#" data-size="lg" data-url="{{route('coupons.create')}}" data-ajax-popup="true" data-title="{{__('Add Coupon')}}" class="btn btn-sm btn-white bor-radius">
        <i class="fa fa-plus"></i>
        {{ __('Add Coupon') }}
    </a> --}}

    <div class="btn btn-sm btn-primary btn-icon m-1">
        <a href="#" class="" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Add Coupon')}}" data-ajax-popup="true" data-size="md" data-title="{{__('Add Coupon')}}" data-url="{{ route('coupons.create') }}"><i class="ti ti-plus text-white"></i></a>
    </div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('coupon') }}</li>
@endsection
@push('script-page')
    <script>
        $(document).on('click', '#code-generate', function () {
            var length = 10;
            var result = '';
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            $('#auto-code').val(result);
        });
    </script>
@endpush
@section('content')

    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive overflow_hidden">
                        <table id="pc-dt-simple" class="table align-items-center">
                            <thead class="thead-light">
                                <tr>
                                    <th> {{__('Name')}}</th>
                                    <th> {{__('Code')}}</th>
                                    <th> {{__('Discount (%)')}}</th>
                                    <th> {{__('Limit')}}</th>
                                    <th> {{__('Used')}}</th>
                                    <th class="text-center"> {{__('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($coupons as $coupon)
                                    <tr>
                                        <td>{{ $coupon->name }}</td>
                                        <td>{{ $coupon->code }}</td>
                                        <td>{{ $coupon->discount }}</td>
                                        <td>{{ $coupon->limit }}</td>
                                        <td>{{ $coupon->used_coupon() }}</td>
                                        <td class="text-center">
                                            <div class="action-btn bg-warning ms-2">
                                                <a href="{{ route('coupons.show',$coupon->id) }}" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-toggle="tooltip"
                                                    title="{{ __('Details') }}"> <span class="text-white"> <i class="ti ti-eye"></i></span></a>
                                            </div>
                                            <div class="action-btn bg-info ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-size="md" data-url="{{route('coupons.edit',[$coupon->id])}}" data-ajax-popup="true" data-title="{{__('Edit Coupon')}}" data-toggle="tooltip" title="{{__('Edit')}}">
                                                <span class="text-white">  <i class="ti ti-edit"></i> </span>
                                                </a>
                                            </div>
                                            <div class="action-btn bg-danger ms-2">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['coupons.destroy', $coupon->id]]) !!}
                                                    <a href="#!" class="mx-3 btn btn-sm  align-items-center show_confirm">
                                                        <i class="ti ti-trash text-white"></i>
                                                    </a>
                                                {!! Form::close() !!}
                                            </div>                                               

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>  
        </div>
        <!-- [ sample-page ] end -->
    </div>

@endsection