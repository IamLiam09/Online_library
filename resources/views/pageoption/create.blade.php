{{Form::open(array('url'=>'custom-page','method'=>'post'))}}
<div class="row">
    <div class="col-12">
        <div class="form-group">
            {{Form::label('name',__('Name')) }}
            {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Name'),'required'=>'required'))}}
        </div>
    </div>
    <div class="form-group col-md-6">
        {{Form::label('enable_page_header',__('Page Header Display'),array('class'=>'form-check-label mb-3')) }}
        <div class="form-check form-check form-switch custom-control-inline">
            <input type="checkbox" class="form-check-input" name="enable_page_header" id="enable_page_header">
            <label class="form-check-label" for="enable_page_header"></label>
        </div>
    </div>
    <div class="form-group col-md-6">
        {{Form::label('enable_page_footer',__('Page Footer Display'),array('class'=>'form-check-label mb-3')) }}
        <div class="form-check form-check form-switch custom-control-inline">
            <input type="checkbox" class="form-check-input" name="enable_page_footer" id="enable_page_footer">
            <label class="form-check-label" for="enable_page_footer"></label>
        </div>
    </div>
    <div class="form-group col-md-12">
        {{Form::label('contents',__('Content'),array('class'=>'form-label')) }}
        {{Form::textarea('contents',null,array('class'=>'form-control summernote-simple','rows'=>3,'placeholder'=>__('Content')))}}
        {{-- {{Form::textarea('contents',null,array('class'=>'form-control pc-tinymce-2','rows'=>3,'placeholder'=>__('Content')))}} --}}
    </div>
    {{-- <div class="w-100 text-right">
        {{Form::submit(__('Save'),array('class'=>'btn btn-sm btn-primary rounded-pill mr-auto'))}}
    </div> --}}
</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
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
