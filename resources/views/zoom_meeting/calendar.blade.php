@extends('layouts.admin')
@section('page-title')
    {{__('Zoom Meetings Calender')}}
@endsection
@push('css-page')
<link rel="stylesheet" href="{{asset('assets/css/plugins/main.css')}}">
{{-- <link rel="stylesheet" type="text/css" href="{{asset('assets/css/daterangepicker.css')}}"> --}}
@endpush

@section('title')
    {{__('Zoom Meeting Calender')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('zoom-meeting.index') }}">{{ __('Zoom Meeting') }}</a></li>
    <li class="breadcrumb-item">{{ __('Calender') }}</li>
@endsection

@section('action-btn')
    <a href="{{ route('zoom-meeting.index') }}" class="btn btn-sm btn-primary btn-icon m-1" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('List view')}}"><i class="ti ti-list"></i></a>

    @if(\Auth::user()->type=='Owner')
        <a href="#" class="btn btn-sm btn-primary btn-icon m-1" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Create Zoom Meeting')}}" data-ajax-popup="true" data-size="lg" data-title="{{__('Create Zoom Meeting')}}" data-url="{{route('zoom-meeting.create')}}"><i class="ti ti-plus text-white"></i></a>
    @endif
@endsection

@section('content')
        
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5>{{__('Calendar')}}</h5>
                </div>
                <div class="card-body">
                    <div id="calendar" class="calendar" data-toggle="calendar"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">            
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">{{ _('Meetings') }}</h4>
                    <ul class="event-cards list-group list-group-flush mt-3 w-100">
                        @foreach ($month_meetings as $meetings)
                            <li class="list-group-item card mb-3">
                                <div class="row align-items-center justify-content-between">
                                    <div class="col-auto mb-3 mb-sm-0">
                                        <div class="d-flex align-items-center">
                                            <div class="theme-avtar bg-primary">
                                                <i class="ti ti-video"></i>
                                            </div>
                                            <div class="ms-3">
                                                <h6 class="m-0">{{ $meetings->title }}</h6>
                                                <small class="text-muted">
                                                    {{ date('d M Y', strtotime($meetings->start_date)) }}
                                                    , {{ __('AT') }}
                                                    {{ date('h:i A', strtotime($meetings->start_date)) }}
                                                </small>                                            
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>    
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>


    

@endsection
@push('script-page')   
<script src="{{asset('assets/js/plugins/main.min.js')}}"></script>

    <script>
        
        (function () {
            var etitle;
            var etype;
            var etypeclass;
            var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                themeSystem: 'bootstrap',
                initialDate: '2022-10-01',
                slotDuration: '00:10:00',
                navLinks: true,
                droppable: true,
                selectable: true,
                selectMirror: true,
                editable: true,
                dayMaxEvents: true,
                handleWindowResize: true,
                events: {!! $calendar !!}            
            });
            calendar.render();

            $(document).on('click', '.fc-daygrid-event', function (e) {
                if (!$(this).hasClass('deal')) {
                    e.preventDefault();
                    var event = $(this);
                    var title = $(this).find('.fc-event-title').html();                        
                    var size = 'md';
                    var url = $(this).attr('href');
                    $("#commonModal .modal-title").html(title);
                    $("#commonModal .modal-dialog").addClass('modal-' + size);

                    $.ajax({
                        url: url,
                        success: function (data) {
                            $('#commonModal .modal-body').html(data);
                            $("#commonModal").modal('show');
                        },
                        error: function (data) {
                            data = data.responseJSON;
                            show_toastr('Error', data.error, 'error')
                        }
                    });
                }
            });
            
        })();

        
  
    </script>
@endpush