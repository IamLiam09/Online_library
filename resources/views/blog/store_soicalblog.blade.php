{{Form::open(array('url'=>'store-social-blog','method'=>'post','enctype'=>'multipart/form-data','id'=>'store_blog_from'))}}
    <div class="row">
        <div class="form-group col-md-12 enable_blog_button">
            {{Form::label('enable_social_button',__('Enable Blog Social Button'),array('class'=>'form-check-label mb-1')) }}
            <div class="form-check form-switch custom-control-inline">
                <input type="checkbox" class="form-check-input" name="enable_social_button" id="enable_social_button">
                <label class="form-check-label" for="enable_social_button"></label>
            </div>
        </div>
        <div class="social-btn p-2 sub_social_button">
            <div class="row">
                <div class="col-6 mb-2">
                    {{Form::label('enable_whatsapp',__('Enable Whatsapp'),array('class'=>'form-check-label mb-1')) }}
                    <div class="form-check form-switch custom-control-inline">
                        <input type="checkbox" class="form-check-input" name="enable_whatsapp" id="enable_whatsapp">
                        <label class="form-check-label" for="enable_whatsapp"></label>
                    </div>
                </div>
                <div class="col-6 mb-2">
                    {{Form::label('enable_facebook',__('Enable Facebook'),array('class'=>'form-check-label mb-1')) }}
                    <div class="form-check form-switch custom-control-inline">
                        <input type="checkbox" class="form-check-input" name="enable_facebook" id="enable_facebook">
                        <label class="form-check-label" for="enable_facebook"></label>
                    </div>
                </div>
                <div class="col-6 mb-2">
                    {{Form::label('enable_linkedIn',__('Enable LinkedIn'),array('class'=>'form-check-label mb-1')) }}
                    <div class="form-check form-switch custom-control-inline">
                        <input type="checkbox" class="form-check-input" name="enable_linkedIn" id="enable_linkedIn">
                        <label class="form-check-label" for="enable_linkedIn"></label>
                    </div>
                </div>
                <div class="col-6 mb-2">
                    {{Form::label('enable_googleplus',__('Enable Google'),array('class'=>'form-check-label mb-1')) }}
                    <div class="form-check form-switch custom-control-inline">
                        <input type="checkbox" class="form-check-input" name="enable_googleplus" id="enable_googleplus">
                        <label class="form-check-label" for="enable_googleplus"></label>
                    </div>
                </div>
                <div class="col-6 mb-2">
                    {{Form::label('enable_email',__('Enable Email'),array('class'=>'form-check-label mb-1')) }}
                    <div class="form-check form-switch custom-control-inline">
                        <input type="checkbox" class="form-check-input" name="enable_email" id="enable_email">
                        <label class="form-check-label" for="enable_email"></label>
                    </div>
                </div>
                <div class="col-6 mb-2">
                    {{Form::label('enable_twitter',__('Enable Twitter'),array('class'=>'form-check-label mb-1')) }}
                    <div class="form-check form-switch custom-control-inline">
                        <input type="checkbox" class="form-check-input" name="enable_twitter" id="enable_twitter">
                        <label class="form-check-label" for="enable_twitter"></label>
                    </div>
                </div>
                <div class="col-6 mb-2">
                    {{Form::label('enable_pinterest',__('Enable Pinterest'),array('class'=>'form-check-label mb-1')) }}
                    <div class="form-check form-switch custom-control-inline">
                        <input type="checkbox" class="form-check-input" name="enable_pinterest" id="enable_pinterest">
                        <label class="form-check-label" for="enable_pinterest"></label>
                    </div>
                </div>
                <div class="col-6 mb-2">
                    {{Form::label('enable_stumbleupon',__('Enable Stumbleupon'),array('class'=>'form-check-label mb-1')) }}
                    <div class="form-check form-switch custom-control-inline">
                        <input type="checkbox" class="form-check-input" name="enable_stumbleupon" id="enable_stumbleupon">
                        <label class="form-check-label" for="enable_stumbleupon"></label>
                    </div>
                </div>
            </div>
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


