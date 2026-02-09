@extends('layouts.master')

@section('title')
    {{ __('admit_card') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('Manage Admit Card Template') }}
            </h3>
        </div>
        <form class="pt-3" id="edit-form" action="{{ route('admit-card-template.design.store', $admitCardTemplate->id) }}"
            method="POST" novalidate="novalidate" enctype="multipart/form-data" data-success-function="formSuccessFunction">
            @csrf
            @method('POST')
            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="custom-card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-between">
                                        <h4 class="card-title">
                                            {{ __('Design Admit Card Template') }}
                                        </h4>
                                        <a class="btn btn-sm btn-theme" href="{{ route('admit-card-template.index') }}">
                                            Back</a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12">
                                    <div class="d-flex flex-wrap">
                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('school_name', $admitCardTemplate->fields ?? []) ? 'checked' : '' }} name="school_data[]"
                                                    value="school_name">{{ __('school_name') }}
                                            </label>
                                        </div>

                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('school_logo', $admitCardTemplate->fields ?? []) ? 'checked' : '' }} name="school_data[]"
                                                    value="school_logo">{{ __('school_logo') }}
                                            </label>
                                        </div>

                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('signature', $admitCardTemplate->fields ?? []) ? 'checked' : '' }} name="school_data[]"
                                                    value="signature">{{ __('signature') }}
                                            </label>
                                        </div>

                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('principal_signature', $admitCardTemplate->fields ?? []) ? 'checked' : '' }} name="school_data[]"
                                                    value="principal_signature">{{ __('principal_signature') }}
                                            </label>
                                        </div>

                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('issue_date', $admitCardTemplate->fields ?? []) ? 'checked' : '' }} name="school_data[]"
                                                    value="issue_date">{{ __('issue_date') }}
                                            </label>
                                        </div>

                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('user_image', $admitCardTemplate->fields ?? []) ? 'checked' : '' }} name="school_data[]"
                                                    value="user_image">{{ __('user_image') }}
                                            </label>
                                        </div>

                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('school_address', $admitCardTemplate->fields ?? []) ? 'checked' : '' }} name="school_data[]"
                                                    value="school_address">{{ __('school_address') }}
                                            </label>
                                        </div>
                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('school_mobile', $admitCardTemplate->fields ?? []) ? 'checked' : '' }} name="school_data[]"
                                                    value="school_mobile">{{ __('school_mobile') }}
                                            </label>
                                        </div>
                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('school_email', $admitCardTemplate->fields ?? []) ? 'checked' : '' }} name="school_data[]"
                                                    value="school_email">{{ __('school_email') }}
                                            </label>
                                        </div>

                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('title', $admitCardTemplate->fields ?? []) ? 'checked' : '' }} name="school_data[]"
                                                    value="title">{{ __('title') }}
                                            </label>
                                        </div>

                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('exam_timetable', $admitCardTemplate->fields ?? []) ? 'checked' : '' }} name="school_data[]"
                                                    value="exam_timetable">{{ __('exam_timetable') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-sm-12 col-md-12">
                                    <span class="text-danger">{{ __('note_required_medium_or_large_screen_only') }}</span>
                                </div>

                                <div class="form-group col-sm-12 col-md-12">
                                    <input class="btn btn-theme float-right ml-3" id="create-btn" type="submit" value={{ __('submit') }}>
                                    <input class="btn btn-secondary float-right" id="reset-btn" type="reset" value={{ __('reset') }}>
                                </div>

                                {{-- school logo --}}
                                <input type="hidden" name="style[school_logo]" value="{{ $style['school_logo'] ?? '' }}"
                                    id="school_logo">
                                {{-- User image --}}
                                <input type="hidden" name="style[user_image]" value="{{ $style['user_image'] ?? '' }}"
                                    id="user_image">
                                {{-- Title --}}
                                <input type="hidden" name="style[title]" value="{{ $style['title'] ?? '' }}" id="title">
                                {{-- Description --}}
                                <input type="hidden" name="style[description]" value="{{ $style['description'] ?? '' }}"
                                    id="description">
                                {{-- Issue date --}}
                                <input type="hidden" name="style[issue_date]" value="{{ $style['issue_date'] ?? '' }}"
                                    id="issue_date">
                                {{-- Signature --}}
                                <input type="hidden" name="style[signature]" value="{{ $style['signature'] ?? '' }}"
                                    id="signature">
                                {{-- Principal Signature --}}
                                <input type="hidden" name="style[principal_signature]" value="{{ $style['principal_signature'] ?? '' }}"
                                    id="principal_signature">
                                {{-- School name --}}
                                <input type="hidden" name="style[school_name]" value="{{ $style['school_name'] ?? '' }}"
                                    id="school_name">
                                {{-- School address --}}
                                <input type="hidden" name="style[school_address]"
                                    value="{{ $style['school_address'] ?? '' }}" id="school_address">
                                {{-- School mobile --}}
                                <input type="hidden" name="style[school_mobile]" value="{{ $style['school_mobile'] ?? '' }}"
                                    id="school_mobile">
                                {{-- School email --}}
                                <input type="hidden" name="style[school_email]" value="{{ $style['school_email'] ?? '' }}"
                                    id="school_email">
                                {{-- Exam Timetable --}}
                                <input type="hidden" name="style[exam_timetable]" value="{{ $style['exam_timetable'] ?? '' }}"
                                    id="exam_timetable">
                            </div>
                            
                        </div>
                    </div>
                </div>

                <div style="position: relative">

                    <div class="design" id="draggableElements">
                        @if ($admitCardTemplate->background_image)
                            {{-- Background image --}}
                            <img src="{{ $admitCardTemplate->background_image }}" class="background-image" alt="">
                        @else
                            <div class="frame-border">

                            </div>
                        @endif

                        {{-- School logo --}}
                        <img id="item_school_logo" class="draggableItem height-100" {!! $style['school_logo'] ?? '' !!}
                            src="{{ $settings['vertical_logo'] }}" alt="school_logo">
                        {{-- User image --}}
                        <img id="item_user_image" class="draggableItem" {!! $style['user_image'] ?? '' !!}
                            src="{{ url('assets/dummy_logo.jpg') }}" height="{{ $admitCardTemplate->image_size }}"
                            width="{{ $admitCardTemplate->image_size }}" alt="user_image">

                        {{-- Title --}}
                        <div class="draggableItem p-2 draggableText text-center title" {!! $style['title'] ?? '' !!}
                            id="item_title">
                            <b>{{ $admitCardTemplate->name }}</b>
                        </div>

                        {{-- Description - Split into chunks --}}
                        @php
                            // Parse description and split by paragraphs
                            $description = $admitCardTemplate->description;
                            
                            // Split by closing paragraph tags to preserve HTML formatting within each paragraph
                            $chunks = preg_split('/<\/p>/', $description, -1, PREG_SPLIT_NO_EMPTY);
                            
                            // Clean up chunks - remove opening <p> tags and trim
                            $chunks = array_filter(array_map(function($chunk) {
                                $chunk = trim($chunk);
                                // Remove opening <p> tag if present
                                $chunk = preg_replace('/^<p[^>]*>/', '', $chunk);
                                
                                // Check if chunk is empty or contains only whitespace/nbsp/br tags
                                $testChunk = strip_tags($chunk, '<table><tr><td><th><thead><tbody>');
                                $testChunk = str_replace(['&nbsp;', ' ', "\n", "\r", "\t"], '', $testChunk);
                                $testChunk = trim($testChunk);
                                
                                // If empty after removing whitespace and nbsp, skip this chunk
                                if (empty($testChunk)) {
                                    return null;
                                }
                                
                                // Keep the original chunk with formatting
                                $chunk = trim(strip_tags($chunk, '<b><strong><i><em><u><table><tr><td><th><thead><tbody><br><span><div>'));
                                return $chunk;
                            }, $chunks));
                            
                            $chunks = array_values($chunks); // Re-index array
                        @endphp

                        @foreach($chunks as $index => $chunk)
                            @if(!empty($chunk))
                                <div class="draggableItem p-2 draggableText description-chunk" 
                                     {!! $style['description_' . $index] ?? ($index == 0 ? ($style['description'] ?? 'style="position:absolute; left: 145px; top: ' . (355 + ($index * 40)) . 'px;"') : 'style="position:absolute; left: 145px; top: ' . (355 + ($index * 40)) . 'px;"') !!}
                                     id="item_description_{{ $index }}">
                                    {!! $chunk !!}
                                </div>
                                {{-- Hidden input for each description chunk --}}
                                <input type="hidden" name="style[description_{{ $index }}]" 
                                       value="{{ $style['description_' . $index] ?? '' }}" 
                                       id="description_{{ $index }}">
                            @endif
                        @endforeach


                        {{-- Signature --}}
                        <img id="item_signature" class="draggableItem height-100" {!! $style['signature'] ?? '' !!}
                            src="{{ $settings['signature'] ?? '' }}" alt="signature">

                        {{-- Principal Signature --}}
                        <img id="item_principal_signature" class="draggableItem height-100" {!! $style['principal_signature'] ?? '' !!}
                            src="{{ $settings['principal_signature'] ?? '' }}" alt="principal_signature">

                        {{-- Issue date --}}
                        <div class="draggableItem p-1 draggableText text-center" {!! $style['issue_date'] ?? '' !!}
                            id="item_issue_date">
                            <b>Issue Date</b>
                        </div>

                        {{-- School Name --}}
                        <div class="draggableItem p-2 draggableText text-center h2" {!! $style['school_name'] ?? '' !!}
                            id="item_school_name">
                            <b>{{ $settings['school_name'] }}</b>
                        </div>

                        {{-- School address --}}
                        <div class="draggableItem p-1 draggableText text-center" {!! $style['school_address'] ?? '' !!}
                            id="item_school_address">
                            {{ $settings['school_address'] }}
                        </div>

                        {{-- School mobile --}}
                        <div class="draggableItem p-1 draggableText text-center" {!! $style['school_mobile'] ?? '' !!}
                            id="item_school_mobile">
                            {{ $settings['school_phone'] }}
                        </div>

                        {{-- School email --}}
                        <div class="draggableItem p-1 draggableText text-center" {!! $style['school_email'] ?? '' !!}
                            id="item_school_email">
                            {{ $settings['school_email'] }}
                        </div>

                        {{-- Exam Timetable Table --}}
                        <div class="draggableItem exam-timetable-table" {!! $style['exam_timetable'] ?? '' !!} id="item_exam_timetable">
                            <table style="width: 100%; border-collapse: collapse; border: 1px solid #000; font-size: 12px;">
                                <thead>
                                    <tr style="background-color: #f0f0f0;">
                                        <th style="border: 1px solid #000; padding: 8px; text-align: left;">{{ __('Date') }}</th>
                                         <th style="border: 1px solid #000; padding: 8px; text-align: left;">{{ __('day') }}</th>
                                        <th style="border: 1px solid #000; padding: 8px; text-align: left;">{{ __('Subject') }}</th>
                                        <th style="border: 1px solid #000; padding: 8px; text-align: left;">{{ __('Start Time') }}</th>
                                        <th style="border: 1px solid #000; padding: 8px; text-align: left;">{{ __('End Time') }}</th>
                                        <th style="border: 1px solid #000; padding: 8px; text-align: left;">{{ __('Total Marks') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="border: 1px solid #000; padding: 8px;">01/01/2024</td>
                                         <td style="border: 1px solid #000; padding: 8px;">sunday</td>
                                        <td style="border: 1px solid #000; padding: 8px;">Mathematics</td>
                                        <td style="border: 1px solid #000; padding: 8px;">09:00 AM</td>
                                        <td style="border: 1px solid #000; padding: 8px;">12:00 PM</td>
                                        <td style="border: 1px solid #000; padding: 8px;">100</td>
                                    </tr>
                                    <tr>
                                        <td style="border: 1px solid #000; padding: 8px;">02/01/2024</td>
                                         <td style="border: 1px solid #000; padding: 8px;">monday</td>
                                        <td style="border: 1px solid #000; padding: 8px;">English</td>
                                        <td style="border: 1px solid #000; padding: 8px;">09:00 AM</td>
                                        <td style="border: 1px solid #000; padding: 8px;">12:00 PM</td>
                                        <td style="border: 1px solid #000; padding: 8px;">100</td>
                                    </tr>
                                    <tr>
                                        <td style="border: 1px solid #000; padding: 8px;">03/01/2024</td>
                                        <td style="border: 1px solid #000; padding: 8px;">Tuesday</td>
                                        <td style="border: 1px solid #000; padding: 8px;">Science</td>
                                        <td style="border: 1px solid #000; padding: 8px;">09:00 AM</td>
                                        <td style="border: 1px solid #000; padding: 8px;">12:00 PM</td>
                                        <td style="border: 1px solid #000; padding: 8px;">100</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                            
                </div>
            </div>
              <div class="col-sm-12 col-md-12 mt-4">
                                <div class="card border">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0"><i class="fa fa-history mr-2"></i>{{ __('Undo/Redo/Reset') }}</h6>
                                                <small class="text-muted">{{ __('Undo, redo, or reset all settings to default') }}</small>
                                            </div>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-outline-secondary" id="undo-btn" title="{{ __('Undo') }}">
                                                    <i class="fa fa-undo mr-1"></i>{{ __('Undo') }}
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary" id="redo-btn" title="{{ __('Redo') }}">
                                                    <i class="fa fa-redo mr-1"></i>{{ __('Redo') }}
                                                </button>
                                                <button type="button" class="btn btn-outline-danger" id="reset-design-btn" title="{{ __('Reset All Settings') }}">
                                                    <i class="fa fa-refresh mr-1"></i>{{ __('Reset Design') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Text Formatting Panel --}}
                            <div class="col-sm-12 col-md-12 mt-4">
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0"><i class="fa fa-paint-brush mr-2"></i>{{ __('Text Formatting') }}</h5>
                                        <small class="text-muted">{{ __('Select an element in the design template to format it') }}</small>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            {{-- Selected Element Display --}}
                                            <div class="col-md-12 mb-3">
                                                <label class="font-weight-bold">{{ __('Selected Element') }}:</label>
                                                <span id="selected-element-name" class="badge badge-info ml-2">{{ __('None') }}</span>
                                            </div>
                                            
                                            {{-- Student Info Border Toggle (visible only for description chunk 3) --}}
                                            <div class="col-md-12 mb-3" id="format-student-info-border" style="display:none;">
                                                <label class="font-weight-bold">{{ __('Student Info Border') }}:</label>
                                                <div class="custom-control custom-switch d-inline-block ml-2">
                                                    <input type="checkbox" class="custom-control-input" id="toggle-student-info-border">
                                                    <label class="custom-control-label" for="toggle-student-info-border">{{ __('On / Off') }}</label>
                                                </div>
                                            </div>
                                            
                                            {{-- Font Size --}}
                                            <div class="col-md-3 col-sm-6 mb-3">
                                                <label for="format-font-size">{{ __('font_size') }} (px)</label>
                                                <input type="number" id="format-font-size" class="form-control format-control" min="8" max="72" value="12" placeholder="12">
                                            </div>
                                            
                                            {{-- Text Color --}}
                                            <div class="col-md-3 col-sm-6 mb-3">
                                                <label for="format-text-color">{{ __('text_color') }}</label>
                                                <input type="color" id="format-text-color" class="form-control format-control" value="#000000" style="height: 38px; padding: 2px;">
                                            </div>
                                            
                                            {{-- Background Color --}}
                                             <div class="col-md-3 col-sm-6 mb-3">
                                                <label for="format-bg-color">{{ __('background_color') }}</label>
                                                <div class="input-group">
                                                    <input type="color" id="format-bg-color" class="form-control format-control" value="#ffffff" style="height: 38px; padding: 2px;">
                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-outline-secondary" id="clear-bg-color" title="{{ __('Clear Background') }}">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            {{-- Border Width --}}
                                            <div class="col-md-3 col-sm-6 mb-3">
                                                <label for="format-border-width">{{ __('border_width') }} (px)</label>
                                                <input type="number" id="format-border-width" class="form-control format-control" min="0" max="10" value="0" placeholder="0">
                                            </div>
                                            
                                            {{-- Border Color --}}
                                            <div class="col-md-4 col-sm-6 mb-3">
                                                <label for="format-border-color">{{ __('border_color') }}</label>
                                                <input type="color" id="format-border-color" class="form-control format-control" value="#000000" style="height: 38px; padding: 2px;">
                                            </div>
                                            
                                            {{-- Text Alignment --}}
                                            <div class="col-md-4 col-sm-6 mb-3">
                                                <label>{{ __('alignment') }}</label>
                                                <div class="btn-group d-flex" role="group">
                                                    <button type="button" class="btn btn-outline-secondary format-align" data-align="left" title="{{ __('Left') }}">
                                                        <i class="fa fa-align-left"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-secondary format-align" data-align="center" title="{{ __('Center') }}">
                                                        <i class="fa fa-align-center"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-secondary format-align" data-align="right" title="{{ __('Right') }}">
                                                        <i class="fa fa-align-right"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            {{-- Text Style (Bold, Italic) --}}
                                            <div class="col-md-4 col-sm-6 mb-3">
                                                <label>{{ __('text_style') }}</label>
                                                <div class="btn-group d-flex" role="group">
                                                    <button type="button" class="btn btn-outline-secondary format-style" data-style="bold" title="{{ __('Bold') }}">
                                                        <i class="fa fa-bold"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-secondary format-style" data-style="italic" title="{{ __('Italic') }}">
                                                        <i class="fa fa-italic"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-secondary format-style" data-style="underline" title="{{ __('Underline') }}">
                                                        <i class="fa fa-underline"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            {{-- Width Control for Images --}}
                                            <div class="col-md-4 col-sm-6 mb-3">
                                                <label for="format-width">{{ __('width') }} (px)</label>
                                                <input type="number" id="format-width" class="form-control format-control" min="20" max="500" value="60" placeholder="60">
                                            </div>
                                            
                                            {{-- Height Control for Images --}}
                                            <div class="col-md-4 col-sm-6 mb-3">
                                                <label for="format-height">{{ __('height') }} (px)</label>
                                                <input type="number" id="format-height" class="form-control format-control" min="20" max="500" value="60" placeholder="60">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                             
                             <div class="col-sm-12 col-md-12">
                                    <hr>
                                </div>
                                {{-- Signature Image - Common for both Student and Staff --}}
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="signature_image">{{ __('teacher signature') }} <span class="text-muted">({{ __('Common for Student & Staff') }})</span></label>
                                        <input type="file" name="signature"
                                            accept="image/jpg,image/png,image/jpeg,image/svg" class="file-upload-default" />
                                        <div class="input-group col-xs-12">
                                            <input type="text" id="signature_image" class="form-control file-upload-info" disabled=""
                                                placeholder="{{ __('image') }}" />
                                            <span class="input-group-append">
                                                <button class="file-upload-browse btn btn-theme" type="button">{{ __('upload') }}</button>
                                            </span>
                                        </div>
                                        @if ($settings['signature'] ?? '')
                                            <div id="signature" class="mt-3">
                                                <img src="{{ $settings['signature'] }}" class="img-fluid w-25" alt="">

                                                <div class="mt-2">
                                                    <a href="" data-type="signature"
                                                        class="btn btn-inverse-danger btn-sm id-card-settings">
                                                        <i class="fa fa-times"></i>
                                                    </a>
                                                </div>
                                                <div class="mt-3">
                                                    <span class="text-info">
                                                        {{ __('note_these_signature_image_are_also_used_in_certificates') }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Principal Signature Image --}}
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="principal_signature_image">{{ __('principal signature') }}</label>
                                        <input type="file" name="principal_signature"
                                            accept="image/jpg,image/png,image/jpeg,image/svg" class="file-upload-default" />
                                        <div class="input-group col-xs-12">
                                            <input type="text" id="principal_signature_image" class="form-control file-upload-info" disabled=""
                                                placeholder="{{ __('image') }}" />
                                            <span class="input-group-append">
                                                <button class="file-upload-browse btn btn-theme" type="button">{{ __('upload') }}</button>
                                            </span>
                                        </div>
                                        @if ($settings['principal_signature'] ?? '')
                                            <div id="principal_signature" class="mt-3">
                                                <img src="{{ $settings['principal_signature'] }}" class="img-fluid w-25" alt="">

                                                <div class="mt-2">
                                                    <a href="" data-type="principal_signature"
                                                        class="btn btn-inverse-danger btn-sm id-card-settings">
                                                        <i class="fa fa-times"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                            <div class="col-sm-12 col-md-12 mt-4">
                                <input class="btn btn-theme float-right ml-3" id="create-btn" type="submit" value="{{ __('submit') }}">
                                <input class="btn btn-secondary float-right" type="reset" value="{{ __('reset') }}">
                            </div>
        </form>
    </div>
@endsection
@section('script')
    <script>
        window.onload = setTimeout(() => {
            $('.page_layout').trigger('change');
            $('.form-check-input').trigger('change');
            
            // Initialize history with current state
            saveState('Initial state');
        }, 500);

        $('#reset-btn').click(function (e) {
            e.preventDefault();
            // School name
            $('#item_school_name').css('top', '60px');
            $('#item_school_name').css('left', '480px');
            // User image
            $('#item_user_image').css('top', '140px');
            $('#item_user_image').css('left', '145px');
            // Title
            $('#item_title').css('top', '290px');
            $('#item_title').css('left', '145px');
            // Description chunks - reset all
            $('.description-chunk').each(function(index) {
                $(this).css('top', (355 + (index * 40)) + 'px');
                $(this).css('left', '145px');
            });
            // Issue date
            $('#item_issue_date').css('top', '610px');
            $('#item_issue_date').css('left', '360px');
            // School logo
            $('#item_school_logo').css('top', '600px');
            $('#item_school_logo').css('left', '500px');
            // Signature
            $('#item_signature').css('top', '560px');
            $('#item_signature').css('left', '655px');

            // School address
            $('#item_school_address').css('top', '85px');
            $('#item_school_address').css('left', '125px');

            // School mobile
            $('#item_school_mobile').css('top', '130px');
            $('#item_school_mobile').css('left', '125px');

            // School email
            $('#item_school_email').css('top', '175px');
            $('#item_school_email').css('left', '125px');

            // Exam Timetable
            $('#item_exam_timetable').css('top', '500px');
            $('#item_exam_timetable').css('left', '100px');

        });


        let isDragging = false;
        let isResizing = false;
        let currentElement = null;
        let selectedElement = null;
        let offsetX, offsetY;
        let startX, startY, startWidth, startHeight, startFontSize;

        // Undo/Redo history
        let history = [];
        let historyStep = -1;
        const MAX_HISTORY = 50;

        // Save initial state
        function saveState(description = '') {
            // Remove any redo steps
            history = history.slice(0, historyStep + 1);
            
            // Create state object
            const state = {
                timestamp: new Date(),
                elements: {}
            };
            
            draggableItems.forEach(element => {
                state.elements[element.id] = {
                    top: element.style.top,
                    left: element.style.left,
                    width: element.style.width,
                    height: element.style.height,
                    fontSize: element.style.fontSize,
                    color: element.style.color,
                    backgroundColor: element.style.backgroundColor,
                    borderWidth: element.style.borderWidth,
                    borderColor: element.style.borderColor,
                    borderStyle: element.style.borderStyle,
                    textAlign: element.style.textAlign,
                    fontWeight: element.style.fontWeight,
                    fontStyle: element.style.fontStyle,
                    textDecoration: element.style.textDecoration
                };
            });
            
            // Add to history
            history.push(state);
            historyStep++;
            
            // Limit history size
            if (history.length > MAX_HISTORY) {
                history.shift();
                historyStep--;
            }
            
            // Update button states
            updateHistoryButtons();
        }

        // Restore state
        function restoreState(step) {
            if (step < 0 || step >= history.length) return;
            
            const state = history[step];
            
            draggableItems.forEach(element => {
                const savedState = state.elements[element.id];
                if (savedState) {
                    element.style.top = savedState.top;
                    element.style.left = savedState.left;
                    element.style.width = savedState.width;
                    element.style.height = savedState.height;
                    element.style.fontSize = savedState.fontSize;
                    element.style.color = savedState.color;
                    element.style.backgroundColor = savedState.backgroundColor;
                    element.style.borderWidth = savedState.borderWidth;
                    element.style.borderColor = savedState.borderColor;
                    element.style.borderStyle = savedState.borderStyle;
                    element.style.textAlign = savedState.textAlign;
                    element.style.fontWeight = savedState.fontWeight;
                    element.style.fontStyle = savedState.fontStyle;
                    element.style.textDecoration = savedState.textDecoration;
                    
                    // Update hidden inputs
                    updateElementStyle(element);
                }
            });
            
            historyStep = step;
            updateHistoryButtons();
        }

        // Undo
        function undo() {
            if (historyStep > 0) {
                restoreState(historyStep - 1);
            }
        }

        // Redo
        function redo() {
            if (historyStep < history.length - 1) {
                restoreState(historyStep + 1);
            }
        }

        // Update history button states
        function updateHistoryButtons() {
            $('#undo-btn').prop('disabled', historyStep <= 0);
            $('#redo-btn').prop('disabled', historyStep >= history.length - 1);
        }

        // Reset design
        $('#reset-design-btn').on('click', function() {
            if (confirm('Are you sure you want to reset all settings to default?')) {
                // Reset all elements to default positions and styles
                $('#item_school_name').css({
                    'top': '60px',
                    'left': '480px',
                    'color': '',
                    'background-color': '',
                    'border-width': '',
                    'font-size': '',
                    'font-weight': '',
                    'font-style': '',
                    'text-decoration': ''
                });
                
                $('#item_user_image').css({
                    'top': '140px',
                    'left': '145px',
                    'width': '',
                    'height': ''
                });
                
                $('#item_title').css({
                    'top': '290px',
                    'left': '145px',
                    'color': '',
                    'background-color': '',
                    'border-width': '',
                    'font-size': ''
                });
                
                $('.description-chunk').each(function(index) {
                    $(this).css({
                        'top': (355 + (index * 40)) + 'px',
                        'left': '145px',
                        'color': '',
                        'background-color': '',
                        'border-width': '',
                        'font-size': ''
                    });
                });
                
                $('#item_issue_date').css({
                    'top': '610px',
                    'left': '360px',
                    'color': '',
                    'background-color': '',
                    'border-width': ''
                });
                
                $('#item_school_logo').css({
                    'top': '600px',
                    'left': '500px',
                    'width': '',
                    'height': ''
                });
                
                $('#item_signature').css({
                    'top': '560px',
                    'left': '655px',
                    'width': '',
                    'height': ''
                });
                
                $('#item_school_address').css({
                    'top': '85px',
                    'left': '125px',
                    'color': '',
                    'background-color': '',
                    'border-width': ''
                });
                
                $('#item_school_mobile').css({
                    'top': '130px',
                    'left': '125px',
                    'color': '',
                    'background-color': '',
                    'border-width': ''
                });
                
                $('#item_school_email').css({
                    'top': '175px',
                    'left': '125px',
                    'color': '',
                    'background-color': '',
                    'border-width': ''
                });
                
                $('#item_exam_timetable').css({
                    'top': '500px',
                    'left': '100px',
                    'color': '',
                    'background-color': '',
                    'border-width': ''
                });
                
                // Update all hidden inputs
                draggableItems.forEach(element => {
                    updateElementStyle(element);
                });
                
                // Save state after reset
                saveState('Reset to default');
                
                deselectElement();
            }
        });

        // Undo/Redo button handlers
        $('#undo-btn').on('click', function() {
            undo();
        });

        $('#redo-btn').on('click', function() {
            redo();
        });

        const container = document.getElementById('draggableElements');
        const draggableItems = document.querySelectorAll('.draggableItem');

        // Create resize handle
        const resizeHandle = document.createElement('div');
        resizeHandle.className = 'resize-handle';
        resizeHandle.style.display = 'none';
        document.body.appendChild(resizeHandle);

        // Add click event to select elements
        draggableItems.forEach(element => {
            element.addEventListener('click', (e) => {
                if (!isDragging && !isResizing) {
                    selectElement(element);
                    e.stopPropagation();
                }
            });

            element.addEventListener('mousedown', (e) => {
                if (e.target.classList.contains('resize-handle')) {
                    return; // Let resize handle handle this
                }
                isDragging = true;
                currentElement = element;
                offsetX = e.clientX - element.getBoundingClientRect().left;
                offsetY = e.clientY - element.getBoundingClientRect().top;
                e.preventDefault();
            });
        });

        // Deselect when clicking outside
        document.addEventListener('click', (e) => {
            // Don't deselect if clicking on format controls or their parents
            if ($(e.target).closest('.format-control, .format-align, .format-style, #clear-bg-color, .card-body').length > 0) {
                return;
            }
            
            if (!e.target.classList.contains('draggableItem') && !e.target.classList.contains('resize-handle')) {
                deselectElement();
            }
        });

        // Resize handle mousedown
        resizeHandle.addEventListener('mousedown', (e) => {
            if (selectedElement) {
                isResizing = true;
                startX = e.clientX;
                startY = e.clientY;
                startWidth = selectedElement.offsetWidth;
                startHeight = selectedElement.offsetHeight;
                
                const computedStyle = getComputedStyle(selectedElement);
                startFontSize = parseFloat(computedStyle.fontSize);
                
                e.preventDefault();
                e.stopPropagation();
            }
        });

        document.addEventListener('mousemove', (e) => {
            if (isResizing && selectedElement) {
                const deltaX = e.clientX - startX;
                const deltaY = e.clientY - startY;
                
                // Calculate new dimensions
                let newWidth = startWidth + deltaX;
                let newHeight = startHeight + deltaY;
                
                // Set minimum size
                newWidth = Math.max(30, newWidth);
                newHeight = Math.max(30, newHeight);
                
                // Apply new dimensions
                if (selectedElement.tagName === 'IMG') {
                    selectedElement.style.width = newWidth + 'px';
                    selectedElement.style.height = newHeight + 'px';
                } else if (selectedElement.classList.contains('description-chunk')) {
                    // For description chunks, only resize width, height auto-adjusts
                    selectedElement.style.width = newWidth + 'px';
                    selectedElement.style.height = 'auto'; // Auto height based on content
                    // Don't scale font size for description chunks
                } else {
                    // For other text elements (title, school name, etc.), scale font size
                    selectedElement.style.width = newWidth + 'px';
                    const scaleFactor = newWidth / startWidth;
                    const newFontSize = startFontSize * scaleFactor;
                    selectedElement.style.fontSize = newFontSize + 'px';
                }
                
                // Update resize handle position
                positionResizeHandle();
                
            } else if (isDragging && currentElement) {
                const containerRect = container.getBoundingClientRect();
                const elemRect = currentElement.getBoundingClientRect();

                let newLeft = e.clientX - containerRect.left - offsetX;
                let newTop = e.clientY - containerRect.top - offsetY;

                // Ensure the element stays within the bounds of the container
                newLeft = Math.max(0, Math.min(newLeft, containerRect.width - elemRect.width));
                newTop = Math.max(0, Math.min(newTop, containerRect.height - elemRect.height));

                currentElement.style.left = newLeft + 'px';
                currentElement.style.top = newTop + 'px';
                
                // Update resize handle position if this is the selected element
                if (currentElement === selectedElement) {
                    positionResizeHandle();
                }
            }
        });

        document.addEventListener('mouseup', () => {
            if (isResizing && selectedElement) {
                isResizing = false;
                updateElementStyle(selectedElement);
                saveState('Resize element');
            } else if (isDragging && currentElement) {
                isDragging = false;
                updateElementStyle(currentElement);
                saveState('Move element');
                currentElement = null;
            }
        });

        function selectElement(element) {
            // Remove previous selection
            deselectElement();
            
            // Add selection to new element
            selectedElement = element;
            element.classList.add('selected-item');
            
            // Show and position resize handle
            resizeHandle.style.display = 'block';
            positionResizeHandle();
            
            // Update formatting panel with current element's styles
            updateFormattingPanel(element);
            
            // Display selected element name
            updateSelectedElementName(element);
        }

        function deselectElement() {
            if (selectedElement) {
                selectedElement.classList.remove('selected-item');
                selectedElement = null;
            }
            resizeHandle.style.display = 'none';
        }

        function positionResizeHandle() {
            if (selectedElement) {
                const rect = selectedElement.getBoundingClientRect();
                resizeHandle.style.left = (rect.right - 5) + 'px';
                resizeHandle.style.top = (rect.bottom - 5) + 'px';
            }
        }

        // Helper function to update element style in hidden input
        function updateElementStyle(element) {
            let style = 'style="position:absolute;';
            style += ' left: ' + element.style.left + ';';
            style += ' top: ' + element.style.top + ';';
            
            if (element.style.width) {
                style += ' width: ' + element.style.width + ';';
            }
            if (element.style.height) {
                style += ' height: ' + element.style.height + ';';
            }
            if (element.style.fontSize) {
                style += ' font-size: ' + element.style.fontSize + ';';
            }
            if (element.style.color) {
                style += ' color: ' + element.style.color + ';';
            }
            if (element.style.backgroundColor) {
                style += ' background-color: ' + element.style.backgroundColor + ';';
            }
            if (element.style.borderWidth) {
                style += ' border-width: ' + element.style.borderWidth + ';';
            }
            if (element.style.borderColor) {
                style += ' border-color: ' + element.style.borderColor + ';';
            }
            if (element.style.borderStyle) {
                style += ' border-style: ' + element.style.borderStyle + ';';
            }
            if (element.style.textAlign) {
                style += ' text-align: ' + element.style.textAlign + ';';
            }
            if (element.style.fontWeight) {
                style += ' font-weight: ' + element.style.fontWeight + ';';
            }
            if (element.style.fontStyle) {
                style += ' font-style: ' + element.style.fontStyle + ';';
            }
            if (element.style.textDecoration) {
                style += ' text-decoration: ' + element.style.textDecoration + ';';
            }
            
            // For images, also capture margin/float/display so alignment persists
            if (element.tagName === 'IMG') {
                if (element.style.display) {
                    style += ' display: ' + element.style.display + ';';
                }
                if (element.style.marginLeft) {
                    style += ' margin-left: ' + element.style.marginLeft + ';';
                }
                if (element.style.marginRight) {
                    style += ' margin-right: ' + element.style.marginRight + ';';
                }
                if (element.style.float) {
                    style += ' float: ' + element.style.float + ';';
                }
            }

            style += '"';
            
            let type = element.id.replace('item_', "");
            $('#' + type).val(style);
        }

        // Update formatting panel with element's current styles
        function updateFormattingPanel(element) {
            const computedStyle = getComputedStyle(element);
            
            // Font size
            const fontSize = computedStyle.fontSize;
            $('#format-font-size').val(parseInt(fontSize));
            
            // Text color
            const color = rgbToHex(computedStyle.color);
            $('#format-text-color').val(color);
            
            // Background color
            const bgColor = computedStyle.backgroundColor;
            if (bgColor !== 'rgba(0, 0, 0, 0)' && bgColor !== 'transparent') {
                $('#format-bg-color').val(rgbToHex(bgColor));
            }
            
            // Border width
            const borderWidth = computedStyle.borderWidth;
            $('#format-border-width').val(parseInt(borderWidth));
            
            // Border color
            const borderColor = computedStyle.borderColor;
            $('#format-border-color').val(rgbToHex(borderColor));
            
            // Determine horizontal alignment (left/center/right) based on element position
            try {
                const containerRect = container.getBoundingClientRect();
                const elemRect = element.getBoundingClientRect();
                const relLeft = elemRect.left - containerRect.left;
                const centerLeft = Math.round((containerRect.width - elemRect.width) / 2);
                const rightLeft = Math.round(containerRect.width - elemRect.width);
                const tolerance = 12; // pixels tolerance to detect center/right

                let align = 'left';
                if (Math.abs(relLeft - centerLeft) <= tolerance) {
                    align = 'center';
                } else if (Math.abs(relLeft - rightLeft) <= tolerance) {
                    align = 'right';
                } else {
                    align = 'left';
                }

                $('.format-align').removeClass('active');
                $('.format-align[data-align="' + align + '"]').addClass('active');
            } catch (err) {
                // fallback to textAlign if anything goes wrong
                let align = computedStyle.textAlign;
                if (align === 'start') align = 'left';
                $('.format-align').removeClass('active');
                $('.format-align[data-align="' + align + '"]').addClass('active');
            }
            
            // Text styles
            const fontWeight = computedStyle.fontWeight;
            const fontStyle = computedStyle.fontStyle;
            const textDecoration = computedStyle.textDecoration;
            
            $('.format-style').removeClass('active');
            if (fontWeight === 'bold' || parseInt(fontWeight) >= 700) {
                $('.format-style[data-style="bold"]').addClass('active');
            }
            if (fontStyle === 'italic') {
                $('.format-style[data-style="italic"]').addClass('active');
            }
            if (textDecoration.includes('underline')) {
                $('.format-style[data-style="underline"]').addClass('active');
            }
            
            // Width and height
            if (element.tagName === 'IMG') {
                $('#format-width').val(element.offsetWidth);
                $('#format-height').val(element.offsetHeight);
            }
        }

        // Convert RGB to HEX
        function rgbToHex(rgb) {
            const match = rgb.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)/);
            if (!match) return '#000000';
            
            const r = parseInt(match[1]);
            const g = parseInt(match[2]);
            const b = parseInt(match[3]);
            
            return "#" + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
        }

        // Update selected element name in the panel
        function updateSelectedElementName(element) {
            const nameMap = {
                'item_school_logo': 'School Logo',
                'item_user_image': 'User Image',
                'item_title': 'Title',
                'item_issue_date': 'Issue Date',
                'item_signature': 'Signature',
                'item_principal_signature': 'Principal Signature',
                'item_school_name': 'School Name',
                'item_school_address': 'School Address',
                'item_school_mobile': 'School Mobile',
                'item_school_email': 'School Email',
                'item_exam_timetable': 'Exam Timetable'
            };
            
            // Check if it's a description chunk
            if (element.id.startsWith('item_description_')) {
                $('#selected-element-name').text('Description');
            } else {
                const displayName = nameMap[element.id] || element.id;
                $('#selected-element-name').text(displayName);
            }

            // Show Student Info Border toggle only for description chunk index 3
            if (element.id === 'item_description_3') {
                // determine current border presence
                const cs = getComputedStyle(element);
                const bw = parseFloat(cs.borderWidth) || 0;
                const bs = cs.borderStyle || 'none';
                const hasBorder = (bs !== 'none' && bw > 0);
                $('#format-student-info-border').show();
                $('#toggle-student-info-border').prop('checked', hasBorder);
            } else {
                $('#format-student-info-border').hide();
            }
        }

        // Format controls event listeners
        $('#format-font-size').on('change', function() {
            if (selectedElement && selectedElement.tagName !== 'IMG') {
                selectedElement.style.fontSize = $(this).val() + 'px';
                updateElementStyle(selectedElement);
                saveState('Change font size');
            }
        });

        $('#format-text-color').on('change', function() {
            if (selectedElement && selectedElement.tagName !== 'IMG') {
                selectedElement.style.color = $(this).val();
                updateElementStyle(selectedElement);
                saveState('Change text color');
            }
        });

        $('#format-bg-color').on('change', function() {
            if (selectedElement) {
                selectedElement.style.backgroundColor = $(this).val();
                updateElementStyle(selectedElement);
                saveState('Change background color');
            }
        });

        $('#clear-bg-color').on('click', function() {
            if (selectedElement) {
                selectedElement.style.backgroundColor = 'transparent';
                $('#format-bg-color').val('#ffffff');
                updateElementStyle(selectedElement);
                saveState('Clear background color');
            }
        });

        $('#format-border-width').on('change', function() {
            if (selectedElement) {
                selectedElement.style.borderWidth = $(this).val() + 'px';
                if ($(this).val() > 0) {
                    selectedElement.style.borderStyle = 'solid';
                }
                updateElementStyle(selectedElement);
                saveState('Change border width');
            }
        });

        $('#format-border-color').on('change', function() {
            if (selectedElement) {
                selectedElement.style.borderColor = $(this).val();
                updateElementStyle(selectedElement);
                saveState('Change border color');
            }
        });

        $('.format-align').on('click', function() {
            if (selectedElement) {
                const align = $(this).data('align');

                // Position-based alignment for absolutely positioned elements
                try {
                    const containerRect = container.getBoundingClientRect();
                    const elemRect = selectedElement.getBoundingClientRect();
                    const padding = 10; // left/right padding inside container

                    let newLeft = padding;
                    if (align === 'center') {
                        newLeft = Math.max(0, Math.round((containerRect.width - elemRect.width) / 2));
                    } else if (align === 'right') {
                        newLeft = Math.max(0, Math.round(containerRect.width - elemRect.width - padding));
                    } else {
                        newLeft = padding;
                    }

                    selectedElement.style.left = newLeft + 'px';
                } catch (err) {
                    // ignore positioning error
                }

                // Also set text-align for text elements so inner text/table aligns
                if (selectedElement.tagName !== 'IMG') {
                    selectedElement.style.textAlign = align;
                }

                $('.format-align').removeClass('active');
                $(this).addClass('active');

                positionResizeHandle();
                updateElementStyle(selectedElement);
                saveState('Change alignment');
            }
        });

        $('.format-style').on('click', function() {
            if (selectedElement && selectedElement.tagName !== 'IMG') {
                const style = $(this).data('style');
                
                switch(style) {
                    case 'bold':
                        if (selectedElement.style.fontWeight === 'bold') {
                            selectedElement.style.fontWeight = 'normal';
                        } else {
                            selectedElement.style.fontWeight = 'bold';
                        }
                        break;
                    case 'italic':
                        if (selectedElement.style.fontStyle === 'italic') {
                            selectedElement.style.fontStyle = 'normal';
                        } else {
                            selectedElement.style.fontStyle = 'italic';
                        }
                        break;
                    case 'underline':
                        if (selectedElement.style.textDecoration.includes('underline')) {
                            selectedElement.style.textDecoration = 'none';
                        } else {
                            selectedElement.style.textDecoration = 'underline';
                        }
                        break;
                }
                
                $(this).toggleClass('active');
                updateElementStyle(selectedElement);
                saveState('Change text style');
            }
        });

        $('#format-width').on('change', function() {
            if (selectedElement && selectedElement.tagName === 'IMG') {
                selectedElement.style.width = $(this).val() + 'px';
                updateElementStyle(selectedElement);
                saveState('Change width');
            }
        });

        $('#format-height').on('change', function() {
            if (selectedElement && selectedElement.tagName === 'IMG') {
                selectedElement.style.height = $(this).val() + 'px';
                updateElementStyle(selectedElement);
                saveState('Change height');
            }
        });

        // Student Info Border toggle handler
        $('#toggle-student-info-border').on('change', function() {
            if (selectedElement && selectedElement.id === 'item_description_3') {
                if ($(this).is(':checked')) {
                    // enable border
                    selectedElement.style.borderWidth = '1px';
                    selectedElement.style.borderStyle = 'solid';
                    if (!selectedElement.style.borderColor) selectedElement.style.borderColor = '#000';
                } else {
                    // disable border
                    selectedElement.style.borderWidth = '0';
                    selectedElement.style.borderStyle = 'none';
                }
                updateElementStyle(selectedElement);
                saveState('Toggle student info border');
                positionResizeHandle();
            }
        });


        $('.form-check-input').change(function (e) {
            e.preventDefault();
            let field = '#item_' + $(this).val();
            let status = $(this).is(':checked');
            if (status) {
                $(field).show(500);
            } else {
                $(field).hide(500);
            }
        });


        function formSuccessFunction(response) {
            setTimeout(() => {
                window.location.reload()
            }, 1000);
        }


    </script>
@endsection
@section('css')
    <style>
        .design {
            height:
                {{ $layout['height'] }}
            ;
            width:
                {{ $layout['width'] }}
            ;
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;

        }

        .frame-border {
            height:
                {{ $layout['height'] }}
            ;
            width:
                {{ $layout['width'] }}
            ;
            border: 1px solid black;
        }

        .background-image {
            height:
                {{ $layout['height'] }}
            ;
            width:
                {{ $layout['width'] }}
            ;
        }

        .draggableItem {
            position: absolute;
            cursor: pointer;
        }

        .height-100 {
            height: 100px;
        }

        .title {
            font-weight: bold;
            font-size: 38px;
        }

        .exam-timetable-table {
            min-width: 600px;
            cursor: pointer;
        }

        .exam-timetable-table table {
            font-size: 11px;
        }

        /* Description chunk styles */
        .description-chunk {
            min-width: 50px;
            word-wrap: break-word;
            white-space: normal;
        }

        /* Table styling within description chunks */
        .description-chunk table {
            border-collapse: collapse;
            width: 100%;
            margin: 5px 0;
        }

        .description-chunk table td,
        .description-chunk table th {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        .description-chunk table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        /* Preserve bold and other formatting */
        .description-chunk b,
        .description-chunk strong {
            font-weight: bold;
        }

        .description-chunk i,
        .description-chunk em {
            font-style: italic;
        }

        .description-chunk u {
            text-decoration: underline;
        }


        /* Resize handle styles */
        .resize-handle {
            position: fixed;
            width: 12px;
            height: 12px;
            background-color: #007bff;
            border: 2px solid white;
            border-radius: 2px;
            cursor: nwse-resize;
            z-index: 10000;
            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .resize-handle:hover {
            background-color: #0056b3;
            transform: scale(1.2);
        }

        /* Selected element indicator */
        .selected-item {
            outline: 2px dashed #007bff !important;
            outline-offset: 2px;
        }

        /* Format button active state */
        .format-align.active,
        .format-style.active {
            background-color: #007bff !important;
            color: white !important;
            border-color: #007bff !important;
        }
    </style>

    @if ($admitCardTemplate->user_image_shape == 'Round')
        <style>
            #item_user_image {
                border-radius: 50%;
            }
        </style>
    @else
        <style>
            #item_user_image {
                border-radius: 6%;
            }
        </style>
    @endif

@endsection
