@extends('layouts.admin')
@section('page-title')
    {{__('Zoom Meeting')}}
@endsection
@push('css-page')
<link rel="stylesheet" type="text/css" href="{{asset('css/daterangepicker.css')}}">
@endpush
@section('title')
    {{__('Zoom Meeting')}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Zoom Meeting') }}</li>
@endsection


@section('action-btn')
        <div class="btn btn-sm btn-primary btn-icon m-1 ">
            <a href="{{route('zoom-meeting.calender')}}" class="" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Calendar')}}"><i class="ti ti-calendar text-white"></i></a>
        </div>

        @if(\Auth::user()->type == 'Owner')
            <div class="btn btn-sm btn-primary btn-icon m-1 float-end">
                <a href="#" class="" id="add-user" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Create Meeting')}}" data-ajax-popup="true" data-size="lg" data-title="{{__('Create Meeting')}}" data-url="{{route('zoom-meeting.create')}}"><i class="ti ti-plus text-white"></i></a>
            </div>
        @endif
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
                                <th> {{ __('TITLE') }} </th>
                                @if(\Auth::user()->type == 'Owner')
                                <th> {{ __('COURSE') }}  </th>
                                <th> {{ __('STUDENT') }}  </th>
                                @endif
                                <th> {{ __('MEETING TIME') }} </th>
                                <th> {{ __('DURATION') }} </th>
                                <th> {{ __('JOIN URL') }} </th>
                                <th> {{ __('STATUS') }} </th>
                                @if(\Auth::user()->type == 'Owner')
                                <th class="text-right"> {{__('Action')}}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($meetings as $item)                    
                                <tr>
                                    <td>{{$item->title}}</td>
                                    @if(\Auth::user()->type == 'Owner')
                                    <td>{{$item->course_name}}</td>
                                    <td>
                                        <div class="avatar-group">
                                            @foreach($item->students($item->student_id) as $projectUser)                                            
                                                <a href="#" class="user-group1">
                                                    <img alt="" @if(!empty($users->avatar)) src="{{$profile.'/'.$projectUser->avatar}}" @else avatar="{{(!empty($projectUser) ? $projectUser->name:'')}}" @endif data-original-title="{{(!empty($projectUser)?$projectUser->name:'hello')}}" data-toggle="tooltip" data-original-title="{{(!empty($projectUser)?$projectUser->name:'')}}" class="">
                                                </a>
                                            @endforeach  
                                        </div>                      
                                    </td>
                                    @endif
                                    <td>{{$item->start_date}}</td>
                                    <td>{{$item->duration}} {{__("Minutes")}}</td>                       
                                    <td>
                                        @if($item->created_by == \Auth::user()->current_store && $item->checkDateTime())
                                        <a href="{{$item->start_url}}" target="_blank"> {{__('Start meeting')}} <i class="fas fa-external-link-square-alt "></i></a>
                                        @elseif($item->checkDateTime())
                                            <a href="{{$item->join_url}}" target="_blank"> {{__('Join meeting')}} <i class="fas fa-external-link-square-alt "></i></a>
                                        @else
                                            -
                                        @endif

                                    </td>
                                    <td>
                                        @if($item->checkDateTime())
                                            @if($item->status == 'waiting')
                                                <span class="badge bg-info p-2 px-3 rounded">{{ucfirst($item->status)}}</span>
                                            @else
                                                <span class="badge bg-success p-2 px-3 rounded">{{ucfirst($item->status)}}</span>
                                            @endif
                                        @else
                                            <span class="badge bg-danger p-2 px-3 rounded">{{__("End")}}</span>
                                        @endif
                                    </td>
                                    @if(\Auth::user()->type == 'Owner')
                                    <td class="text-right">
                                        {{-- <a  data-id="{{$item->id}}"  class="delete-icon member_remove" data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-trash"></i> </a> --}}

                                        <div class="action-btn bg-danger ms-2">
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['zoom-meeting.destroy', $item->id]]) !!}
                                                <a href="#!" class="mx-3 btn btn-sm  align-items-center show_confirm" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Delete')}}">
                                                    <i class="ti ti-trash text-white"></i>
                                                </a>
                                            {!! Form::close() !!}
                                        </div>

                                    </td>
                                    @endif
                                </tr>
                                @empty
                                
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
    

@endsection
@push('script-page')   
<script src="{{url('js/daterangepicker.js')}}"></script>
<script type="text/javascript">

$(document).on('change', '#course_id', function() {
    getStudents($(this).val());
});
// alert('hgjh');
function getStudents(id){

    $("#students-div").html('');
        $('#students-div').append('<select class="form-control" id="student_id" name="students[]"  multiple></select>');

    $.get("{{url('get-students')}}/"+id, function(data, status){

        var list = '';
        $('#student_id').empty();
        if(data.length > 0){
            list += "<option value=''>  </option>";
        }else{
            list += "<option value=''> {{__('No Students')}} </option>";
        }
        $.each(data, function(i, item) {

            list += "<option value='"+item.id+"'>"+item.name+"</option>"  
        });
        $('#student_id').html(list);
        var multipleCancelButton = new Choices('#student_id', {
                        removeItemButton: true,
        });
    });
}
    // $(document).on("click", '.member_remove', function () {
    //     var rid = $(this).attr('data-id');
    //     $('.swal2-confirm').addClass('m_remove');
    //     $('.swal2-confirm').attr('uid', rid);
    //     $('#cModal').modal('show');
        
    // });
    // $(document).on('click', '.m_remove', function (e) {
    
    //     var id = $(this).attr('uid');
    //     var p_url = "{{url('zoom-meeting')}}"+'/'+id;
    //     var data = {id: id};
    //     deleteAjax(p_url, data, function (res) {
    //         show_toastr('Success', res.msg , 'success');
    //         $('#cModal').modal('hide');
    //         setTimeout(function() { 
    //             location.reload();
    //     }, 1000);       
        
    //     });
    // });
</script>
@endpush