<h5>Question : {{$quiz->question}}</h5>
@foreach(explode(',',$quiz->options) as $key => $value)
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-prepend" style="height:60px;">
                <div class="input-group-text">
                    <input type="checkbox" class="mt-0" name="answer[{{$key}}]" {{(in_array($key,explode(',',$quiz->answer)))? 'checked' : ''}} disabled>
                </div>
            </div>
            <input type="text" class="form-control options"  name="options[{{$key}}]" value="{{$value}}" placeholder="Option " readonly>
        </div>
    </div>
@endforeach
<h5>Content of : {{$quiz->quiz_id->title}}</h5>
<b>Created at : {{ Utility::dateFormat($quiz->created_at)}}</b>
