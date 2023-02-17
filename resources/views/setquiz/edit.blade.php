{{Form::model($quiz,array('route' => array('setquiz.update', $quiz->id), 'method' => 'PUT')) }}
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
        {{Form::label('category',__('Select Category'),['class'=>'form-control-label'])}}
        {!! Form::select('category',$category,null,array('class'=>'form-control','id'=>'category_id')) !!}
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
                <input type="checkbox" class="custom-control-input" id="customSwitches" name="time" {{ ($quiz->time == 'on')? 'checked' :''}}>
                {{Form::label('customSwitches',__('Per question time count'),['class'=>'custom-control-label form-control-label'])}}
            </div>
            <div class="form-group col-md-6 ml-auto {{ ($quiz->time == 'on')? '' :'d-none'}}" id="time-count">
                {{Form::label('per_question_time',__('Per question time count(Minute)'),['class'=>'form-control-label'])}}
                {{Form::text('per_question_time',null,array('class'=>'form-control font-style'))}}
            </div>
            <div class="form-group col-md-6 ml-auto {{ ($quiz->time == 'on')? 'd-none' :''}}" id="total-time">
                {{Form::label('total_time',__('Total quiz time(Minute)'),['class'=>'form-control-label'])}}
                {{Form::text('total_time',null,array('class'=>'form-control font-style'))}}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="custom-control form-group col-md-12 mt-5 ml-3 custom-switch">
                <input type="checkbox" class="custom-control-input" id="customSwitches2" name="question_review" {{ ($quiz->review == 'on')? 'checked' :''}}>
                {{Form::label('customSwitches2',__('Question Review'),['class'=>'custom-control-label form-control-label'])}}
            </div>
        </div>
    </div>
    <div class="col-md-6 {{ ($quiz->review == 'on')? 'd-none' :''}}" id="show-result">
        <div class="row">
            <div class="custom-control form-group col-md-12 mt-5 ml-3 custom-switch">
                <input type="checkbox" class="custom-control-input" id="customSwitches5"  name="result_after_submit" {{ ($quiz->result_after_submit	 == 'on')? 'checked' :''}}>
                <label class="custom-control-label form-control-label" for="customSwitches5">{{__('Show result after each submit')}}</label>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="custom-control form-group col-md-12 mt-5 ml-3 custom-switch">
                <input type="checkbox" class="custom-control-input" id="customSwitches3"  name="random_questions" {{ ($quiz->random_questions	 == 'on')? 'checked' :''}}>
                <label class="custom-control-label form-control-label" for="customSwitches3">{{__('Random Questions')}}</label>
            </div>
        </div>
    </div>
    <div class="w-100 mt-5 text-right">
        <button type="submit" class="btn btn-sm btn-primary rounded-pill mr-auto" id="submit-all">{{ __('Save') }}</button>
    </div>
</div>
{!! Form::close() !!}

<script>
    function getSubcategory(cid) {
      /*  console.log(cid);
        return false;*/
        $.ajax({
            url: '{{route('course.getsubcategory')}}',
            type: 'POST',
            data: {
                "category_id": cid, "_token": "{{ csrf_token() }}",
            },
            success: function (data) {
                $('#subcategory').empty();
                $('#subcategory').append('<option value="">{{__('Select Subcategory')}}</option>');

                $.each(data, function (key, value) {
                    var select = '';
                    if (key == '{{ $quiz->sub_category }}') {
                        select = 'selected';
                    }
                    $('#subcategory').append('<option value="' + key + '"' + select + '>' + value + '</option>');
                });
            }
        });
    }
    $(document).ready(function () {
        var c_id = $('#category_id').val();
        var category_id = '{{$quiz->sub_category}}';
        getSubcategory(c_id);
    });
    $(document).on('change', '#category_id', function () {
        var category_id = $(this).val();
        getSubcategory(category_id);
    });
</script>

