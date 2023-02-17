@php
    $profile=asset(Storage::url('uploads/profile/'));
    //$default_avatar = asset(Storage::url('uploads/default_avatar/avatar.png'));
@endphp
<!-- User Proile Popup Start -->
       
    {{Form::model($userDetail,array('route' => array('student.profile.update',$slug,$userDetail), 'method' => 'put', 'enctype' => "multipart/form-data"))}}
        <div class="moda-form-container">
            <div class="form-container-title">
                <h5>{{__('Main Information')}}</h5>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-12">
                    <div class="form-group">
                        <label for="name">{{__('NAME')}}</label>
                        {{Form::text('name',null,array('class'=>'form-control font-style'))}}
                        @error('name')
                            <span class="invalid-name" role="alert">
                                <strong class="text-danger">{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-4  col-md-4 col-12">
                    <div class="form-group">
                        <label for="">{{__('Email')}}</label>
                        {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter User Email')))}}
                        @error('email')
                            <span class="invalid-email" role="alert">
                                <strong class="text-danger">{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-12">
                    <div class="form-group">
                        <label for="avtar">{{__('Avatar')}}</label>
                        <div class="upload-btn-wrapper">
                            <label for="file-1" class="file-upload btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="14"
                                    viewBox="0 0 15 14" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M5.19789 4.51798C5.61238 3.61134 6.50689 2.33329 8.39961 2.33329C10.3091 2.33329 11.6996 3.89119 11.6996 5.40599C11.6996 5.72816 11.9682 5.98932 12.2996 5.98932C12.458 5.98932 12.8569 6.08179 13.2105 6.34425C13.5379 6.58719 13.7996 6.95336 13.7996 7.51524C13.7996 7.96156 13.6483 8.42879 13.371 8.77234C13.1048 9.10204 12.7227 9.32239 12.1996 9.32239C11.8626 9.32239 11.6732 9.32306 11.58 9.32413C11.5568 9.3244 11.5369 9.32472 11.5212 9.32513C11.5138 9.32533 11.5038 9.32564 11.4934 9.32617L11.4854 9.32662C11.4808 9.3269 11.4752 9.32729 11.469 9.32782C11.4681 9.3279 11.4315 9.33049 11.388 9.33999C11.3749 9.34283 11.3491 9.34887 11.3175 9.35982L11.3167 9.36011C11.297 9.36692 11.2149 9.39535 11.1316 9.46387C11.0867 9.5048 10.9969 9.62178 10.959 9.6999C10.9284 9.80624 10.9307 10.0243 10.9629 10.1284C11.0483 10.3334 11.2207 10.4166 11.2446 10.4282L11.2465 10.4291C11.3249 10.468 11.3961 10.4805 11.4011 10.4814L11.4014 10.4815C11.4214 10.4854 11.4375 10.4875 11.4448 10.4884C11.4689 10.4913 11.49 10.4924 11.4942 10.4926L11.4947 10.4926C11.5012 10.493 11.5081 10.4933 11.5149 10.4935C11.5234 10.4938 11.5318 10.4941 11.539 10.4943C11.5943 10.4958 11.6911 10.4972 11.7698 10.4982L11.8742 10.4995L11.9063 10.4998L11.9152 10.4999L11.9175 10.4999L11.9181 10.4999L11.9183 10.4999L11.9183 10.4999L11.9184 10.4893H11.9185L11.9183 10.4999C11.9601 10.5004 12.001 10.4966 12.0404 10.4891L12.1996 10.4891C13.1255 10.4891 13.8434 10.0768 14.315 9.49263C14.7754 8.92227 14.9996 8.19426 14.9996 7.51524C14.9996 6.53033 14.5113 5.84188 13.9387 5.41689C13.5961 5.16265 13.2123 4.99361 12.8648 4.90288C12.5952 2.94789 10.7865 1.16663 8.39961 1.16663C6.06048 1.16663 4.84963 2.6279 4.27472 3.69535C2.99352 3.73287 2.07977 4.22599 1.48228 4.89254C0.84634 5.60197 0.599609 6.47274 0.599609 7.09313C0.599609 7.10321 0.599878 7.11328 0.600414 7.12334C0.667639 8.38373 1.13891 9.2458 1.77828 9.78813C2.40194 10.3171 3.13641 10.5 3.67461 10.5C3.88088 10.5 4.00556 10.4993 4.07541 10.4982C4.1029 10.4977 4.14172 10.4971 4.17296 10.4946C4.17564 10.4944 4.21094 10.492 4.25286 10.4833C4.26545 10.4807 4.28922 10.4755 4.31822 10.4661L4.31833 10.466C4.33698 10.46 4.40802 10.437 4.48424 10.3826C4.54489 10.3393 4.79458 10.1334 4.71483 9.78543C4.6565 9.53094 4.45523 9.42124 4.42845 9.40664L4.42663 9.40564C4.37126 9.3749 4.32351 9.36004 4.30642 9.35489C4.28381 9.34808 4.26523 9.34391 4.2544 9.34165C4.23277 9.33712 4.21536 9.33471 4.2075 9.33367C4.19048 9.33142 4.17637 9.33025 4.17027 9.32977C4.1329 9.32681 4.07032 9.32513 4.03897 9.32431L3.98533 9.32301L3.96846 9.32264L3.9637 9.32254L3.96239 9.32252L3.96202 9.32251L3.96191 9.32251L3.96187 9.32251L3.96166 9.3326H3.96163L3.96185 9.32251C3.9182 9.32164 3.87554 9.32533 3.83436 9.33312C3.78839 9.33323 3.73543 9.33329 3.67461 9.33329C3.38781 9.33329 2.94478 9.2293 2.56719 8.90902C2.20727 8.60373 1.85541 8.05834 1.79969 7.07896C1.80373 6.71399 1.9634 6.13153 2.38694 5.65905C2.79386 5.2051 3.47711 4.81499 4.62334 4.86383C4.86977 4.87433 5.0977 4.73713 5.19789 4.51798ZM3.96122 9.35246L3.96124 9.35246L3.94961 9.90572L3.96122 9.35246ZM8.39961 12.2502C8.39961 12.5724 8.13098 12.8335 7.79961 12.8335C7.46824 12.8335 7.19961 12.5724 7.19961 12.2502L7.19961 8.99158L6.42387 9.74577C6.18956 9.97358 5.80966 9.97358 5.57535 9.74577C5.34103 9.51797 5.34103 9.14862 5.57535 8.92081L7.37535 7.17081C7.48787 7.06142 7.64048 6.99996 7.79961 6.99996C7.95874 6.99996 8.11135 7.06142 8.22387 7.17081L10.0239 8.92081C10.2582 9.14862 10.2582 9.51797 10.0239 9.74577C9.78956 9.97358 9.40966 9.97358 9.17535 9.74577L8.39961 8.99158L8.39961 12.2502Z"
                                        fill="white" />
                                </svg>
                                {{__('Choose file here')}}
                            </label>
                            <input type="file" name="profile" id="file-1" class="file-input">
                        </div>

                    </div>
                </div>
            </div>
        </div>
        
        <div class="moda-form-container">
            <div class="form-container-title">
                <h5>{{__('Password Informations')}}</h5>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-12">
                    <div class="form-group">
                        <label for="current_password">{{__('Current Password')}}</label>
                        {{Form::password('current_password',array('class'=>'form-control','placeholder'=>__('Enter Current Password')))}}
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-12">
                    <div class="form-group">
                        <label for="new_password">{{__('New Password')}}</label>
                        {{Form::password('new_password',array('class'=>'form-control','placeholder'=>__('Enter New Password')))}}
                        @error('new_password')
                            <span class="invalid-new_password" role="alert">
                                <strong class="text-danger">{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-12">
                    <div class="form-group">
                        <label for="confirm_password">{{__('Re-type New Password')}}</label>
                        {{Form::password('confirm_password',array('class'=>'form-control','placeholder'=>__('Enter Re-type New Password')))}}
                        @error('confirm_password')
                            <span class="invalid-confirm_password" role="alert">
                                <strong class="text-danger">{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="form-footer">
            <button type="submit" class="btn">{{ __('Save Changes') }}</button>
        </div>
    {{Form::close()}}
           
<!-- User Proile Popup End -->