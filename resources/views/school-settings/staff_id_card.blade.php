<div class="row">
    {{-- Profile Image Style --}}
    <div class="form-group col-sm-12 col-md-12">
        <label>{{ __('profile_image_style') }} <span class="text-danger">*</span></label>
        <div class="col-12 d-flex row">
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" @if (isset($settings['staff_profile_image_style']) && $settings['staff_profile_image_style'] == 'round') checked @endif
                        name="staff_profile_image_style" id="profile_image_style" value="round" required>
                    {{ __('round') }}
                </label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" @if (isset($settings['staff_profile_image_style']) && $settings['staff_profile_image_style'] == 'squre') checked @endif
                        name="staff_profile_image_style" id="profile_image_style" value="squre" required>
                    {{ __('squre') }}
                </label>
            </div>
        </div>
    </div>
    {{-- End Profile Image Style --}}

    {{-- Background Image --}}
    <div class="form-group col-sm-12 col-md-6">
        <label for="image">{{ __('background_image') }} </label>
        <input type="file" name="staff_background_image" accept="image/jpg,image/png,image/jpeg,image/svg"
            class="file-upload-default" />
        <div class="input-group col-xs-12">
            <input type="text" id="image" class="form-control file-upload-info" disabled=""
                placeholder="{{ __('image') }}" />
            <span class="input-group-append">
                <button class="file-upload-browse btn btn-theme" type="button">{{ __('upload') }}</button>
            </span>
        </div>
        @if ($settings['staff_background_image'] ?? '')
            <div id="background">
                <img src="{{ $settings['staff_background_image'] }}" class="img-fluid w-25" alt="">

                <div class="mt-2">
                    <a href="" data-type="staff_background"
                        class="btn btn-inverse-danger btn-sm id-card-settings">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
            </div>
        @endif
    </div>
    {{-- End Background Image --}}

    {{-- Fields --}}
    <div class="form-group col-sm-12 col-md-12">
        <label for="">{{ __('select_fields') }} <span class="text-danger">*</span></label>
    </div>

<div class="form-group col-sm-12 col-md-3">
    <div class="d-flex align-items-center">
        <input id="staff_name" class="feature-checkbox"
            @if (in_array('name', $settings['staff_id_card_fields'])) checked @endif
            type="checkbox" name="staff_id_card_fields[]" value="name" />

        <label class="feature-list text-center flex-grow-1 mb-0" for="staff_name">
            <span class="field-label-text" data-field="name">
                {{ $settings['staff_id_card_labels']['name'] ?? __('name') }}
            </span>

            <input type="text"
                   class="form-control form-control-sm field-label-edit d-none"
                   data-field="name"
                   value="{{ $settings['staff_id_card_labels']['name'] ?? __('name') }}"
                   style="margin-top:5px;">
        </label>

        <button type="button"
                class="btn btn-sm btn-link p-0 ml-1 edit-field-label"
                data-field="name"
                title="{{ __('Edit Field Name') }}">
            <i class="fa fa-edit"></i>
        </button>
    </div>
</div>

   <div class="form-group col-sm-12 col-md-3">
    <div class="d-flex align-items-center">
        <input id="staff_role" class="feature-checkbox"
            @if (in_array('role', $settings['staff_id_card_fields'])) checked @endif
            type="checkbox" name="staff_id_card_fields[]" value="role" />

        <label class="feature-list text-center flex-grow-1 mb-0" for="staff_role">
            <span class="field-label-text" data-field="role">
                {{ $settings['staff_id_card_labels']['role'] ?? __('role') }}
            </span>

            <input type="text"
                   class="form-control form-control-sm field-label-edit d-none"
                   data-field="role"
                   value="{{ $settings['staff_id_card_labels']['role'] ?? __('role') }}"
                   style="margin-top:5px;">
        </label>

        <button type="button"
                class="btn btn-sm btn-link p-0 ml-1 edit-field-label"
                data-field="role">
            <i class="fa fa-edit"></i>
        </button>
    </div>
</div>
<div class="form-group col-sm-12 col-md-3">
    <div class="d-flex align-items-center">
        <input id="staff_contact" class="feature-checkbox"
            @if (in_array('contact', $settings['staff_id_card_fields'])) checked @endif
            type="checkbox" name="staff_id_card_fields[]" value="contact" />

        <label class="feature-list text-center flex-grow-1 mb-0">
            <span class="field-label-text" data-field="contact">
                {{ $settings['staff_id_card_labels']['contact'] ?? __('contact') }}
            </span>

            <input type="text"
                   class="form-control form-control-sm field-label-edit d-none"
                   data-field="contact"
                   value="{{ $settings['staff_id_card_labels']['contact'] ?? __('contact') }}">
        </label>

        <button type="button" class="btn btn-sm btn-link edit-field-label" data-field="contact">
            <i class="fa fa-edit"></i>
        </button>
    </div>
</div>

    <div class="form-group col-sm-12 col-md-3">
    <div class="d-flex align-items-center">
        <input id="staff_email" class="feature-checkbox"
            @if (in_array('email', $settings['staff_id_card_fields'])) checked @endif
            type="checkbox" name="staff_id_card_fields[]" value="email" />

        <label class="feature-list text-center flex-grow-1 mb-0">
            <span class="field-label-text" data-field="email">
                {{ $settings['staff_id_card_labels']['email'] ?? __('email') }}
            </span>

            <input type="text"
                   class="form-control form-control-sm field-label-edit d-none"
                   data-field="email"
                   value="{{ $settings['staff_id_card_labels']['email'] ?? __('email') }}"
                   style="margin-top:5px;">
        </label>

        <button type="button" class="btn btn-sm btn-link edit-field-label" data-field="email">
            <i class="fa fa-edit"></i>
        </button>
    </div>
</div>

   <div class="form-group col-sm-12 col-md-3">
    <div class="d-flex align-items-center">
        <input id="staff_qualification" class="feature-checkbox"
            @if (in_array('qualification', $settings['staff_id_card_fields'])) checked @endif
            type="checkbox" name="staff_id_card_fields[]" value="qualification" />

        <label class="feature-list text-center flex-grow-1 mb-0">
            <span class="field-label-text" data-field="qualification">
                {{ $settings['staff_id_card_labels']['qualification'] ?? __('qualification') }}
            </span>

            <input type="text"
                   class="form-control form-control-sm field-label-edit d-none"
                   data-field="qualification"
                   value="{{ $settings['staff_id_card_labels']['qualification'] ?? __('qualification') }}">
        </label>

        <button type="button" class="btn btn-sm btn-link edit-field-label" data-field="qualification">
            <i class="fa fa-edit"></i>
        </button>
    </div>
</div>

  <div class="form-group col-sm-12 col-md-3">
    <div class="d-flex align-items-center">
        <input id="staff_dob" class="feature-checkbox"
            @if (in_array('dob', $settings['staff_id_card_fields'])) checked @endif
            type="checkbox" name="staff_id_card_fields[]" value="dob" />

        <label class="feature-list text-center flex-grow-1 mb-0">
            <span class="field-label-text" data-field="dob">
                {{ $settings['staff_id_card_labels']['dob'] ?? __('dob') }}
            </span>

            <input type="text"
                   class="form-control form-control-sm field-label-edit d-none"
                   data-field="dob"
                   value="{{ $settings['staff_id_card_labels']['dob'] ?? __('dob') }}">
        </label>

        <button type="button" class="btn btn-sm btn-link edit-field-label" data-field="dob">
            <i class="fa fa-edit"></i>
        </button>
    </div>
</div>

   <div class="form-group col-sm-12 col-md-3">
    <div class="d-flex align-items-center">
        <input id="staff_gender" class="feature-checkbox"
            @if (in_array('gender', $settings['staff_id_card_fields'])) checked @endif
            type="checkbox" name="staff_id_card_fields[]" value="gender" />

        <label class="feature-list text-center flex-grow-1 mb-0">
            <span class="field-label-text" data-field="gender">
                {{ $settings['staff_id_card_labels']['gender'] ?? __('gender') }}
            </span>

            <input type="text"
                   class="form-control form-control-sm field-label-edit d-none"
                   data-field="gender"
                   value="{{ $settings['staff_id_card_labels']['gender'] ?? __('gender') }}">
        </label>

        <button type="button" class="btn btn-sm btn-link edit-field-label" data-field="gender">
            <i class="fa fa-edit"></i>
        </button>
    </div>
</div>

   <div class="form-group col-sm-12 col-md-3">
    <div class="d-flex align-items-center">
        <input id="staff_session_year" class="feature-checkbox"
            @if (in_array('session_year', $settings['staff_id_card_fields'])) checked @endif
            type="checkbox" name="staff_id_card_fields[]" value="session_year" />

        <label class="feature-list text-center flex-grow-1 mb-0">
            <span class="field-label-text" data-field="session_year">
                {{ $settings['staff_id_card_labels']['session_year'] ?? __('session_year') }}
            </span>

            <input type="text"
                   class="form-control form-control-sm field-label-edit d-none"
                   data-field="session_year"
                   value="{{ $settings['staff_id_card_labels']['session_year'] ?? __('session_year') }}">
        </label>

        <button type="button" class="btn btn-sm btn-link edit-field-label" data-field="session_year">
            <i class="fa fa-edit"></i>
        </button>
    </div>
</div>

    <div class="form-group col-sm-12 col-md-3">
        <input id="staff_profile_image" class="feature-checkbox" @if (in_array('profile_image', $settings['staff_id_card_fields'])) checked @endif
            type="checkbox" name="staff_id_card_fields[]" value="profile_image" />
        <label class="feature-list text-center" for="staff_profile_image">{{ __('profile_image') }}</label>
    </div>

 <div class="form-group col-sm-12 col-md-3">
    <div class="d-flex align-items-center">
        <input id="staff_school_name" class="feature-checkbox"
            @if (in_array('school_name', $settings['staff_id_card_fields'])) checked @endif
            type="checkbox" name="staff_id_card_fields[]" value="school_name" />

        <label class="feature-list text-center flex-grow-1 mb-0">
            <span class="field-label-text" data-field="school_name">
                {{ $settings['staff_id_card_labels']['school_name'] ?? __('school_name') }}
            </span>

            <input type="text"
                   class="form-control form-control-sm field-label-edit d-none"
                   data-field="school_name"
                   value="{{ $settings['staff_id_card_labels']['school_name'] ?? __('school_name') }}">
        </label>

        <button type="button" class="btn btn-sm btn-link edit-field-label" data-field="school_name">
            <i class="fa fa-edit"></i>
        </button>
    </div>
</div>


    <div class="form-group col-sm-12 col-md-3">
        <input id="staff_school_logo" class="feature-checkbox" @if (in_array('school_logo', $settings['staff_id_card_fields'])) checked @endif
            type="checkbox" name="staff_id_card_fields[]" value="school_logo" />
        <label class="feature-list text-center" for="staff_school_logo">{{ __('school_logo') }}</label>
    </div>

  <div class="form-group col-sm-12 col-md-3">
    <div class="d-flex align-items-center">
        <input id="staff_school_address" class="feature-checkbox"
            @if (in_array('school_address', $settings['staff_id_card_fields'])) checked @endif
            type="checkbox" name="staff_id_card_fields[]" value="school_address" />

        <label class="feature-list text-center flex-grow-1 mb-0">
            <span class="field-label-text" data-field="school_address">
                {{ $settings['staff_id_card_labels']['school_address'] ?? __('school_address') }}
            </span>

            <input type="text"
                   class="form-control form-control-sm field-label-edit d-none"
                   data-field="school_address"
                   value="{{ $settings['staff_id_card_labels']['school_address'] ?? __('school_address') }}">
        </label>

        <button type="button" class="btn btn-sm btn-link edit-field-label" data-field="school_address">
            <i class="fa fa-edit"></i>
        </button>
    </div>
</div>


<div class="form-group col-sm-12 col-md-3">
    <div class="d-flex align-items-center">
        <input id="staff_signature" class="feature-checkbox"
            @if (in_array('signature', $settings['staff_id_card_fields'])) checked @endif
            type="checkbox" name="staff_id_card_fields[]" value="signature" />

        <label class="feature-list text-center flex-grow-1 mb-0">
            <span class="field-label-text" data-field="signature">
                {{ $settings['staff_id_card_labels']['signature'] ?? __('signature') }}
            </span>

            <input type="text"
                   class="form-control form-control-sm field-label-edit d-none"
                   data-field="signature"
                   value="{{ $settings['staff_id_card_labels']['signature'] ?? __('signature') }}">
        </label>

        <button type="button" class="btn btn-sm btn-link edit-field-label" data-field="signature">
            <i class="fa fa-edit"></i>
        </button>
    </div>
</div>

    {{-- End Fields --}}

    {{-- Extra form fields --}}
    @foreach ($formFields as $field)
        @if ($field->user_type == 2) <!-- 2 => Staff -->
            <div class="form-group col-sm-12 col-md-3">
                <input id="{{ $field->id }}" class="feature-checkbox" @if ($field->display_on_id) checked @endif
                    type="checkbox" name="extra_form_fields[]" value="{{ $field->id }}" />
                <label class="feature-list text-center" for="{{ $field->id }}">{{ $field->name }}</label>
            </div>
        @endif
    @endforeach
    {{-- End Fields --}}

    <div class="form-group col-sm-12 col-md-12">

    </div>

    {{-- Page Size --}}
    <div class="form-group col-sm-12 col-md-4">
        <label for="">{{ __('page_width') }} ({{ __('mm') }})<span class="text-danger">*</span></label>
        <input name="staff_page_width" id="staff_page_width" value="{{ $settings['staff_page_width'] ?? '' }}" type="number"
            required placeholder="{{ __('page_width') }}" class="form-control" />
        <small class="form-text text-muted">{{__('Standard ID card width is typically 100-105mm')}}</small>
    </div>

    <div class="form-group col-sm-12 col-md-4">
        <label for="">{{ __('page_height') }} ({{ __('mm') }})<span
                class="text-danger">*</span></label>
        <input name="staff_page_height" id="staff_page_height" value="{{ $settings['staff_page_height'] ?? '' }}" type="number"
            required placeholder="{{ __('page_height') }}" class="form-control" />
        <small class="form-text text-muted">{{__('Standard ID card height is typically 150-155mm')}}</small>
    </div>
    
    {{-- Cards Per A4 Page --}}
    <div class="form-group col-sm-12 col-md-4">
        <label for="staff_cards_per_page">{{ __('cards_per_page') }} <span class="text-danger">*</span></label>
        <select name="staff_cards_per_page" id="staff_cards_per_page" class="form-control" required>
            <option value="1" {{ ($settings['staff_cards_per_page'] ?? '1') == '1' ? 'selected' : '' }}>1 {{ __('card') }}</option>
            <option value="2" {{ ($settings['staff_cards_per_page'] ?? '') == '2' ? 'selected' : '' }}>2 {{ __('cards') }}</option>
            <option value="4" {{ ($settings['staff_cards_per_page'] ?? '') == '4' ? 'selected' : '' }}>4 {{ __('cards') }}</option>
            <option value="6" {{ ($settings['staff_cards_per_page'] ?? '') == '6' ? 'selected' : '' }}>6 {{ __('cards') }}</option>
            <option value="8" {{ ($settings['staff_cards_per_page'] ?? '') == '8' ? 'selected' : '' }}>8 {{ __('cards') }}</option>
        </select>
        <small class="form-text text-muted">{{ __('Number of ID cards to print per A4 page') }}</small>
    </div>
    {{-- End Page Size --}}

    {{-- Design Template --}}
    <div class="col-md-12 mt-4">
        <h4>{{ __('Design Template') }}</h4>
        <div class="design" id="staff_draggableElements">
            <div class="frame-border"></div>
            
            @if ($settings['staff_background_image'] ?? '')
                <img src="{{ $settings['staff_background_image'] }}" style="position: absolute; width: 100%; height: 100%; object-fit: cover; z-index: 1; pointer-events: none;" alt="">
            @endif

            {{-- Fields --}}
            <div class="draggableItem p-2 draggableText text-center" id="staff_item_name" style="position:absolute; @if (!in_array('name', $settings['staff_id_card_fields'] ?? [])) display:none; @endif {{ $settings['staff_id_card_styles']['name'] ?? '' }}">
                {{ __('name') }}
            </div>

            <div class="draggableItem p-2 draggableText text-center" id="staff_item_role" style="position:absolute; @if (!in_array('role', $settings['staff_id_card_fields'] ?? [])) display:none; @endif {{ $settings['staff_id_card_styles']['role'] ?? '' }}">
                {{ __('role') }}
            </div>

            <div class="draggableItem p-2 draggableText text-center" id="staff_item_contact" style="position:absolute; @if (!in_array('contact', $settings['staff_id_card_fields'] ?? [])) display:none; @endif {{ $settings['staff_id_card_styles']['contact'] ?? '' }}">
                {{ __('contact') }}
            </div>

            <div class="draggableItem p-2 draggableText text-center" id="staff_item_email" style="position:absolute; @if (!in_array('email', $settings['staff_id_card_fields'] ?? [])) display:none; @endif {{ $settings['staff_id_card_styles']['email'] ?? '' }}">
                {{ __('email') }}
            </div>

            <div class="draggableItem p-2 draggableText text-center" id="staff_item_qualification" style="position:absolute; @if (!in_array('qualification', $settings['staff_id_card_fields'] ?? [])) display:none; @endif {{ $settings['staff_id_card_styles']['qualification'] ?? '' }}">
                {{ __('qualification') }}
            </div>

            <div class="draggableItem p-2 draggableText text-center" id="staff_item_dob" style="position:absolute; @if (!in_array('dob', $settings['staff_id_card_fields'] ?? [])) display:none; @endif {{ $settings['staff_id_card_styles']['dob'] ?? '' }}">
                {{ __('dob') }}
            </div>

            <div class="draggableItem p-2 draggableText text-center" id="staff_item_gender" style="position:absolute; @if (!in_array('gender', $settings['staff_id_card_fields'] ?? [])) display:none; @endif {{ $settings['staff_id_card_styles']['gender'] ?? '' }}">
                {{ __('gender') }}
            </div>

            <div class="draggableItem p-2 draggableText text-center" id="staff_item_session_year" style="position:absolute; @if (!in_array('session_year', $settings['staff_id_card_fields'] ?? [])) display:none; @endif {{ $settings['staff_id_card_styles']['session_year'] ?? '' }}">
                {{ __('session_year') }}
            </div>

            {{-- Profile Image --}}
            <img id="staff_item_profile_image" class="draggableItem {{ ($settings['staff_profile_image_style'] ?? '') == 'round' ? 'rounded-style' : '' }}" 
                style="position:absolute; width:60px; height:60px; @if (!in_array('profile_image', $settings['staff_id_card_fields'] ?? [])) display:none; @else display:block; @endif {{ $settings['staff_id_card_styles']['profile_image'] ?? '' }}"
                src="{{ url('assets/dummy_logo.jpg') }}" alt="profile_image">

            {{-- School Name --}}
            <div class="draggableItem p-2 draggableText text-center" id="staff_item_school_name" style="position:absolute; @if (!in_array('school_name', $settings['staff_id_card_fields'] ?? [])) display:none; @endif {{ $settings['staff_id_card_styles']['school_name'] ?? '' }}">
                {{ __('school_name') }}
            </div>

            {{-- School Address --}}
            <div class="draggableItem p-2 draggableText text-center" id="staff_item_school_address" style="position:absolute; @if (!in_array('school_address', $settings['staff_id_card_fields'] ?? [])) display:none; @endif {{ $settings['staff_id_card_styles']['school_address'] ?? '' }}">
                {{ __('school_address') }}
            </div>

            {{-- School Logo --}}
            <img id="staff_item_school_logo" class="draggableItem" 
                style="position:absolute; width:40px; height:40px; @if (!in_array('school_logo', $settings['staff_id_card_fields'] ?? [])) display:none; @else display:block; @endif {{ $settings['staff_id_card_styles']['school_logo'] ?? '' }}"
                src="{{ $settings['vertical_logo'] ?? url('assets/dummy_logo.jpg') }}" alt="school_logo">

            {{-- Signature --}}
            <img id="staff_item_signature" class="draggableItem" 
                style="position:absolute; width:60px; height:30px; @if (!in_array('signature', $settings['staff_id_card_fields'] ?? [])) display:none; @else display:block; @endif {{ $settings['staff_id_card_styles']['signature'] ?? '' }}"
                src="{{ $settings['signature'] ?? url('assets/dummy_logo.jpg') }}" alt="signature">

            {{-- Extra Fields --}}
            @foreach ($formFields as $field)
                @if ($field->user_type == 2)
                    <div class="draggableItem p-2 draggableText text-center" id="staff_item_{{ $field->id }}" style="position:absolute; @if (!$field->display_on_id) display:none; @endif {{ $settings['staff_id_card_styles'][$field->id] ?? '' }}">
                        {{ $field->name }}
                    </div>
                @endif
            @endforeach

        </div>
    </div>

    {{-- Layout Type (hidden, default to vertical) --}}
    <input type="hidden" name="staff_layout_type" value="{{ $settings['staff_layout_type'] ?? 'vertical' }}">

    {{-- Hidden Inputs for Styles --}}
    <input type="hidden" name="staff_id_card_styles[name]" id="staff_style_name" value="{{ $settings['staff_id_card_styles']['name'] ?? '' }}">
    <input type="hidden" name="staff_id_card_styles[role]" id="staff_style_role" value="{{ $settings['staff_id_card_styles']['role'] ?? '' }}">
    <input type="hidden" name="staff_id_card_styles[contact]" id="staff_style_contact" value="{{ $settings['staff_id_card_styles']['contact'] ?? '' }}">
    <input type="hidden" name="staff_id_card_styles[email]" id="staff_style_email" value="{{ $settings['staff_id_card_styles']['email'] ?? '' }}">
    <input type="hidden" name="staff_id_card_styles[qualification]" id="staff_style_qualification" value="{{ $settings['staff_id_card_styles']['qualification'] ?? '' }}">
    <input type="hidden" name="staff_id_card_styles[dob]" id="staff_style_dob" value="{{ $settings['staff_id_card_styles']['dob'] ?? '' }}">
    <input type="hidden" name="staff_id_card_styles[gender]" id="staff_style_gender" value="{{ $settings['staff_id_card_styles']['gender'] ?? '' }}">
    <input type="hidden" name="staff_id_card_styles[session_year]" id="staff_style_session_year" value="{{ $settings['staff_id_card_styles']['session_year'] ?? '' }}">
    <input type="hidden" name="staff_id_card_styles[profile_image]" id="staff_style_profile_image" value="{{ $settings['staff_id_card_styles']['profile_image'] ?? '' }}">
    <input type="hidden" name="staff_id_card_styles[school_name]" id="staff_style_school_name" value="{{ $settings['staff_id_card_styles']['school_name'] ?? '' }}">
    <input type="hidden" name="staff_id_card_styles[school_address]" id="staff_style_school_address" value="{{ $settings['staff_id_card_styles']['school_address'] ?? '' }}">
    <input type="hidden" name="staff_id_card_styles[school_logo]" id="staff_style_school_logo" value="{{ $settings['staff_id_card_styles']['school_logo'] ?? '' }}">
    <input type="hidden" name="staff_id_card_styles[signature]" id="staff_style_signature" value="{{ $settings['staff_id_card_styles']['signature'] ?? '' }}">
    <input type="hidden" name="staff_id_card_styles[header]" id="staff_style_header" value="{{ $settings['staff_id_card_styles']['header'] ?? '' }}">
    <input type="hidden" name="staff_id_card_styles[footer]" id="staff_style_footer" value="{{ $settings['staff_id_card_styles']['footer'] ?? '' }}">

    @foreach ($formFields as $field)
        @if ($field->user_type == 2)
             <input type="hidden" name="staff_id_card_styles[{{ $field->id }}]" id="staff_style_{{ $field->id }}" value="{{ $settings['staff_id_card_styles'][$field->id] ?? '' }}">
        @endif
    @endforeach
<input type="hidden" name="staff_id_card_labels[name]" id="staff_id_card_labels_name" value="{{ $settings['staff_id_card_labels']['name'] ?? '' }}">
    <input type="hidden" name="staff_id_card_labels[role]" id="staff_id_card_labels_role" value="{{ $settings['staff_id_card_labels']['role'] ?? '' }}">
    <input type="hidden" name="staff_id_card_labels[contact]" id="staff_id_card_labels_contact" value="{{ $settings['staff_id_card_labels']['contact'] ?? '' }}">
    <input type="hidden" name="staff_id_card_labels[email]" id="staff_id_card_labels_email" value="{{ $settings['staff_id_card_labels']['email'] ?? '' }}">
    <input type="hidden" name="staff_id_card_labels[qualification]" id="staff_id_card_labels_qualification" value="{{ $settings['staff_id_card_labels']['qualification'] ?? '' }}">
    <input type="hidden" name="staff_id_card_labels[dob]" id="staff_id_card_labels_dob" value="{{ $settings['staff_id_card_labels']['dob'] ?? '' }}">
    <input type="hidden" name="staff_id_card_labels[gender]" id="staff_id_card_labels_gender" value="{{ $settings['staff_id_card_labels']['gender'] ?? '' }}">
    <input type="hidden" name="staff_id_card_labels[session_year]" id="staff_id_card_labels_session_year" value="{{ $settings['staff_id_card_labels']['session_year'] ?? '' }}">
    <input type="hidden" name="staff_id_card_labels[profile_image]" id="staff_id_card_labels_profile_image" value="{{ $settings['staff_id_card_labels']['profile_image'] ?? '' }}">
    <input type="hidden" name="staff_id_card_labels[school_name]" id="staff_id_card_labels_school_name" value="{{ $settings['staff_id_card_labels']['school_name'] ?? '' }}">
    <input type="hidden" name="staff_id_card_labels[school_address]" id="staff_id_card_labels_school_address" value="{{ $settings['staff_id_card_labels']['school_address'] ?? '' }}">
    <input type="hidden" name="staff_id_card_labels[school_logo]" id="staff_id_card_labels_school_logo" value="{{ $settings['staff_id_card_labels']['school_logo'] ?? '' }}">
    <input type="hidden" name="staff_id_card_labels[signature]" id="staff_id_card_labels_signature" value="{{ $settings['staff_id_card_labels']['signature'] ?? '' }}">
    <input type="hidden" name="staff_id_card_labels[header]" id="staff_id_card_labels_header" value="{{ $settings['staff_id_card_labels']['header'] ?? '' }}">
    <input type="hidden" name="staff_id_card_labels[footer]" id="staff_id_card_labels_footer" value="{{ $settings['staff_id_card_labels']['footer'] ?? '' }}">
</div>
