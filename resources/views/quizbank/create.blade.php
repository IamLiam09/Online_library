{!! Form::open(['route' => 'quizbank.store','method' => 'post']) !!}
<div class="row">
    <div class="form-group col-lg-12 col-md-12">
        {!! Form::label('question', __('Question'),['class'=>'form-control-label']) !!}
        {!! Form::text('question', null, ['class' => 'form-control','required' => 'required']) !!}
    </div>
    <div class="form-group col-lg-6 col-md-6">
        {{Form::label('quiz',__('Content of:'),array('class'=>'form-control-label')) }}
        {!! Form::select('quiz', $quiz, null,array('class' => 'form-control')) !!}
    </div>
    <div class="form-group col-md-6">
        {!! Form::label('option_count', __('Option Count'),['class'=>'form-control-label']) !!}
        <div class="row">
            <div class="col-md-8">
                <input class="form-control" type="text" id="option_count">
            </div>
            <div class="col-md-4">
                <a href="#" class="btn btn-primary btn-sm rounded-pill my-auto text-white" id="create_fields" data="count"><i class="ti ti-plus"></i></a>
            </div>
        </div>
    </div>
    <div class="form-group col-md-12 col-lg-12 col-sm-12" id="options_div">

    </div>
    <div class="w-100 text-right">
        <button type="submit" class="btn btn-sm btn-primary rounded-pill mr-auto" id="submit-all">{{ __('Save') }}</button>
    </div>
</div>
{!! Form::close() !!}
