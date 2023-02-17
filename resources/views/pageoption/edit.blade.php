{{Form::model($pageOption, array('route' => array('custom-page.update', $pageOption->id), 'method' => 'PUT')) }}
<div class="row">
    <div class="col-12">
        <div class="form-group">
            {{Form::label('name',__('Name'))}}
            {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Name')))}}
            @error('name')
            <span class="invalid-name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="form-group col-md-6">
        {{Form::label('enable_page_header',__('Page Header Display'),array('class'=>'form-check-label mb-3')) }}
        <div class="form-check form-check form-switch custom-control-inline">
            <input type="checkbox" class="form-check-input" name="enable_page_header" id="enable_page_header" {{ ($pageOption['enable_page_header'] == 'on') ? 'checked=checked' : '' }}>
            <label class="form-check-label" for="enable_page_header"></label>
        </div>
    </div>
    <div class="form-group col-md-6">
        {{Form::label('enable_page_footer',__('Page Footer Display'),array('class'=>'form-check-label mb-3')) }}
        <div class="form-check form-check form-switch custom-control-inline">
            <input type="checkbox" class="form-check-input" name="enable_page_footer" id="enable_page_footer" {{ ($pageOption['enable_page_footer'] == 'on') ? 'checked=checked' : '' }}>
            <label class="form-check-label" for="enable_page_footer"></label>
        </div>
    </div>
    <div class="form-group col-md-12">
        {{Form::label('contents',__('Contents'),array('class'=>'form-label')) }}
        {{-- {{Form::textarea('contents',null,array('class'=>'form-control pc-tinymce-2','rows'=>3,'placeholder'=>__('Contents')))}} --}}
        {{Form::textarea('contents',null,array('class'=>'form-control summernote-simple','rows'=>3,'placeholder'=>__('Contents')))}}
        @error('contents')
        <span class="invalid-contents" role="alert">
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
