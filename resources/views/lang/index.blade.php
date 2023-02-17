@extends('layouts.admin')
@push('css-page')
@endpush
@push('script-page')
@endpush
@section('page-title')
    {{__('Language')}}
@endsection
@section('title')
      {{__('Language')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Language') }}</li>
@endsection

@section('action-btn')

<div class="btn btn-sm btn-primary btn-icon m-1 float-end">
    <a href="#" class="" data-bs-toggle="tooltip" data-bs-placement="top"
        title="{{ __('Create Language') }}" data-ajax-popup="true" data-size="md"
        data-title="{{ __('Create Language') }}" data-url="{{ route('create.language') }}">
        <i class="ti ti-plus text-white"></i>
    </a>
</div>

@if($currantLang != (!empty(env('default_language')) ? env('default_language') : 'en'))
    <div class="action-btn btn btn-sm btn-danger btn-icon m-1 float-end ms-2">
        <form method="POST" action="{{ route('lang.destroy', $currantLang) }}" id="delete-form-{{ $currantLang }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="mx-3 btn btn-sm align-items-center show_confirm"
                data-toggle="tooltip" title='Delete'>
                <span class="text-white"> <i class="ti ti-trash"></i></span>
            </button>
        </form>
    </div>
@endif




    {{-- <a href="#" data-url="{{ route('create.language') }}" data-size="md" data-ajax-popup="true" data-title="{{__('Create New Language')}}" class="btn btn-sm btn-white btn-icon-only rounded-circle ml-4" data-toggle="tooltip">
        <span class="btn-inner--icon"><i class="ti ti-plus text-whote"></i></span>
    </a>
    @if($currantLang != (!empty(env('default_language')) ? env('default_language') : 'en'))
        <a href="#!" class="btn btn-sm btn-white btn-icon-only rounded-circle ml-4" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').' | '.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$currantLang}}').submit();">
            <i class="fas fa-trash"></i>
        </a>
        {!! Form::open(['method' => 'DELETE', 'route' => ['lang.destroy', $currantLang],'id'=>'delete-form-'.$currantLang]) !!}
        {!! Form::close() !!}

    @endif --}}

@endsection
@section('content')
    <div class="row">
        <div class="col-xl-3 col-md-3">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-pills flex-column " id="myTab4" role="tablist">
                        @foreach($languages as $lang)
                            <li class="nav-item">
                                <a href="{{route('manage.language',[$lang])}}" class="nav-link {{($currantLang == $lang)?'active':''}}">{{Str::upper($lang)}}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xl-9 col-md-9">
            <div class="card">
                <div class="card-body">
                    
                    <ul class="nav nav-pills mb-3 row" id="pills-tab" role="tablist">
                    
                        <li class="nav-item col-6">
                            <a class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" href="#home" role="tab" aria-controls="pills-home" aria-selected="true">{{ __('Labels')}}</a>
                        </li>
                        <li class="nav-item col-6">
                            <a class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">{{ __('Messages')}}</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="pills-home-tab">
                            <form method="post" action="{{route('store.language.data',[$currantLang])}}">
                                @csrf
                                <div class="row">
                                    @foreach($arrLabel as $label => $value)

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-control-label" for="example3cols1Input">{{$label}} </label>
                                                <input type="text" class="form-control" name="label[{{$label}}]" value="{{$value}}">
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="col-lg-12 text-end">
                                        <button class="btn btn-primary" type="submit">{{ __('Save Changes')}}</button>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                            <form method="post" action="{{route('store.language.data',[$currantLang])}}">
                                @csrf
                                <div class="row">
                                    @foreach($arrMessage as $fileName => $fileValue)
                                        <div class="col-lg-12">
                                            <h5>{{ucfirst($fileName)}}</h5>
                                        </div>
                                        @foreach($fileValue as $label => $value)
                                            @if(is_array($value))
                                                @foreach($value as $label2 => $value2)
                                                    @if(is_array($value2))
                                                        @foreach($value2 as $label3 => $value3)
                                                            @if(is_array($value3))
                                                                @foreach($value3 as $label4 => $value4)
                                                                    @if(is_array($value4))
                                                                        @foreach($value4 as $label5 => $value5)
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label>{{$fileName}}.{{$label}}.{{$label2}}.{{$label3}}.{{$label4}}.{{$label5}}</label>
                                                                                    <input type="text" class="form-control" name="message[{{$fileName}}][{{$label}}][{{$label2}}][{{$label3}}][{{$label4}}][{{$label5}}]" value="{{$value5}}">
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    @else
                                                                        <div class="col-lg-6">
                                                                            <div class="form-group">
                                                                                <label>{{$fileName}}.{{$label}}.{{$label2}}.{{$label3}}.{{$label4}}</label>
                                                                                <input type="text" class="form-control" name="message[{{$fileName}}][{{$label}}][{{$label2}}][{{$label3}}][{{$label4}}]" value="{{$value4}}">
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label>{{$fileName}}.{{$label}}.{{$label2}}.{{$label3}}</label>
                                                                        <input type="text" class="form-control" name="message[{{$fileName}}][{{$label}}][{{$label2}}][{{$label3}}]" value="{{$value3}}">
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label>{{$fileName}}.{{$label}}.{{$label2}}</label>
                                                                <input type="text" class="form-control" name="message[{{$fileName}}][{{$label}}][{{$label2}}]" value="{{$value2}}">
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @else
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label>{{$fileName}}.{{$label}}</label>
                                                        <input type="text" class="form-control" name="message[{{$fileName}}][{{$label}}]" value="{{$value}}">
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endforeach
                                </div>
                                <div class="col-lg-12 text-end">
                                    <button class="btn btn-primary" type="submit">{{ __('Save Changes')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
@endsection

@push('scripts')
    <script>
        $(document).on('click', '.lang-tab .nav-link', function() {
            $('.lang-tab .nav-link').removeClass('active');
            $('.tab-pane').removeClass('active');
            $(this).addClass('active');
            var id = $('.lang-tab .nav-link.active').attr('data-href');
            $(id).addClass('active');
        });
    </script>
@endpush
