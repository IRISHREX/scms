<?php

namespace App\Http\Controllers;

use App\Repositories\ReportCardTemplate\ReportCardTemplateInterface;
use App\Repositories\ClassSection\ClassSectionInterface;
use App\Repositories\Exam\ExamInterface;
use App\Repositories\Grades\GradesInterface;
use App\Repositories\ExamResult\ExamResultInterface;
use App\Repositories\SessionYear\SessionYearInterface;
use App\Repositories\User\UserInterface;
use App\Repositories\Attendance\AttendanceInterface;
use App\Services\BootstrapTableService;
use App\Services\CachingService;
use App\Services\ResponseService;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Throwable;
use Illuminate\Support\Facades\Auth;
use App\Models\FormField;

class ReportCardTemplateController extends Controller
{
    private ReportCardTemplateInterface $reportCardTemplate;
    private CachingService $cache;
    private ClassSectionInterface $classSection;
    private ExamInterface $exam;
    private SessionYearInterface $sessionYear;
    private GradesInterface $grades;
    private ExamResultInterface $examResult;
    private UserInterface $user;
    private AttendanceInterface $attendance;

    public function __construct(
        ReportCardTemplateInterface $reportCardTemplate,
        CachingService $cache,
        ClassSectionInterface $classSection,
        ExamInterface $exam,
        SessionYearInterface $sessionYear,
        GradesInterface $grades,
        ExamResultInterface $examResult,
        UserInterface $user,
        AttendanceInterface $attendance
    ) {
        $this->reportCardTemplate = $reportCardTemplate;
        $this->cache = $cache;
        $this->classSection = $classSection;
        $this->exam = $exam;
        $this->sessionYear = $sessionYear;
        $this->grades = $grades;
        $this->examResult = $examResult;
        $this->user = $user;
        $this->attendance = $attendance;
    }

    public function index()
    {
        $formFields = FormField::where('user_type', 1)->get();
        return view('reports.report-card-template.template', compact('formFields'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'page_layout' => 'required',
            'height' => 'required',
            'width' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $page_layout = 'A4 Portrait';
            if ($request->height == 210 && $request->width == 297) {
                $page_layout = 'A4 Landscape';
            } else if ($request->height == 297 && $request->width == 210) {
                $page_layout = 'A4 Portrait';
            } else {
                $page_layout = 'Custom';
            }

            // Colors
            $colors = $request->colors ?? [
                'header_bg' => '#22577a',
                'footer_bg' => '#f2f5f7',
                'table_header_bg' => '#22577a',
                'table_text_color' => '#333333'
            ];

            // Configuration
            $configuration = [
                'show_attendance' => $request->has('show_attendance') ? 1 : 0,
                'show_rank' => $request->has('show_rank') ? 1 : 0,
                'show_performance' => $request->has('show_performance') ? 1 : 0,
                'page_orientation' => $request->page_orientation ?? 'portrait',
                'fields' => $request->visible_fields ?? ['header', 'student_image', 'results_table', 'grading_system', 'co_scholastic', 'footer']
            ];

            // Style (Description)
            $style = $request->style ?? [];

            $data = [
                'school_id' => Auth::user()->school_id,
                'name' => $request->name,
                'page_layout' => $page_layout,
                'height' => $request->height,
                'width' => $request->width,
                'colors' => json_encode($colors),
                'configuration' => json_encode($configuration),
                'style' => json_encode($style)
            ];

            $this->reportCardTemplate->create($data);
            DB::commit();
            ResponseService::successResponse('Report Card Template Created Successfully');
        } catch (Throwable $e) {
            DB::rollBack();
            ResponseService::logErrorResponse($e, "Report Card Template Controller -> Store Method");
            ResponseService::errorResponse();
        }
    }

    public function show(Request $request)
    {
        ResponseService::noPermissionThenSendJson('certificate-list');
        try {
            $offset = $request->input('offset', 0);
            $limit = $request->input('limit', 10);
            $sort = $request->input('sort', 'id');
            $order = $request->input('order', 'desc');

            $builder = $this->reportCardTemplate->builder();
            if (Auth::user()->school_id) {
                $builder = $builder->where('school_id', Auth::user()->school_id);
            }

            $total = $builder->count();
            $rows = $builder->orderBy($sort, $order)->skip($offset)->take($limit)->get();

            $no = $offset + 1;
            $bulkData = array();
            $bulkRows = array();
            foreach ($rows as $row) {
                $operate = BootstrapTableService::button('fa fa-edit', route('report-card-template.edit', $row->id), ['btn-gradient-primary'], ['title' => trans('edit')]);
                $operate .= BootstrapTableService::button('fa fa-table-layout', route('report-card-template.design', $row->id), ['btn-gradient-info'], ['title' => trans('design')]);
                $operate .= BootstrapTableService::deleteButton(route('report-card-template.destroy', $row->id));
                $tempRow = $row->toArray();
                $tempRow['no'] = $no++;
                $tempRow['operate'] = $operate;
                $bulkRows[] = $tempRow;
            }
            $bulkData['rows'] = $bulkRows;
            return response()->json($bulkData);
        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e, "Report Card Template Controller -> Show Method");
            ResponseService::errorResponse();
        }
    }

    public function edit($id)
    {
        $reportCardTemplate = $this->reportCardTemplate->findById($id);
        $formFields = FormField::where('user_type', 1)->get();

        return view('reports.report-card-template.edit-template', compact('reportCardTemplate', 'formFields'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'page_layout' => 'required',
            'height' => 'required',
            'width' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $page_layout = 'A4 Portrait';
            if ($request->height == 210 && $request->width == 297) {
                $page_layout = 'A4 Landscape';
            } else if ($request->height == 297 && $request->width == 210) {
                $page_layout = 'A4 Portrait';
            } else {
                $page_layout = 'Custom';
            }

            // Colors
            $colors = $request->colors ?? [
                'header_bg' => '#22577a',
                'footer_bg' => '#f2f5f7',
                'table_header_bg' => '#22577a',
                'table_text_color' => '#333333'
            ];

            // Configuration - preserve existing fields
            $reportCardTemplate = $this->reportCardTemplate->findById($id);
            $existingConfig = json_decode($reportCardTemplate->configuration, true) ?? [];
            
            $configuration = [
                'show_attendance' => $request->has('show_attendance') ? 1 : 0,
                'show_rank' => $request->has('show_rank') ? 1 : 0,
                'show_performance' => $request->has('show_performance') ? 1 : 0,
                'page_orientation' => $request->page_orientation ?? 'portrait',
                'fields' => $existingConfig['fields'] ?? ['header', 'student_image', 'results_table', 'grading_system', 'co_scholastic', 'footer']
            ];

            // Style (Description)
            $existingStyle = json_decode($reportCardTemplate->style, true) ?? [];
            $newStyle = $request->style ?? [];
            $style = array_merge($existingStyle, $newStyle);

            $data = [
                'name' => $request->name,
                'page_layout' => $page_layout,
                'height' => $request->height,
                'width' => $request->width,
                'colors' => json_encode($colors),
                'configuration' => json_encode($configuration),
                'style' => json_encode($style)
            ];

            $this->reportCardTemplate->update($id, $data);
            DB::commit();
            ResponseService::successResponse('Report Card Template Updated Successfully');
        } catch (Throwable $e) {
            DB::rollBack();
            ResponseService::logErrorResponse($e, "Report Card Template Controller -> Update Method");
            ResponseService::errorResponse();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $this->reportCardTemplate->deleteById($id);
            DB::commit();
            ResponseService::successResponse('Report Card Template Deleted Successfully');
        } catch (Throwable $e) {
            DB::rollBack();
            ResponseService::logErrorResponse($e, "Report Card Template Controller -> Destroy Method");
            ResponseService::errorResponse();
        }
    }

    public function design($id)
    {
    
        try {
            $reportCardTemplate = $this->reportCardTemplate->findById($id);
            $settings = $this->cache->getSchoolSettings();

            $style = json_decode($reportCardTemplate->style, true) ?? [];
            $colors = json_decode($reportCardTemplate->colors, true) ?? [
                'header_bg' => '#22577a',
                'footer_bg' => '#f2f5f7',
                'table_header_bg' => '#22577a',
                'table_text_color' => '#333333'
            ];
            $configuration = json_decode($reportCardTemplate->configuration, true) ?? [];

            // Set default positions for elements
            if (!isset($style['header'])) {
                $style['header'] = 'style="position:absolute; left: 0px;top: 0px"';
            }
            if (!isset($style['student_info'])) {
                $style['student_info'] = 'style="position:absolute; left: 0px;top: 100px"';
            }
            if (!isset($style['results_table'])) {
                $style['results_table'] = 'style="position:absolute; left: 0px;top: 200px"';
            }
            if (!isset($style['footer'])) {
                $style['footer'] = 'style="position:absolute; left: 0px;top: 900px"';
            }

            $height = $reportCardTemplate->height * 3.7795275591;
            $width = $reportCardTemplate->width * 3.7795275591;

            $layout = [
                'height' => $height . 'px',
                'width' => $width . 'px'
            ];
            $formFields = FormField::where('user_type', 1)->get();

            return view('reports.report-card-template.design', compact('reportCardTemplate', 'settings', 'style', 'layout', 'colors', 'configuration', 'formFields'));
        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e, "Report Card Template Controller -> Design Method");
            ResponseService::errorResponse();
        }
    }

    public function design_store(Request $request, $id)
    {
        try {
            $reportCardTemplate = $this->reportCardTemplate->findById($id);
            $existingStyle = json_decode($reportCardTemplate->style, true) ?? [];
            
            $newStyle = array();
            if ($request->style) {
                foreach ($request->style as $key => $value) {
                    $newStyle[$key] = $value;
                }
            }
            
            // Merge existing style with new style (new style overwrites existing keys)
            $style = array_merge($existingStyle, $newStyle);

            // Get existing colors to preserve them
            $existingColors = json_decode($reportCardTemplate->colors, true) ?? [
                'header_bg' => '#22577a',
                'footer_bg' => '#f2f5f7',
                'table_header_bg' => '#22577a',
                'table_text_color' => '#333333'
            ];
            
            $colors = array();
            if ($request->colors) {
                foreach ($request->colors as $key => $value) {
                    $colors[$key] = $value;
                }
            }
            
            // Merge with existing colors - preserve existing colors if new ones not provided
            $colors = array_merge($existingColors, $colors);

            // Fetch existing configuration to merge or create new
            $reportCardTemplate = $this->reportCardTemplate->findById($id);
            $existingConfig = json_decode($reportCardTemplate->configuration, true) ?? [];

            $configuration = [
                'show_attendance' => $request->has('show_attendance') ? 1 : ($existingConfig['show_attendance'] ?? 0),
                'show_rank' => $request->has('show_rank') ? 1 : ($existingConfig['show_rank'] ?? 0),
                'show_performance' => $request->has('show_performance') ? 1 : ($existingConfig['show_performance'] ?? 0),
                'page_orientation' => $request->page_orientation ?? ($existingConfig['page_orientation'] ?? 'portrait'),
                'fields' => $request->visible_fields ?? ($existingConfig['fields'] ?? [])
            ];

            $value = [
                'style' => json_encode($style),
                'colors' => json_encode($colors),
                'configuration' => json_encode($configuration)
            ];
            $this->reportCardTemplate->update($id, $value);

            ResponseService::successResponse('Report Card Template Design Updated Successfully');

        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e, "Report Card Template Controller -> Design Store Method");
            ResponseService::errorResponse();
        }
    }

    public function generate_report(Request $request)
    {
        $request->validate([
            'report_card_template_id' => 'required',
            'class_section_id' => 'required',
            'exam_id' => 'required'
        ]);

        try {
            $reportCardTemplate = $this->reportCardTemplate->findById($request->report_card_template_id);
            
            // Ensure reportCardTemplate is an object, not a collection
            if (is_array($reportCardTemplate) || $reportCardTemplate instanceof \Illuminate\Support\Collection) {
                throw new \Exception('Invalid report card template data format');
            }
            
            $classSection = $this->classSection->findById($request->class_section_id);
            $exam = $this->exam->findById($request->exam_id);

            // Get all students in the class
            $students = $this->user->builder()
                ->whereHas('student', function ($q) use ($request) {
                    $q->where('class_section_id', $request->class_section_id);
                })
                ->where('user_type', 1)
                ->get();

            // Get exam results
            $examResults = $this->examResult->builder()
                ->where('exam_id', $exam->id)
                ->where('class_section_id', $classSection->id)
                ->with('user', 'class_section')
                ->get()
                ->keyBy('user_id');

            $allStudentsData = [];
            $grades = $this->grades->builder()->orderBy('starting_range')->get();

            foreach ($students as $student) {
                if (isset($examResults[$student->id])) {
                    $result = $examResults[$student->id];
                    
                    // Get attendance
                    $attendanceData = $this->attendance->builder()
                        ->where('user_id', $student->id)
                        ->whereMonth('attendance_date', now()->month)
                        ->get();
                    
                    $studentAttendanceCount = $attendanceData->where('status', 'P')->count();
                    $attendanceTotal = $attendanceData->count();

                    $allStudentsData[] = [
                        'result' => $result,
                        'studentAttendanceCount' => $studentAttendanceCount,
                        'attendanceTotal' => $attendanceTotal,
                        // Provide per-student results collection for dynamic table rendering
                        'results' => collect([$result]),
                        'exams' => collect([$exam])
                    ];
                }
            }

            $style = json_decode($reportCardTemplate->style, true) ?? [];
            $colors = json_decode($reportCardTemplate->colors, true) ?? [];
            $settings = $this->cache->getSchoolSettings();
            $exams = collect([$exam]);

            return view('reports.report-card-template.report-card-pdf', compact(
                'reportCardTemplate',
                'allStudentsData',
                'grades',
                'exam',
                'style',
                'colors',
                'settings',
                'exams'
            ));

        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e, "Report Card Template Controller -> Generate Report Method");
            ResponseService::errorResponse($e->getMessage());
        }
    }
}
