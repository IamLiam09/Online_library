{{Form::model($productCategorie, array('route' => array('product_categorie.update', $productCategorie->id), 'method' => 'PUT')) }}
<div class="row">
    <div class="col-12">
        <div class="form-group">
            {{Form::label('name',__('Name'))}}
            {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Product Category')))}}
            @error('name')
            <span class="invalid-name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
            @enderror
        </div>
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
