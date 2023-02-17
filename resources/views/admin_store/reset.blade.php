{{Form::model($employee,array('route' => array('store.password.update', $employee->id), 'method' => 'post')) }}
    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('password', __('Password')) }}
        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
        @error('password')
        <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('password_confirmation', __('Confirm Password')) }}
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
        {{Form::submit(__('Update'),array('class'=>'btn btn-primary'))}}
    </div>

{{ Form::close() }}
