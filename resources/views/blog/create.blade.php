{{Form::open(array('url'=>'blog','method'=>'post','enctype'=>'multipart/form-data'))}}
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                {{Form::label('title',__('Title'),array('class'=>'form-label')) }}
                {{Form::text('title',null,array('class'=>'form-control','placeholder'=>__('Enter Title'),'required'=>'required'))}}
            </div>
        </div>
        <div class="col-12">
            {{-- <div class="form-group">
                <label for="blog_cover_image" class="form-label">{{ __('Blog Cover image') }}</label>
                <input type="file" name="blog_cover_image" id="blog_cover_image" class="custom-input-file">
                <label for="blog_cover_image">
                    <i class="fa fa-upload"></i>
                    <span>{{__('Choose a file')}}</span>
                </label>
            </div> --}}
            <div class="form-group">
                <div class="form-file mb-3">
                    <label for="blog_cover_image" class="form-label">{{ __('Blog Cover image') }}</label>
                    <input type="file" class="form-control" name="blog_cover_image" id="blog_cover_image" aria-label="file example">
                    {{-- <input type="file" class="form-control mb-2" name="blog_cover_image" id="blog_cover_image" aria-label="file example" onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                    <img src="" id="blah" width="25%"/> --}}
                    <div class="invalid-feedback">{{ __('invalid form file') }}</div>
                </div>
            </div>

        </div>
        <div class="form-group col-md-12">
            {{Form::label('detail',__('Detail'),array('class'=>'form-label')) }}
            {{Form::textarea('detail',null,array('class'=>'form-control summernote-simple','rows'=>3,'placeholder'=>__('Detail')))}}
            {{-- {{Form::textarea('detail',null,array('class'=>'form-control pc-tinymce-2','rows'=>3,'placeholder'=>__('Detail')))}} --}}
        </div>
        {{-- <div class="w-100 text-right">
            {{Form::submit(__('Save'),array('class'=>'btn btn-sm btn-primary rounded-pill mr-auto'))}}
        </div> --}}
    </div>
    <div class="modal-footer">
        <input type="button" value="{{ __('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{ __('Save') }}" class="btn btn-primary">
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

