<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings['school_name'] ?? 'School Name' }} - Report Card</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
    <style>
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: white;
            color: {{ $colors['table_text_color'] ?? '#333333' }};
            margin: 0;
            padding: 0;
        }

        @php
            // Ensure reportCardTemplate is an object, not a collection
            if (is_array($reportCardTemplate) || $reportCardTemplate instanceof \Illuminate\Support\Collection) {
                $reportCardTemplate = (object) (is_array($reportCardTemplate) ? $reportCardTemplate : $reportCardTemplate->toArray());
            }
            
            // Safe height/width calculation with fallback values
            $height = ((isset($reportCardTemplate->height) && is_numeric($reportCardTemplate->height)) ? $reportCardTemplate->height : 297) * 3.7795275591 . 'px';
            $width = ((isset($reportCardTemplate->width) && is_numeric($reportCardTemplate->width)) ? $reportCardTemplate->width : 210) * 3.7795275591 . 'px';
        @endphp

        .sheet {
            width: {{ $width }};
            height: {{ $height }};
            page-break-after: always;
            position: relative;
            overflow: hidden;
            margin: 0 auto;
            background-color: white;
        }

        .sheet:last-child {
            page-break-after: auto;
        }

        .report-card-container {
            width: 100%;
            height: 100%;
            position: relative;
        }

        /* Positioning Styles */
        .component {
            position: absolute;
            z-index: 2;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }
        th {
            background-color: {{ $colors['table_header_bg'] ?? '#22577a' }};
            color: white;
            font-weight: bold;
        }

        .no-print {
            display: none;
        }
        
        /* Description Chunk Styles */
        .description-chunk {
            min-width: 50px;
            word-wrap: break-word;
            white-space: normal;
        }
        .description-chunk p { margin: 0; }
        
        /* Header and Footer Styles */
        .header-component {
            min-height: 100px;
            width: 100% !important;
        }
        .header-component > div {
            min-height: 200px;
        }
        .footer-component {
            min-height: 50px;
            width: 100% !important;
        }
        .footer-component > div {
            min-height: 50px;
        }
        /* Results table styling mirrors design preview */
        .results-table-wrapper > div {
            padding: 5px; border: 1px solid #ddd; background: #fff;
        }
        .results-table-wrapper table {
            width: 100%; border-collapse: collapse; font-size: 10px; text-align: center; table-layout: fixed; word-break: break-word; hyphens: auto;
        }
        .results-table-wrapper thead tr { background-color: {{ $colors['table_header_bg'] ?? '#22577a' }}; color: white; }
        .results-table-wrapper thead th { border: 1px solid #fff; padding: 5px; box-sizing: border-box; overflow-wrap: break-word; }
        .results-table-wrapper tbody td { border: 1px solid #ccc; padding: 4px; box-sizing: border-box; overflow-wrap: break-word; white-space: normal; }
        .results-table-wrapper td.subject { text-align: left; }
        .results-table-wrapper .subject-text { display: block; width: 100%; white-space: nowrap; }
        
    </style>
</head>
<body>
    @php
        // Ensure all variables are properly set
        $reportCardTemplate = $reportCardTemplate ?? null;
        if (!$reportCardTemplate) {
            abort(404, 'Report Card Template not found');
        }
        
        // Extra validation - ensure it's not a collection
        if (is_array($reportCardTemplate) || $reportCardTemplate instanceof \Illuminate\Support\Collection) {
            \Log::error('Report Card Template is in wrong format', ['type' => gettype($reportCardTemplate)]);
            abort(500, 'Report card template format error');
        }
        
        // Ensure arrays/objects are in correct format
        // Normalize incoming data so blade placeholders work with arrays
        $colors = $colors instanceof \Illuminate\Support\Collection ? $colors->toArray() : (is_array($colors) ? $colors : json_decode((array)$colors, true) ?? []);
        $style = $style instanceof \Illuminate\Support\Collection ? $style->toArray() : (is_array($style) ? $style : json_decode((array)$style, true) ?? []);
        if ($settings instanceof \Illuminate\Support\Collection) {
            $settings = $settings->toArray();
        } else {
            $settings = is_array($settings) ? $settings : (array)$settings ?? [];
        }

        // Prepare logo path for PDF renderer (prefer local file, else remote URL)
        $horizontalLogo = $settings['vertical_logo'] ?? '';
        $horizontalLogoPath = $horizontalLogo;
        $horizontalLogoDataUri = '';
        try {
            if (!empty($horizontalLogo)) {
                $parsedPath = ltrim(parse_url($horizontalLogo, PHP_URL_PATH) ?? '', '/');

                // 1) If file exists in public path (e.g. /storage/...) use that absolute path for dompdf
                $publicPath = public_path($parsedPath);
                if (!empty($parsedPath) && file_exists($publicPath)) {
                    $horizontalLogoPath = $publicPath;
                    // Build inline data URI to avoid dompdf remote issues
                    $mime = mime_content_type($publicPath);
                    $horizontalLogoDataUri = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($publicPath));
                } else {
                    // 2) If stored on default public disk, try storage/app/public
                    $storagePath = storage_path('app/public/' . $parsedPath);
                    if (!empty($parsedPath) && file_exists($storagePath)) {
                        $horizontalLogoPath = $storagePath;
                        $mime = mime_content_type($storagePath);
                        $horizontalLogoDataUri = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($storagePath));
                    } else {
                        // 3) If http/https, keep URL (requires dompdf remote enabled)
                        if (preg_match('#^https?://#i', $horizontalLogo)) {
                            // Try to fetch and inline if remote and accessible
                            $context = stream_context_create(['http' => ['timeout' => 3], 'https' => ['timeout' => 3]]);
                            $remoteData = @file_get_contents($horizontalLogo, false, $context);
                            if ($remoteData !== false) {
                                $mime = 'image/png';
                                if (!empty($http_response_header)) {
                                    foreach ($http_response_header as $hdr) {
                                        if (stripos($hdr, 'Content-Type:') === 0) {
                                            $mime = trim(substr($hdr, strlen('Content-Type:')));
                                            break;
                                        }
                                    }
                                }
                                $horizontalLogoDataUri = 'data:' . $mime . ';base64,' . base64_encode($remoteData);
                            }
                        } else {
                            // Last resort: leave as-is; renderer may still resolve relative path
                            $horizontalLogoPath = $horizontalLogo;
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::warning('Report Card logo path parse error: ' . $e->getMessage());
        }
        
        // Safely get configuration
        $config = [];
        if (is_object($reportCardTemplate) && isset($reportCardTemplate->configuration)) {
            $config = json_decode($reportCardTemplate->configuration, true) ?? [];
        } elseif (is_array($reportCardTemplate) && isset($reportCardTemplate['configuration'])) {
            $config = json_decode($reportCardTemplate['configuration'], true) ?? [];
        }
        
        $visibleFields = $config['fields'] ?? [];
        
        // Fallback if no fields saved (e.g. old template), show all basic ones
        if (empty($visibleFields)) {
            $visibleFields = [
                'header',
                'school_logo',
                'school_name',
                'school_address',
                'school_mobile',
                'school_email',
                'title',
                'student_image',
                'results_table',
                'grading_system',
                'co_scholastic',
                'overall',
                'attendance',
                'rank',
                'footer'
            ];
        }

        // Ensure critical branding fields are always available
        foreach (['header', 'school_logo', 'school_name'] as $mustHave) {
            if (!in_array($mustHave, $visibleFields, true)) {
                $visibleFields[] = $mustHave;
            }
        }

        // Extract and process styles from the style array
        $processedStyles = [];
        foreach ($style as $key => $value) {
            if (is_string($value) && strpos($value, 'style="') !== false) {
                // Extract style content between quotes - use safe string extraction
                $start = strpos($value, 'style="') + 7; // 7 = length of 'style="'
                $end = strpos($value, '"', $start);
                if ($start !== false && $end !== false) {
                    $processedStyles[$key] = substr($value, $start, $end - $start);
                } else {
                    $processedStyles[$key] = '';
                }
            }
        }
    @endphp

    @foreach($allStudentsData as $key => $studentData)
        @php
            try {
                if (!isset($studentData['result'])) {
                    \Log::warning('Student data missing result object at index: ' . $key);
                    continue;
                }
                
                $result = $studentData['result'];
                
                // Ensure result is an object
                if (is_array($result) || $result instanceof \Illuminate\Support\Collection) {
                    $result = (object) (is_array($result) ? $result : $result->toArray());
                }
                
                // Safely get student from result
                $student = null;
                if (isset($result->user) && !empty($result->user)) {
                    $student = is_array($result->user) ? (object)$result->user : $result->user;
                } else {
                    \Log::warning('Student not found in result at index: ' . $key);
                    continue;
                }
                
                $studentAttendanceCount = $studentData['studentAttendanceCount'] ?? 0;
                $attendanceTotal = $studentData['attendanceTotal'] ?? 0;
                $percentage = $attendanceTotal > 0 ? round(($studentAttendanceCount / $attendanceTotal) * 100, 2) : 0;
            } catch (\Exception $e) {
                \Log::error('Report Card PDF Student Error: ' . $e->getMessage(), ['index' => $key, 'trace' => $e->getTraceAsString()]);
                continue;
            }
            
            // Prepare variables for text replacement with safe property access
            $studentStudent = null;
            $guardianName = '';
            $rollNo = '';
            $admissionNo = '';
            
            try {
                if (isset($student->student)) {
                    $studentStudent = is_array($student->student) ? (object)$student->student : $student->student;
                    $rollNo = $studentStudent->roll_number ?? '';
                    $admissionNo = $studentStudent->admission_no ?? '';
                    
                    if (isset($studentStudent->guardian)) {
                        $guardian = is_array($studentStudent->guardian) ? (object)$studentStudent->guardian : $studentStudent->guardian;
                        $guardianName = (isset($guardian->first_name) ? $guardian->first_name : '') . ' ' . (isset($guardian->last_name) ? $guardian->last_name : '');
                    }
                }
            } catch (\Exception $e) {
                \Log::warning('Error accessing student relationships: ' . $e->getMessage());
            }
            
            $classSectionName = '';
            $sessionYearName = '';
            $examName = '';
            $examDescription = '';
            $guardianMobile = '';
            $guardianEmail = '';
            $studentMobile = '';
            $currentAddress = '';
            $permanentAddress = '';
            $gender = '';
            $admissionDate = '';
            
            try {
                if (isset($result->class_section)) {
                    $classSectionName = is_array($result->class_section) ? ($result->class_section['full_name'] ?? '') : ($result->class_section->full_name ?? '');
                }
                if (isset($result->session_year)) {
                    $sessionYearName = is_array($result->session_year) ? ($result->session_year['name'] ?? '') : ($result->session_year->name ?? '');
                }
                if (isset($result->exam)) {
                    $examName = is_array($result->exam) ? ($result->exam['name'] ?? '') : ($result->exam->name ?? '');
                    $examDescription = is_array($result->exam) ? ($result->exam['description'] ?? '') : ($result->exam->description ?? '');
                }
                
                // Get additional student details
                $studentMobile = $student->mobile ?? '';
                $currentAddress = $student->current_address ?? '';
                $permanentAddress = $student->permanent_address ?? '';
                $gender = $student->gender ?? '';
                
                if (isset($studentStudent)) {
                    $admissionDate = $studentStudent->admission_date ?? '';
                    if (isset($studentStudent->guardian)) {
                        $guardianMobile = $guardian->mobile ?? '';
                        $guardianEmail = $guardian->email ?? '';
                    }
                }
            } catch (\Exception $e) {
                \Log::warning('Error accessing result relationships: ' . $e->getMessage());
            }
            
            // All available tag replacements matching tags.blade.php
            $fullName = trim((isset($student->first_name) ? $student->first_name : '') . ' ' . (isset($student->last_name) ? $student->last_name : ''));
            $replacements = [
                // Student basic info
                '{full_name}' => $fullName,
                '{First name}' => $student->first_name ?? '',
                '{first_name}' => $student->first_name ?? '',
                '{Last name}' => $student->last_name ?? '',
                '{last_name}' => $student->last_name ?? '',
                '{Student name}' => $fullName,
                '{student_name}' => $fullName,
                '{Roll No}' => $rollNo,
                '{roll_no}' => $rollNo,
                '{Admission No}' => $admissionNo,
                '{admission_no}' => $admissionNo,
                '{Reg No}' => $admissionNo,
                '{reg_no}' => $admissionNo,
                '{REG_NO}' => $admissionNo,
                '{Gr No}' => $admissionNo,
                '{gr_no}' => $admissionNo,
                '{DOB}' => $student->dob ?? '',
                '{dob}' => $student->dob ?? '',
                '{Student Mobile}' => $studentMobile,
                '{student_mobile}' => $studentMobile,
                '{Gender}' => $gender,
                '{gender}' => $gender,
                '{Current Address}' => $currentAddress,
                '{current_address}' => $currentAddress,
                '{Permanent Address}' => $permanentAddress,
                '{permanent_address}' => $permanentAddress,
                '{Admission Date}' => $admissionDate,
                '{admission_date}' => $admissionDate,
                
                // Class & Session
                '{Class Section}' => $classSectionName,
                '{class_section}' => $classSectionName,
                '{Session Year}' => $sessionYearName,
                '{session_year}' => $sessionYearName,
                
                // Exam info
                '{Exam}' => $examName,
                '{exam}' => $examName,
                '{Exam Description}' => $examDescription,
                '{exam_description}' => $examDescription,
                
                // Guardian info
                '{Guardian Name}' => $guardianName,
                '{guardian_name}' => $guardianName,
                '{Guardian Mobile}' => $guardianMobile,
                '{guardian_mobile}' => $guardianMobile,
                '{Guardian Email}' => $guardianEmail,
                '{guardian_email}' => $guardianEmail,
            ];
        @endphp

        <div class="sheet">
            <div class="report-card-container">
                <div class="frame-border" style="width: 100%; height: 100%; border: 1px solid black; position: absolute; top: 0; left: 0; pointer-events: none;"></div>

                {{-- Header Background --}}
                @if(in_array('header', $visibleFields))
                    <div class="component header-component" style="{{ $processedStyles['header'] ?? 'position:absolute; top: 0; left: 0; width: 100%; height: 200px;' }}; z-index: 1; overflow: hidden;">
                        <div style="background-color: {{ $colors['header_bg'] ?? '#22577a' }}; width: 100%; height: 100%;"></div>
                    </div>
                @endif

                  {{-- School Logo --}}
                @if(in_array('school_logo', $visibleFields))
                    <div class="component" style="{{ $processedStyles['school_logo'] ?? 'position:absolute; top: 20px; left: 20px; width: 120px; height: 60px;' }}; overflow: hidden;">
                        @php
                            $logoSrc = $horizontalLogoDataUri ?: $horizontalLogoPath;
                        @endphp
                            <img src="{{ $logoSrc }}" alt="School Logo" style="width: 100%; height: 100%; object-fit: contain;">
                    </div>
                @endif

                {{-- School Name --}}
                @if(in_array('school_name', $visibleFields))
                    <div class="component" style="{{ $processedStyles['school_name'] ?? 'position:absolute; top: 20px; left: 180px; width: 300px;' }}">
                        <h2 style="margin: 0; font-weight: bold; color: black;">{{ $settings['school_name'] ?? 'School Name' }}</h2>
                    </div>
                @endif

                {{-- School Address --}}
                @if(in_array('school_address', $visibleFields))
                    <div class="component" style="{{ $processedStyles['school_address'] ?? 'position:absolute; top: 55px; left: 180px; width: 400px; font-size: 11px;' }}">
                        <span>{{ $settings['school_address'] ?? '' }}</span>
                    </div>
                @endif

                {{-- School Mobile --}}
                @if(in_array('school_mobile', $visibleFields))
                    <div class="component" style="{{ $processedStyles['school_mobile'] ?? 'position:absolute; top: 75px; left: 180px; width: 200px; font-size: 11px;' }}">
                        <span>{{ $settings['school_phone'] ?? '' }}</span>
                    </div>
                @endif

                {{-- School Email --}}
                @if(in_array('school_email', $visibleFields))
                    <div class="component" style="{{ $processedStyles['school_email'] ?? 'position:absolute; top: 95px; left: 180px; width: 300px; font-size: 11px;' }}">
                        <span>{{ $settings['school_email'] ?? '' }}</span>
                    </div>
                @endif

                {{-- Title --}}
                @if(in_array('title', $visibleFields))
                    <div class="component" style="{{ $processedStyles['title'] ?? 'position:absolute; top: 130px; left: 180px; width: 300px;' }}">
                        <h4 style="margin: 0; font-weight: bold; color: black;">{{ $reportCardTemplate->name ?? 'Report Card' }}</h4>
                    </div>
                @endif

                {{-- Student Image --}}
                @if(in_array('student_image', $visibleFields))
                    @php
                        $studentImage = '';
                        try {
                            $studentImage = isset($student->image) ? $student->image : '';
                        } catch (\Exception $e) {
                            \Log::warning('Error accessing student image: ' . $e->getMessage());
                        }
                    @endphp
                    <div class="component" style="{{ $processedStyles['student_image'] ?? 'position:absolute; top: 100px; left: 650px;' }}">
                        @if($studentImage)
                            <img src="{{ $studentImage }}" alt="Student" style="width: 100%; height: 100%; object-fit: cover; border: 1px solid #ddd;">
                        @else
                            <div style="width: 100%; height: 100%; background-color: #f0f0f0; display: flex; align-items: center; justify-content: center; border: 1px solid #ddd;">
                                <span style="font-size: 12px; color: #999;">No Image</span>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Template Description Content (from TinyMCE) - Student Info, Custom Tables, etc. --}}
                @php
                    // Use template_description from style - this is the main content from TinyMCE editor
                    $description = $style['template_description'] ?? '';
                    if ($description) {
                        // Replace all placeholders with actual student data
                        foreach ($replacements as $placeholder => $value) {
                            $description = str_replace($placeholder, $value, $description);
                        }

                        // Split content into chunks for positioning
                        // Split by closing tags to preserve tables and other block elements
                        $chunks = preg_split('/(<\/p>|<\/table>|<\/div>)/i', $description, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
                        
                        // Rebuild chunks properly (pair content with closing tags)
                        $rebuiltChunks = [];
                        $currentChunk = '';
                        foreach ($chunks as $part) {
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
                    <div class="component description-chunk" style="{{ $processedStyles['description_' . $index] ?? 'position:absolute; top: ' . (200 + ($index * 50)) . 'px; left: 20px; width: 95%;' }}">
                        {!! $chunk !!}
                    </div>
                @endforeach

                {{-- Results Table --}}
                @if(in_array('results_table', $visibleFields))
                    <div class="component" style="{{ $processedStyles['results_table'] ?? 'position:absolute; top: 400px; left: 0; width: 100%;' }}">
                        <div class="results-table-wrapper">
                            <div>
                                <h6 style="font-weight: bold; background-color: {{ $colors['table_header_bg'] ?? '#22577a' }}; color: white; padding: 5px; margin: 0 0 0 0;">Results Table</h6>
                            @php
                                // Prefer rank-wise style data if available
                                $resultsForTable = collect($studentData['results'] ?? ($results ?? []));
                                $examsForTable = collect($studentData['exams'] ?? ($exams ?? []));
                            @endphp

                            @if($resultsForTable->count() > 0 && $examsForTable->count() > 0)
                                @php
                                    $all_subjects = [];
                                    $exam_marks_data = [];
                                    $exam_mark_types = [];

                                    foreach ($resultsForTable as $res) {
                                        $marks = json_decode($res->marks, true);
                                        if ($marks) {
                                            $exam_id = $res->exam_id;
                                            if (!isset($exam_mark_types[$exam_id])) {
                                                $exam_mark_types[$exam_id] = [];
                                            }
                                            foreach ($marks as $mark) {
                                                $subject_name = $mark['subject'] ?? 'Subject';
                                                if (!in_array($subject_name, $all_subjects)) {
                                                    $all_subjects[] = $subject_name;
                                                }
                                                $exam_marks_data[$exam_id][$subject_name] = $mark['marks'] ?? [];
                                                $current_mark_types = array_keys($mark['marks'] ?? []);
                                                $exam_mark_types[$exam_id] = array_unique(array_merge($exam_mark_types[$exam_id], $current_mark_types));
                                            }
                                        }
                                    }
                                @endphp

                                @php
                                    // Calculate table font-size so rows fit inside admin-set component height
                                    $componentStyleStr = $processedStyles['results_table'] ?? '';
                                    $componentHeightPx = null;
                                    if (!empty($componentStyleStr) && preg_match('/height\s*:\s*([\d\.]+)(px|mm|cm|in)?/i', $componentStyleStr, $m)) {
                                        $val = floatval($m[1]);
                                        $unit = strtolower($m[2] ?? 'px');
                                        if ($unit == 'mm') {
                                            $componentHeightPx = $val * 3.7795275591;
                                        } elseif ($unit == 'cm') {
                                            $componentHeightPx = $val * 37.795275591;
                                        } elseif ($unit == 'in') {
                                            $componentHeightPx = $val * 96;
                                        } else {
                                            $componentHeightPx = $val;
                                        }
                                    }

                                    // fallback: compute from sheet height and top offset
                                    if (empty($componentHeightPx)) {
                                        $sheetHeightPx = is_string($height) ? floatval($height) : (float)($height ?? 842);
                                        $topPx = 0;
                                        if (!empty($componentStyleStr) && preg_match('/top\s*:\s*([\d\.]+)(px|mm|cm|in)?/i', $componentStyleStr, $mt)) {
                                            $topPx = floatval($mt[1]);
                                        }
                                        $componentHeightPx = max($sheetHeightPx - $topPx - 40, 100);
                                    }

                                    // estimate rows: subjects + totals row
                                    $rowsCount = max(count($all_subjects), 1) + 1; // +1 for totals
                                    $headerEstimate = 36; // header title height inside the component
                                    $availableHeight = max($componentHeightPx - $headerEstimate - 10, 50);

                                    // compute max row height and derive font-size
                                    $maxRowHeight = floor($availableHeight / $rowsCount);
                                    $fontSize = round(max(7, min(12, $maxRowHeight * 0.48)), 1);

                                    // determine padding based on font size
                                    $cellPadding = max(2, floor($fontSize * 0.35));
                                    // ensure fixed table layout so columns shrink to fit
                                    $tableInlineStyle = "width:100%; border-collapse:collapse; font-size:{$fontSize}px; table-layout:fixed; word-break:break-word;";
                                    $thInlineStyle = "border:1px solid #fff; padding:{$cellPadding}px; box-sizing:border-box; word-break:break-word;";
                                    $tdInlineStyle = "border:1px solid #ccc; padding:{$cellPadding}px; box-sizing:border-box; word-break:break-word; white-space:normal;";
                                @endphp

                                @php
                                    // Try to parse component width (for horizontal fitting)
                                    $componentWidthPx = null;
                                    if (!empty($componentStyleStr) && preg_match('/width\s*:\s*([\d\.]+)(px|mm|cm|in|%)?/i', $componentStyleStr, $mw)) {
                                        $wval = floatval($mw[1]);
                                        $wunit = strtolower($mw[2] ?? 'px');
                                        if ($wunit == 'mm') {
                                            $componentWidthPx = $wval * 3.7795275591;
                                        } elseif ($wunit == 'cm') {
                                            $componentWidthPx = $wval * 37.795275591;
                                        } elseif ($wunit == 'in') {
                                            $componentWidthPx = $wval * 96;
                                        } elseif ($wunit == '%') {
                                            // percent of sheet width
                                            $sheetWidthPx = is_string($width) ? floatval($width) : (float)($width ?? 595);
                                            $componentWidthPx = ($wval / 100.0) * $sheetWidthPx;
                                        } else {
                                            $componentWidthPx = $wval;
                                        }
                                    }
                                    if (empty($componentWidthPx)) {
                                        $sheetWidthPx = is_string($width) ? floatval($width) : (float)($width ?? 595);
                                        $componentWidthPx = max($sheetWidthPx - 40, 200);
                                    }

                                    // Count columns: subject + for each exam (types or Marks+Grade) + GR. TOTAL (3 cols: Total, Percentage, Grade)
                                    $columnsCount = 1; // SUBJECT
                                    foreach ($examsForTable as $examItem) {
                                        $types = $exam_mark_types[$examItem->id] ?? [];
                                        $columnsCount += (count($types) > 0) ? (count($types) + 2) : 2;
                                    }
                                    $columnsCount += 3; // GR. TOTAL (Total, Percentage, Grade)

                                    // Subject column share (fraction); rest of columns share remaining width
                                    $subjectColPercent = 0.28; // 28% reserved for SUBJECT
                                    $otherCols = max(1, $columnsCount - 1);
                                    $otherColPercent = (1 - $subjectColPercent) / $otherCols; // fraction per other column
                                    $otherColPercentPct = round($otherColPercent * 100, 2);

                                    // Horizontal fit: estimate font-size from available column width
                                    $avgColWidth = max(30, floor($componentWidthPx / max(1, $columnsCount)));
                                    // rough char-per-px estimate: fontSize ~ avgColWidth * 0.15 (empirical)
                                    $fontSizeHoriz = floor($avgColWidth * 0.15);

                                    // final font-size is the smaller of vertical-fit and horizontal-fit calculations
                                    $fontSize = round(max(6, min(12, min($fontSize, max(6, $fontSizeHoriz)))), 1);
                                    // ensure minimum readable line-height (user requested 12px)
                                    $lineHeight = max(12, round($fontSize * 1.1, 1));

                                    // recompute padding smaller for very small fonts
                                    $cellPadding = max(1, floor($fontSize * 0.28));
                                    $tableInlineStyle = "width:100%; border-collapse:collapse; font-size:{$fontSize}px; line-height:{$lineHeight}px; table-layout:fixed; word-break:break-word;";
                                    $thInlineStyle = "border:1px solid #fff; padding:{$cellPadding}px; box-sizing:border-box; word-break:break-word; overflow-wrap:break-word;";
                                    $tdInlineStyle = "border:1px solid #ccc; padding:{$cellPadding}px; box-sizing:border-box; word-break:break-word; white-space:normal; overflow-wrap:break-word;";
                                @endphp

                                <table style="font-size:10px; line-height:16px;">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" style="{{ $thInlineStyle }}">SUBJECT</th>
                                            @foreach ($examsForTable as $examItem)
                                                @php
                                                    $types = $exam_mark_types[$examItem->id] ?? [];
                                                    $colspan = count($types) > 0 ? count($types) + 2 : 2; // +2 for Total and Grade
                                                    // compute header span width for exam group (approx)
                                                    $groupWidthPct = $colspan * $otherColPercentPct;
                                                @endphp
                                                <th colspan="{{ $colspan }}" style="{{ $thInlineStyle }}; width:{{ $groupWidthPct }}%">{{ $examItem->name }}</th>
                                            @endforeach
                                            <th colspan="3" rowspan="2" style="{{ $thInlineStyle }}; width:{{ round(3 * $otherColPercentPct,2) }}%">GR. TOTAL</th>
                                        </tr>
                                        <tr>
                                            @foreach ($examsForTable as $examItem)
                                                @php $types = $exam_mark_types[$examItem->id] ?? []; @endphp
                                                @if (!empty($types))
                                                    @foreach ($types as $type)
                                                        <th style="{{ $thInlineStyle }}; width:{{ $otherColPercentPct }}%">{{ ucfirst($type) }}</th>
                                                    @endforeach
                                                    <th style="{{ $thInlineStyle }}; width:{{ $otherColPercentPct }}%">Total</th>
                                                    <th style="{{ $thInlineStyle }}; width:{{ $otherColPercentPct }}%">Grade</th>
                                                @else
                                                    <th style="{{ $thInlineStyle }}; width:{{ $otherColPercentPct }}%">Marks</th>
                                                    <th style="{{ $thInlineStyle }}; width:{{ $otherColPercentPct }}%">Grade</th>
                                                @endif
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($all_subjects as $subject)
                                            <tr>
                                                @php
                                                    // Compute a per-subject font-size so the subject name fits on one line
                                                    $subjectText = strip_tags((string)$subject);
                                                    $chars = max(1, mb_strlen($subjectText));
                                                    $subjectColPercent = 0.28; // matches the td width:28%
                                                    $subjectMaxWidthPx = (int) floor($componentWidthPx * $subjectColPercent) - ($cellPadding * 2) - 4; // safety margin
                                                    $approxCharWidth = max(2.5, ($fontSize * 0.55)); // px per char estimate
                                                    $neededWidth = $chars * $approxCharWidth;
                                                    $subjectFontSize = $fontSize;
                                                    if ($neededWidth > $subjectMaxWidthPx && $chars > 0) {
                                                        // compute smaller font to fit horizontally (clamp to 6px min)
                                                        $subjectFontSize = max(6, floor(($subjectMaxWidthPx / $chars) / 0.55));
                                                        // also cap at the global font size
                                                        $subjectFontSize = min($subjectFontSize, $fontSize);
                                                    }
                                                @endphp
                                                <td class="subject" style="{{ $tdInlineStyle }}; text-align:left; width:28%;">
                                                    <span class="subject-text" style="font-size:{{ $subjectFontSize }}px; line-height:{{ $lineHeight }}px;">{{ $subject }}</span>
                                                </td>
                                                @php $subject_grand_total = 0; @endphp
                                                @foreach ($examsForTable as $examItem)
                                                    @php
                                                        $marks = $exam_marks_data[$examItem->id][$subject] ?? [];
                                                        $types = $exam_mark_types[$examItem->id] ?? [];
                                                        $exam_subject_total = 0;
                                                    @endphp
                                                    @if (!empty($types))
                                                        @foreach ($types as $type)
                                                            <td style="{{ $tdInlineStyle }}; text-align:center; width:{{ $otherColPercentPct }}%">{{ $marks[$type] ?? '-' }}</td>
                                                            @php $exam_subject_total += $marks[$type] ?? 0; @endphp
                                                        @endforeach
                                                        <td style="{{ $tdInlineStyle }}; text-align:center; width:{{ $otherColPercentPct }}%">{{ $exam_subject_total }}</td>
                                                        <td style="{{ $tdInlineStyle }}; text-align:center; width:{{ $otherColPercentPct }}%"> 
                                                            @php
                                                                $grade = '';
                                                                $percentage = $exam_subject_total; // assuming 100 total
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
                                                        <td style="{{ $tdInlineStyle }}; text-align:center; width:{{ $otherColPercentPct }}%">-</td>
                                                        <td style="{{ $tdInlineStyle }}; text-align:center; width:{{ $otherColPercentPct }}%">-</td>
                                                    @endif
                                                    @php $subject_grand_total += $exam_subject_total; @endphp
                                                @endforeach
                                                <td style="{{ $tdInlineStyle }}; text-align:center; width:{{ $otherColPercentPct }}%; font-weight:bold;">{{ $subject_grand_total }}</td>
                                                <td style="{{ $tdInlineStyle }}; text-align:center; width:{{ $otherColPercentPct }}%;">
                                                    @php
                                                        $subject_percentage = (count($examsForTable) * 100) ? ($subject_grand_total / (count($examsForTable) * 100)) * 100 : 0;
                                                    @endphp
                                                    {{ number_format($subject_percentage, 2) }}%
                                                </td>
                                                <td style="{{ $tdInlineStyle }}; text-align:center; width:{{ $otherColPercentPct }}%;">
                                                    @php
                                                        $final_grade = '';
                                                        $percentage = (count($examsForTable) * 100) ? ($subject_grand_total / (count($examsForTable) * 100)) * 100 : 0;
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

                                        <tr style="font-weight:bold; background:#f0f0f0;">
                                            <td style="{{ $tdInlineStyle }}; text-align:left;">Total Marks</td>
                                            @php $grand_total = 0; @endphp
                                            @foreach ($examsForTable as $examItem)
                                                @php
                                                    $types = $exam_mark_types[$examItem->id] ?? [];
                                                    $exam_grand_total = 0;
                                                @endphp
                                                @if (!empty($types))
                                                        @foreach ($types as $type)
                                                        @php
                                                            $type_total = 0;
                                                            if (isset($exam_marks_data[$examItem->id])) {
                                                                foreach ($exam_marks_data[$examItem->id] as $subject => $marks) {
                                                                    $type_total += $marks[$type] ?? 0;
                                                                }
                                                            }
                                                        @endphp
                                                        <td style="{{ $tdInlineStyle }}; text-align:center; width:{{ $otherColPercentPct }}%">{{ $type_total }}</td>
                                                        @php $exam_grand_total += $type_total; @endphp
                                                    @endforeach
                                                    <td style="{{ $tdInlineStyle }}; text-align:center; width:{{ $otherColPercentPct }}%">{{ $exam_grand_total }}</td>
                                                    <td style="{{ $tdInlineStyle }}; text-align:center; width:{{ $otherColPercentPct }}%"> 
                                                        @php
                                                            $exam_percentage = (count($all_subjects) * 100) ? ($exam_grand_total / (count($all_subjects) * 100)) * 100 : 0;
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
                                                    <td style="{{ $tdInlineStyle }}; text-align:center; width:{{ $otherColPercentPct }}%">-</td>
                                                    <td style="{{ $tdInlineStyle }}; text-align:center; width:{{ $otherColPercentPct }}%">-</td>
                                                @endif
                                                @php $grand_total += $exam_grand_total; @endphp
                                            @endforeach
                                            <td style="{{ $tdInlineStyle }}; text-align:center;">{{ $grand_total }}</td>
                                            <td style="{{ $tdInlineStyle }}; text-align:center;">
                                                @php
                                                    $grand_total_percentage = (count($examsForTable) * count($all_subjects) * 100) ? ($grand_total / (count($examsForTable) * count($all_subjects) * 100)) * 100 : 0;
                                                @endphp
                                                {{ number_format($grand_total_percentage, 2) }}%
                                            </td>
                                            <td style="{{ $tdInlineStyle }}; text-align:center;">
                                                @php
                                                    $percentage = (count($examsForTable) * count($all_subjects) * 100) ? ($grand_total / (count($examsForTable) * count($all_subjects) * 100)) * 100 : 0;
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
                            @else
                                @php
                                    // Fallback to simple marks table (single exam)
                                    $marks = collect([]);
                                    try {
                                        $studentId = is_array($student) ? ($student['id'] ?? null) : ($student->id ?? null);
                                        $examId = is_array($result) ? ($result['exam_id'] ?? null) : ($result->exam_id ?? null);
                                        
                                        if (!$studentId || !$examId) {
                                            \Log::warning('Missing student_id or exam_id for marks query', ['student_id' => $studentId, 'exam_id' => $examId]);
                                        } else {
                                            $marks = App\Models\ExamMarks::where('student_id', $studentId)
                                                ->whereHas('timetable', function($q) use ($examId) {
                                                    $q->where('exam_id', $examId);
                                                })
                                                ->with(['subject', 'timetable'])
                                                ->get();
                                        }
                                    } catch (\Exception $e) {
                                        \Log::error('Error fetching exam marks: ' . $e->getMessage(), ['student_id' => $studentId ?? 'unknown', 'exam_id' => $examId ?? 'unknown']);
                                        $marks = collect([]);
                                    }
                                @endphp
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Subject</th>
                                            <th>Marks</th>
                                            <th>Grade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($marks as $mark)
                                        <tr>
                                            @php
                                                $subjectName = 'Subject';
                                                $obtainedMarks = '0';
                                                $totalMarks = '0';
                                                $markGrade = '-';
                                                
                                                try {
                                                    if (isset($mark->subject)) {
                                                        $subject = is_array($mark->subject) ? (object)$mark->subject : $mark->subject;
                                                        // subject relationship returns collection; pick first if needed
                                                        if ($subject instanceof \Illuminate\Support\Collection) {
                                                            $subjectName = optional($subject->first())->name ?? 'Subject';
                                                        } else {
                                                            $subjectName = $subject->name ?? 'Subject';
                                                        }
                                                    }
                                                    $obtainedMarks = $mark->obtained_marks ?? '0';
                                                    if (isset($mark->timetable)) {
                                                        $timetable = is_array($mark->timetable) ? (object)$mark->timetable : $mark->timetable;
                                                        $totalMarks = $timetable->total_marks ?? '0';
                                                    }
                                                    $markGrade = $mark->grade ?? '-';
                                                } catch (\Exception $e) {
                                                    \Log::warning('Error processing mark: ' . $e->getMessage());
                                                }
                                            @endphp
                                            <td>{{ $subjectName }}</td>
                                            <td>{{ $obtainedMarks }} / {{ $totalMarks }}</td>
                                            <td>{{ $markGrade }}</td>
                                        </tr>
                                        @endforeach
                                        @if($marks->isEmpty())
                                        <tr>
                                            <td colspan="3">No marks found</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            @endif
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Grading System --}}
                @if(in_array('grading_system', $visibleFields))
                    <div class="component" style="{{ $processedStyles['grading_system'] ?? 'position:absolute; top: 600px; left: 0; width: 100%;' }}">
                        <div style="padding: 10px;">
                            <h6 style="font-weight: bold; background-color: {{ $colors['table_header_bg'] ?? '#22577a' }}; color: white; padding: 5px; margin: 0;">Grading System</h6>
                            <table style="width: 100%; border-collapse: collapse; text-align: center;">
                                <tbody>
                                    <tr>
                                        <th style="background-color: {{ $colors['table_header_bg'] ?? '#22577a' }}; color: white; width: 100px;">Range</th>
                                        @foreach($grades as $grade)
                                            <td style="border: 1px solid #000;">{{ $grade->starting_range }} - {{ $grade->ending_range }}</td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <th style="background-color: {{ $colors['table_header_bg'] ?? '#22577a' }}; color: white;">Grade</th>
                                        @foreach($grades as $grade)
                                            <td style="border: 1px solid #000;">{{ $grade->grade }}</td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

              {{-- Co-Scholastic & Summary (Merged) --}}
                @if(in_array('co_scholastic', $visibleFields))
                    <div class="component" style="{{ $processedStyles['co_scholastic'] ?? 'position:absolute; top: 700px; left: 0; width: 100%;' }}">
                        <div style="padding: 10px;">
                            
                            <h6 style="font-weight: bold; background-color: {{ $colors['table_header_bg'] ?? '#22577a' }}; color: white; padding: 5px; margin: 0 0 10px 0;">
                                Co-Scholastic Activities
                            </h6>

                            {{-- Activity Table --}}
                            <table style="margin-bottom: 20px;">
                                <thead>
                                    <tr>
                                        <th style="text-align: left; padding-left: 10px;">ACTIVITY</th>
                                        <th style="width: 80px;">GRADE</th>
                                    </tr>
                                </thead>
                            </table>

                            {{-- Summary Block --}}
                            @php
                                $resultPercentage = '0';
                                $resultGrade = '-';
                                try {
                                    $resultPercentage = is_array($result) ? ($result['percentage'] ?? '0') : ($result->percentage ?? '0');
                                    $resultGrade = is_array($result) ? ($result['grade'] ?? '-') : ($result->grade ?? '-');
                                } catch (\Exception $e) {
                                    \Log::warning('Error accessing result percentage/grade: ' . $e->getMessage());
                                }
                            @endphp

                            {{-- NEW FLEXBOX LAYOUT (Left = Overall, Center = Attendance, Right = Rank) --}}
                            <div style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-top: 5px;">

                                <!-- LEFT : OVERALL -->
                                @if(in_array('overall', $visibleFields))
                                <div>
                                    <div style="width: 70px; height: 70px; border-radius: 50%; 
                                        border: 3px solid {{ $colors['table_header_bg'] ?? '#22577a' }};
                                        display: flex; flex-direction: column; justify-content: center; align-items: center;
                                        color: {{ $colors['table_header_bg'] ?? '#22577a' }};">
                                        <span style="font-size: 14px; font-weight: bold;">{{ $resultPercentage }}%</span>
                                        <span style="font-size: 10px;">{{ $resultGrade }}</span>
                                        <span style="font-size: 8px;">OVERALL</span>
                                    </div>
                                </div>
                                @endif

                                <!-- CENTER : ATTENDANCE -->
                                @if(in_array('attendance', $visibleFields))
                                <div style="text-align: center;">
                                    <div style="font-weight: bold; color: {{ $colors['table_header_bg'] ?? '#22577a' }}; font-size: 10px;">ATTENDANCE</div>
                                    <div style="font-size: 12px; font-weight: bold;">{{ $studentAttendanceCount }} / {{ $attendanceTotal }} days</div>
                                    <div style="font-size: 10px; color: #666;">{{ number_format($percentage, 2) }}%</div>
                                </div>
                                @endif

                                <!-- RIGHT : RANK -->
                                @if(in_array('rank', $visibleFields))
                                <div>
                                    <div style="display: inline-block; padding: 5px 15px; border: 1px solid #ddd; border-radius: 5px;">
                                        <div style="font-size: 16px; color: orange;">🏆</div>
                                        <div style="font-size: 10px; font-weight: bold;">
                                            Rank: {{ $result->rank ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                                @endif

                            </div>

                        </div>
                    </div>
                @endif

                {{-- Teacher Signature --}}
                @if(in_array('teacher_signature', $visibleFields))
                    <div class="component" style="{{ $processedStyles['teacher_signature'] ?? 'position:absolute; left: 150px; top: 650px; width: 120px; height: 80px;' }}">
                        @php
                            $teacherSigSrc = $settings['signature'] ?? '';
                            $teacherSigDataUri = '';
                            try {
                                if (!empty($teacherSigSrc)) {
                                    $parsedPath = ltrim(parse_url($teacherSigSrc, PHP_URL_PATH) ?? '', '/');
                                    $publicPath = public_path($parsedPath);
                                    if (!empty($parsedPath) && file_exists($publicPath)) {
                                        $mime = mime_content_type($publicPath);
                                        $teacherSigDataUri = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($publicPath));
                                    } else {
                                        $storagePath = storage_path('app/public/' . $parsedPath);
                                        if (!empty($parsedPath) && file_exists($storagePath)) {
                                            $mime = mime_content_type($storagePath);
                                            $teacherSigDataUri = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($storagePath));
                                        }
                                    }
                                }
                            } catch (\Exception $e) {
                                \Log::warning('Teacher signature path error: ' . $e->getMessage());
                            }
                        @endphp
                        <div style="display: flex; flex-direction: column; align-items: center; width: 100%; height: 100%;">
                            @if($teacherSigDataUri || $teacherSigSrc)
                                <img src="{{ $teacherSigDataUri ?: $teacherSigSrc }}" alt="Teacher Signature" style="width: 100%; height: 50px; object-fit: contain; margin-bottom: 5px;">
                            @endif
                            <div style="width: 100%; height: 1px; background-color: #000; margin: 5px 0;"></div>
                            <div style="font-size: 11px; font-weight: bold; text-align: center; margin-top: 2px;">Class Teacher</div>
                        </div>
                    </div>
                @endif

                {{-- Principal Signature --}}
                @if(in_array('principal_signature', $visibleFields))
                    <div class="component" style="{{ $processedStyles['principal_signature'] ?? 'position:absolute; left: 550px; top: 650px; width: 120px; height: 80px;' }}">
                        @php
                            $principalSigSrc = $settings['principal_signature'] ?? '';
                            $principalSigDataUri = '';
                            try {
                                if (!empty($principalSigSrc)) {
                                    $parsedPath = ltrim(parse_url($principalSigSrc, PHP_URL_PATH) ?? '', '/');
                                    $publicPath = public_path($parsedPath);
                                    if (!empty($parsedPath) && file_exists($publicPath)) {
                                        $mime = mime_content_type($publicPath);
                                        $principalSigDataUri = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($publicPath));
                                    } else {
                                        $storagePath = storage_path('app/public/' . $parsedPath);
                                        if (!empty($parsedPath) && file_exists($storagePath)) {
                                            $mime = mime_content_type($storagePath);
                                            $principalSigDataUri = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($storagePath));
                                        }
                                    }
                                }
                            } catch (\Exception $e) {
                                \Log::warning('Principal signature path error: ' . $e->getMessage());
                            }
                        @endphp
                        <div style="display: flex; flex-direction: column; align-items: center; width: 100%; height: 100%;">
                            @if($principalSigDataUri || $principalSigSrc)
                                <img src="{{ $principalSigDataUri ?: $principalSigSrc }}" alt="Principal Signature" style="width: 100%; height: 50px; object-fit: contain; margin-bottom: 5px;">
                            @endif
                            <div style="width: 100%; height: 1px; background-color: #000; margin: 5px 0;"></div>
                            <div style="font-size: 11px; font-weight: bold; text-align: center; margin-top: 2px;">Principal</div>
                        </div>
                    </div>
                @endif

                {{-- Footer --}}
                @if(in_array('footer', $visibleFields))
                    <div class="component footer-component" style="{{ $processedStyles['footer'] ?? 'position:absolute; bottom: 0; left: 0; width: 100%; height: 50px;' }}; z-index: 1; overflow: hidden;">
                        <div style="background-color: {{ $colors['footer_bg'] ?? '#22577a' }}; width: 100%; height: 100%;"></div>
                    </div>
                @endif

            </div>
        </div>
    @endforeach
</body>
</html>
