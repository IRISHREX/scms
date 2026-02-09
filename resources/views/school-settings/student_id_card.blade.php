<div class="row">

    {{-- Profile Image Style --}}
    <div class="form-group col-sm-12 col-md-12">
        <label>{{ __('profile_image_style') }} <span class="text-danger">*</span></label>
        <div class="col-12 d-flex row">
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" @if (isset($settings['profile_image_style']) && $settings['profile_image_style'] == 'round') checked @endif
                        name="profile_image_style" id="profile_image_style" value="round" required>
                    {{ __('round') }}
                </label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" @if (isset($settings['profile_image_style']) && $settings['profile_image_style'] == 'squre') checked @endif
                        name="profile_image_style" id="profile_image_style" value="squre" required>
                    {{ __('squre') }}
                </label>
            </div>
        </div>
    </div>
    {{-- End Profile Image Style --}}

    {{-- Background Image --}}
    <div class="form-group col-sm-12 col-md-6">
        <label for="image">{{ __('background_image') }} </label>
        <input type="file" name="background_image" accept="image/jpg,image/png,image/jpeg,image/svg"
            class="file-upload-default" />
        <div class="input-group col-xs-12">
            <input type="text" id="image" class="form-control file-upload-info" disabled=""
                placeholder="{{ __('image') }}" />
            <span class="input-group-append">
                <button class="file-upload-browse btn btn-theme" type="button">{{ __('upload') }}</button>
            </span>
        </div>
        @if ($settings['background_image'] ?? '')
            <div id="background">
                <img src="{{ $settings['background_image'] }}" class="img-fluid w-25" alt="">

                <div class="mt-2">
                    <a href="" data-type="background"
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
        <input id="student_name" class="feature-checkbox" @if (in_array('student_name', $settings['student_id_card_fields'])) checked @endif
            type="checkbox" name="student_id_card_fields[]" value="student_name" />
        <label class="feature-list text-center" for="student_name">{{ __('student_name') }}</label>
    </div>
    <div class="form-group col-sm-12 col-md-3">
        <input id="class_section" class="feature-checkbox" @if (in_array('class_section', $settings['student_id_card_fields'])) checked @endif
            type="checkbox" name="student_id_card_fields[]" value="class_section" />
        <label class="feature-list text-center" for="class_section">{{ __('class_section') }}</label>
    </div>
    <div class="form-group col-sm-12 col-md-3">
        <input id="roll_number" class="feature-checkbox" type="checkbox"
            @if (in_array('roll_no', $settings['student_id_card_fields'])) checked @endif name="student_id_card_fields[]" value="roll_no" />
        <label class="feature-list text-center" for="roll_number">{{ __('roll_no') }}</label>
    </div>
    <div class="form-group col-sm-12 col-md-3">
        <input id="dob" class="feature-checkbox" type="checkbox"
            @if (in_array('dob', $settings['student_id_card_fields'])) checked @endif name="student_id_card_fields[]" value="dob" />
        <label class="feature-list text-center" for="dob">{{ __('dob') }}</label>
    </div>
    <div class="form-group col-sm-12 col-md-3">
        <input id="gender" class="feature-checkbox" type="checkbox"
            @if (in_array('gender', $settings['student_id_card_fields'])) checked @endif name="student_id_card_fields[]" value="gender" />
        <label class="feature-list text-center" for="gender">{{ __('gender') }}</label>
    </div>
    <div class="form-group col-sm-12 col-md-3">
        <input id="session_year" class="feature-checkbox" type="checkbox"
            @if (in_array('session_year', $settings['student_id_card_fields'])) checked @endif name="student_id_card_fields[]" value="session_year" />
        <label class="feature-list text-center" for="session_year">{{ __('session_year') }}</label>
    </div>
    <div class="form-group col-sm-12 col-md-3">
        <input id="guardian_name" class="feature-checkbox" type="checkbox"
            @if (in_array('guardian_name', $settings['student_id_card_fields'])) checked @endif name="student_id_card_fields[]"
            value="guardian_name" />
        <label class="feature-list text-center" for="guardian_name">{{ __('guardian') }} {{ __('name') }}</label>
    </div>
    <div class="form-group col-sm-12 col-md-3">
        <input id="guardian_contact" class="feature-checkbox" @if (in_array('guardian_contact', $settings['student_id_card_fields'])) checked @endif
            type="checkbox" name="student_id_card_fields[]" value="guardian_contact" />
        <label class="feature-list text-center" for="guardian_contact">{{ __('guardian') }}
            {{ __('contact') }}</label>
    </div>

    <div class="form-group col-sm-12 col-md-3">
        <input id="profile_image" class="feature-checkbox" @if (in_array('profile_image', $settings['student_id_card_fields'])) checked @endif
            type="checkbox" name="student_id_card_fields[]" value="profile_image" />
        <label class="feature-list text-center" for="profile_image">{{ __('profile_image') }}</label>
    </div>

    <div class="form-group col-sm-12 col-md-3">
        <input id="school_name" class="feature-checkbox" @if (in_array('school_name', $settings['student_id_card_fields'])) checked @endif
            type="checkbox" name="student_id_card_fields[]" value="school_name" />
        <label class="feature-list text-center" for="school_name">{{ __('school_name') }}</label>
    </div>

    <div class="form-group col-sm-12 col-md-3">
        <input id="school_logo" class="feature-checkbox" @if (in_array('school_logo', $settings['student_id_card_fields'])) checked @endif
            type="checkbox" name="student_id_card_fields[]" value="school_logo" />
        <label class="feature-list text-center" for="school_logo">{{ __('school_logo') }}</label>
    </div>

    <div class="form-group col-sm-12 col-md-3">
        <input id="school_address" class="feature-checkbox" @if (in_array('school_address', $settings['student_id_card_fields'])) checked @endif
            type="checkbox" name="student_id_card_fields[]" value="school_address" />
        <label class="feature-list text-center" for="school_address">{{ __('school_address') }}</label>
    </div>

    <div class="form-group col-sm-12 col-md-3">
        <input id="student_signature" class="feature-checkbox" @if (in_array('signature', $settings['student_id_card_fields'])) checked @endif
            type="checkbox" name="student_id_card_fields[]" value="signature" />
        <label class="feature-list text-center" for="student_signature">{{ __('signature') }}</label>
    </div>

    {{-- Extra form fields --}}
    @foreach ($formFields as $field)
        @if ($field->user_type == 1) <!-- 1 => Student -->
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
        <input name="page_width" id="student_page_width" value="{{ $settings['page_width'] ?? '' }}" type="number"
            required placeholder="{{ __('page_width') }}" class="form-control" />
        <small class="form-text text-muted">{{__('Standard ID card width is typically 100-105mm')}}</small>
    </div>

    <div class="form-group col-sm-12 col-md-4">
        <label for="">{{ __('page_height') }} ({{ __('mm') }})<span
                class="text-danger">*</span></label>
        <input name="page_height" id="student_page_height" value="{{ $settings['page_height'] ?? '' }}" type="number"
            required placeholder="{{ __('page_height') }}" class="form-control" />
        <small class="form-text text-muted">{{__('Standard ID card height is typically 150-155mm')}}</small>
    </div>
    
    {{-- Cards Per A4 Page --}}
    <div class="form-group col-sm-12 col-md-4">
        <label for="cards_per_page">{{ __('cards_per_page') }} <span class="text-danger">*</span></label>
        <select name="cards_per_page" id="student_cards_per_page" class="form-control" required>
            <option value="1" {{ ($settings['cards_per_page'] ?? '1') == '1' ? 'selected' : '' }}>1 {{ __('card') }}</option>
            <option value="2" {{ ($settings['cards_per_page'] ?? '') == '2' ? 'selected' : '' }}>2 {{ __('cards') }}</option>
            <option value="4" {{ ($settings['cards_per_page'] ?? '') == '4' ? 'selected' : '' }}>4 {{ __('cards') }}</option>
            <option value="6" {{ ($settings['cards_per_page'] ?? '') == '6' ? 'selected' : '' }}>6 {{ __('cards') }}</option>
            <option value="8" {{ ($settings['cards_per_page'] ?? '') == '8' ? 'selected' : '' }}>8 {{ __('cards') }}</option>
        </select>
        <small class="form-text text-muted">{{ __('Number of ID cards to print per A4 page') }}</small>
    </div>
    {{-- End Page Size --}}

    {{-- Design Template --}}
    <div class="col-md-12 mt-4">
        <h4>{{ __('Design Template') }}</h4>
        <div class="design" id="student_draggableElements">
            <div class="frame-border"></div>
            
            @if ($settings['background_image'] ?? '')
                <img src="{{ $settings['background_image'] }}" style="position: absolute; width: 100%; height: 100%; object-fit: cover; z-index: 1; pointer-events: none;" alt="">
            @endif

            {{-- Fields --}}
            <div class="draggableItem p-2 draggableText text-center" id="student_item_student_name" style="position:absolute; @if (!in_array('student_name', $settings['student_id_card_fields'] ?? [])) display:none; @endif {{ $settings['student_id_card_styles']['student_name'] ?? '' }}">
                {{ __('student_name') }}
            </div>
            
            <div class="draggableItem p-2 draggableText text-center" id="student_item_class_section" style="position:absolute; @if (!in_array('class_section', $settings['student_id_card_fields'] ?? [])) display:none; @endif {{ $settings['student_id_card_styles']['class_section'] ?? '' }}">
                {{ __('class_section') }}
            </div>

            <div class="draggableItem p-2 draggableText text-center" id="student_item_roll_no" style="position:absolute; @if (!in_array('roll_no', $settings['student_id_card_fields'] ?? [])) display:none; @endif {{ $settings['student_id_card_styles']['roll_no'] ?? '' }}">
                {{ __('roll_no') }}
            </div>

            <div class="draggableItem p-2 draggableText text-center" id="student_item_dob" style="position:absolute; @if (!in_array('dob', $settings['student_id_card_fields'] ?? [])) display:none; @endif {{ $settings['student_id_card_styles']['dob'] ?? '' }}">
                {{ __('dob') }}
            </div>

            <div class="draggableItem p-2 draggableText text-center" id="student_item_gender" style="position:absolute; @if (!in_array('gender', $settings['student_id_card_fields'] ?? [])) display:none; @endif {{ $settings['student_id_card_styles']['gender'] ?? '' }}">
                {{ __('gender') }}
            </div>

            <div class="draggableItem p-2 draggableText text-center" id="student_item_session_year" style="position:absolute; @if (!in_array('session_year', $settings['student_id_card_fields'] ?? [])) display:none; @endif {{ $settings['student_id_card_styles']['session_year'] ?? '' }}">
                {{ __('session_year') }}
            </div>

            <div class="draggableItem p-2 draggableText text-center" id="student_item_guardian_name" style="position:absolute; @if (!in_array('guardian_name', $settings['student_id_card_fields'] ?? [])) display:none; @endif {{ $settings['student_id_card_styles']['guardian_name'] ?? '' }}">
                {{ __('guardian') }} {{ __('name') }}
            </div>

            <div class="draggableItem p-2 draggableText text-center" id="student_item_guardian_contact" style="position:absolute; @if (!in_array('guardian_contact', $settings['student_id_card_fields'] ?? [])) display:none; @endif {{ $settings['student_id_card_styles']['guardian_contact'] ?? '' }}">
                {{ __('guardian') }} {{ __('contact') }}
            </div>

            {{-- Profile Image --}}
            <img id="student_item_profile_image" class="draggableItem {{ ($settings['profile_image_style'] ?? '') == 'round' ? 'rounded-style' : '' }}" 
                style="position:absolute; width:60px; height:60px; @if (!in_array('profile_image', $settings['student_id_card_fields'] ?? [])) display:none; @else display:block; @endif {{ $settings['student_id_card_styles']['profile_image'] ?? '' }}"
                src="{{ url('assets/dummy_logo.jpg') }}" alt="profile_image">

            {{-- School Name --}}
            <div class="draggableItem p-2 draggableText text-center" id="student_item_school_name" style="position:absolute; @if (!in_array('school_name', $settings['student_id_card_fields'] ?? [])) display:none; @endif {{ $settings['student_id_card_styles']['school_name'] ?? '' }}">
                {{ __('school_name') }}
            </div>

            {{-- School Address --}}
            <div class="draggableItem p-2 draggableText text-center" id="student_item_school_address" style="position:absolute; @if (!in_array('school_address', $settings['student_id_card_fields'] ?? [])) display:none; @endif {{ $settings['student_id_card_styles']['school_address'] ?? '' }}">
                {{ __('school_address') }}
            </div>

            {{-- School Logo --}}
            <img id="student_item_school_logo" class="draggableItem" 
                style="position:absolute; width:40px; height:40px; @if (!in_array('school_logo', $settings['student_id_card_fields'] ?? [])) display:none; @else display:block; @endif {{ $settings['student_id_card_styles']['school_logo'] ?? '' }}"
                src="{{ $settings['vertical_logo'] ?? url('assets/dummy_logo.jpg') }}" alt="school_logo">

            {{-- Signature --}}
            <img id="student_item_signature" class="draggableItem" 
                style="position:absolute; width:60px; height:30px; @if (!in_array('signature', $settings['student_id_card_fields'] ?? [])) display:none; @else display:block; @endif {{ $settings['student_id_card_styles']['signature'] ?? '' }}"
                src="{{ $settings['signature'] ?? url('assets/dummy_logo.jpg') }}" alt="signature">

            {{-- Extra Fields --}}
            @foreach ($formFields as $field)
                @if ($field->user_type == 1)
                    <div class="draggableItem p-2 draggableText text-center" id="student_item_{{ $field->id }}" style="position:absolute; @if (!$field->display_on_id) display:none; @endif {{ $settings['student_id_card_styles'][$field->id] ?? '' }}">
                        {{ $field->name }}
                    </div>
                @endif
            @endforeach

        </div>
    </div>

    {{-- Layout Type (hidden, default to vertical) --}}
    <input type="hidden" name="layout_type" value="{{ $settings['layout_type'] ?? 'vertical' }}">

    {{-- Hidden Inputs for Styles --}}
    <input type="hidden" name="student_id_card_styles[student_name]" id="student_style_student_name" value="{{ $settings['student_id_card_styles']['student_name'] ?? '' }}">
    <input type="hidden" name="student_id_card_styles[class_section]" id="student_style_class_section" value="{{ $settings['student_id_card_styles']['class_section'] ?? '' }}">
    <input type="hidden" name="student_id_card_styles[roll_no]" id="student_style_roll_no" value="{{ $settings['student_id_card_styles']['roll_no'] ?? '' }}">
    <input type="hidden" name="student_id_card_styles[dob]" id="student_style_dob" value="{{ $settings['student_id_card_styles']['dob'] ?? '' }}">
    <input type="hidden" name="student_id_card_styles[gender]" id="student_style_gender" value="{{ $settings['student_id_card_styles']['gender'] ?? '' }}">
    <input type="hidden" name="student_id_card_styles[session_year]" id="student_style_session_year" value="{{ $settings['student_id_card_styles']['session_year'] ?? '' }}">
    <input type="hidden" name="student_id_card_styles[guardian_name]" id="student_style_guardian_name" value="{{ $settings['student_id_card_styles']['guardian_name'] ?? '' }}">
    <input type="hidden" name="student_id_card_styles[guardian_contact]" id="student_style_guardian_contact" value="{{ $settings['student_id_card_styles']['guardian_contact'] ?? '' }}">
    <input type="hidden" name="student_id_card_styles[profile_image]" id="student_style_profile_image" value="{{ $settings['student_id_card_styles']['profile_image'] ?? '' }}">
    <input type="hidden" name="student_id_card_styles[school_name]" id="student_style_school_name" value="{{ $settings['student_id_card_styles']['school_name'] ?? '' }}">
    <input type="hidden" name="student_id_card_styles[school_address]" id="student_style_school_address" value="{{ $settings['student_id_card_styles']['school_address'] ?? '' }}">
    <input type="hidden" name="student_id_card_styles[school_logo]" id="student_style_school_logo" value="{{ $settings['student_id_card_styles']['school_logo'] ?? '' }}">
    <input type="hidden" name="student_id_card_styles[signature]" id="student_style_signature" value="{{ $settings['student_id_card_styles']['signature'] ?? '' }}">
    <input type="hidden" name="student_id_card_styles[header]" id="student_style_header" value="{{ $settings['student_id_card_styles']['header'] ?? '' }}">
    <input type="hidden" name="student_id_card_styles[footer]" id="student_style_footer" value="{{ $settings['student_id_card_styles']['footer'] ?? '' }}">

    @foreach ($formFields as $field)
        @if ($field->user_type == 1)
             <input type="hidden" name="student_id_card_styles[{{ $field->id }}]" id="student_style_{{ $field->id }}" value="{{ $settings['student_id_card_styles'][$field->id] ?? '' }}">
        @endif
    @endforeach

</div>
