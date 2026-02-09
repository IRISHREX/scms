<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Seat Number</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
        }

        .certificate {
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: flex-start;
            padding: 10px;
        }

        .template {
            width: 380px;
            height: 200px;
            position: relative;
            border: 1px solid #000;
            font-size: 12px;
            overflow: hidden;
        }

        .template img {
            object-fit: cover;
        }

        /* Specific style for user image */
        .user_image {
            height: {{ $template->image_size }}px;
            width: {{ $template->image_size }}px;
            object-fit: cover;
            border-radius: 6%;
            position: absolute;
            z-index: 10;
        }
        
        @if (($template->user_image_shape ?? '') == 'Round')
        .user_image {
            border-radius: 50%;
        }
        @endif

        .template img[alt="School Logo"] {
            height: 50px;
            width: 50px;
            object-fit: contain;
            position: absolute;
        }

        .frame-border {
            width: 100%;
            height: 100%;
            border: 1px solid black;
            position: absolute;
            top: 0;
            left: 0;
            z-index: -1;
        }

        .sheet {
            width: 380px;
            height: 200px;
            float: left;
            margin: 8px;
            page-break-inside: avoid;
            display: inline-block;
        }

        .school-name {
            font-size: 14px;
        }

        @media print {
            .sheet {
                margin-bottom: 0;
            }
        }
    </style>
</head>

<body>
    <div class="certificate">
        {{-- Loop --}}
        @foreach ($studentsData as $data)
            @php
                $user = $data['user'];
                $seat_number = $data['seat_number'];
                $exam = $data['exam'];
            @endphp
            <div class="sheet">
                <div class="template">

                    <div class="frame-border"></div>

                    {{-- School name --}}
                    @if (in_array('school_name', $template->fields ?? []))
                        <div {!! $style['school_name'] ?? '' !!} class="school-name">
                            <b>{{ $settings['school_name'] }}</b>
                        </div>
                    @endif

                    {{-- Exam Name --}}
                    @if (in_array('exam_name', $template->fields ?? []))
                        <div {!! $style['exam_name'] ?? '' !!}>
                            Exam Name: {{ $exam->name ?? 'Exam Name' }}
                        </div>
                    @endif

                    {{-- Student Name --}}
                    @if (in_array('student_name', $template->fields ?? []))
                        <div {!! $style['student_name'] ?? '' !!}>
                            Name: {{ $user->full_name }}
                        </div>
                    @endif

                    {{-- Seat Number --}}
                    @if (in_array('seat_number', $template->fields ?? []))
                        <div {!! $style['seat_number'] ?? '' !!}>
                            Seat Number: {{ $seat_number }}
                        </div>
                    @endif

                    {{-- Roll Number --}}
                    @if (in_array('roll_number', $template->fields ?? []))
                        <div {!! $style['roll_number'] ?? '' !!}>
                            Roll No: {{ $user->student->roll_number }}
                        </div>
                    @endif

                    {{-- Class Section --}}
                    @if (in_array('class_section', $template->fields ?? []))
                        <div {!! $style['class_section'] ?? '' !!}>
                            Class: {{ $user->student->class_section->full_name ?? '' }}
                        </div>
                    @endif

                    {{-- User image --}}
                    @if (in_array('user_image', $template->fields ?? []))
                        <img src="{{ $user->image }}" {!! $style['user_image'] ?? '' !!} alt="" class="user_image">
                    @endif

                    {{-- School Address --}}
                    @if (in_array('school_address', $template->fields ?? []))
                        <div {!! $style['school_address'] ?? '' !!}>
                            {{ $settings['school_address'] ?? '' }}
                        </div>
                    @endif

                    {{-- Issue Date --}}
                    @if (in_array('issue_date', $template->fields ?? []))
                        <div {!! $style['issue_date'] ?? '' !!}>
                            Date: {{ isset($data['issue_date']) ? date('d-m-Y', strtotime($data['issue_date'])) : '' }}
                        </div>
                    @endif

                    {{-- Session Year --}}
                    @if (in_array('session_year', $template->fields ?? []))
                        <div {!! $style['session_year'] ?? '' !!}>
                            EXAMINATION YEAR : {{ $sessionYear->name ?? '' }}
                        </div>
                    @endif

                    {{-- School Logo --}}
                    @if (in_array('school_logo', $template->fields ?? []))
                        <img src="{{ $settings['vertical_logo'] ?? '' }}" {!! $style['school_logo'] ?? '' !!} alt="School Logo"
                            height="50px">
                    @endif

                </div>
            </div>
        @endforeach

    </div>
</body>
</html>
