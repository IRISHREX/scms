<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Student ID Cards</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <style>
        * {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html, body {
            margin: 0px !important;
        }
        .full-width {
            width: 100%;
        }
        
        table {
            border-collapse: collapse;
            border: none;
            font-size: 14px;
            z-index: 1;
        }
        .student-image {
            width: 30%;
            padding: 0px 10px;
            text-align: center;
            vertical-align: middle;
            height: 80px;
        }
        .student-data {
            text-align: left;
            padding-left: 10px;
            padding: 2px 5px;
            font-weight: bold;
        }
        .card-title {
            padding: 6px 0px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
        .school-name {
            padding-right: 10px !important;
            text-align: right;
            font-size: 20px;
            text-transform: uppercase;
            font-weight: bold;
            border-bottom-right-radius: 10px;
        }
    
        .school-logo {
            border-bottom-left-radius: 10px;
        }

        .vertical-student-data {
            text-align: left;
            padding: 2px 2px 5px 10px;
            font-weight: bold;
        }
        .signature {
            background-size: contain;
            background-position: center center;
            background-repeat: no-repeat;
            padding: 10px;
            position: absolute;
            bottom: 35px;
            right: 10px;   
        }
        .vertical-school-name {
            padding: 10px 10px !important;
            text-align: center;
            font-size: 20px;
            text-transform: uppercase;
            font-weight: bold;
            border-bottom-right-radius: 10px;
            border-bottom-left-radius: 10px;
        }
        .draggable-content {
            white-space: nowrap;
            padding: 2px;
        }
        
        /* Multiple cards per page grid */
        .cards-grid {
            width: 100%;
            display: table;
        }
        .cards-row {
            display: table-row;
        }
        .card-cell {
            display: table-cell;
            padding: 5mm;
            vertical-align: top;
        }
        
        /* Single card container */
        .card-body {
            position: relative;
            overflow: hidden;
            page-break-inside: avoid;
        }
        
        /* Cards per page layouts */
        .cards-1 .card-body {
            width: 100%;
            height: 100%;
        }
        .cards-2 .card-cell {
            width: 50%;
        }
        .cards-4 .card-cell {
            width: 50%;
        }
        .cards-6 .card-cell {
            width: 33.33%;
        }
        .cards-8 .card-cell {
            width: 25%;
        }
    </style>

    @if (isset($settings['profile_image_style']) && $settings['profile_image_style'] == 'squre')
        <style>
            .student-profile {
                border: none;
                border-radius: 6px;
                background-size: contain;
                background-position: center center;
                background-repeat: no-repeat;
                padding: 2px;
                object-fit: cover;
        }
        </style>
    @else
        <style>
            .student-profile {
                border: none;
                border-radius: 80px;
                background-size: contain;
                background-position: center center;
                background-repeat: no-repeat;
                padding: 2px;
                object-fit: cover;
        }
        </style>
    @endif

    @if (isset($settings['layout_type']) && $settings['layout_type'] == 'horizontal')
        <style>
            .background-image {
                position: fixed;
                width: auto;
                padding: 5px;
                height: auto;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                opacity: 0.2;
                z-index: -1;
            }
            .background_image {
                z-index: -1;
                object-fit: cover;
                background-size: contain;
                background-position: center center;
                background-repeat: no-repeat;
            }

        </style>
    @else
        <style>
            .background-image {
                position: fixed;
                width: auto;
                padding: 5px;
                height: auto;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                opacity: 0.2;
                z-index: -1;
            }
            .background_image {
                z-index: -1;
                object-fit: cover;
                background-size: contain;
                background-position: center center;
                background-repeat: no-repeat;
            }
        </style>
    @endif
</head>
<body>
    @php
        $cardsPerPage = (int) ($settings['cards_per_page'] ?? 1);
        $cardWidth = $settings['page_width_px'] ?? '100%';
        $cardHeight = $settings['page_height_px'] ?? '100%';
        
        // Calculate grid dimensions based on cards per page
        $cols = 1;
        $rows = 1;
        switch($cardsPerPage) {
            case 2: $cols = 2; $rows = 1; break;
            case 4: $cols = 2; $rows = 2; break;
            case 6: $cols = 3; $rows = 2; break;
            case 8: $cols = 4; $rows = 2; break;
            default: $cols = 1; $rows = 1;
        }
    @endphp

    @if (!empty($settings['student_id_card_styles']))
        @if ($cardsPerPage > 1)
            {{-- Multiple cards per page layout --}}
            @php
                $studentChunks = $students->chunk($cardsPerPage);
            @endphp
            
            @foreach ($studentChunks as $chunk)
                <div class="cards-grid cards-{{ $cardsPerPage }}" style="page-break-after: always;">
                    <table style="width: 100%; border-collapse: collapse;">
                        @for ($row = 0; $row < $rows; $row++)
                            <tr>
                                @for ($col = 0; $col < $cols; $col++)
                                    @php
                                        $index = $row * $cols + $col;
                                        $student = $chunk->values()->get($index);
                                    @endphp
                                    <td style="width: {{ 100/$cols }}%; vertical-align: top; padding: 3mm;">
                                        @if ($student)
                                            <div class="card-body" style="position: relative; width: {{ $cardWidth }}; height: {{ $cardHeight }}; overflow: hidden; border: 1px solid #ddd;">
                                                @if ($settings['background_image'] ?? '')
                                                    <img src="{{ public_path('storage/').$settings['background_image'] }}" style="position: absolute; width: 100%; height: 100%; top: 0; left: 0; object-fit: cover; z-index: -1;" alt="">
                                                @endif

                                                @if (in_array('student_name', $settings['student_id_card_fields']))
                                                    <div class="draggable-content" style="position:absolute; {{ $settings['student_id_card_styles']['student_name'] ?? '' }}"><b>{{ __('student_name') }} :</b> {{ $student->full_name }}</div>
                                                @endif
                                                
                                                @if (in_array('class_section', $settings['student_id_card_fields']))
                                                    <div class="draggable-content" style="position:absolute; {{ $settings['student_id_card_styles']['class_section'] ?? '' }}"><b>{{ __('class_section') }} :</b> {{ $student->student->class_section->full_name }}</div>
                                                @endif
                                                
                                                @if (in_array('roll_no', $settings['student_id_card_fields']))
                                                    <div class="draggable-content" style="position:absolute; {{ $settings['student_id_card_styles']['roll_no'] ?? '' }}"><b>{{ __('roll_no') }} :</b> {{ $student->student->roll_number }}</div>
                                                @endif
                                                
                                                @if (in_array('dob', $settings['student_id_card_fields']))
                                                    <div class="draggable-content" style="position:absolute; {{ $settings['student_id_card_styles']['dob'] ?? '' }}"><b>{{ __('dob') }} :</b> {{ date($settings['date_format'],strtotime($student->dob)) }}</div>
                                                @endif
                                                
                                                @if (in_array('gender', $settings['student_id_card_fields']))
                                                    <div class="draggable-content" style="position:absolute; {{ $settings['student_id_card_styles']['gender'] ?? '' }}"><b>{{ __('gender') }} :</b> {{ $student->gender }}</div>
                                                @endif
                                                
                                                @if (in_array('session_year', $settings['student_id_card_fields']))
                                                    <div class="draggable-content" style="position:absolute; {{ $settings['student_id_card_styles']['session_year'] ?? '' }}"><b>{{ __('session_year') }} :</b> {{ $sessionYear->name }}</div>
                                                @endif
                                                
                                                @if (in_array('guardian_name', $settings['student_id_card_fields']))
                                                    <div class="draggable-content" style="position:absolute; {{ $settings['student_id_card_styles']['guardian_name'] ?? '' }}"><b>{{ __('guardian') }} {{ __('name') }} :</b> {{ $student->student->guardian->full_name }}</div>
                                                @endif
                                                
                                                @if (in_array('guardian_contact', $settings['student_id_card_fields']))
                                                    <div class="draggable-content" style="position:absolute; {{ $settings['student_id_card_styles']['guardian_contact'] ?? '' }}"><b>{{ __('guardian') }} {{ __('contact') }} :</b> {{ $student->student->guardian->mobile }}</div>
                                                @endif

                                                @if (in_array('profile_image', $settings['student_id_card_fields']))
                                                    @if ($student->getRawOriginal('image'))
                                                        <img class="student-profile"
                                                            src="{{ public_path('storage/').$student->getRawOriginal('image') }}"
                                                            style="position:absolute; height:80px; width:80px; {{ $settings['student_id_card_styles']['profile_image'] ?? '' }}">
                                                    @else
                                                       <img class="student-profile"
                                                                    src="{{ public_path($student->getRawOriginal('image') ?: 'assets/dummy_logo.jpg') }}"
                                                                    alt="profile"
                                                                    style="position:absolute; height:80px; width:80px; {{ $settings['student_id_card_styles']['profile_image'] ?? '' }}">
                                                    @endif
                                                @endif

                                                @if (in_array('school_name', $settings['student_id_card_fields']))
                                                    <div class="draggable-content" style="position:absolute; {{ $settings['student_id_card_styles']['school_name'] ?? '' }}">
                                                        <span style="font-weight: bold;">{{ $settings['school_name'] }}</span></div>
                                                @endif

                                                @if (in_array('school_address', $settings['student_id_card_fields']))
                                                    <div class="draggable-content" style="position:absolute; {{ $settings['student_id_card_styles']['school_address'] ?? '' }}">{{ $settings['school_address'] }}</div>
                                                @endif

                                                @if (in_array('school_logo', $settings['student_id_card_fields']))
                                                    <img
                                                        src="{{ public_path(!empty($settings['vertical_logo']) ? ltrim(parse_url($settings['vertical_logo'], PHP_URL_PATH), '/') : 'assets/dummy_logo.jpg') }}"
                                                        alt="school_logo"
                                                        style="position:absolute; height:50px; width:50px; object-fit:cover; {{ $settings['student_id_card_styles']['school_logo'] ?? '' }}"
                                                    >
                                                @endif

                                                @if (in_array('signature', $settings['student_id_card_fields']))
                                                    <img src="{{ public_path('storage/' . $settings['signature']) }}"
                                                        style="position:absolute; height:40px; width:80px; object-fit:contain; {{ $settings['student_id_card_styles']['signature'] ?? '' }}">
                                                @endif

                                                {{-- Extra Fields --}}
                                                @foreach ($student->extra_student_details as $data)
                                                    @if ($data->form_field && $data->form_field->display_on_id == 1)
                                                        <div class="draggable-content" style="position:absolute; {{ $settings['student_id_card_styles'][$data->form_field->id] ?? '' }}">
                                                            <b>{{ $data->form_field->name }} :</b> 
                                                            @if (in_array($data->form_field->type, ['text','number','radio','textarea']))
                                                                {{ $data->data }}
                                                            @elseif($data->form_field->type == 'dropdown')
                                                                {!! isset($data->form_field->default_values[$data->data]) ? $data->form_field->default_values[$data->data] : $data->data !!}
                                                            @elseif($data->form_field->type == 'checkbox')
                                                                {!! implode(",",json_decode($data->data ?? '[]')) !!}
                                                            @endif
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                @endfor
                            </tr>
                        @endfor
                    </table>
                </div>
            @endforeach
        @else
            {{-- Single card per page layout (original behavior) --}}
            @foreach ($students as $student)
                <div class="card-body" style="position: relative; width: 100%; height: 100%; overflow: hidden;">
                    @if ($settings['background_image'] ?? '')
                        <img src="{{ public_path('storage/').$settings['background_image'] }}" style="position: absolute; width: 100%; height: 100%; top: 0; left: 0; object-fit: cover; z-index: -1;" alt="">
                    @endif

                    @if (in_array('student_name', $settings['student_id_card_fields']))
                        <div class="draggable-content" style="position:absolute; {{ $settings['student_id_card_styles']['student_name'] ?? '' }}"><b>{{ __('student_name') }} :</b> {{ $student->full_name }}</div>
                    @endif
                    
                    @if (in_array('class_section', $settings['student_id_card_fields']))
                        <div class="draggable-content" style="position:absolute; {{ $settings['student_id_card_styles']['class_section'] ?? '' }}"><b>{{ __('class_section') }} :</b> {{ $student->student->class_section->full_name }}</div>
                    @endif
                    
                    @if (in_array('roll_no', $settings['student_id_card_fields']))
                        <div class="draggable-content" style="position:absolute; {{ $settings['student_id_card_styles']['roll_no'] ?? '' }}"><b>{{ __('roll_no') }} :</b> {{ $student->student->roll_number }}</div>
                    @endif
                    
                    @if (in_array('dob', $settings['student_id_card_fields']))
                        <div class="draggable-content" style="position:absolute; {{ $settings['student_id_card_styles']['dob'] ?? '' }}"><b>{{ __('dob') }} :</b> {{ date($settings['date_format'],strtotime($student->dob)) }}</div>
                    @endif
                    
                    @if (in_array('gender', $settings['student_id_card_fields']))
                        <div class="draggable-content" style="position:absolute; {{ $settings['student_id_card_styles']['gender'] ?? '' }}"><b>{{ __('gender') }} :</b> {{ $student->gender }}</div>
                    @endif
                    
                    @if (in_array('session_year', $settings['student_id_card_fields']))
                        <div class="draggable-content" style="position:absolute; {{ $settings['student_id_card_styles']['session_year'] ?? '' }}"><b>{{ __('session_year') }} :</b> {{ $sessionYear->name }}</div>
                    @endif
                    
                    @if (in_array('guardian_name', $settings['student_id_card_fields']))
                        <div class="draggable-content" style="position:absolute; {{ $settings['student_id_card_styles']['guardian_name'] ?? '' }}"><b>{{ __('guardian') }} {{ __('name') }} :</b> {{ $student->student->guardian->full_name }}</div>
                    @endif
                    
                    @if (in_array('guardian_contact', $settings['student_id_card_fields']))
                        <div class="draggable-content" style="position:absolute; {{ $settings['student_id_card_styles']['guardian_contact'] ?? '' }}"><b>{{ __('guardian') }} {{ __('contact') }} :</b> {{ $student->student->guardian->mobile }}</div>
                    @endif

                    @if (in_array('profile_image', $settings['student_id_card_fields']))
                        @if ($student->getRawOriginal('image'))
                            <img class="student-profile"
                                src="{{ public_path('storage/').$student->getRawOriginal('image') }}"
                                style="position:absolute; height:80px; width:80px; {{ $settings['student_id_card_styles']['profile_image'] ?? '' }}">
                        @else
                           <img class="student-profile"
                                        src="{{ public_path($student->getRawOriginal('image') ?: 'assets/dummy_logo.jpg') }}"
                                        alt="profile"
                                        style="position:absolute; height:80px; width:80px; {{ $settings['student_id_card_styles']['profile_image'] ?? '' }}">

                        @endif
                    @endif

                    @if (in_array('school_name', $settings['student_id_card_fields']))
                        <div class="draggable-content" style="position:absolute; {{ $settings['student_id_card_styles']['school_name'] ?? '' }}">
                            <span style="font-weight: bold;">{{ $settings['school_name'] }}</span></div>
                    @endif

                    @if (in_array('school_address', $settings['student_id_card_fields']))
                        <div class="draggable-content" style="position:absolute; {{ $settings['student_id_card_styles']['school_address'] ?? '' }}">{{ $settings['school_address'] }}</div>
                    @endif

                    @if (in_array('school_logo', $settings['student_id_card_fields']))
                        <img
                            src="{{ public_path(!empty($settings['vertical_logo']) ? ltrim(parse_url($settings['vertical_logo'], PHP_URL_PATH), '/') : 'assets/dummy_logo.jpg') }}"
                            alt="school_logo"
                            style="position:absolute; height:50px; width:50px; object-fit:cover; {{ $settings['student_id_card_styles']['school_logo'] ?? '' }}"
                        >
                    @endif

                    @if (in_array('signature', $settings['student_id_card_fields']))
                        <img src="{{ public_path('storage/' . $settings['signature']) }}"
                            style="position:absolute; height:40px; width:80px; object-fit:contain; {{ $settings['student_id_card_styles']['signature'] ?? '' }}">
                    @endif

                    {{-- Extra Fields --}}
                    @foreach ($student->extra_student_details as $data)
                        @if ($data->form_field && $data->form_field->display_on_id == 1)
                            <div class="draggable-content" style="position:absolute; {{ $settings['student_id_card_styles'][$data->form_field->id] ?? '' }}">
                                <b>{{ $data->form_field->name }} :</b> 
                                @if (in_array($data->form_field->type, ['text','number','radio','textarea']))
                                    {{ $data->data }}
                                @elseif($data->form_field->type == 'dropdown')
                                    {!! isset($data->form_field->default_values[$data->data]) ? $data->form_field->default_values[$data->data] : $data->data !!}
                                @elseif($data->form_field->type == 'checkbox')
                                    {!! implode(",",json_decode($data->data ?? '[]')) !!}
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
            @endforeach
        @endif
    @else
        {{-- Fallback layout when no custom styles are set --}}
        @foreach ($students as $key => $student)
            <div class="card-body" style="height: {{ $settings['page_height'] ?? '100%' }};">
                @if ($settings['layout_type'] == 'horizontal')
                    <table class="table full-width">
                        <tr class="header">
                            <th class="school-logo">
                                @if ($settings['horizontal_logo'] ?? '')
                                    <img height="40" src="{{ public_path('storage/').$settings['horizontal_logo'] }}" alt="">
                                @else
                                    <img height="40" src="{{ public_path('assets/horizontal-logo2.svg') }}" alt="">
                                @endif
                            </th>
                            <th class="school-name" colspan="2">{{ $settings['school_name'] }}</th>
                        </tr>
                        <tr>
                            <th class="card-title" colspan="3">Student Identification Card</th>
                        </tr>
                        <tr>
                            <td class="student-image" rowspan="{{ count($settings['student_id_card_fields']) + count($student->extra_student_details) }}">
                                @if ($student->getRawOriginal('image'))
                                    <img class="student-profile" height="120" width="120" align="center" src="{{ public_path('storage/').$student->getRawOriginal('image') }}" alt="">
                                @else
                                    <img class="student-profile" height="120" width="120" align="center" src="{{ public_path('assets/dummy_logo.jpg') }}" alt="">    
                                @endif
                                
                            </td>
                            @if (in_array('student_name',$settings['student_id_card_fields']))
                                <th class="student-data">Student Name :</th>
                                <td>{{ $student->full_name }}</td>
                            @endif
                            
                        </tr>
                        @if (in_array('class_section',$settings['student_id_card_fields']))
                        <tr>
                            <th class="student-data">Class Section :</th>
                            <td>{{ $student->student->class_section->full_name }}</td>
                        </tr>
                        @endif

                        @if (in_array('roll_no',$settings['student_id_card_fields']))
                        <tr>
                            <th class="student-data">Roll No. :</th>
                            <td>{{ $student->student->roll_number }}</td>
                        </tr>
                        @endif

                        @if (in_array('dob',$settings['student_id_card_fields']))
                        <tr>
                            <th class="student-data">DOB :</th>
                            <td>{{ date($settings['date_format'],strtotime($student->dob)) }}</td>
                        </tr>
                        @endif

                        @if (in_array('gender',$settings['student_id_card_fields']))
                        <tr>
                            <th class="student-data">Gender :</th>
                            <td style="text-transform: capitalize">{{ $student->gender }}</td>
                        </tr>
                        @endif

                        @if (in_array('session_year',$settings['student_id_card_fields']))
                        <tr>
                            <th class="student-data">Session Year :</th>
                            <td>{{ $sessionYear->name }}</td>
                        </tr>
                        @endif

                        @if (in_array('guardian_name',$settings['student_id_card_fields']))
                        <tr>
                            <th class="student-data">Guardian Name :</th>
                            <td>{{ $student->student->guardian->full_name }}</td>
                        </tr>
                        @endif

                        @if (in_array('guardian_contact',$settings['student_id_card_fields']))
                        <tr>
                            <th class="student-data">Guardian Contact :</th>
                            <td>{{ $student->student->guardian->mobile }}</td>
                        </tr>
                        @endif

                        <?php $processedFieldsHorizontal = []; ?>
                        @foreach ($student->extra_student_details as $data)
                            @if ($data->form_field && $data->form_field->display_on_id == 1)
                                @php
                                    // Skip this iteration if we've already processed a field with this name
                                    $fieldName = $data->form_field->name;
                                    if (isset($processedFieldsHorizontal[$fieldName])) continue;
                                    $processedFieldsHorizontal[$fieldName] = true;
                                @endphp
                                
                                @if (in_array($data->form_field->type, ['text','number','radio','textarea']))
                                    <tr>
                                        <th class="vertical-student-data">{{ $data->form_field->name }} :</th>
                                        <td>{{ $data->data }}</td>
                                    </tr>
                                @elseif($data->form_field->type == 'dropdown')
                                    <tr>
                                        <th class="vertical-student-data">{{ $data->form_field->name }} :</th>
                                        <td>{!! isset($data->form_field->default_values[$data->data]) ? $data->form_field->default_values[$data->data] : $data->data !!}</td>
                                    </tr>
                                @elseif($data->form_field->type == 'checkbox')
                                    <tr>
                                        <th class="vertical-student-data">{{ $data->form_field->name }} :</th>
                                        <td>{!! implode(",",json_decode($data->data ?? '[]')) !!}</td>
                                    </tr>
                                @endif
                            @endif
                        @endforeach

                        <tr>
                            <td></td>
                            <td colspan="">
                                @if ($settings['signature'] ?? '')
                                    <img height="40" width="100" align="center" src="{{ public_path('storage/' . $settings['signature']) }}" alt="" style="object-fit: contain;">
                                    <span style="display: inline-block; margin-left: 10px;"><b>Signature</b></span>
                                @endif
                            </td>
                        </tr>
                    </table>
                @else
                    {{-- Vertical --}}
                    <table class="table full-width">
                        <tr class="header">
                            <th class="vertical-school-name" colspan="2">{{ $settings['school_name'] }}</th>
                        </tr>
                        <tr>
                            <th colspan="2">
                                @if ($settings['horizontal_logo'] ?? '')
                                    <img height="40" style="padding-top: 5px" src="{{ public_path('storage/').$settings['horizontal_logo'] }}" alt="">
                                @else
                                    <img height="40" style="padding-top: 5px" src="{{ public_path('assets/horizontal-logo2.svg') }}" alt="">
                                @endif
                            </th>
                        </tr>
                        <tr>
                            <th class="card-title" colspan="2" style="font-size: 12px">Student Identification Card</th>
                        </tr>
                        <tr>
                            <td class="student-image" colspan="2">
                                @if ($student->getRawOriginal('image'))
                                    <img class="student-profile" height="120" width="120" align="center" src="{{ public_path('storage/').$student->getRawOriginal('image') }}" alt="">
                                @else
                                    <img class="student-profile" height="120" width="120" align="center" src="{{ public_path('assets/dummy_logo.jpg') }}" alt="">    
                                @endif
                            </td>
                        </tr>

                        @if (in_array('student_name',$settings['student_id_card_fields']))
                        <tr>
                            <th class="vertical-student-data">Name :</th>
                            <td>{{ $student->full_name }}</td>
                        </tr>
                        @endif

                        @if (in_array('class_section',$settings['student_id_card_fields']))
                        <tr>
                            <th class="vertical-student-data">Class Section :</th>
                            <td>{{ $student->student->class_section->full_name }}</td>
                        </tr>
                        @endif

                        @if (in_array('roll_no',$settings['student_id_card_fields']))
                        <tr>
                            <th class="vertical-student-data">Roll No. :</th>
                            <td>{{ $student->student->roll_number }}</td>
                        </tr>
                        @endif

                        @if (in_array('dob',$settings['student_id_card_fields']))
                        <tr>
                            <th class="vertical-student-data">DOB :</th>
                            <td>{{ date($settings['date_format'],strtotime($student->dob)) }}</td>
                        </tr>
                        @endif

                        @if (in_array('gender',$settings['student_id_card_fields']))
                        <tr>
                            <th class="vertical-student-data">Gender :</th>
                            <td style="text-transform: capitalize">{{ $student->gender }}</td>
                        </tr>
                        @endif

                        @if (in_array('session_year',$settings['student_id_card_fields']))
                        <tr>
                            <th class="vertical-student-data">Session Year :</th>
                            <td>{{ $sessionYear->name }}</td>
                        </tr>
                        @endif

                        @if (in_array('guardian_name',$settings['student_id_card_fields']))
                        <tr>
                            <th class="vertical-student-data">Guardian Name :</th>
                            <td>{{ $student->student->guardian->full_name }}</td>
                        </tr>
                        @endif

                        @if (in_array('guardian_contact',$settings['student_id_card_fields']))
                        <tr>
                            <th class="vertical-student-data">Guardian Contact :</th>
                            <td>{{ $student->student->guardian->mobile }}</td>
                        </tr>
                        @endif

                        <?php $processedFieldsVertical = []; ?>
                        @foreach ($student->extra_student_details as $data)
                            @if ($data->form_field && $data->form_field->display_on_id == 1)
                                @php
                                    // Skip this iteration if we've already processed a field with this name
                                    $fieldName = $data->form_field->name;
                                    if (isset($processedFieldsVertical[$fieldName])) continue;
                                    $processedFieldsVertical[$fieldName] = true;
                                @endphp
                                
                                @if (in_array($data->form_field->type, ['text','number','radio','textarea']))
                                    <tr>
                                        <th class="vertical-student-data">{{ $data->form_field->name }} :</th>
                                        <td>{{ $data->data }}</td>
                                    </tr>
                                @elseif($data->form_field->type == 'dropdown')
                                    <tr>
                                        <th class="vertical-student-data">{{ $data->form_field->name }} :</th>
                                        <td>{!! isset($data->form_field->default_values[$data->data]) ? $data->form_field->default_values[$data->data] : $data->data !!}</td>
                                    </tr>
                                @elseif($data->form_field->type == 'checkbox')
                                    <tr>
                                        <th class="vertical-student-data">{{ $data->form_field->name }} :</th>
                                        <td>{!! implode(",",json_decode($data->data ?? '[]')) !!}</td>
                                    </tr>
                                @endif
                            @endif
                        @endforeach

                        <tr>
                            <td></td>
                            <td>
                                @if ($settings['signature'] ?? '')
                                    <img height="40" width="100" align="center" src="{{ public_path('storage/' . $settings['signature']) }}" alt="" style="object-fit: contain;">
                                    <span style="display: inline-block; margin-left: 10px;"><b>Signature</b></span>
                                @endif
                            </td>
                        </tr>
                    </table>
                @endif
                <div class="footer">
                    <span class="footer-text" style="padding-right:10px;">Valid Until {{ $valid_until }}</span>
                </div>
                @if (isset($settings['layout_type']) && $settings['layout_type'] == 'horizontal')
                    <div class="background-image">
                        @if ($settings['background_image'] ?? '')
                            <img src="{{ public_path('storage/').$settings['background_image'] }}" class="background_image" height="140" width="360" alt="">
                            
                        @endif
                    </div>
                @else
                    <div class="background-image">
                        @if ($settings['background_image'] ?? '')
                            <img src="{{ public_path('storage/').$settings['background_image'] }}" class="background_image" height="140" width="280" alt="">
                            
                        @endif
                    </div>
                @endif
            </div>
        @endforeach
    @endif 
</body>
</html>
