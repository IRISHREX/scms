<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings['school_name'] ?? 'School Name' }} - Bulk Results</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @media print {
            @page {
                size: A4;
                margin: 0.5cm;
            }

            body {
                margin: 0;
                padding: 0;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                font-size: 10pt;
            }

            .report-card {
                width: 100%;
                max-width: none;
                margin: 0;
                box-shadow: none;
                border-radius: 0;
                padding: 0;
                page-break-after: always;
            }

            .page-break {
                page-break-after: always;
                page-break-inside: avoid;
            }

            .header {
                padding: 10px;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .student-profile {
                padding: 8px 10px;
            }

            .content-section {
                padding: 8px 10px;
            }

            .grades-table th,
            .grade-system-table th {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .grades-table {
                width: 100%;
                border-collapse: collapse;
                border: 1px solid var(--secondary);
                border-radius: 4px;
                overflow: hidden;
                margin-bottom: 10px;
                font-size: 8pt;
                table-layout: fixed;
            }

            .grades-table th,
            .grades-table td {
                border: 1px solid #0084a9;
                padding: 3px;
                vertical-align: middle;
            }

            .grades-table th {
                background-color: var(--primary);
                color: white;
                font-weight: 600;
                text-align: center;
            }

            .grades-table td:first-child {
                text-align: left;
                font-weight: 600;
                color: #000;
            }

            .grades-table tr.total-row td {
                background-color: #e8f5f7;
                font-weight: 700;
            }

            .no-print {
                display: none !important;
            }

            .footer {
                position: relative;
                width: 100%;
                background: white;
                padding: 5px 10px;
            }

            .signature-area {
                display: flex !important;
                justify-content: space-between !important;
                flex-direction: row !important;
                page-break-inside: avoid;
                margin-top: 15px;
            }

            .no-print {
                display: none !important;
            }

            table {
                page-break-inside: avoid;
            }

            .section-title {
                page-break-after: avoid;
            }

            .student-name {
                font-size: 14pt;
            }

            .section-title {
                font-size: 11pt;
            }

            .info-item {
                font-size: 8pt;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .mb-4 {
                margin-bottom: 1rem !important;
            }

            .mt-3 {
                margin-top: 1rem !important;
            }

            .p-3 {
                padding: 0.75rem !important;
            }
        }

        :root {
            --primary:
                {{ $settings['theme_color'] ?? '#22577a' }}
            ;
            --secondary:
                {{ $settings['secondary_color'] ?? '#38a3a5' }}
            ;
            --accent:
                {{ $settings['primary_color'] ?? '#22577a' }}
            ;
            --light:
                {{ $settings['primary_background_color'] ?? '#f2f5f7' }}
            ;
            --dark:
                {{ $settings['text_secondary_color'] ?? '#2d2c2fb5' }}
            ;
            --success: #4CAF50;
            --info: #2196F3;
            --warning: #FF9800;
            --danger: #f72585;
            --hover:
                {{ $settings['primary_hover_color'] ?? '#143449' }}
            ;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--light);
            color: var(--dark);
            margin: 0;
            padding: 0;
        }

        .report-card {
            max-width: 1000px;
            margin: 0px auto;
            background-color: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            overflow: hidden;
            position: relative;
        }

        .header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 15px 10px;
            text-align: center;
            position: relative;
        }

        .header h2 {
            font-weight: 700;
            margin-bottom: 0;
            letter-spacing: 1px;
            font-size: 18px;
        }

        .student-profile {
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
        }

        .student-details {
            flex: 1;
        }

        .student-name {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--dark);
        }

        .student-info {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .info-item {
            background-color: var(--light);
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 11px;
        }

        .percentage-circle {
            width: 80px;
            height: 80px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .percentage-value {
            font-size: 16px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 2px;
        }

        .grade-value {
            font-size: 14px;
            font-weight: 600;
            color: var(--secondary);
            margin-bottom: 2px;
        }

        .percentage-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--dark);
        }

        .content-section {
            padding: 10px;
        }

        .section-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--primary);
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 6px;
        }

        .grades-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 10px;
            font-size: 9pt;
        }

        .grades-table th {
            background-color: var(--primary);
            color: white;
            font-weight: 500;
            text-align: center;
            padding: 6px;
            white-space: nowrap;
        }

        .grades-table td {
            padding: 6px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }

        .grades-table td:first-child {
            text-align: left;
            font-weight: 500;
        }

        .grades-table tr:last-child td {
            border-bottom: none;
        }

        .grades-table tr:nth-child(even) {
            background-color: var(--light);
        }

        .total-row {
            background-color: var(--light) !important;
            font-weight: 600;
        }

        .marks-obtained {
            font-weight: 600;
            color: var(--primary);
        }

        .two-columns {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }

        .column {
            flex: 1;
        }

        .signature-area {
            display: flex;
            justify-content: space-between;
            margin-top: 100px;
            padding-top: 10px;
        }

        .signature-box {
            text-align: center;
        }

        .signature-line {
            width: 150px;
            height: 1px;
            background-color: var(--dark);
            margin: 5px auto;
        }

        .signature-name {
            font-weight: 600;
            margin-bottom: 0;
            color: var(--dark);
            font-size: 11px;
        }

        .signature-title {
            font-size: 10px;
            color: var(--dark);
        }

        .footer {
            padding: 8px 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #eee;
            font-size: 11px;
            color: var(--dark);
        }

        .issued-date {
            font-weight: 500;
        }

        .page-number {
            color: var(--dark);
        }

        .toolbar {
            background: white;
            padding: 15px;
            border-bottom: 2px solid var(--primary);
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .print-btn {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .print-btn:hover {
            background-color: var(--hover);
        }

        .co-scholastic-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .co-scholastic-table th {
            background-color: var(--secondary);
            color: white;
            font-weight: 500;
            text-align: left;
            padding: 6px;
        }

        .co-scholastic-table td {
            padding: 6px;
            border-bottom: 1px solid #eee;
        }

        .co-scholastic-table td:last-child {
            text-align: center;
            font-weight: 600;
        }

        .co-scholastic-table tr:last-child td {
            border-bottom: none;
        }

        .attendance-box {
            background-color: var(--light);
            border-radius: 6px;
            padding: 8px;
            text-align: center;
            margin-top: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .attendance-value {
            font-size: 16px;
            font-weight: 600;
            color: var(--primary);
        }

        .reflection-box {
            background-color: var(--light);
            border-radius: 6px;
            padding: 8px;
            margin-top: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .grade-system-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #0084a9;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            margin-top: 15px;
            font-size: 9pt;
        }

        .grade-system-table th {
            background-color: var(--primary);
            color: white;
            font-weight: 600;
            text-align: center;
            padding: 6px;
            border: 1px solid #0084a9;
        }

        .grade-system-table td {
            padding: 6px;
            text-align: center;
            border: 1px solid #0084a9;
            font-weight: 500;
        }

        .grade-system-table tr:nth-child(even) {
            background-color: var(--light);
        }

        .activity-grade-box {
            background-color: var(--secondary);
            color: white;
            border-radius: 6px;
            padding: 6px 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .activity-content {
            background-color: #f9f9f9;
            border-radius: 0 0 6px 6px;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            position: relative;
        }

        .rank-box {
            background: white;
            border-radius: 6px;
            padding: 6px 12px;
            text-align: center;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--secondary);
        }

        @media (max-width: 768px) {
            .student-profile {
                flex-direction: column;
                text-align: center;
            }

            .percentage-circle {
                margin-top: 10px;
            }

            .two-columns {
                flex-direction: column;
            }

            .signature-area {
                flex-direction: column;
                gap: 10px;
            }

            .toolbar {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <div class="toolbar no-print">
        <button class="print-btn" onclick="window.print()">
            <i class="fas fa-print"></i> Print All Results
        </button>
        <span style="color: var(--dark); display: flex; align-items: center;">
            Total Students: <strong style="margin-left: 5px;">{{ count($allStudentsData) }}</strong>
        </span>
    </div>

    @foreach($allStudentsData as $key => $studentData)
        @php
            $result = $studentData['result'];
            $results = $studentData['results'];
            $studentAttendanceCount = $studentData['studentAttendanceCount'];
            $attendanceTotal = $studentData['attendanceTotal'];
        @endphp

        <div class="report-card">
            <div class="header d-flex justify-content-between align-items-center p-2"
                style="background: #22577a; color: white;">
                <div class="school-logo bg-white p-1 rounded">
                    @if(isset($settings['horizontal_logo']) && $settings['horizontal_logo'])
                        <img src="{{ $settings['horizontal_logo'] }}" alt="School Logo" style="height: 40px;">
                    @else
                        <img src="{{ asset('assets/vertical-logo.svg') }}" alt="School Logo" style="height: 40px;">
                    @endif
                </div>
                <div class="text-center">
                    <h4 class="mb-0 fw-bold" style="font-size: 16px;">{{ $settings['school_name'] ?? 'School Name' }}</h4>
                    <small style="font-size: 10px;">{{ $settings['school_address'] ?? 'School Address' }}</small><br>
                    <small style="font-size: 9px;">{{ $settings['school_email'] ?? 'info@school.com' }} |
                        {{ $settings['school_phone'] ?? '0000000000' }}</small>
                </div>
                <div class="student-photo">
                    @if(isset($result->user->image) && $result->user->image)
                        <img src="{{ $result->user->image }}" alt="Student Photo"
                            style="height: 60px; width: 60px; border-radius: 50%;">
                    @else
                        <div
                            style="height: 60px; width: 60px; border: 2px solid white; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-align: center; color: white; font-size: 9px;">
                            Photo
                        </div>
                    @endif
                </div>
            </div>

            <div class="content-section border-top">
                <h5 class="text-center fw-bold mt-2 mb-1" style="font-size: 14px;">Student Information</h5>
                <table class="table table-bordered table-sm mb-2" style="font-size: 9pt;">
                    <tbody>
                        <tr>
                            <td><strong>Name :</strong> {{ $result->user->first_name }} {{ $result->user->last_name }}</td>
                            <td><strong>DOB :</strong> {{ date('d-m-Y', strtotime($result->user->dob)) }}</td>
                            <td><strong>GR No. :</strong> {{ $result->user->student->admission_no }}</td>
                        </tr>
                        <tr>
                            <td><strong>Guardian :</strong> {{ $result->user->student->guardian->first_name ?? 'N/A' }}</td>
                            <td><strong>Class :</strong> {{ $result->class_section->full_name }}</td>
                            <td><strong>Exam :</strong> {{ $result->exam->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td colspan="2"><strong>Session :</strong> {{ $result->session_year->name ?? '-' }}</td>
                            <td><strong>Roll No. :</strong> {{ $result->user->student->roll_number ?? 'N/A' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="content-section">
                <div class="section-title">
                    <i class="fas fa-book"></i> Scholastic Achievements
                </div>

                @php
                    $all_subjects = [];
                    $exam_marks_data = [];
                    $exam_mark_types = [];

                    foreach ($results as $res) {
                        $marks = json_decode($res->marks, true);
                        if ($marks) {
                            $exam_id = $res->exam_id;
                            if (!isset($exam_mark_types[$exam_id])) {
                                $exam_mark_types[$exam_id] = [];
                            }
                            foreach ($marks as $mark) {
                                $subject_name = $mark['subject'];
                                if (!in_array($subject_name, $all_subjects)) {
                                    $all_subjects[] = $subject_name;
                                }
                                $exam_marks_data[$exam_id][$subject_name] = $mark['marks'];
                                $current_mark_types = array_keys($mark['marks']);
                                $exam_mark_types[$exam_id] = array_unique(array_merge($exam_mark_types[$exam_id], $current_mark_types));
                            }
                        }
                    }
                @endphp
                <table class="grades-table">
                    <thead>
                        <tr>
                            <th rowspan="2" style="width: 15%;">SUBJECT</th>
                            @foreach ($exams as $exam)
                                @php
                                    $types = $exam_mark_types[$exam->id] ?? [];
                                    $colspan = count($types) > 0 ? count($types) + 2 : 2;
                                @endphp
                                <th colspan="{{ $colspan }}">{{ $exam->name }}</th>
                            @endforeach
                            <th colspan="2" rowspan="2" style="width: 10%;">GR. TOTAL</th>
                        </tr>
                        <tr>
                            @foreach ($exams as $exam)
                                @php
                                    $types = $exam_mark_types[$exam->id] ?? [];
                                @endphp
                                @if (!empty($types))
                                    @foreach ($types as $type)
                                        <th>{{ ucfirst($type) }}</th>
                                    @endforeach
                                    <th>Total</th>
                                    <th>Grade</th>
                                @else
                                    <th>Marks</th>
                                    <th>Grade</th>
                                @endif
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($all_subjects as $subject)
                            <tr>
                                <td>{{ $subject }}</td>
                                @php
                                    $subject_grand_total = 0;
                                @endphp
                                @foreach ($exams as $exam)
                                    @php
                                        $marks = $exam_marks_data[$exam->id][$subject] ?? [];
                                        $types = $exam_mark_types[$exam->id] ?? [];
                                        $exam_subject_total = 0;
                                    @endphp
                                    @if (!empty($types))
                                        @foreach ($types as $type)
                                            <td>{{ $marks[$type] ?? '-' }}</td>
                                            @php
                                                $exam_subject_total += $marks[$type] ?? 0;
                                            @endphp
                                        @endforeach
                                        <td>{{ $exam_subject_total }}</td>
                                        <td>
                                            @php
                                                $grade = '';
                                                $percentage = $exam_subject_total;
                                                foreach ($grades as $g) {
                                                    if ($percentage >= $g->starting_range && $percentage <= $g->ending_range) {
                                                        $grade = $g->grade;
                                                        break;
                                                    }
                                                }
                                            @endphp
                                            {{ $grade }}
                                        </td>
                                    @else
                                        <td>-</td>
                                        <td>-</td>
                                    @endif
                                    @php
                                        $subject_grand_total += $exam_subject_total;
                                    @endphp
                                @endforeach
                                <td class="marks-obtained">{{ $subject_grand_total }}</td>
                                <td>
                                    @php
                                        $final_grade = '';
                                        $percentage = ($subject_grand_total / (count($exams) * 100)) * 100;
                                        foreach ($grades as $g) {
                                            if ($percentage >= $g->starting_range && $percentage <= $g->ending_range) {
                                                $final_grade = $g->grade;
                                                break;
                                            }
                                        }
                                    @endphp
                                    {{ $final_grade }}
                                </td>
                            </tr>
                        @endforeach

                        <tr class="total-row">
                            <td>Total Marks</td>
                            @php
                                $grand_total = 0;
                                $grand_total_grade = '';
                            @endphp
                            @foreach ($exams as $exam)
                                @php
                                    $types = $exam_mark_types[$exam->id] ?? [];
                                    $exam_grand_total = 0;
                                @endphp
                                @if (!empty($types))
                                    @foreach ($types as $type)
                                        @php
                                            $type_total = 0;
                                            if (isset($exam_marks_data[$exam->id])) {
                                                foreach ($exam_marks_data[$exam->id] as $subject => $marks) {
                                                    $type_total += $marks[$type] ?? 0;
                                                }
                                            }
                                        @endphp
                                        <td>{{ $type_total }}</td>
                                        @php $exam_grand_total += $type_total; @endphp
                                    @endforeach
                                    <td>{{ $exam_grand_total }}</td>
                                    <td>
                                        @php
                                            $exam_percentage = ($exam_grand_total / (count($all_subjects) * 100)) * 100;
                                            $exam_grade = '';
                                            foreach ($grades as $g) {
                                                if ($exam_percentage >= $g->starting_range && $exam_percentage <= $g->ending_range) {
                                                    $exam_grade = $g->grade;
                                                    break;
                                                }
                                            }
                                        @endphp
                                        {{ $exam_grade }}
                                    </td>
                                @else
                                    <td>-</td>
                                    <td>-</td>
                                @endif
                                @php $grand_total += $exam_grand_total; @endphp
                            @endforeach
                            <td class="marks-obtained">{{ $grand_total }}</td>
                            <td>
                                @php
                                    $percentage = ($grand_total / (count($exams) * count($all_subjects) * 100)) * 100;
                                    $grand_total_grade = '';
                                    foreach ($grades as $g) {
                                        if ($percentage >= $g->starting_range && $percentage <= $g->ending_range) {
                                            $grand_total_grade = $g->grade;
                                            break;
                                        }
                                    }
                                @endphp
                                {{ $grand_total_grade }}
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="two-columns">
                    <div class="column">
                        <div class="section-title">
                            <i class="fas fa-palette"></i> Performance Overview
                        </div>

                        <div class="activity-grade-box"
                            style="background-color: var(--secondary); color: white; border-radius: 6px; padding: 6px 10px; display: flex; justify-content: space-between; align-items: center;">
                            <div style="flex: 1; font-weight: 600; font-size: 11px;">OVERALL PERFORMANCE</div>
                        </div>

                        <div class="activity-content"
                            style="background-color: #f9f9f9; border-radius: 0 0 6px 6px; padding: 10px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 6px rgba(0,0,0,0.05); position: relative;">
                            <div
                                style="position: absolute; bottom: 0; left: 0; width: 100%; height: 2px; background-color: var(--secondary); border-radius: 0 0 6px 6px;">
                            </div>

                            <!-- Percentage Circle -->
                            <div class="percentage-circle"
                                style="width: 70px; height: 70px; border-radius: 50%; background: white; display: flex; flex-direction: column; justify-content: center; align-items: center; box-shadow: 0 2px 6px rgba(0,0,0,0.1); border: 2px solid var(--secondary);">
                                @php
                                    $totalMarks = $results->sum('total_marks');
                                    $obtainedMarks = $results->sum('obtained_marks');
                                    $percentage = ($obtainedMarks / ($totalMarks ?: 1)) * 100;
                                    $overallGrade = '';
                                    foreach ($grades as $grade) {
                                        if ($percentage >= $grade->starting_range && $percentage <= $grade->ending_range) {
                                            $overallGrade = $grade->grade;
                                            break;
                                        }
                                    }
                                @endphp
                                <div class="percentage-value"
                                    style="font-size: 14px; font-weight: 700; color: var(--primary);">
                                    {{ number_format($percentage, 2) }}%
                                </div>
                                <div class="grade-value"
                                    style="font-size: 12px; font-weight: 600; color: var(--secondary);">
                                    {{ $overallGrade }}
                                </div>
                                <div class="percentage-label"
                                    style="font-size: 8px; text-transform: uppercase; color: var(--dark);">Overall</div>
                            </div>

                            <!-- Attendance Info -->
                            <div class="attendance-info"
                                style="text-align: center; flex: 1; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                                <div style="font-weight: 600; color: var(--primary); font-size: 11px;">ATTENDANCE</div>
                                <div style="font-size: 14px; font-weight: 700;">
                                    {{ $studentAttendanceCount }} / {{ $attendanceTotal }} days
                                </div>
                                <div style="font-size: 10px; color: var(--dark);">
                                    {{ number_format(($studentAttendanceCount / ($attendanceTotal ?: 1)) * 100, 1) }}%
                                </div>
                            </div>

                            <!-- Rank Box -->
                            <div class="rank-box"
                                style="background: white; border-radius: 6px; padding: 6px 12px; text-align: center; box-shadow: 0 2px 6px rgba(0,0,0,0.1); border: 1px solid var(--secondary);">
                                <i class="fas fa-trophy" style="color: var(--warning); font-size: 14px;"></i>
                                <div style="font-weight: 600; color: var(--dark); font-size: 11px;">Rank:
                                    {{ $result->rank ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="column">
                    <div class="section-title">
                        <i class="fas fa-chart-bar"></i> Grading System
                    </div>

                    <table class="grade-system-table text-center">
                        <tr>
                            <th>Range</th>
                            @foreach ($grades as $grade)
                                <td>{{ $grade->starting_range }} - {{ $grade->ending_range }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <th>Grade</th>
                            @foreach ($grades as $grade)
                                <td>{{ $grade->grade }}</td>
                            @endforeach
                        </tr>
                    </table>
                </div>

            </div>

            <div class="signature-area">
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-name">{{ $settings['principal_name'] ?? 'Principal' }}</div>
                    <div class="signature-title">PRINCIPAL</div>
                </div>

                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-name">{{ $result->class_teacher_name ?? 'Class Teacher' }}</div>
                    <div class="signature-title">CLASS TEACHER</div>
                </div>
            </div>

            <div class="footer">
                <div class="issued-date">Issued Date: {{ date('d-M-Y') }}</div>
                <div class="flex-grow-1 text-center">&copy; {{ $settings['school_name'] ?? 'School' }}</div>
                <div class="page-number">{{ $key + 1 }} of {{ count($allStudentsData) }}</div>
            </div>
        </div>
    @endforeach

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>