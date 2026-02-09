<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admit Card</title>
    <link rel="stylesheet" href="{{ asset('/assets/css/certificate.css') }}" />

</head>

<body>
    <div class="certificate">
        {{-- Loop --}}
        @foreach ($users as $user)
        <div class="sheet">
            <div class="template">

                {{-- Background image --}}
                @if ($admitCardTemplate->background_image)
                    <img src="{{ $admitCardTemplate->background_image }}" class="background-image" alt="">
                @else
                    <div class="frame-border"></div>
                @endif
                

                {{-- School address --}}
                @if (in_array('school_address', $admitCardTemplate->fields ?? []))
                    <div {!! $style['school_address'] ?? '' !!}>
                        {{ $settings['school_address'] }}
                    </div>
                @endif

                {{-- School mobile --}}
                @if (in_array('school_mobile', $admitCardTemplate->fields ?? []))
                    <div {!! $style['school_mobile'] ?? '' !!}>
                        {{ $settings['school_phone'] }}
                    </div>
                @endif

                {{-- School email --}}
                @if (in_array('school_email', $admitCardTemplate->fields ?? []))
                    <div {!! $style['school_email'] ?? '' !!}>
                        {{ $settings['school_email'] }}
                    </div>
                @endif

                {{-- User image --}}
                @if (in_array('user_image', $admitCardTemplate->fields ?? []))
                    <img src="{{ $user['image'] }}" {!! $style['user_image'] ?? '' !!} alt="" class="user_image">
                @endif
                
                {{-- School logo --}}
                @if (in_array('school_logo', $admitCardTemplate->fields ?? []))
                    <img src="{{ $settings['vertical_logo'] }}" {!! $style['school_logo'] ?? '' !!} alt="" class="school_logo">
                @endif

                {{-- School name --}}
                @if (in_array('school_name', $admitCardTemplate->fields ?? []))
                <div {!! $style['school_name'] ?? '' !!} class="school-name">
                    <b>{{ $settings['school_name'] }}</b>
                </div>
                @endif

                {{-- Signature --}}
                @if (in_array('signature', $admitCardTemplate->fields ?? []))
                    <img src="{{ $settings['signature'] ?? '' }}" {!! $style['signature'] ?? '' !!} alt="" class="school_logo">
                @endif

                {{-- Principal Signature --}}
                @if (in_array('principal_signature', $admitCardTemplate->fields ?? []))
                    <img src="{{ $settings['principal_signature'] ?? '' }}" {!! $style['principal_signature'] ?? '' !!} alt="" class="school_logo">
                @endif

                {{-- Issue date --}}
                @if (in_array('issue_date', $admitCardTemplate->fields ?? []))
                    <div {!! $style['issue_date'] ?? '' !!} class="issue-date">
                        {{ date($settings['date_format'],strtotime(date('Y-m-d'))) }}
                    </div>
                @endif

                {{-- Title --}}
                @if (in_array('title', $admitCardTemplate->fields ?? []))
                    <div class="title" {!! $style['title'] ?? '' !!}>
                        {{ $admitCardTemplate->name }}
                    </div>
                @endif

                {{-- Description - Split into chunks --}}
                @php
                    // Parse description and split by paragraphs
                    $description = $user['description'];
                    
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
                        <div class="description-chunk" 
                             {!! $style['description_' . $index] ?? ($index == 0 ? ($style['description'] ?? 'style="position:absolute; left: 145px; top: ' . (355 + ($index * 40)) . 'px;"') : 'style="position:absolute; left: 145px; top: ' . (355 + ($index * 40)) . 'px;"') !!}>
                            {!! $chunk !!}
                        </div>
                    @endif
                @endforeach


                {{-- Exam Timetable Table --}}
                @if (in_array('exam_timetable', $admitCardTemplate->fields ?? []) && isset($user['exam_timetable']) && count($user['exam_timetable']) > 0)
                <div class="exam-timetable" {!! $style['exam_timetable'] ?? 'style="position:absolute; left: 100px; top: 500px;"' !!}>
                    <table style="width: 100%; border-collapse: collapse; border: 1px solid #000; min-width: 600px;">
                        <thead>
                            <tr style="background-color: #f0f0f0;">
                                <th style="border: 1px solid #000; padding: 8px; text-align: left;">{{ __('Date') }}</th>
                                <th style="border: 1px solid #000; padding: 8px; text-align: left;">{{ __('Day Name') }}</th>
                                <th style="border: 1px solid #000; padding: 8px; text-align: left;">{{ __('Subject') }}</th>
                                <th style="border: 1px solid #000; padding: 8px; text-align: left;">{{ __('Start Time') }}</th>
                                <th style="border: 1px solid #000; padding: 8px; text-align: left;">{{ __('End Time') }}</th>
                                <th style="border: 1px solid #000; padding: 8px; text-align: left;">{{ __('Total Marks') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user['exam_timetable'] as $timetable)
                            <tr>
                                <td style="border: 1px solid #000; padding: 8px;">{{ date($settings['date_format'], strtotime($timetable->date)) }}</td>
                                <td style="border: 1px solid #000; padding: 8px;">{{ date('l', strtotime($timetable->date)) }}</td>
                                <td style="border: 1px solid #000; padding: 8px;">{{ $timetable->class_subject->subject->name ?? '-' }}</td>
                                <td style="border: 1px solid #000; padding: 8px;">{{ date('h:i A', strtotime($timetable->start_time)) }}</td>
                                <td style="border: 1px solid #000; padding: 8px;">{{ date('h:i A', strtotime($timetable->end_time)) }}</td>
                                <td style="border: 1px solid #000; padding: 8px;">{{ $timetable->total_marks }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
        @endforeach
        
    </div>
</body>
<style>
    body, html {
        margin: 0;
        padding: 0;
        width: 100%;
        height: 100%;
    }
    .template {
        width: {{ $layout['width'] }};
        height: {{ $layout['height'] }};
        position: relative;
        overflow: hidden; /* Prevent content from overflowing */
        box-sizing: border-box;
    }
    .frame-border {
        width: {{ $layout['width'] }};
        height: {{ $layout['height'] }};
        border: 1px solid black;
        box-sizing: border-box;
    }

    .sheet {
        width: {{ $layout['width'] }};
        height: {{ $layout['height'] }};
        page-break-after: always;
        overflow: hidden; /* Prevent overflow to next page */
        box-sizing: border-box;
    }

    /* Remove page break from the last sheet to prevent blank page */
    .sheet:last-child {
        page-break-after: auto;
    }
    .school_logo {
        height: 100px !important;
    }
    .background-image {
        width: {{ $layout['width'] }};
        height: {{ $layout['height'] }};
    }
    .user_image {
        height: {{ $admitCardTemplate->image_size }}px;
        width: {{ $admitCardTemplate->image_size }}px;
    }
    .exam-timetable {
        font-size: 12px;
    }
    .exam-timetable table {
        font-size: 11px;
    }
    
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
    
</style>
@if ($admitCardTemplate->user_image_shape == 'Round')
        <style>
            .user_image {
                border-radius: 50%;
            }
        </style>
    @else
        <style>
            .user_image {
                border-radius: 6%;
            }
        </style>
    @endif

</html>
