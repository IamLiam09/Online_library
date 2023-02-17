{{Form::model($subcategory,array('route' => array('subcategory.update', $subcategory->id), 'method' => 'PUT')) }}
<div class="row">
    <div class="form-group col-lg-6 col-md-6">
        {!! Form::label('name', __('Name'),['class'=>'form-label']) !!}
        {!! Form::text('name', null, ['class' => 'form-control','required' => 'required']) !!}
    </div>
    <div class="form-group col-lg-6 col-md-6">
        {!! Form::label('category', __('Category'),['class'=>'form-label']) !!}
        {!! Form::select('category',$category,null,array('class'=>'form-control' )) !!}
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

