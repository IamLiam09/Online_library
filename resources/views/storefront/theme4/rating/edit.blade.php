{{Form::model($rating, array('route' => array('rating.update', $rating->id), 'method' => 'PUT')) }}
<div class="row">
    <div class="col-12">
        <div class="form-group">
            {{Form::label('name',__('Name')) }}
            {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Name'),'required'=>'required'))}}
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            {{Form::label('title',__('Title')) }}
            {{Form::text('title',null,array('class'=>'form-control','placeholder'=>__('Enter Title'),'required'=>'required'))}}
        </div>
    </div>
    <div class="col-sm-6 pb-2">
        {{Form::label('title',__('Ratting')) }}
        <div id="rating_div">
            <div class="rate pl-0">
                <input type="radio" class="rating" id="star5" name="rate" value="5" {{($rating->ratting == '5')?'checked':''}}>
                <label for="star5" title="text">5 stars</label>
                <input type="radio" class="rating" id="star4" name="rate" value="4" {{($rating->ratting == '4')?'checked':''}}>
                <label for="star4" title="text">4 stars</label>
                <input type="radio" class="rating" id="star3" name="rate" value="3" {{($rating->ratting == '3')?'checked':''}}>
                <label for="star3" title="text">3 stars</label>
                <input type="radio" class="rating" id="star2" name="rate" value="2" {{($rating->ratting == '2')?'checked':''}}>
                <label for="star2" title="text">2 stars</label>
                <input type="radio" class="rating" id="star1" name="rate" value="1" {{($rating->ratting == '1')?'checked':''}}>
                <label for="star1" title="text">1 star</label>
            </div>
        </div>
    </div>
    <div class="col-sm-6 pb-2">
        <div class="custom-control custom-switch mt-5">
            <input type="checkbox" class="custom-control-input rating_view" name="rating_view" id="enable_rating" {{($rating->rating_view == 'on')?'checked':''}}>
            <label class="custom-control-label form-control-label" for="enable_rating">Enable Rating</label>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            {{Form::label('description',__('Description')) }}
            {{Form::textarea('description',null,array('class'=>'form-control','rows'=>3,'placeholder'=>__('Enter Description'),'required'=>'required'))}}
        </div>
    </div>
    <div class="w-100 text-right">
        {{Form::submit(__('Save'),array('class'=>'btn btn-sm btn-primary rounded-pill mr-auto','id'=>'saverating'))}}{{Form::close()}}
    </div>
</div>
{{Form::close()}}
