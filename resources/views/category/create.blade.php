{!! Form::open(['route' => 'category.store','method' => 'post', 'enctype'=>'multipart/form-data']) !!}
<div class="row">
    <div class="form-group col-lg-6 col-md-6">
        {!! Form::label('name', __('Name'),['class'=>'form-label']) !!}
        {!! Form::text('name', null, ['class' => 'form-control','required' => 'required']) !!}
    </div>
    <div class="form-group col-lg-6">
        <div class="col-12">
            {{-- <div class="form-group">
                <label for="category_image" class="form-label">{{ __('Upload category_image') }}</label>
                <input type="file" name="category_image" id="category_image" class="custom-input-file">
                <label for="category_image">
                    <i class="fa fa-upload"></i>
                    <span>{{__('Choose a image')}}</span>
                </label>
            </div> --}}

            <div class="form-file mb-3">
                <label for="category_image" class="form-label">{{ __('Upload category_image') }}</label>
                {{-- <input type="file" class="form-control" name="category_image" id="category_image" aria-label="file example" required> --}}
                <input type="file" class="form-control mb-2" name="category_image" id="category_image" aria-label="file example" onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                {{-- <img id="blah" alt="your image" width="100" height="100" /> --}}
                <img src="" id="blah" width="25%"/>
                <div class="invalid-feedback">{{ __('invalid form file') }}</div>
            </div>

        </div>
    </div>
    {{-- <div class="w-100 text-right">
        <button type="submit" class="btn btn-sm btn-primary rounded-pill mr-auto" id="submit-all">{{ __('Save') }}</button>
    </div> --}}
</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Save') }}" class="btn btn-primary" id="submit-all">
</div>

{!! Form::close() !!}

