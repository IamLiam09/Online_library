{!! Form::open(['route' => 'setquiz.store','method' => 'post']) !!}
@csrf
<div class="row">
    <div class="form-group col-lg-6 col-md-6">
        {!! Form::label('title', __('Title'),['class'=>'form-control-label']) !!}
        {!! Form::text('title', null, ['class' => 'form-control','required' => 'required']) !!}
    </div>
    <div class="form-group col-lg-6 col-md-6">
        {!! Form::label('min_percentage', __('Min. Percentage'),['class'=>'form-control-label']) !!}
        {!! Form::text('min_percentage', null, ['class' => 'form-control','required' => 'required']) !!}
    </div>
    <div class="form-group col-md-6" id="course-content-2">
        {{Form::label('category',__('Select Category'),['class'=>'form-control-label'])}}
        <select class="form-control" name="category" id="category_id" placeholder="{{__('Select Category')}}">
            <option value="">{{__('Select Category')}}</option>
            @foreach($category as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-6" id="course-content-3">
        {{Form::label('subcategory',__('Select Subcategory'),['class'=>'form-control-label'])}}
        <select class="form-control" name="subcategory" id="subcategory" placeholder="{{__('Select Subcategory')}}">
            <option value="">{{__('Select category first')}}</option>
        </select>
    </div>
    <div class="form-group col-lg-12 col-md-12">
        {!! Form::label('instructions', __('Instructions'),['class'=>'form-control-label']) !!}
        {!! Form::text('instructions', null, ['class' => 'form-control','required' => 'required']) !!}
    </div>
    <div class="col-md-12">
        <div class="row">
            <div class="custom-control form-group col-md-5 mt-5 ml-3 custom-switch">
                <input type="checkbox" class="custom-control-input" id="customSwitches"  name="time">
                <label class="custom-control-label form-control-label" for="customSwitches">Per question time count</label>
            </div>
            <div class="form-group col-md-6 ml-auto d-none" id="time-count">
                {{Form::label('per_question_time',__('Per question time count(Minute)'),['class'=>'form-control-label'])}}
                {{Form::text('per_question_time',null,array('class'=>'form-control font-style'))}}
            </div>
            <div class="form-group col-md-6 ml-auto" id="total-time">
                {{Form::label('total_time',__('Total quiz time(Minute)'),['class'=>'form-control-label'])}}
                {{Form::text('total_time',null,array('class'=>'form-control font-style'))}}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="custom-control form-group col-md-12 mt-5 ml-3 custom-switch">
                <input type="checkbox" class="custom-control-input" id="customSwitches2"  name="question_review">
                <label class="custom-control-label form-control-label" for="customSwitches2">Question Review</label>
            </div>
        </div>
    </div>
    <div class="col-md-6" id="show-result">
        <div class="row">
            <div class="custom-control form-group col-md-12 mt-5 ml-3 custom-switch">
                <input type="checkbox" class="custom-control-input" id="customSwitches5"  name="result_after_submit">
                <label class="custom-control-label form-control-label" for="customSwitches5">Show result after each submit</label>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="custom-control form-group col-md-12 mt-5 ml-3 custom-switch">
                <input type="checkbox" class="custom-control-input" id="customSwitches3"  name="random_questions">
                <label class="custom-control-label form-control-label" for="customSwitches3">Random Questions</label>
            </div>
        </div>
    </div>
    <div class="w-100 mt-5 text-right">
        <button type="submit" class="btn btn-sm btn-primary rounded-pill mr-auto" id="submit-all">{{ __('Save') }}</button>
    </div>
</div>
{!! Form::close() !!}
