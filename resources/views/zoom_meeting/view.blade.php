<div class="card-box">
    <div class="tab-content tab-bordered">
        <div class="tab-pane fade show active" id="tab-1" role="tabpanel">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-sm-4"><span class="h6 text-sm mb-0">{{__('Name')}}</span></dt>
                                <dd class="col-sm-8"><span class="text-sm">{{ $zoomMeeting->title }}</span></dd>

                                <dt class="col-sm-4"><span class="h6 text-sm mb-0">{{__('Meeting Id')}}</span></dt>
                                <dd class="col-sm-8"><span class="text-sm">{{$zoomMeeting->meeting_id}}</span></dd>
                                
                                <dt class="col-sm-4"><span class="h6 text-sm mb-0">{{__('Course')}}</span></dt>
                                <dd class="col-sm-8"><span class="text-sm">{{ !empty($zoomMeeting->getCourseInfo)?$zoomMeeting->getCourseInfo->title:'-' }}</span></dd>
                                
                                <dt class="col-sm-4"><span class="h6 text-sm mb-0">{{__('Student')}}</span></dt>
                                <dd class="col-sm-8"><span class="text-sm">{{!empty($zoomMeeting->getStudentInfo)?$zoomMeeting->getStudentInfo->name:'-'}}</span></dd>
                                
                                <dt class="col-sm-4"><span class="h6 text-sm mb-0">{{__('Start Date')}}</span></dt>
                                <dd class="col-sm-8"><span class="text-sm">{{ \Auth::user()->dateFormat($zoomMeeting->start_date) }}</span></dd>
                                
                                <dt class="col-sm-4"><span class="h6 text-sm mb-0">{{__('Duration')}}</span></dt>
                                <dd class="col-sm-8"><span class="text-sm">{{ $zoomMeeting->duration }}</span></dd>
                                
                                <dt class="col-sm-4"><span class="h6 text-sm mb-0">{{__('Start URl')}}</span></dt>
                                <dd class="col-sm-8"><span class="text-sm">@if($zoomMeeting->created_by == \Auth::user()->id && $zoomMeeting->checkDateTime())
                                    <a href="{{$zoomMeeting->start_url}}" target="_blank"> {{__('Start meeting')}} <i class="fas fa-external-link-square-alt "></i></a>
                                    @elseif($zoomMeeting->checkDateTime())
                                    <a href="{{$zoomMeeting->join_url}}" target="_blank"> {{__('Join meeting')}} <i class="fas fa-external-link-square-alt "></i></a>
                                    @else
                                    -
                                    @endif</span>
                                </dd>
                                    
                                <dt class="col-sm-4"><span class="h6 text-sm mb-0">{{__('Status')}}</span></dt>
                                <dd class="col-sm-8">@if($zoomMeeting->checkDateTime())
                                    @if($zoomMeeting->status == 'waiting')
                                    <span class="badge bg-info">{{ucfirst($zoomMeeting->status)}}</span>
                                    @else
                                    <span class="badge bg-success">{{ucfirst($zoomMeeting->status)}}</span>
                                    @endif
                                    @else
                                    <span class="badge bg-danger">{{__("End")}}</span>
                                    @endif
                                </dd>

                                <dt class="col-sm-4"><span class="h6 text-sm mb-0">{{__('Created')}}</span></dt>
                                <dd class="col-sm-8"><span class="text-sm">{{\Auth::user()->dateFormat($zoomMeeting->created_at)}}</span></dd>
                                
                            </dl>

                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

