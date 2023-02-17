@php( $store_logo=asset(Storage::url('uploads/store_logo/')))
{{Form::model($blog, array('route' => array('blog.update', $blog->id), 'method' => 'PUT','enctype'=>'multipart/form-data')) }}
<div class="row">
    <div class="col-12">
        <div class="form-group">
            {{Form::label('title',__('Title'))}}
            {{Form::text('title',null,array('class'=>'form-control','placeholder'=>__('Enter Title')))}}
            @error('title')
            <span class="invalid-title" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            {{-- <label for="blog_cover_image" class="form-control-label">{{ __('Blog Cover image') }}</label>
            <input type="file" name="blog_cover_image" id="blog_cover_image" class="custom-input-file">
            <label for="blog_cover_image">
                <i class="fa fa-upload"></i>
                <span>{{__('Choose a file')}}</span>
            </label> --}}
            <div class="form-file mb-3">
                <label for="blog_cover_image" class="form-label">{{ __('Blog Cover image') }}</label>
                <input type="file" class="form-control" name="blog_cover_image" id="blog_cover_image" aria-label="file example">
                <a href="{{$store_logo}}/{{$blog->blog_cover_image}}" target="_blank">
                    <img src="{{$store_logo}}/{{$blog->blog_cover_image}}" name="blog_cover_image" id="blog_cover_image"  alt="user-image" class="avatar avatar-lg mt-3">
                </a>
                <div class="invalid-feedback">{{ __('invalid form file') }}</div>
            </div>
        </div>
    </div>
    <div class="form-group col-md-12">
        {{Form::label('detail',__('Detail'),array('class'=>'form-control-label')) }}
        {{Form::textarea('detail',null,array('class'=>'form-control summernote-simple','rows'=>3,'placeholder'=>__('Detail')))}}
        {{-- {{Form::textarea('detail',null,array('class'=>'form-control pc-tinymce-2','rows'=>3,'placeholder'=>__('Detail')))}} --}}
        @error('detail')
        <span class="invalid-detail" role="alert">
             <strong class="text-danger">{{ $message }}</strong>
         </span>
        @enderror
    </div>
</div>
{{-- <div class="text-right">
    {{Form::submit(__('Update'),array('class'=>'btn btn-sm btn-primary rounded-pill'))}}
</div> --}}

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>
{{Form::close()}}

{{-- <script src="{{ asset('assets/js/plugins/tinymce/tinymce.min.js') }}"></script>
<script>
    if ($(".pc-tinymce-2").length) {
        tinymce.init({
            selector: '.pc-tinymce-2',
            height: "400",
            content_style: 'body { font-family: "Inter", sans-serif; }'
        });
    }
</script> --}}

