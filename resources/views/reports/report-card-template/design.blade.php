@extends('layouts.master')

@section('title')
    {{ __('Design Report Card Template') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('Manage Report Card Template') }}
            </h3>
        </div>
        <form class="pt-3 edit-form-without-reset" id="edit-form" action="{{ route('report-card-template.design.store', $reportCardTemplate->id) }}"
            method="POST" novalidate="novalidate" enctype="multipart/form-data" data-success-function="formSuccessFunction">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="custom-card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-between">
                                        <h4 class="card-title">
                                            {{ __('Design Report Card Template') }}
                                        </h4>
                                        <a class="btn btn-sm btn-theme" href="{{ route('report-card-template.index') }}">
                                            Back</a>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Display Options Checkboxes -->
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <h5 class="card-title">{{ __('Display Options') }}</h5>
                                </div>
                                <div class="form-group col-sm-12 col-md-12">
                                    <div class="d-flex flex-wrap">
                                        @php
                                            // Get fields from configuration, ensure it's an array
                                            $fields = [];
                                            if (isset($configuration['fields'])) {
                                                $fields = is_array($configuration['fields']) ? $configuration['fields'] : [];
                                            }
                                        @endphp
                                        
                                        {{-- Header --}}
                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('header', $fields) ? 'checked' : '' }} name="visible_fields[]" value="header">{{ __('Header') }}
                                            </label>
                                        </div>
                                        {{-- School Name --}}
                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('school_name', $fields) ? 'checked' : '' }} name="visible_fields[]" value="school_name">{{ __('school_name') }}
                                            </label>
                                        </div>

                                        {{-- School Logo --}}
                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('school_logo', $fields) ? 'checked' : '' }} name="visible_fields[]" value="school_logo">{{ __('school_logo') }}
                                            </label>
                                        </div>

                                        {{-- School Address --}}
                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('school_address', $fields) ? 'checked' : '' }} name="visible_fields[]" value="school_address">{{ __('school_address') }}
                                            </label>
                                        </div>

                                        {{-- School Mobile --}}
                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('school_mobile', $fields) ? 'checked' : '' }} name="visible_fields[]" value="school_mobile">{{ __('school_mobile') }}
                                            </label>
                                        </div>

                                        {{-- School Email --}}
                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('school_email', $fields) ? 'checked' : '' }} name="visible_fields[]" value="school_email">{{ __('school_email') }}
                                            </label>
                                        </div>

                                        {{-- Title --}}
                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('title', $fields) ? 'checked' : '' }} name="visible_fields[]" value="title">{{ __('title') }}
                                            </label>
                                        </div>

                                        {{-- Student Image --}}
                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('student_image', $fields) ? 'checked' : '' }} name="visible_fields[]" value="student_image">{{ __('Student Image') }}
                                            </label>
                                        </div>

                                        {{-- Results Table --}}
                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('results_table', $fields) ? 'checked' : '' }} name="visible_fields[]" value="results_table">{{ __('Results Table') }}
                                            </label>
                                        </div>

                                        {{-- Grading System --}}
                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('grading_system', $fields) ? 'checked' : '' }} name="visible_fields[]" value="grading_system">{{ __('Grading System') }}
                                            </label>
                                        </div>

                                        {{-- Co-Scholastic --}}
                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('co_scholastic', $fields) ? 'checked' : '' }} name="visible_fields[]" value="co_scholastic">{{ __('Co-Scholastic') }}
                                            </label>
                                        </div>

                                        {{-- Footer --}}
                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('footer', $fields) ? 'checked' : '' }} name="visible_fields[]" value="footer">{{ __('Footer') }}
                                            </label>
                                        </div>

                                        {{-- Teacher Signature --}}
                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('teacher_signature', $fields) ? 'checked' : '' }} name="visible_fields[]" value="teacher_signature">{{ __('teacher signature') }}
                                            </label>
                                        </div>

                                        {{-- Principal Signature --}}
                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('principal_signature', $fields) ? 'checked' : '' }} name="visible_fields[]" value="principal_signature">{{ __('principal signature') }}
                                            </label>
                                        </div>

                                        {{-- Overall --}}
                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('overall', $fields) ? 'checked' : '' }} name="visible_fields[]" value="overall">{{ __('Overall') }}
                                            </label>
                                        </div>

                                        {{-- Attendance --}}
                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('attendance', $fields) ? 'checked' : '' }} name="visible_fields[]" value="attendance">{{ __('Attendance') }}
                                            </label>
                                        </div>

                                        {{-- Rank --}}
                                        <div class="form-check w-auto col-auto col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <label class="form-check-label mx-4">
                                                <input type="checkbox" class="form-check-input" {{ in_array('rank', $fields) ? 'checked' : '' }} name="visible_fields[]" value="rank">{{ __('Rank') }}
                                            </label>
                                        </div>

                                        {{-- Description checkbox removed to match Admit Card behavior --}}
                                    </div>
                                </div>

                                <div class="form-group col-sm-12 col-md-12">
                                    <span class="text-danger">{{ __('note_required_medium_or_large_screen_only') }}</span>
                                </div>

                                <div class="form-group col-sm-12 col-md-12">
                                    <input class="btn btn-theme float-right ml-3" id="save-btn" type="submit" value={{ __('submit') }}>
                                    <input class="btn btn-secondary float-right" id="reset-btn" type="reset" value={{ __('reset') }}>
                                </div>

                                {{-- Hidden Inputs for Styles --}}
                                {{-- Hidden Inputs for Styles --}}
                                <input type="hidden" name="style[header]" value="{{ $style['header'] ?? '' }}" id="header">
                                <input type="hidden" name="style[school_name]" value="{{ $style['school_name'] ?? '' }}" id="school_name">
                                <input type="hidden" name="style[school_logo]" value="{{ $style['school_logo'] ?? '' }}" id="school_logo">
                                <input type="hidden" name="style[school_address]" value="{{ $style['school_address'] ?? '' }}" id="school_address">
                                <input type="hidden" name="style[school_mobile]" value="{{ $style['school_mobile'] ?? '' }}" id="school_mobile">
                                <input type="hidden" name="style[school_email]" value="{{ $style['school_email'] ?? '' }}" id="school_email">
                                <input type="hidden" name="style[title]" value="{{ $style['title'] ?? '' }}" id="title">
                                
                                <input type="hidden" name="style[student_image]" value="{{ $style['student_image'] ?? '' }}" id="student_image">
                                <input type="hidden" name="style[results_table]" value="{{ $style['results_table'] ?? '' }}" id="results_table">
                                <input type="hidden" name="style[grading_system]" value="{{ $style['grading_system'] ?? '' }}" id="grading_system">
                                <input type="hidden" name="style[co_scholastic]" value="{{ $style['co_scholastic'] ?? '' }}" id="co_scholastic">
                                <input type="hidden" name="style[footer]" value="{{ $style['footer'] ?? '' }}" id="footer">
                                <input type="hidden" name="style[teacher_signature]" value="{{ $style['teacher_signature'] ?? '' }}" id="teacher_signature">
                                <input type="hidden" name="style[principal_signature]" value="{{ $style['principal_signature'] ?? '' }}" id="principal_signature">
                                {{-- Overall, Attendance, and Rank are not draggable, so no style storage needed --}}
                                {{-- Description is handled dynamically --}}
                            </div>
                        </div>
                    </div>
                </div>

                <div style="position: relative; margin-top: 20px;" class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ __('Design Preview') }}</h5>
                            
                            <div class="design" id="draggableElements">
                                <div class="frame-border"></div>

                                {{-- Header --}}
                                <div id="item_header" class="draggableItem" {!! !empty($style['header']) ? $style['header'] : 'style="position:absolute; left: 0px; top: 0px; width: 100%; height: 200px;"' !!}>
                                    <div style="background-color: {{ $colors['header_bg'] ?? '#22577a' }}; position: absolute; top: 0; left: 0; right: 0; bottom: 0;"></div>
                                </div>

                                {{-- School logo --}}
                                <img id="item_school_logo" class="draggableItem height-100" {!! $style['school_logo'] ?? '' !!}
                                    src="{{ $settings['vertical_logo'] ?? url('assets/logo.svg') }}" alt="school_logo">

                                {{-- School Name --}}
                                <div class="draggableItem p-2 draggableText text-center h2" {!! $style['school_name'] ?? '' !!}
                                    id="item_school_name">
                                    <b>{{ data_get($settings, 'school_name', 'School Name') }}</b>
                                </div>

                                {{-- School address --}}
                                <div class="draggableItem p-1 draggableText text-center" {!! $style['school_address'] ?? '' !!}
                                    id="item_school_address">
                                    {{ data_get($settings, 'school_address', 'School Address') }}
                                </div>

                                {{-- School mobile --}}
                                <div class="draggableItem p-1 draggableText text-center" {!! $style['school_mobile'] ?? '' !!}
                                    id="item_school_mobile">
                                    {{ data_get($settings, 'school_phone', 'School Phone') }}
                                </div>

                                {{-- School email --}}
                                <div class="draggableItem p-1 draggableText text-center" {!! $style['school_email'] ?? '' !!}
                                    id="item_school_email">
                                    {{ data_get($settings, 'school_email', 'School Email') }}
                                </div>

                                {{-- Title --}}
                                <div class="draggableItem p-2 draggableText text-center title" {!! $style['title'] ?? '' !!}
                                    id="item_title">
                                    <b>{{ $reportCardTemplate->name }}</b>
                                </div>

                                {{-- Student Image --}}
                                <div id="item_student_image" class="draggableItem" {!! !empty($style['student_image']) ? $style['student_image'] : 'style="position:absolute; left: 650px; top: 100px; width: 100px; height: 100px;"' !!}>
                                    <div style="width: 100%; height: 100%; background-color: #ddd; display: flex; align-items: center; justify-content: center;">
                                        <img src="{{ url('assets/dummy_logo.jpg') }}" alt="Student" style="max-width: 100%; max-height: 100%;">
                                    </div>
                                </div>

                                {{-- Description (from TinyMCE) - Split into chunks for draggable positioning --}}
                                @php
                                    // Use template_description from style if available
                                    $description = $style['template_description'] ?? '';
                                    if ($description) {
                                        // Split by closing tags to preserve tables and other block elements
                                        $splitChunks = preg_split('/(<\/p>|<\/table>|<\/div>)/i', $description, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
                                        
                                        // Rebuild chunks properly (pair content with closing tags)
                                        $rebuiltChunks = [];
                                        $currentChunk = '';
                                        foreach ($splitChunks as $part) {
                                            $currentChunk .= $part;
                                            if (preg_match('/<\/(p|table|div)>/i', $part)) {
                                                $rebuiltChunks[] = $currentChunk;
                                                $currentChunk = '';
                                            }
                                        }
                                        if (!empty(trim($currentChunk))) {
                                            $rebuiltChunks[] = $currentChunk;
                                        }
                                        
                                        // Filter out empty chunks
                                        $chunks = array_filter(array_map(function($chunk) {
                                            $chunk = trim($chunk);
                                            $testChunk = strip_tags($chunk);
                                            $testChunk = str_replace(['&nbsp;', ' ', "\n", "\r", "\t"], '', $testChunk);
                                            if (empty(trim($testChunk))) return null;
                                            return $chunk;
                                        }, $rebuiltChunks));
                                        $chunks = array_values($chunks);
                                    } else {
                                        $chunks = [];
                                    }
                                @endphp

                                @foreach($chunks as $index => $chunk)
                                    <div class="draggableItem p-2 draggableText description-chunk" 
                                         {!! !empty($style['description_' . $index]) ? $style['description_' . $index] : 'style="position:absolute; left: 20px; top: ' . (200 + ($index * 50)) . 'px; width: 95%;"' !!}
                                         id="item_description_{{ $index }}">
                                        {!! $chunk !!}
                                    </div>
                                    <input type="hidden" name="style[description_{{ $index }}]" value="{{ $style['description_' . $index] ?? '' }}" id="description_{{ $index }}">
                                @endforeach

                                {{-- Results Table --}}
                                <div id="item_results_table" class="draggableItem" {!! !empty($style['results_table']) ? $style['results_table'] : 'style="position:absolute; left: 0px; top: 400px; width: 100%;"' !!}>
                                    <div style="padding: 5px; border: 1px solid #ddd; background: #fff;">
                                        <h6 style="font-weight: bold; background-color: {{ $colors['table_header_bg'] ?? '#22577a' }}; color: white; padding: 5px; margin-bottom: 0;">Results Table</h6>
                                        <table style="width: 100%; border-collapse: collapse; font-size: 10px; text-align: center;">
                                            <thead>
                                                <tr style="background-color: {{ $colors['table_header_bg'] ?? '#22577a' }}; color: white;">
                                                    <th rowspan="2" style="border: 1px solid #fff; padding: 5px;">SUBJECT</th>
                                                    <th colspan="5" style="border: 1px solid #fff; padding: 5px;">Term 1</th>
                                                    <th colspan="5" style="border: 1px solid #fff; padding: 5px;">Term 2</th>
                                                    <th colspan="3" rowspan="2" style="border: 1px solid #fff; padding: 5px;">GR. TOTAL</th>
                                                </tr>
                                                <tr style="background-color: {{ $colors['table_header_bg'] ?? '#22577a' }}; color: white;">
                                                    <th style="border: 1px solid #fff; padding: 2px;">Written</th>
                                                    <th style="border: 1px solid #fff; padding: 2px;">Oral</th>
                                                    <th style="border: 1px solid #fff; padding: 2px;">Theory</th>
                                                    <th style="border: 1px solid #fff; padding: 2px;">Total</th>
                                                    <th style="border: 1px solid #fff; padding: 2px;">Grade</th>
                                                    <th style="border: 1px solid #fff; padding: 2px;">Written</th>
                                                    <th style="border: 1px solid #fff; padding: 2px;">Oral</th>
                                                    <th style="border: 1px solid #fff; padding: 2px;">Theory</th>
                                                    <th style="border: 1px solid #fff; padding: 2px;">Total</th>
                                                    <th style="border: 1px solid #fff; padding: 2px;">Grade</th>
                                                </tr>
                                            </thead>
                                            <tbody style="color: {{ $colors['table_text_color'] ?? '#333' }};">
                                                <tr>
                                                    <td style="border: 1px solid #ccc; padding: 4px; text-align: left;">English</td>
                                                    <td style="border: 1px solid #ccc;">25</td><td style="border: 1px solid #ccc;">30</td><td style="border: 1px solid #ccc;">25</td><td style="border: 1px solid #ccc;">80</td><td style="border: 1px solid #ccc;">A+</td>
                                                    <td style="border: 1px solid #ccc;">10</td><td style="border: 1px solid #ccc;">20</td><td style="border: 1px solid #ccc;">30</td><td style="border: 1px solid #ccc;">60</td><td style="border: 1px solid #ccc;">A</td>
                                                    <td style="border: 1px solid #ccc;">140</td><td style="border: 1px solid #ccc;">70.00%</td><td style="border: 1px solid #ccc;">A</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 1px solid #ccc; padding: 4px; text-align: left;">Bengali</td>
                                                    <td style="border: 1px solid #ccc;">20</td><td style="border: 1px solid #ccc;">15</td><td style="border: 1px solid #ccc;">20</td><td style="border: 1px solid #ccc;">55</td><td style="border: 1px solid #ccc;">B+</td>
                                                    <td style="border: 1px solid #ccc;">16</td><td style="border: 1px solid #ccc;">20</td><td style="border: 1px solid #ccc;">25</td><td style="border: 1px solid #ccc;">61</td><td style="border: 1px solid #ccc;">A</td>
                                                    <td style="border: 1px solid #ccc;">116</td><td style="border: 1px solid #ccc;">58.00%</td><td style="border: 1px solid #ccc;">B+</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 1px solid #ccc; padding: 4px; text-align: left;">Mathematics</td>
                                                    <td style="border: 1px solid #ccc;">30</td><td style="border: 1px solid #ccc;">25</td><td style="border: 1px solid #ccc;">30</td><td style="border: 1px solid #ccc;">85</td><td style="border: 1px solid #ccc;">A+</td>
                                                    <td style="border: 1px solid #ccc;">12</td><td style="border: 1px solid #ccc;">25</td><td style="border: 1px solid #ccc;">25</td><td style="border: 1px solid #ccc;">62</td><td style="border: 1px solid #ccc;">A</td>
                                                    <td style="border: 1px solid #ccc;">147</td><td style="border: 1px solid #ccc;">73.50%</td><td style="border: 1px solid #ccc;">A</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 1px solid #ccc; padding: 4px; text-align: left;">Life Science</td>
                                                    <td style="border: 1px solid #ccc;">30</td><td style="border: 1px solid #ccc;">25</td><td style="border: 1px solid #ccc;">30</td><td style="border: 1px solid #ccc;">85</td><td style="border: 1px solid #ccc;">A+</td>
                                                    <td style="border: 1px solid #ccc;">12</td><td style="border: 1px solid #ccc;">25</td><td style="border: 1px solid #ccc;">25</td><td style="border: 1px solid #ccc;">62</td><td style="border: 1px solid #ccc;">A</td>
                                                    <td style="border: 1px solid #ccc;">147</td><td style="border: 1px solid #ccc;">73.50%</td><td style="border: 1px solid #ccc;">A</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 1px solid #ccc; padding: 4px; text-align: left;">History</td>
                                                    <td style="border: 1px solid #ccc;">30</td><td style="border: 1px solid #ccc;">25</td><td style="border: 1px solid #ccc;">30</td><td style="border: 1px solid #ccc;">85</td><td style="border: 1px solid #ccc;">A+</td>
                                                    <td style="border: 1px solid #ccc;">12</td><td style="border: 1px solid #ccc;">25</td><td style="border: 1px solid #ccc;">25</td><td style="border: 1px solid #ccc;">62</td><td style="border: 1px solid #ccc;">A</td>
                                                    <td style="border: 1px solid #ccc;">147</td><td style="border: 1px solid #ccc;">73.50%</td><td style="border: 1px solid #ccc;">A</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 1px solid #ccc; padding: 4px; text-align: left;">Geography</td>
                                                    <td style="border: 1px solid #ccc;">30</td><td style="border: 1px solid #ccc;">25</td><td style="border: 1px solid #ccc;">30</td><td style="border: 1px solid #ccc;">85</td><td style="border: 1px solid #ccc;">A+</td>
                                                    <td style="border: 1px solid #ccc;">12</td><td style="border: 1px solid #ccc;">25</td><td style="border: 1px solid #ccc;">25</td><td style="border: 1px solid #ccc;">62</td><td style="border: 1px solid #ccc;">A</td>
                                                    <td style="border: 1px solid #ccc;">147</td><td style="border: 1px solid #ccc;">73.50%</td><td style="border: 1px solid #ccc;">A</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 1px solid #ccc; padding: 4px; text-align: left;">Physical Education</td>
                                                    <td style="border: 1px solid #ccc;">30</td><td style="border: 1px solid #ccc;">25</td><td style="border: 1px solid #ccc;">30</td><td style="border: 1px solid #ccc;">85</td><td style="border: 1px solid #ccc;">A+</td>
                                                    <td style="border: 1px solid #ccc;">12</td><td style="border: 1px solid #ccc;">25</td><td style="border: 1px solid #ccc;">25</td><td style="border: 1px solid #ccc;">62</td><td style="border: 1px solid #ccc;">A</td>
                                                    <td style="border: 1px solid #ccc;">147</td><td style="border: 1px solid #ccc;">73.50%</td><td style="border: 1px solid #ccc;">A</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 1px solid #ccc; padding: 4px; text-align: left;">Work Education</td>
                                                    <td style="border: 1px solid #ccc;">30</td><td style="border: 1px solid #ccc;">25</td><td style="border: 1px solid #ccc;">30</td><td style="border: 1px solid #ccc;">85</td><td style="border: 1px solid #ccc;">A+</td>
                                                    <td style="border: 1px solid #ccc;">12</td><td style="border: 1px solid #ccc;">25</td><td style="border: 1px solid #ccc;">25</td><td style="border: 1px solid #ccc;">62</td><td style="border: 1px solid #ccc;">A</td>
                                                    <td style="border: 1px solid #ccc;">147</td><td style="border: 1px solid #ccc;">73.50%</td><td style="border: 1px solid #ccc;">A</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 1px solid #ccc; padding: 4px; text-align: left;">Computer Studies</td>
                                                    <td style="border: 1px solid #ccc;">30</td><td style="border: 1px solid #ccc;">25</td><td style="border: 1px solid #ccc;">30</td><td style="border: 1px solid #ccc;">85</td><td style="border: 1px solid #ccc;">A+</td>
                                                    <td style="border: 1px solid #ccc;">12</td><td style="border: 1px solid #ccc;">25</td><td style="border: 1px solid #ccc;">25</td><td style="border: 1px solid #ccc;">62</td><td style="border: 1px solid #ccc;">A</td>
                                                    <td style="border: 1px solid #ccc;">147</td><td style="border: 1px solid #ccc;">73.50%</td><td style="border: 1px solid #ccc;">A</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 1px solid #ccc; padding: 4px; text-align: left;">Science</td>
                                                    <td style="border: 1px solid #ccc;">30</td><td style="border: 1px solid #ccc;">25</td><td style="border: 1px solid #ccc;">30</td><td style="border: 1px solid #ccc;">85</td><td style="border: 1px solid #ccc;">A+</td>
                                                    <td style="border: 1px solid #ccc;">12</td><td style="border: 1px solid #ccc;">25</td><td style="border: 1px solid #ccc;">25</td><td style="border: 1px solid #ccc;">62</td><td style="border: 1px solid #ccc;">A</td>
                                                    <td style="border: 1px solid #ccc;">147</td><td style="border: 1px solid #ccc;">73.50%</td><td style="border: 1px solid #ccc;">A</td>
                                                </tr>
                                                <tr style="font-weight: bold; background-color: #f0f0f0;">
                                                    <td style="border: 1px solid #ccc; padding: 4px; text-align: left;">Total Marks</td>
                                                    <td style="border: 1px solid #ccc;">75</td><td style="border: 1px solid #ccc;">70</td><td style="border: 1px solid #ccc;">75</td><td style="border: 1px solid #ccc;">220</td><td style="border: 1px solid #ccc;">A</td>
                                                    <td style="border: 1px solid #ccc;">38</td><td style="border: 1px solid #ccc;">65</td><td style="border: 1px solid #ccc;">80</td><td style="border: 1px solid #ccc;">183</td><td style="border: 1px solid #ccc;">A</td>
                                                    <td style="border: 1px solid #ccc;">403</td><td style="border: 1px solid #ccc;">67.17%</td><td style="border: 1px solid #ccc;">A</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                {{-- Grading System --}}
                                <div id="item_grading_system" class="draggableItem" {!! !empty($style['grading_system']) ? $style['grading_system'] : 'style="position:absolute; left: 0px; top: 600px; width: 100%;"' !!}>
                                    <div style="padding: 10px; border: 1px solid #ddd;">
                                        <h6 style="font-weight: bold; background-color: {{ $colors['table_header_bg'] ?? '#22577a' }}; color: white; padding: 5px;">Grading System</h6>
                                        
                                        <!-- Grading Table Example -->
                                        <div style="overflow-x: auto;">
                                            <table style="width: 100%; border-collapse: collapse; font-size: 10px; text-align: center;">
                                                <tbody>
                                                    <tr>
                                                        <th style="background-color: {{ $colors['table_header_bg'] ?? '#22577a' }}; color: white; border: 1px solid #ddd; padding: 5px; width: 80px;">Range</th>
                                                        <td style="border: 1px solid #ddd; padding: 5px;">0 - 24</td>
                                                        <td style="border: 1px solid #ddd; padding: 5px;">25 - 34</td>
                                                        <td style="border: 1px solid #ddd; padding: 5px;">35 - 44</td>
                                                        <td style="border: 1px solid #ddd; padding: 5px;">45 - 59</td>
                                                        <td style="border: 1px solid #ddd; padding: 5px;">60 - 79</td>
                                                        <td style="border: 1px solid #ddd; padding: 5px;">80 - 89</td>
                                                        <td style="border: 1px solid #ddd; padding: 5px;">90 - 100</td>
                                                    </tr>
                                                    <tr>
                                                        <th style="background-color: {{ $colors['table_header_bg'] ?? '#22577a' }}; color: white; border: 1px solid #ddd; padding: 5px;">Grade</th>
                                                        <td style="border: 1px solid #ddd; padding: 5px;">D</td>
                                                        <td style="border: 1px solid #ddd; padding: 5px;">C</td>
                                                        <td style="border: 1px solid #ddd; padding: 5px;">B</td>
                                                        <td style="border: 1px solid #ddd; padding: 5px;">B+</td>
                                                        <td style="border: 1px solid #ddd; padding: 5px;">A</td>
                                                        <td style="border: 1px solid #ddd; padding: 5px;">A+</td>
                                                        <td style="border: 1px solid #ddd; padding: 5px;">AA</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                {{-- Co-Scholastic --}}
                                <div id="item_co_scholastic" class="draggableItem" {!! !empty($style['co_scholastic']) ? $style['co_scholastic'] : 'style="position:absolute; left: 0px; top: 700px; width: 100%;"' !!}>
                                    <div style="padding: 10px; border: 1px solid #ddd;">
                                        <h6 style="font-weight: bold; background-color: {{ $colors['table_header_bg'] ?? '#22577a' }}; color: white; padding: 5px;">Co-Scholastic Activities</h6>
                                        
                                        <!-- Activity Header -->
                                        <div style="background-color: {{ $colors['table_header_bg'] ?? '#22577a' }}; color: white; padding: 5px; font-size: 10px; display: flex; justify-content: space-between; margin-bottom: 5px;">
                                            <span>ACTIVITY</span>
                                            <span>GRADE</span>
                                        </div>
                                        
                                        <!-- Summary Section -->
                                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px;">
                                            <!-- Circle - Overall -->
                                            <div id="item_overall" style="width: 60px; height: 60px;">
                                                <div style="width: 60px; height: 60px; border-radius: 50%; border: 3px solid {{ $colors['table_header_bg'] ?? '#22577a' }}; display: flex; flex-direction: column; justify-content: center; align-items: center; color: {{ $colors['table_header_bg'] ?? '#22577a' }};">
                                                    <span style="font-weight: bold; font-size: 12px;">00.00%</span>
                                                    <span style="font-size: 8px;">A</span>
                                                    <span style="font-size: 6px;">OVERALL</span>
                                                </div>
                                            </div>
                                            
                                            <!-- Attendance -->
                                            <div id="item_attendance" style="text-align: center;">
                                                <div style="text-align: center;">
                                                    <div style="color: {{ $colors['table_header_bg'] ?? '#22577a' }}; font-weight: bold; font-size: 10px;">ATTENDANCE</div>
                                                    <div style="font-weight: bold; font-size: 12px;">0 / 0 days</div>
                                                    <div style="font-size: 10px; color: #666;">0.0%</div>
                                                </div>
                                            </div>
                                            
                                            <!-- Rank -->
                                            <div id="item_rank" style="border: 1px solid #ddd; padding: 5px 10px; border-radius: 5px; text-align: center;">
                                                <div style="color: orange; font-size: 14px;">🏆</div>
                                                <div style="font-size: 10px; font-weight: bold;">Rank: 0</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Teacher Signature --}}
                                <div id="item_teacher_signature" class="draggableItem" {!! !empty($style['teacher_signature']) ? $style['teacher_signature'] : 'style="position:absolute; left: 150px; top: 650px; width: 120px; height: 80px;"' !!}>
                                    <div style="display: flex; flex-direction: column; align-items: center; width: 100%; height: 100%;">
                                        @if(!empty($settings['signature']))
                                            <img src="{{ $settings['signature'] }}" alt="teacher_signature" style="width: 100%; height: 50px; object-fit: contain; margin-bottom: 5px;">
                                        @endif
                                        <div style="width: 100%; height: 1px; background-color: #000; margin: 5px 0;"></div>
                                        <div style="font-size: 11px; font-weight: bold; text-align: center; margin-top: 2px;">Class Teacher</div>
                                    </div>
                                </div>

                                {{-- Principal Signature --}}
                                <div id="item_principal_signature" class="draggableItem" {!! !empty($style['principal_signature']) ? $style['principal_signature'] : 'style="position:absolute; left: 550px; top: 650px; width: 120px; height: 80px;"' !!}>
                                    <div style="display: flex; flex-direction: column; align-items: center; width: 100%; height: 100%;">
                                        @if(!empty($settings['principal_signature']))
                                            <img src="{{ $settings['principal_signature'] }}" alt="principal_signature" style="width: 100%; height: 50px; object-fit: contain; margin-bottom: 5px;">
                                        @endif
                                        <div style="width: 100%; height: 1px; background-color: #000; margin: 5px 0;"></div>
                                        <div style="font-size: 11px; font-weight: bold; text-align: center; margin-top: 2px;">Principal</div>
                                    </div>
                                </div>

                                {{-- Footer --}}
                                <div id="item_footer" class="draggableItem" {!! !empty($style['footer']) ? $style['footer'] : 'style="position:absolute; left: 0px; top: 900px; width: 100%; height: 50px;"' !!}>
                                    <div style="background-color: {{ $colors['footer_bg'] ?? '#22577a' }}; position: absolute; top: 0; left: 0; right: 0; bottom: 0;"></div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script>
        // Initialize visibility based on checked state
        window.onload = setTimeout(() => {
            // Only process checkboxes that are already checked
            $('.form-check-input:checked').each(function() {
                let val = $(this).val();
                let field = '#item_' + val;
                $(field).show();
            });
            
            // Hide unchecked items
            $('.form-check-input:not(:checked)').each(function() {
                let val = $(this).val();
                let field = '#item_' + val;
                $(field).hide();
            });
        }, 500);

        $('#reset-btn').click(function (e) {
            e.preventDefault();
            
            // Default positions (approximations)
            $('#item_header').css({top: '0px', left: '0px', width: '100%', height: '100px'});
            $('#item_school_name').css({top: '60px', left: '200px'});
            $('#item_school_logo').css({top: '20px', left: '20px'});
            $('#item_school_address').css({top: '100px', left: '200px'});
            $('#item_school_mobile').css({top: '130px', left: '200px'});
            $('#item_school_email').css({top: '160px', left: '200px'});
            $('#item_title').css({top: '200px', left: '200px'});
            
            $('#item_student_image').css({top: '100px', left: '650px'});
            
            // Reset description chunks
            $('.description-chunk').each(function(index) {
                $(this).css({top: (350 + (index * 40)) + 'px', left: '20px'});
            });
            
            $('#item_results_table').css({top: '500px', left: '0px'});
            $('#item_grading_system').css({top: '700px', left: '0px'});
            $('#item_co_scholastic').css({top: '800px', left: '0px'});
            $('#item_footer').css({top: '1000px', left: '0px', width: '100%', height: '50px'});
        });

        $('.form-check-input').change(function (e) {
            // e.preventDefault(); // Don't prevent default, we want checkbox to toggle
            let val = $(this).val();
            let field = '#item_' + val;
            
            // Special handling for description removed

            if ($(this).is(':checked')) {
                // Ensure header has a reasonable default size if it's being shown
                if (val === 'header') {
                    let $el = $(field);
                    let h = $el[0].style.height ? parseInt($el[0].style.height) : 0;
                    // If height is not set or very small, reset to default large size
                    if (h < 50) {
                        $el.css({
                            'top': '0px',
                            'left': '0px',
                            'width': '100%',
                            'height': '100px'
                        });
                        // Update hidden input to persist the new size
                        updateElementStyle($el[0]);
                    }
                } else if (val === 'footer') {
                    let $el = $(field);
                    let h = $el[0].style.height ? parseInt($el[0].style.height) : 0;
                    // If height is not set or very small, reset to default large size
                    if (h < 20) {
                        $el.css({
                            'top': '1000px',
                            'left': '0px',
                            'width': '100%',
                            'height': '50px'
                        });
                        // Update hidden input to persist the new size
                        updateElementStyle($el[0]);
                    }
                }
                $(field).show(500);
            } else {
                $(field).hide(500);
            }
        });

        let isDragging = false;
        let isResizing = false;
        let currentElement = null;
        let selectedElement = null;
        let offsetX, offsetY;
        let startX, startY, startWidth, startHeight, startFontSize;

        const container = document.getElementById('draggableElements');
        const draggableItems = document.querySelectorAll('.draggableItem');
        const resizeHandle = document.createElement('div');
        resizeHandle.className = 'resize-handle';
        resizeHandle.style.display = 'none';
        document.body.appendChild(resizeHandle);

        draggableItems.forEach(element => {
            element.addEventListener('click', (e) => {
                if (!isDragging && !isResizing) {
                    selectElement(element);
                    e.stopPropagation();
                }
            });
            element.addEventListener('mousedown', (e) => {
                if (e.target.classList.contains('resize-handle')) return;
                isDragging = true;
                currentElement = element;
                offsetX = e.clientX - element.getBoundingClientRect().left;
                offsetY = e.clientY - element.getBoundingClientRect().top;
                e.preventDefault();
            });
        });

        document.addEventListener('click', (e) => {
            if (!e.target.classList.contains('draggableItem') && !e.target.classList.contains('resize-handle')) {
                deselectElement();
            }
        });

        resizeHandle.addEventListener('mousedown', (e) => {
            if (selectedElement) {
                isResizing = true;
                startX = e.clientX;
                startY = e.clientY;
                startWidth = selectedElement.offsetWidth;
                startHeight = selectedElement.offsetHeight;
                e.preventDefault();
                e.stopPropagation();
            }
        });

        document.addEventListener('mousemove', (e) => {
            if (isResizing && selectedElement) {
                const deltaX = e.clientX - startX;
                const deltaY = e.clientY - startY;
                let newWidth = Math.max(30, startWidth + deltaX);
                let newHeight = Math.max(30, startHeight + deltaY);
                
                selectedElement.style.width = newWidth + 'px';
                if (!selectedElement.classList.contains('description-chunk')) {
                    selectedElement.style.height = newHeight + 'px';
                }
                
                positionResizeHandle();
            } else if (isDragging && currentElement) {
                const containerRect = container.getBoundingClientRect();
                const elemRect = currentElement.getBoundingClientRect();
                let newLeft = e.clientX - containerRect.left - offsetX;
                let newTop = e.clientY - containerRect.top - offsetY;
                
                newLeft = Math.max(0, Math.min(newLeft, containerRect.width - elemRect.width));
                newTop = Math.max(0, Math.min(newTop, containerRect.height - elemRect.height));
                
                currentElement.style.left = newLeft + 'px';
                currentElement.style.top = newTop + 'px';
                
                if (currentElement === selectedElement) positionResizeHandle();
            }
        });

        document.addEventListener('mouseup', () => {
            if (isResizing && selectedElement) {
                isResizing = false;
                updateElementStyle(selectedElement);
            } else if (isDragging && currentElement) {
                isDragging = false;
                updateElementStyle(currentElement);
                currentElement = null;
            }
        });

        function selectElement(element) {
            deselectElement();
            selectedElement = element;
            element.classList.add('selected-item');
            resizeHandle.style.display = 'block';
            positionResizeHandle();
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

        function updateElementStyle(element) {
            let style = 'style="position:absolute;';
            style += ' left: ' + element.style.left + ';';
            style += ' top: ' + element.style.top + ';';
            if (element.style.width) style += ' width: ' + element.style.width + ';';
            if (element.style.height) style += ' height: ' + element.style.height + ';';
            style += '"';
            
            let type = element.id.replace('item_', "");
            $('#' + type).val(style);
        }

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
            height: {{ $layout['height'] }};
            width: {{ $layout['width'] }};
            user-select: none;
            border: 1px solid #ccc;
            background-color: white;
            position: relative;
            margin: 0 auto;
            overflow: hidden;
        }
        .frame-border {
            height: 100%;
            width: 100%;
            border: 1px solid black;
            position: absolute;
            top: 0; left: 0;
            pointer-events: none;
        }
        .draggableItem {
            position: absolute;
            cursor: move;
            min-width: 50px;
            background-color: rgba(255, 255, 255, 0.8);
            border: 1px dashed #ccc;
            z-index: 10;
        }
        #item_header, #item_footer {
            z-index: 1 !important;
        }
        #item_header {
            min-height: 100px;
            min-width: 100%;
        }
        #item_footer {
            min-height: 50px;
            min-width: 100%;
        }
        .resize-handle {
            position: fixed;
            width: 12px; height: 12px;
            background-color: #007bff;
            border: 2px solid white;
            z-index: 10000;
            cursor: nwse-resize;
        }
        .selected-item {
            outline: 2px dashed #007bff !important;
            z-index: 100;
        }
    </style>
@endsection
