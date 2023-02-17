{!! Form::open(array('route' => array('store_rating',$slug,$course_id,$tutor_id), 'method' => 'POST')) !!}

    <div class="input-wrapper">
        {{Form::label('name',__('Name')) }}
        {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Name'),'required'=>'required'))}} 
    </div>
    <div class="input-wrapper">
        {{Form::label('title',__('Title')) }}
        {{Form::text('title',null,array('class'=>'form-control','placeholder'=>__('Enter Title'),'required'=>'required'))}}  
    </div>
    <div class="input-wrapper">
        {{Form::label('title',__('Ratting')) }}
        <div id="rating_div">
            <div class="rate">
                <input type="radio" id="star5" name="rate" class="rating" value="5">
                <label for="star5" title="Very Happy"></label>
                <input type="radio" id="star4" name="rate" class="rating" value="4">
                <label for="star4" title="Somewhat Happy"></label>
                <input type="radio" id="star3" name="rate" class="rating" value="3">
                <label for="star3" title="Neither happy nor sad"></label>
                <input type="radio" id="star2" name="rate" class="rating" value="2">
                <label for="star2" title="Somewhat Sad"></label>
                <input type="radio" id="star1" name="rate" class="rating" value="1">
                <label for="star1" title="Very Sad"></label>
            </div>
        </div>
    </div>
    <div class="input-wrapper">
        {{Form::label('description',__('Description')) }}
        {{Form::textarea('description',null,array('class'=>'form-control','rows'=>3,'placeholder'=>__('Enter Description'),'required'=>'required'))}}
    </div>
    <div class="form-footer">
        <button type="submit" class="btn submit_button">{{ __('Save Changes') }}</button>
    </div>
{{Form::close()}}
