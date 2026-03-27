<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Result</title>

    <style>
        body {
            border: 1px solid black;
            border-radius: 4px;
            padding: 10px;
        }

        table {
            border-collapse: collapse;
            border: none;
            font-size: 12px;
            z-index: 1;
        }

        .header tr td {
            padding: 10px;
        }

        .school-name {
            font-size: 20px;
            font-weight: bold;
        }

        .full-width {
            width: 100%;
        }

        .cell-pedding tr th,
        tr td {
            padding: 10px;
            font-size: 12px;

        }

        .table-gap {
            margin-top: 20px;
        }
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .student-info table, .student-info table tr {
            border: 1px solid lightgray;
        }
        .table-result table, .table-result table tr, .table-result table tr th, .table-result table tr td {
            border: 1px solid lightgray;
            margin-top: 10px;
        }
        .footer {
            /* position: absolute; */
            bottom: 20px;
            right: 20px;
            left: 20px;
        }
    </style>
</head>

<body>
    <div class="body">
        <table class="full-width header">
            <tr>
                <td style="width: 80%">
                    <span class="school-name">{{ $settings['school_name'] }}</span>
                    <br>
                    {{ $settings['school_address'] }}
                    <br>
                    {{ $settings['school_email'] }}
                    <br>
                    {{ $settings['school_phone'] }}
                </td>
                <td style="text-align: center">
                    @if ($settings['horizontal_logo'] ?? '')
                        <img height="50" src="{{ public_path('storage/') . $settings['horizontal_logo'] }}" alt="">
                    @else
                        <img height="50" src="{{ public_path('assets/horizontal-logo2.svg') }}" alt="">
                    @endif
                </td>
            </tr>
        </table>
        <hr>

        <div class="student-info table-gap">
            <table class="full-width student-info-table cell-pedding">
                <tr>
                    <th colspan="4" style="font-size: 16px;letter-spacing: 2px">Student Information</th>
                </tr>
                <tr>
                    <th class="text-left">Student Name :</th>
                    <td>{{ $result->user->full_name }}</td>
                    <th class="text-left">DOB :</th>
                    <td>{{ date($settings['date_format'], strtotime($result->user->dob)) }}</td>
                </tr>
                <tr>
                    <th class="text-left">GR No. :</th>
                    <td>{{ $result->user->student->admission_no ?? 'N/A' }}</td>
                    <th class="text-left">Guardian Name :</th>
                    <td>{{ $result->user->student->guardian->full_name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th class="text-left">Roll Number :</th>
                    <td>{{ $result->user->student->roll_number ?? 'N/A' }}</td>
                     <th class="text-left">Rank :</th>
                    <td>{{ $result->rank ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>

        <div class="exam-info table-gap">
            <table class="full-width cell-pedding" border="0">
                <tr>
                    <th class="text-left">Exam Name :</th>
                    <td>{{ $result->exam->name }}</td>
                    <th class="text-right">Session Year :</th>
                    <td class="text-right">{{ $result->session_year->name }}</td>
                </tr>
            </table>
            <hr>
        </div>

        <div class="table-result table-gap">
            <table class="full-width cell-pedding">
                @php
                    $exam_types = json_decode($result->exam->exam_type, true) ?? [];
                @endphp
                <tr>
                    <th>SR No.</th>
                    <th>Subject</th>
                    <th>Total Marks</th>
                    @if (!empty($exam_types))
                        @foreach ($exam_types as $exam_type)
                            <th style="text-transform: capitalize">{{ $exam_type }}</th>
                        @endforeach
                    @endif
                    <th>Obtain Marks</th>
                    <th>Grade</th>
                </tr>
                @foreach ($result->user->exam_marks as $mark)
                    @php
                        $marks = json_decode($mark->marks, true) ?? [];
                    @endphp
                    <tr>
                        <td class="text-center">{{ ($loop->index + 1) }}</td>
                        <td>{{ $mark->class_subject->subject_with_name }}</td>
                        <td class="text-center">{{ $mark->timetable->total_marks }}</td>
                        @if (!empty($exam_types))
                            @foreach ($exam_types as $exam_type)
                                <td class="text-center">{{ $marks[$exam_type] ?? '' }}</td>
                            @endforeach
                        @endif
                        <td class="text-center">{{ $mark->obtained_marks }}</td>
                        <td class="text-center">{{ $mark->grade }}</td>
                    </tr>
                @endforeach
                <tr>
                    <th colspan="2">Total</th>
                    <th>{{ $result->total_marks }}</th>
                    @if (!empty($exam_types))
                        @foreach ($exam_types as $exam_type)
                            <th>
                                @php
                                    $total_exam_type_marks = 0;
                                    foreach ($result->user->exam_marks as $mark) {
                                        $marks = json_decode($mark->marks, true) ?? [];
                                        if (isset($marks[$exam_type])) {
                                            $total_exam_type_marks += $marks[$exam_type];
                                        }
                                    }
                                    echo $total_exam_type_marks;
                                @endphp
                            </th>
                        @endforeach
                    @endif
                    <th>{{ $result->obtained_marks }}</th>
                    <th>{{ $result->grade }}</th>
                </tr>
            </table>
        </div>

        <div class="result-status table-result">
            <table class="full-width cell-pedding table-gap">
                <tr>
                    <th>Status :</th>
                    <td>
                        @if ($result->status == 1)
                            Pass
                        @else
                            Fail                            
                        @endif
                    </td>
                    <th>Percentage :</th>
                    <td>
                        @if ($result->status == 1)
                            {{ number_format($result->percentage,2) }} %
                        @else
                            -
                        @endif
                    </td>
                    <th>
                        Grade :
                    </th>
                    <td>
                        @if ($result->status == 1)
                            {{ $result->grade }}
                        @else
                            -
                        @endif
                    </td>
                    <th>Rank :</th>
                    <td>
                        @if ($result->status == 1)
                            {{ $result->rank }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <div class="footer table-result">
            <table class="full-width cell-pedding">
                <tr>
                    <th>Range</th>
                    @foreach ($grades as $grade)
                        <th>
                            {{ $grade->starting_range }} - {{ $grade->ending_range }}
                        </th>
                    @endforeach
                </tr>
                <tr>
                    <th>Grade</th>
                    @foreach ($grades as $grade)
                        <th>
                            {{ $grade->grade }}
                        </th>
                    @endforeach
                </tr>
            </table>
        </div>

       <div class="signature-section" style="margin-top: 20px;">
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="text-align: left;">
                ______________________<br><br>
                <strong>Class Teacher</strong>
            </td>

            <!-- Principal on right -->
            <td style="text-align: right;">
                ______________________<br><br>
                <strong>Principal</strong>
            </td>
        </tr>
    </table>
</div>

    </div>
</body>

</html>
