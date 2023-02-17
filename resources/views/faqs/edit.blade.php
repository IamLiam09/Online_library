{{Form::model($faq,array('route' => array('faqs.update',[$faq->id,$course_id]), 'method' => 'PUT')) }}
<div class="row">
    <div class="form-group col-lg-12 col-md-12">
        {!! Form::label('question', __('Question'),['class'=>'form-label']) !!}
        {!! Form::text('question', null, ['class' => 'form-control','required' => 'required']) !!}
    </div>
    <div class="form-group col-lg-12 col-md-12">
        {!! Form::label('answer', __('Answer'),['class'=>'form-label']) !!}
        {!! Form::textarea('answer', null, ['class' => 'form-control','required' => 'required']) !!}
    </div>
    {{-- <div class="w-100 text-right">
        <button type="submit" class="btn btn-sm btn-primary rounded-pill mr-auto" id="submit-all">{{ __('Save') }}</button>
    </div> --}}
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Save') }}" class="btn btn-primary" id="submit-all">
</div>
{!! Form::close() !!}

