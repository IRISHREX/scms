<?php

namespace App\Http\Controllers\Exam;

use App\Exports\MarksDataExport;
use App\Imports\MarksDataImport;
use App\Http\Controllers\Controller;
use App\Models\ExamResult;
use App\Models\ExamTimetable;
use App\Repositories\ClassSchool\ClassSchoolInterface;
use App\Repositories\ClassSection\ClassSectionInterface;
use App\Repositories\ClassSubject\ClassSubjectInterface;
use App\Repositories\ClassTeachers\ClassTeachersInterface;
use App\Repositories\Exam\ExamInterface;
use App\Repositories\ExamMarks\ExamMarksInterface;
use App\Repositories\ExamResult\ExamResultInterface;
use App\Repositories\ExamTimetable\ExamTimetableInterface;
use App\Repositories\Grades\GradesInterface;
use App\Repositories\Medium\MediumInterface;
use App\Repositories\SessionYear\SessionYearInterface;
use App\Repositories\Student\StudentInterface;
use App\Repositories\StudentSubject\StudentSubjectInterface;
use App\Repositories\Subject\SubjectInterface;
use App\Repositories\User\UserInterface;
use App\Repositories\ReportCardTemplate\ReportCardTemplateInterface;
use App\Repositories\Attendance\AttendanceInterface;
use App\Services\BootstrapTableService;
use App\Services\SessionYearsTrackingsService;
use App\Services\CachingService;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PDF;
use Throwable;
use Excel;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use App\Models\ClassTeacher;
use Illuminate\Support\Str;

use function PHPUnit\Framework\isEmpty;

class ExamController extends Controller
{
    private ExamInterface $exam;
    private ClassSchoolInterface $class;
    private SessionYearInterface $sessionYear;
    private SubjectInterface $subject;
    private ExamTimetableInterface $examTimetable;
    private ClassSectionInterface $classSection;
    private ExamMarksInterface $examMarks;
    private ExamResultInterface $examResult;
    private StudentSubjectInterface $studentSubject;
    private ClassSubjectInterface $classSubject;
    private UserInterface $users;
    private CachingService $cache;
    private MediumInterface $mediums;
    private ClassTeachersInterface $classTeacher;
    private GradesInterface $grade;
    private StudentInterface $student;
    private SessionYearsTrackingsService $sessionYearsTrackingsService;
    private ReportCardTemplateInterface $reportCardTemplate;
    private AttendanceInterface $attendance;

    public function __construct(ExamInterface $exam, ClassSchoolInterface $class, SessionYearInterface $sessionYear, SubjectInterface $subject, ExamTimetableInterface $examTimetable, ClassSectionInterface $classSection, ExamMarksInterface $examMarks, ExamResultInterface $examResult, StudentSubjectInterface $studentSubject, ClassSubjectInterface $classSubject, UserInterface $users, CachingService $cache, MediumInterface $mediums, ClassTeachersInterface $classTeacher, GradesInterface $grade, StudentInterface $student, SessionYearsTrackingsService $sessionYearsTrackingsService, ReportCardTemplateInterface $reportCardTemplate, AttendanceInterface $attendance)
    {
        $this->exam = $exam;
        $this->class = $class;
        $this->sessionYear = $sessionYear;
        $this->subject = $subject;
        $this->examTimetable = $examTimetable;
        $this->classSection = $classSection;
        $this->examMarks = $examMarks;
        $this->examResult = $examResult;
        $this->studentSubject = $studentSubject;
        $this->classSubject = $classSubject;
        $this->users = $users;
        $this->cache = $cache;
        $this->mediums = $mediums;
        $this->classTeacher = $classTeacher;
        $this->grade = $grade;
        $this->student = $student;
        $this->sessionYearsTrackingsService = $sessionYearsTrackingsService;
        $this->reportCardTemplate = $reportCardTemplate;
        $this->attendance = $attendance;
    }

    public function index()
    {
        ResponseService::noFeatureThenRedirect('Exam Management');
        ResponseService::noPermissionThenRedirect('exam-create');
        $classes = $this->class->all(['*'], ['stream', 'medium', 'stream']);
        $subjects = $this->subject->builder()->orderBy('id', 'DESC')->get();
        $session_year_all = $this->sessionYear->all();
        $mediums = $this->mediums->builder()->pluck('name', 'id');
        return response(view('exams.index', compact('classes', 'subjects', 'session_year_all', 'mediums')));
    }

    public function store(Request $request)
    {
        ResponseService::noFeatureThenRedirect('Exam Management');
        ResponseService::noPermissionThenSendJson('exam-create');
        $request->validate([
            'name' => 'required',
            'session_year_id' => 'required',
            'class_id' => 'required|array',
            'class_id.*' => 'exists:classes,id',
            'exam_type' => 'required|array',
        ]);
        try {
            DB::beginTransaction();
            $sessionYear = $this->cache->getDefaultSessionYear();
            $examData = [];

            // Loop through each class ID and create exam records
            foreach ($request->class_id as $classId) {
                $exam = $this->exam->create([
                    'name' => $request->name,
                    'session_year_id' => $request->session_year_id,
                    'description' => $request->description,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'school_id' => Auth::user()->school_id,
                    'publish' => $request->publish ?? 0,
                    'last_result_submission_date' => $request->last_result_submission_date,
                    'class_id' => $classId,
                    'exam_type' => json_encode($request->exam_type)
                ]);

                if ($sessionYear) {
                    $this->sessionYearsTrackingsService->storeSessionYearsTracking(
                        'App\Models\Exam',
                        $exam->id,
                        Auth::user()->id,
                        $sessionYear->id,
                        Auth::user()->school_id,
                        null
                    );
                }

                $examData[] = $exam;
            }

            DB::commit();
            ResponseService::successResponse('Data Stored Successfully');
        } catch (Throwable $e) {
            if (
                Str::contains($e->getMessage(), [
                    'does not exist',
                    'file_get_contents'
                ])
            ) {
                DB::commit();
                ResponseService::warningResponse("Data Stored successfully. But App push notification not send.");
            } else {
                DB::rollBack();
                ResponseService::logErrorResponse($e, "Exam Controller -> Store method");
                ResponseService::errorResponse();
            }
        }
    }

    public function show()
    {
        ResponseService::noFeatureThenRedirect('Exam Management');
        ResponseService::noPermissionThenSendJson('exam-list');
        $offset = request('offset', 0);
        $limit = request('limit', 10);
        $sort = request('sort', 'id');
        $order = request('order', 'DESC');
        $search = request('search');
        $showDeleted = request('show_deleted');
        $medium_id = request('medium_id');
        $schoolSettings = $this->cache->getSchoolSettings();

        $sql = $this->exam->builder()->with([
            'class.medium',
            'class.stream',
            'class.section',
            'timetable.class_subject.subject',
            'timetable.exam_marks.user.student'
        ])
            ->with([
                'class.section' => function ($q) {
                    $q->whereNull('class_sections.deleted_at');
                }
            ])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('id', 'LIKE', "%$search%")
                        ->orWhere('name', 'LIKE', "%$search%")
                        ->orWhere('description', 'LIKE', "%$search%")
                        ->orWhere('created_at', 'LIKE', "%" . date('Y-m-d H:i:s', strtotime($search)) . "%")
                        ->orWhere('updated_at', 'LIKE', "%" . date('Y-m-d H:i:s', strtotime($search)) . "%")
                        ->orWhereHas('session_year', function ($subQuery) use ($search) {
                            $subQuery->where('name', 'LIKE', "%$search%");
                        });
                });
            })->when(request('session_year_id') != null, function ($query) {
                $query->where('session_year_id', request('session_year_id'));
            })->when($medium_id, function ($query) use ($medium_id) {
                $query->whereHas('class', function ($q) use ($medium_id) {
                    $q->where('medium_id', $medium_id);
                });
            })
            ->when(!empty($showDeleted), function ($query) {
                $query->onlyTrashed();
            });

        $total = $sql->count();
        if ($offset >= $total && $total > 0) {
            $lastPage = floor(($total - 1) / $limit) * $limit; // calculate last page offset
            $offset = $lastPage;
        }
        $sql->orderBy($sort, $order)->skip($offset)->take($limit);
        $res = $sql->get();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $no = 1;

        $classSections = $this->classSection->builder()->get()->toArray();

        foreach ($res as $row) {
            $operate = '';

            if ($showDeleted) {
                $operate .= BootstrapTableService::menuRestoreButton('restore', route('exams.restore', $row->id));
                $operate .= BootstrapTableService::menuTrashButton('delete', route('exams.trash', $row->id));
            } else if ($row->publish == 0) {
                $operate .= BootstrapTableService::menuButton('timetable', route('exam.timetable.edit', $row->id));
                $operate .= BootstrapTableService::menuButton('publish', "#", ["publish-exam-result"], ['data-id' => $row->id]);

                if (($row->exam_status == 0)) {
                    $operate .= BootstrapTableService::menuEditButton('edit', route('exams.update', $row->id));
                }
                $operate .= BootstrapTableService::menuDeleteButton('delete', route('exams.destroy', $row->id));
            } else if ($row->publish == 1) {
                $operate .= BootstrapTableService::menuButton('timetable', route('exam.timetable.edit', $row->id));
                $operate .= BootstrapTableService::menuButton('Unpublish', "#", ["publish-exam-result"], ['data-id' => $row->id]);
            }

            $tempRow = $row->toArray();
            $tempRow['no'] = $no++;
            $tempRow['exam_type'] = json_decode($row->exam_type);
            // Ensure publish is present and a simple scalar for the front-end formatter
            $tempRow['publish'] = (int) $row->publish;
            $classSectionWiseStatus = []; // Initialize here to accumulate all data for the current exam

            foreach ($row->class->section as $section) {
                $sectionId = $section->id;
                $subjectWiseStatus = [];
                $processedSubjects = [];

                $classNameWithSection = $row->class->name . ' - ' . $section->name ?? '' . ' ' . $row->class->medium->name;

                $class_section_id = $this->findClassSection($classSections, $row->class_id, $sectionId);

                if ($row->has_timetable) {
                    foreach ($row->timetable as $timetable) {
                        $subject = $timetable->subject_with_name;
                        $subjectId = $timetable->class_subject_id;

                        if (isset($processedSubjects[$subjectId])) {
                            continue;
                        }
                        $marks = collect([]);
                        if ($class_section_id) {
                            $marks = $timetable->exam_marks->where('user.student.class_section_id', $class_section_id);
                        }
                        $marksSubmitted = $marks->isNotEmpty();
                        $status = $marksSubmitted ? 'Submitted' : 'Pending';

                        $subjectWiseStatus[] = [
                            'subject_id' => $subjectId,
                            'subject' => $subject,
                            'status' => $status,
                            'marks_count' => $marksSubmitted ? $marks->count() : 0,
                        ];

                        $processedSubjects[$subjectId] = true;
                    }

                    $classSectionStatus = count($subjectWiseStatus) > 0
                        ? (collect($subjectWiseStatus)->contains('status', 'Pending') ? 'Pending' : 'Submitted')
                        : 'Pending';


                    $classSectionWiseStatus[] = [
                        'class_section_name' => $classNameWithSection,
                        'status' => $classSectionStatus,
                        'subjectWiseStatus' => $subjectWiseStatus,
                    ];
                }
            }
            $tempRow['classSectionWiseStatus'] = $classSectionWiseStatus;
            $tempRow['operate'] = BootstrapTableService::menuItem($operate);
            $tempRow['created_at'] = date($schoolSettings['date_format'], strtotime($row->created_at));
            $tempRow['updated_at'] = date($schoolSettings['date_format'], strtotime($row->updated_at));
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        return response()->json($bulkData);
    }

    public function update($id, Request $request)
    {
        ResponseService::noFeatureThenRedirect('Exam Management');
        ResponseService::noPermissionThenSendJson('exam-edit');
        $request->validate([
            'name' => 'required',
            'exam_type' => 'nullable|array',
        ]);
        try {
            $data = $request->all();
            if ($request->has('exam_type')) {
                $data['exam_type'] = json_encode($request->exam_type);
            }
            $this->exam->update($id, $data);
            ResponseService::successResponse('Data Updated Successfully');
        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e, "Exam Controller -> Update method");
            ResponseService::errorResponse();
        }
    }

    public function destroy($id)
    {
        ResponseService::noFeatureThenRedirect('Exam Management');
        ResponseService::noPermissionThenSendJson('exam-delete');
        try {
            $this->exam->deleteById($id);
            $sessionYear = $this->cache->getDefaultSessionYear();
            $this->sessionYearsTrackingsService->storeSessionYearsTracking('App\Models\Exam', $id, Auth::user()->id, $sessionYear->id, Auth::user()->school_id, null);
            ResponseService::successResponse('Data Deleted Successfully');
        } catch (Throwable $e) {
            DB::rollBack();
            ResponseService::logErrorResponse($e, "Exam Controller -> Delete method");
            ResponseService::errorResponse();
        }
    }

    public function restore(int $id)
    {
        ResponseService::noFeatureThenRedirect('Exam Management');
        ResponseService::noPermissionThenSendJson('exam-delete');
        try {
            $this->exam->findOnlyTrashedById($id)->restore();
            ResponseService::successResponse("Data Restored Successfully");
        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e);
            ResponseService::errorResponse();
        }
    }

    public function trash($id)
    {
        ResponseService::noFeatureThenRedirect('Exam Management');
        ResponseService::noPermissionThenSendJson('exam-delete');
        try {
            $this->exam->findOnlyTrashedById($id)->forceDelete();
            ResponseService::successResponse("Data Deleted Permanently");
        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e, "Exam Controller ->Trash Method", 'Can not Delete this because marks are already submitted');

            ResponseService::errorResponse();
        }
    }

    // -----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    /*** Upload Marks ***/
    public function uploadMarks()
    {
        ResponseService::noFeatureThenRedirect('Exam Management');
        ResponseService::noPermissionThenRedirect('exam-upload-marks');

        $teacherId = Auth::user()->teacher->user_id;
        $classes = $this->classSection->builder()->whereHas('class_teachers', function ($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId);
        })->with('class', 'section', 'medium')->orWhereHas('subject_teachers', function ($q) use ($teacherId) {
            $q->where('teacher_id', $teacherId);
        })->get();

        //        $exams = $this->exam->builder()
        //            ->with(['timetable' => function ($query) {
        //                $query->where('date', '<', date('Y-m-d'))
        //                    ->orWhere(function($q) {
        //                        $q->whereDate('date','=', date('Y-m-d'))->where('end_time', '<=', date('H:i:s'));
        //                    })->with('class_subject.subject');
        //            }])->where('publish', 0)
        //            ->get();
        //
        //        $exams = $this->exam->builder()
        //            ->with(['timetable' => function ($query) {
        //                $query->where('date', '<', date('Y-m-d'))
        //                    ->orWhere(function ($q) {
        //                        $q->whereDate('date', '=', date('Y-m-d'))->where('end_time', '<=', date('H:i:s'));
        //                    })->with('class_subject.subject');
        //            }])
        //            ->where('publish', 0)
        //            ->get();

        $exams = $this->exam->builder()->with([
            'timetable' => function ($query) {
                $query->where('date', '<', date('Y-m-d'))->orWhere(function ($q) {
                    $q->whereDate('date', '=', date('Y-m-d'))->where('end_time', '<=', date('H:i:s'));
                })->with([
                            'class_subject' => function ($q) {
                                $q->SubjectTeacherClassTeacher()->with([
                                    'subject_teacher' => function ($q) {
                                        $q->where('teacher_id', Auth::user()->id)->with('subject');
                                    }
                                ]);
                            }
                        ]);
            }
        ])->where('publish', 0)->get();


        return response()->view('exams.upload-marks', compact('exams', 'classes'));
    }

    public function marksList(Request $request)
    {
        ResponseService::noFeatureThenRedirect('Exam Management');

        $request->validate([
            'class_section_id' => 'required',
            'exam_id' => 'required',
            'class_subject_id' => 'required',
        ], [
            'class_section_id.required' => 'Class section field is required',
            'exam_id.required' => 'Exam field is required',
            'class_subject_id.required' => 'Class subject field is required',
        ]);

        try {
            $sort = $request->input('sort', 'id');
            $order = $request->input('order', 'ASC');
            $search = $request->input('search');

            // Get Exam with timetable and exam types
            $exam = $this->exam->builder()->with('timetable')->where('id', $request->exam_id)->first();

            // Get exam types dynamically
            $exam_type = json_decode($exam->exam_type, true) ?? [];

            // Get Student ids according to Subject is elective or compulsory
            $classSubject = $this->classSubject->findById($request->class_subject_id);
            if ($classSubject->type == "Elective") {
                $studentIds = $this->studentSubject->builder()->where([
                    'class_section_id' => $request->class_section_id,
                    'class_subject_id' => $classSubject->id
                ])->pluck('student_id');
            } else {
                $studentIds = $this->users->builder()->role('Student')->whereHas('student', function ($query) use ($request) {
                    $query->where('class_section_id', $request->class_section_id);
                })->pluck('id');
            }
            
            // Get Timetable Data
            $timetable = $exam->timetable()->where('class_subject_id', $request->class_subject_id)->first();

            if (!$timetable) {
                return response()->json(['error' => true, 'message' => trans('Exam Timetable Does not Exists')]);
            }

            if ($exam->exam_status != 2) {
                ResponseService::errorResponse('Exam not completed yet');
            }

            $sessionYear = $this->cache->getDefaultSessionYear();
            // Build students query. If sorting by roll_number (a field on the students table),
            // join the students table and order by that column. Otherwise use the normal order.
            $studentsQuery = $this->users->builder()->role('Student')->whereIn('id', $studentIds)->with([
                'exam_marks' => function ($query) use ($timetable) {
                    $query->where('exam_timetable_id', $timetable->id);
                }, 'student'
            ])->when($search, function ($q) use ($search) {
                $q->whereRaw("concat(first_name,' ',last_name) LIKE '%" . $search . "%'");
            })->where('users.school_id', Auth::user()->school_id)->whereHas('student', function ($q) use ($sessionYear) {
                $q->where('session_year_id', $sessionYear->id);
            });
            // dd($studentsQuery);
            if ($sort === 'roll_number') {
                // Avoid joining students table to prevent ambiguous column errors from global scopes
                // Use a subquery in ORDER BY to fetch students.roll_number for each user
                $students = $studentsQuery->orderByRaw("(SELECT roll_number FROM students WHERE students.user_id = users.id) $order")->get();
            } else {
                // ensure school scoping when not joining
                $students = $studentsQuery->orderBy($sort, $order)->get();
            }
      

            $rows = [];
            foreach ($students as $no => $student) {
                $exam_mark = $student->exam_marks->first();
                $marks_data = $exam_mark && !empty($exam_mark->marks) ? json_decode($exam_mark->marks, true) : [];

                $row = [
                    'id' => $student->id,
                    'no' => $no + 1,
                    'student_name' => $student->full_name,
                    'roll_number' => $student->student->roll_number,
                    'total_marks' => $timetable->total_marks,
                    'exam_marks_id' => $exam_mark->id ?? '',
                    'obtained_marks' => $exam_mark->obtained_marks ?? '',
                ];

                // Add exam types dynamically - this is what makes the columns dynamic
                foreach ($exam_type as $type) {
                    $row[$type] = $marks_data[$type] ?? '';
                }

                $rows[] = $row;
            }

            $bulkData['rows'] = $rows;
            // include total so bootstrap-table (server mode) can know total rows when needed
            $bulkData['total'] = count($rows);
            return response()->json($bulkData);
        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e, "Exam Controller -> Get Exam Subjects");
            ResponseService::errorResponse('Something went wrong',$e);
        }
    }
    public function submitMarks(Request $request)
    {
        ResponseService::noFeatureThenRedirect('Exam Management');
        $request->validate(['exam_id' => 'required|numeric', 'class_subject_id' => 'required|numeric', 'exam_marks' => 'required|array',], ['class_id.required' => 'Class section field is required.', 'exam_id.required' => 'Exam field is required.', 'class_subject_id.required' => 'Subject field is required.', 'exam_marks.required' => 'No records found.',]);

        try {
            $exam_timetable = $this->examTimetable->builder()->where(['exam_id' => $request->exam_id, 'class_subject_id' => $request->class_subject_id])->firstOrFail();
            $exam = $this->exam->findById($request->exam_id);
            $exam_types = json_decode($exam->exam_type, true) ?? [];

            foreach ($request->exam_marks as $examMarks) {
                if (empty($examMarks['student_id'])) {
                    continue;
                }
                $passing_marks = $exam_timetable->passing_marks;
                if ($examMarks['obtained_marks'] >= $passing_marks) {
                    $status = 1;
                } else {
                    $status = 0;
                }
                $marks_percentage = ($examMarks['obtained_marks'] / $examMarks['total_marks']) * 100;
                $exam_grade = findExamGrade($marks_percentage);

                if ($exam_grade == null) {
                    ResponseService::errorResponse('Grades data does not exists');
                }

                $type_marks = [];
                if (isset($exam_types)) {
                    foreach ($exam_types as $type) {
                        if (isset($examMarks[$type])) {
                            $type_marks[$type] = $examMarks[$type];
                        }
                    }
                }

                $this->examMarks->updateOrCreate(['id' => $examMarks['exam_marks_id'] ?? null], [
                    'exam_timetable_id' => $exam_timetable->id,
                    'student_id' => $examMarks['student_id'],
                    'class_subject_id' => $request->class_subject_id,
                    'obtained_marks' => $examMarks['obtained_marks'],
                    'passing_status' => $status,
                    'session_year_id' => $exam_timetable->session_year_id,
                    'grade' => $exam_grade,
                    'marks' => json_encode($type_marks)
                ]);
            }

            $sessionYear = $this->cache->getDefaultSessionYear();
            $this->sessionYearsTrackingsService->storeSessionYearsTracking('App\Models\Exam', $request->exam_id, Auth::user()->id, $sessionYear->id, Auth::user()->school_id, null);

            ResponseService::successResponse('Data Stored Successfully');
        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e, "Exam Controller -> Get Exam Subjects");
            ResponseService::errorResponse();
        }
    }

    public function getSubjectByExam($exam_id, Request $request)
    {
        ResponseService::noFeatureThenRedirect('Exam Management');
        try {
            $teacherId = Auth::user()->id;

            $isClassTeacher = ClassTeacher::where('teacher_id', $teacherId)->where('class_section_id', $request->class_section_id)->first();


            if ($isClassTeacher) {
                $exam_timetable = ExamTimetable::with(['class_subject', 'subject_teacher'])
                    ->where('exam_id', $exam_id)
                    ->get();
            } else {
                $exam_timetable = ExamTimetable::with(['class_subject', 'subject_teacher'])
                    ->whereHas('subject_teacher', function ($query) use ($teacherId, $request) {
                        $query->where('teacher_id', $teacherId)->where('class_section_id', $request->class_section_id);
                    })
                    ->where('exam_id', $exam_id)
                    ->get();
            }

            $response = array('error' => false, 'message' => trans('data_fetch_successfully'), 'data' => $exam_timetable);
        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e, "Exam Controller -> Get Exam Subjects");
            ResponseService::errorResponse();
        }
        return response()->json($response);
    }



    public function getExamResultIndex()
    {
        ResponseService::noFeatureThenRedirect('Exam Management');
        ResponseService::noPermissionThenRedirect('exam-result');
        $exams = $this->exam->builder()->with('class.medium')->where('publish', 1);
        $sessionYears = $this->sessionYear->all();
        // $classSections = $this->classSection->all(['*'], ['class', 'class.stream', 'section', 'medium']);

        $classSections = $this->classSection->builder()->with('class.stream', 'section', 'medium');
        if (Auth::user()->hasRole('Teacher')) {
            $classTeacher = $this->classTeacher->builder()->where('teacher_id', Auth::user()->id)->with('class_section')->get();
            $classSections = $classSections->whereIn('id', $classTeacher->pluck('class_section_id'));
            $exams = $exams->whereIn('class_id', $classTeacher->pluck('class_id'));
        }

        $classSections = $classSections->get();
        $exams = $exams->get()->append(['prefix_name']);

        // Get report card templates
        $reportCardTemplates = $this->reportCardTemplate->builder()->get();

        return view('exams.show_exam_result', compact('exams', 'sessionYears', 'classSections', 'reportCardTemplates'));
    }

    public function showExamResult(Request $request)
    {
        ResponseService::noFeatureThenRedirect('Exam Management');
        ResponseService::noPermissionThenSendJson('exam-result');
        $offset = request('offset', 0);
        $limit = request('limit', 10);
        $sort = request('sort', 'id');
        $order = request('order', 'DESC');
        $search = request('search');
        $sql = $this->examResult->builder()->with([
            'user.student',
            'user.exam_marks' => function ($q) use ($request) {
                $q->whereHas('timetable', function ($q) use ($request) {
                    $q->where('exam_id', $request->exam_id);
                })->with('timetable', 'subject');
            }
        ])->where('exam_id', $request->exam_id)
            ->where('session_year_id', $request->session_year_id)
            ->when($search, function ($q) use ($search, $request) {
                $q->where(function ($q) use ($search) {
                    $q->where('id', 'LIKE', "%$search%")
                        ->orwhere('total_marks', 'LIKE', "%$search%")
                        ->orwhere('grade', 'LIKE', "%$search%")
                        ->orwhere('obtained_marks', 'LIKE', "%$search%")
                        ->orwhere('percentage', 'LIKE', "%$search%")
                        ->orWhereHas('user', function ($q) use ($search) {
                            $q->whereRaw("concat(first_name,' ',last_name) LIKE '%" . $search . "%'");
                        });
                })->where('exam_id', $request->exam_id)->Owner();
            });



        if ($request->class_section_id) {
            $sql = $sql->where('class_section_id', $request->class_section_id);
        }

        // Prepare ranks: compute total obtained marks per student within this exam and session year,
        // then calculate class and section ranks.
        try {
            $marksSub = DB::table('students')
                ->join('exam_results', function ($join) use ($request) {
                    $join->on('students.user_id', '=', 'exam_results.student_id')
                        ->where('exam_results.session_year_id', $request->session_year_id)
                        ->where('exam_results.exam_id', $request->exam_id);
                })
                ->join('class_sections', 'students.class_section_id', '=', 'class_sections.id')
                ->select(
                    'students.user_id',
                    'students.class_section_id',
                    'class_sections.class_id',
                    DB::raw('SUM(exam_results.obtained_marks) as total_obtained_marks')
                )
                ->groupBy('students.user_id', 'students.class_section_id', 'class_sections.class_id');

            $rankedData = DB::table(DB::raw("({$marksSub->toSql()}) as ranked"))
                ->mergeBindings($marksSub)
                ->orderBy('class_id')
                ->orderByDesc('total_obtained_marks')
                ->get();

            $classRanks = [];
            $sectionRanks = [];
            $lastClassMarks = [];
            $lastSectionMarks = [];
            $classRankValue = [];
            $sectionRankValue = [];

            foreach ($rankedData as $r) {
                // class rank
                if (!isset($classRanks[$r->class_id])) {
                    $classRanks[$r->class_id] = 1;
                    $lastClassMarks[$r->class_id] = $r->total_obtained_marks;
                    $classRankValue[$r->class_id] = 1;
                } else {
                    if ($lastClassMarks[$r->class_id] != $r->total_obtained_marks) {
                        $classRankValue[$r->class_id]++;
                    }
                    $lastClassMarks[$r->class_id] = $r->total_obtained_marks;
                }
                $r->class_rank = $classRankValue[$r->class_id];

                // section rank
                if (!isset($sectionRanks[$r->class_section_id])) {
                    $sectionRanks[$r->class_section_id] = 1;
                    $lastSectionMarks[$r->class_section_id] = $r->total_obtained_marks;
                    $sectionRankValue[$r->class_section_id] = 1;
                } else {
                    if ($lastSectionMarks[$r->class_section_id] != $r->total_obtained_marks) {
                        $sectionRankValue[$r->class_section_id]++;
                    }
                    $lastSectionMarks[$r->class_section_id] = $r->total_obtained_marks;
                }
                $r->section_rank = $sectionRankValue[$r->class_section_id];
            }

            $ranks = collect($rankedData)->keyBy('user_id');
        } catch (Throwable $e) {
            // If rank calculation fails, continue without ranks
            $ranks = collect([]);
        }

        $total = $sql->count();
        if ($offset >= $total && $total > 0) {
            $lastPage = floor(($total - 1) / $limit) * $limit; // calculate last page offset
            $offset = $lastPage;
        }

        $sql->orderBy($sort, $order)->skip($offset)->take($limit);
        $res = $sql->get();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $no = 1;
        foreach ($res as $row) {
            $operate = '';
            if (Auth::user()->can('exam-result-edit')) {
                $operate = BootstrapTableService::button('fa fa-edit', '#', ['btn-gradient-primary', 'btn-xs', 'btn-rounded', 'btn-icon', 'edit-data'], ['data-id' => $row->id, 'data-student_id' => $row->student_id, 'title' => 'Edit', 'data-toggle' => 'modal', 'data-target' => '#editModal']);

                $operate .= BootstrapTableService::button('fa fa-file-pdf-o', '#', ['btn-gradient-info', 'btn-xs', 'btn-rounded', 'btn-icon', 'view-pdf-result'], ['data-student_id' => $row->student_id, 'data-exam_id' => $row->exam_id, 'title' => __('view_result')]);
            }
            $tempRow = $row->toArray();
            $tempRow['no'] = $no++;
            // Ensure publish is present for front-end usage in view marks table
            $tempRow['publish'] = (int) $row->publish;
            // Attach computed ranks if available
            $tempRow['class_rank'] = $ranks[$row->student_id]->class_rank ?? null;
            $tempRow['section_rank'] = $ranks[$row->student_id]->section_rank ?? null;
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;

        return response()->json($bulkData);
    }

    public function updateExamResultMarks(Request $request)
    {
        ResponseService::noFeatureThenRedirect('Exam Management');
        ResponseService::noPermissionThenSendJson('exam-result-edit');
        $request->validate([
            'edit.*.marks_id' => 'required|numeric',
            'edit.*.obtained_marks' => 'required|numeric|lte:edit.*.total_marks'
        ]);
        try {
            DB::beginTransaction();
            // Loop Through Request Data
            foreach ($request->edit as $data) {
                $passingMarks = $data['passing_marks']; // Get Passing Marks
                $marksPercentage = ($data['obtained_marks'] / $data['total_marks']) * 100; // Get Percentage

                // Get Percentage And Check that Grades Should not be NULL
                $grade = findExamGrade($marksPercentage);
                if ($grade == null) {
                    ResponseService::errorResponse("Grades data does not exists");
                }

                // Array for Update Marks
                $updateMarksData = array(
                    'obtained_marks' => $data['obtained_marks'],
                    'passing_status' => $data['obtained_marks'] >= $passingMarks ? 1 : 0,
                    'grade' => $grade
                );

                $this->examMarks->update($data['marks_id'], $updateMarksData); // Update Exam Marks

                $examResultId = $this->examResult->builder()->where(['exam_id' => $data['exam_id'], 'student_id' => $data['student_id']])->value('id'); // Get Exam Result ID

                // Query Data From Exam Table To Get Exam Marks According to Exam ID

                DB::enableQueryLog();
                $exam = $this->exam->builder()->with([
                    'marks' => function ($query) use ($data) {
                        $query->with('user.student:id,user_id,class_section_id')
                            ->selectRaw('SUM(obtained_marks) as total_obtained_marks,student_id')
                            ->selectRaw('SUM(total_marks) as total_marks')
                            ->selectRaw('MIN(CASE WHEN passing_status = 0 THEN 0 ELSE 1 END) as overall_passing_status')
                            ->where('student_id', $data['student_id'])
                            ->groupBy('student_id');
                    },
                    'timetable' => function ($query) use ($data) {
                        $query->where(['exam_id' => $data['exam_id']]);
                    }
                ])->where('id', $data['exam_id'])->first();

                // Loop through Exam Marks Data
                foreach ($exam->marks as $examMarks) {
                    $percentage = ($examMarks['total_obtained_marks'] * 100) / $examMarks['total_marks']; // Get Percentage

                    // Get Percentage And Check that Grades Should not be NULL
                    $grade = findExamGrade($percentage);
                    if ($grade == null) {
                        ResponseService::errorResponse("Grades data does not exists");
                    }

                    // Array For Update Exam Result Data
                    $examResultData = array("obtained_marks" => $examMarks['total_obtained_marks'], "percentage" => round($percentage, 2), "grade" => $grade, "status" => $examMarks['overall_passing_status']);

                    $this->examResult->update($examResultId, $examResultData); // Update Exam Result
                }
            }
            DB::commit();
            ResponseService::successResponse("Data Updated Successfully");
        } catch (Throwable $e) {
            DB::rollBack();
            ResponseService::logErrorResponse($e, "Exam Controller -> updateExamResultMarks method");
            ResponseService::errorResponse();
        }
    }

    // -----------------------------------------------------------------------------------------------------

    public function publishExamResult($id)
    {
        ResponseService::noFeatureThenRedirect('Exam Management');
        try {

            // Get The Exam Data with Marks and Timetable
            $exam = $this->exam->builder()->with([
                'marks' => function ($query) {
                    $query->with('user:id,first_name,last_name,image', 'user.student:id,user_id,class_section_id')->selectRaw('SUM(obtained_marks) as total_obtained_marks, student_id')->selectRaw('SUM(total_marks) as total_marks')->groupBy('student_id');
                },
                'timetable:id,exam_id,start_time,end_time'
            ])->with([
                        'timetable' => function ($q) {
                            $q->with('exam_marks');
                        }
                    ])->findOrFail($id);

            $allSubjectsSubmitted = true;
            foreach ($exam->timetable as $timetable) {
                // Check if there are no exam marks associated with this timetable
                if ($timetable->exam_marks->isEmpty()) {
                    // If marks for any subject are missing, set the flag to false and break the loop
                    $allSubjectsSubmitted = false;
                    break;
                }
            }

            if (!$allSubjectsSubmitted) {
                ResponseService::errorResponse("Marks are not uploaded yet.");
            }



            DB::beginTransaction();
            if ($exam->exam_status == 2 && $exam->marks->isNotEmpty()) {

                if ($exam->publish == 0) {


                    // If exam is Unpublished then Insert ExamResult records and Publish the Exam
                    $examResult = $exam->marks->map(function ($examMarks) use ($exam, $id) {
                        $percentage = ($examMarks['total_obtained_marks'] * 100) / $examMarks['total_marks'];
                        $grade = findExamGrade($percentage);

                        if ($grade === null) {
                            ResponseService::errorResponse("Grades data does not exists");
                        }

                        // Get passing status
                        $status = $this->resultStatus($id, $examMarks['student_id']);


                        $subjectMarks = $this->examMarks->builder()
                            ->whereHas('timetable', function ($q) use ($id) {
                                $q->where('exam_id', $id);
                            })
                            ->where('student_id', $examMarks['student_id'])
                            ->get()
                            ->map(function ($mark) {
                                $subjectName = $mark->timetable->class_subject->subject->name ?? $mark->subject->name ?? 'Unknown Subject';
                                $marksData = json_decode($mark->marks, true) ?? [];

                                // Return only subject name and marks
                                return [
                                    'subject' => $subjectName,
                                    'marks' => $marksData
                                ];
                            })
                            ->toArray();

                        $data = [
                            'exam_id' => $exam->id,
                            'class_section_id' => $examMarks['user']['student']['class_section_id'],
                            'student_id' => $examMarks['student_id'],
                            'total_marks' => $examMarks['total_marks'],
                            'obtained_marks' => $examMarks['total_obtained_marks'],
                            'percentage' => round($percentage, 2),
                            'grade' => $grade,
                            'status' => $status,
                            'session_year_id' => $exam->session_year_id,
                            'marks' => json_encode($subjectMarks),
                        ];
                        return $data;
                    });

                    $studentIds = $examResult->pluck('student_id')->toArray();
                    $guardian_id = $this->student->builder()->with('user')->whereIn('user_id', $studentIds)->pluck('guardian_id')->toArray();

                    $this->examResult->createBulk($examResult->toArray()); // Add Data in Exam Result
                    $this->exam->update($id, ['publish' => 1]); // Update Exam with Publish status 1

                    $user = array_merge($studentIds, $guardian_id);

                    $title = 'Result Publish for ' . $exam->name . ' examinations !!!';
                    $body = 'Congrats your result has been publish Click here see your result ';
                    $type = "exam result";

                    send_notification($user, $title, $body, $type);

                } else {
                    ExamResult::where('exam_id', $id)->delete(); // If Exam is already published then unpublished it and delete Exam Result
                    $this->exam->update($id, ['publish' => 0]); // Update Exam with Publish status 0
                }
                DB::commit();
                ResponseService::successResponse('Data Stored Successfully');
            } else {
                ResponseService::errorResponse('Exam not completed yet');
            }

        } catch (Throwable $e) {

            if (
                Str::contains($e->getMessage(), [
                    'does not exist',
                    'file_get_contents'
                ])
            ) {
                DB::commit();
                ResponseService::warningResponse("Data Stored successfully. But App push notification not send.");
            } else {
                DB::rollBack();
                ResponseService::logErrorResponse($e, "Exam Controller -> publishExamResult method");
                ResponseService::errorResponse();
            }
        }
    }


    public function resultStatus($exam_id, $student_id)
    {

        $status = $this->examMarks->builder()->whereHas('timetable', function ($q) use ($exam_id) {
            $q->where('exam_id', $exam_id);
        })->where('student_id', $student_id)->where('passing_status', 0)->first();

        if ($status) {
            return 0;
        }
        return 1;
    }

    public function deleteExamTimetable($id)
    {
        ResponseService::noPermissionThenSendJson('exam-timetable-delete');
        try {
            DB::beginTransaction();
            $this->examTimetable->deleteById($id);
            $sessionYear = $this->cache->getDefaultSessionYear();
            $this->sessionYearsTrackingsService->storeSessionYearsTracking('App\Models\ExamTimetable', $id, Auth::user()->id, $sessionYear->id, Auth::user()->school_id, null);
            DB::commit();
            ResponseService::successResponse('Data Deleted Successfully');
        } catch (Throwable $e) {
            DB::rollBack();
            ResponseService::logErrorResponse($e, "Exam Controller -> DeleteTimetable method", trans('cannot_delete_because_data_is_associated_with_other_data'));
            ResponseService::errorResponse();
        }
    }

    public function resultReport($session_year_id, $exam_name)
    {
        try {

            $exams = $this->exam->builder()->select('id', 'name', 'class_id', 'session_year_id', 'publish')->with('class.medium')->where('session_year_id', $session_year_id)->where('publish', 1)->where('name', $exam_name)->withCount('results as total_students')->withCount([
                'results as pass_students' => function ($q) {
                    $q->where('status', 1);
                }
            ])->get()->makeHidden(['exam_status', 'exam_status_name', 'has_timetable']);

            ResponseService::successResponse('Data Fetched Successfully', $exams);
        } catch (Throwable $e) {
            DB::rollBack();
            ResponseService::logErrorResponse($e);
            ResponseService::errorResponse();
        }
    }

    public function examTimetableIndex()
    {
        ResponseService::noFeatureThenRedirect('Exam Management');
        ResponseService::noAnyPermissionThenRedirect(['exam-upload-marks', 'exam-timetable-list']);
        try {

            $class_sections = $this->classSection->builder()->with('class.stream', 'medium')->get()->pluck('full_name', 'class_id');

            $sessionYear = $this->cache->getDefaultSessionYear();
            $class_id = array_keys($class_sections->toArray());
            $exams = $this->exam->builder()->whereIn('class_id', $class_id)->where('session_year_id', $sessionYear->id)->get();

            return view('exams.exams_timetable', compact('class_sections', 'exams'));
        } catch (Throwable $e) {
            DB::rollBack();
            ResponseService::logErrorResponse($e);
            ResponseService::errorResponse();
        }
    }

    public function examTimetableShow(Request $request)
    {
        ResponseService::noFeatureThenRedirect('Exam Management');
        ResponseService::noAnyPermissionThenRedirect(['exam-upload-marks', 'exam-timetable-list']);
        try {


            $sql = $this->examTimetable->builder()->with('class_subject.subject')
                ->where('exam_id', $request->exam_id)
                ->orderBy('date', 'ASC')->orderBy('start_time', 'ASC');
            $total = $sql->get()->count();

            $res = $sql->get();
            $bulkData = array();
            $bulkData['total'] = $total;
            $rows = array();
            $no = 1;
            foreach ($res as $row) {
                $tempRow = $row->toArray();
                $tempRow['no'] = $no++;
                $rows[] = $tempRow;
            }

            $bulkData['rows'] = $rows;
            return response()->json($bulkData);
        } catch (Throwable $e) {
            DB::rollBack();
            ResponseService::logErrorResponse($e);
            ResponseService::errorResponse();
        }
    }

    public function examResultPdf($student_id, $exam_id, Request $request)
    {
        ResponseService::noFeatureThenRedirect('Exam Management');
        ResponseService::noPermissionThenRedirect('exam-result');

        try {
            // Get template_id from request
            $templateId = $request->get('template_id');
            
            if (!$templateId) {
                return redirect()->back()->with('error', __('Please select report card template'));
            }

            // Get the exam
            $exam = $this->exam->builder()
                ->with([
                    'timetable' => function ($q) {
                        $q->with('exam_marks');
                    }
                ])
                ->where('id', $exam_id)
                ->first();

            if (!$exam) {
                return redirect()->back()->with('error', trans('no_records_found'));
            }

            // Get all results for the exam (for table rendering)
            $results = $this->examResult->builder()
                ->with([
                    'exam',
                    'session_year',
                    'class_section.class.stream',
                    'class_section.section',
                    'class_section.medium',
                    'user' => function ($q) use ($exam_id) {
                        $q->with([
                            'student.guardian',
                            'exam_marks' => function ($q) use ($exam_id) {
                                $q->whereHas('timetable', function ($q) use ($exam_id) {
                                    $q->where('exam_id', $exam_id);
                                })->with([
                                            'class_subject' => function ($q) {
                                                $q->withTrashed()->with('subject:id,name,type');
                                            },
                                            'timetable'
                                        ]);
                            }
                        ]);
                    }
                ])
                ->where('exam_id', $exam_id)
                ->select('exam_results.*')
                ->get();

            // Convert the results to a collection
            $results = collect($results);

            // Add rank calculation to each item in the collection
            $results = $results->map(function ($result) {
                $rank = DB::table('exam_results as er2')
                    ->where('er2.class_section_id', $result->class_section_id)
                    ->where('er2.obtained_marks', '>', $result->obtained_marks)
                    ->where('er2.exam_id', $result->exam_id)
                    ->where('er2.status', 1)
                    ->distinct('er2.obtained_marks')
                    ->count() + 1;

                $result->rank = $rank;
                return $result;
            });

            // Filter the collection based on student ID
            $result = $results->where('student_id', $student_id)->first();

            if (!$result) {
                return redirect()->back()->with('error', trans('no_records_found'));
            }

            $grades = $this->grade->builder()->orderBy('starting_range', 'ASC')->get();

            // Get student attendance count
            $studentAttendanceCount = $this->attendance->builder()
                ->where('student_id', $student_id)
                ->where('type', 1)
                ->where('session_year_id', $result->session_year_id)
                ->count();

            $attendanceTotal = $this->attendance->builder()
                ->where('student_id', $student_id)
                ->where('session_year_id', $result->session_year_id)
                ->count();

            $settings = $this->cache->getSchoolSettings();

            // Get Report Card Template by template_id from request
            $reportCardTemplate = $this->reportCardTemplate->builder()->find($templateId);
            
            if (!$reportCardTemplate) {
                return redirect()->back()->with('error', __('Report card template not found'));
            }
            
            if ($reportCardTemplate) {
                $style = json_decode($reportCardTemplate->style, true) ?? [];
                $colors = json_decode($reportCardTemplate->colors, true) ?? [];
                
                // Format data for the template view
                $exams = collect([$exam]);
                $allStudentsData = [
                    [
                        'result' => $result,
                        'studentAttendanceCount' => $studentAttendanceCount,
                        'attendanceTotal' => $attendanceTotal,
                        // Provide collections for dynamic table rendering
                        'results' => $results,
                        'exams' => $exams
                    ]
                ];

                return view('reports.report-card-template.report-card-pdf', compact(
                    'reportCardTemplate',
                    'allStudentsData',
                    'grades',
                    'style',
                    'colors',
                    'settings',
                    'results',
                    'exams'
                ));
            }

            // Fallback to old PDF if no template found
            $data = explode("storage/", $settings['horizontal_logo'] ?? '');
            $settings['horizontal_logo'] = end($data);

            if ($settings['horizontal_logo'] == null) {
                $systemSettings = $this->cache->getSystemSettings();
                $data = explode("storage/", $systemSettings['horizontal_logo'] ?? '');
                $settings['horizontal_logo'] = end($data);
            }

            $pdf = PDF::loadView('exams.exam_result_pdf', compact('result', 'settings', 'grades'));
            return $pdf->stream();

        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e);
            ResponseService::errorResponse();
        }
    }

    public function bulkUploadIndex()
    {
        ResponseService::noFeatureThenRedirect('Exam Management');

        $teacherId = Auth::user()->teacher->user_id;

        // Fetch classes where the teacher is a class teacher or subject teacher
        $classes = $this->classSection->builder()
            ->whereHas('class_teachers', function ($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            })
            ->orWhereHas('subject_teachers', function ($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            })
            ->with('class', 'section', 'medium', 'subjects')
            ->get();

        $exams = $this->exam->builder()->with([
            'timetable' => function ($query) {
                $query->where('date', '<', date('Y-m-d'))->orWhere(function ($q) {
                    $q->whereDate('date', '=', date('Y-m-d'))->where('end_time', '<=', date('H:i:s'));
                })->with([
                            'class_subject' => function ($q) {
                                $q->SubjectTeacherClassTeacher()->with([
                                    'subject_teacher' => function ($q) {
                                        $q->where('teacher_id', Auth::user()->id)->with('subject');
                                    }
                                ]);
                            }
                        ]);
            }
        ])->where('publish', 0)->get();
        // dd($classes);

        return response(view('exams.bulk_upload_marks', compact('classes', 'exams')));
    }

    public function downloadSampleFile(Request $request)
    {
        ResponseService::noFeatureThenRedirect('Exam Management');

        $validator = Validator::make($request->all(), [
            'class_section_id' => 'required|numeric',
            'exam_id' => 'required',
            'class_subject_id' => 'required',
        ]);
        if ($validator->fails()) {
            ResponseService::errorResponse($validator->errors()->first());
        }

        try {

            $exam = $this->exam->builder()->with('timetable')->where('id', $request->exam_id)->first();

            // Get Student ids according to Subject is elective or compulsory
            $classSubject = $this->classSubject->findById($request->class_subject_id);


            if ($classSubject->type == "Elective") {
                $studentIds = $this->studentSubject->builder()->where(['class_section_id' => $request->class_section_id, 'class_subject_id' => $classSubject->id])->pluck('student_id');
            } else {
                $studentIds = $this->users->builder()->role('Student')->whereHas('student', function ($query) use ($request) {
                    $query->where('class_section_id', $request->class_section_id);
                })->pluck('id');
            }

            // Get Timetable Data
            $timetable = $exam->timetable()->where('class_subject_id', $request->class_subject_id)->first();

            // IF Timetable is empty then show error message
            if (!$timetable) {
                return redirect()->route('exam.bulk-upload-marks')->with('error', trans('Exam Timetable Does not Exists'));
            }

            // IF Exam status is not 2 that is exam not completed then show error message
            if ($exam->exam_status != 2) {

                ResponseService::errorRedirectResponse(null, 'Exam not completed yet');
            }

            $sessionYear = $this->cache->getDefaultSessionYear();
            $students = $this->users->builder()->role('Student')->whereIn('id', $studentIds)->with([
                'exam_marks' => function ($query) use ($timetable) {
                    $query->where('exam_timetable_id', $timetable->id);
                }
            ])->get();
            $data = [];
            // Loop on the Students Data
            foreach ($students as $student) {
                $data[] = [
                    'exam_marks_id' => $student->exam_marks[0]->id ?? '',
                    'student_id' => $student->id,
                    'student_name' => $student->full_name,
                    'total_marks' => $timetable->total_marks,
                    'obtained_marks' => $student->exam_marks[0]->obtained_marks ?? '',
                ];
            }

            // Create a file name using Class Section, Exam Name, and Subject Name
            $classSection = $this->classSection->builder()->with('class', 'class.stream', 'section', 'medium')->where('id', $request->class_section_id)->first()->full_name;
            $examName = $exam->name;
            $subjectName = $classSubject->subject->name;

            $file_name = $classSection . '_' . $examName . '_' . $subjectName . '_marks_bulk_upload.xlsx';

            $file_name = str_replace(['/', '\\'], '-', $file_name);
            $file_name = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $file_name);

            return Excel::download(new MarksDataExport($data), $file_name);
        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e, 'Exam Controller ---> Download Sample File');
            ResponseService::errorResponse();
        }
    }

    public function storeBulkData(Request $request)
    {
        ResponseService::noFeatureThenRedirect('Exam Management');

        $validator = Validator::make($request->all(), [
            'class_section_id' => 'required|numeric',
            'exam_id' => 'required',
            'class_subject_id' => 'required',
            'file' => 'required|mimes:csv,txt'
        ]);
        if ($validator->fails()) {
            ResponseService::errorResponse($validator->errors()->first());
        }
        try {
            Excel::import(new MarksDataImport($request->class_section_id, $request->exam_id, $request->class_subject_id), $request->file);

            ResponseService::successResponse('Data Stored Successfully');
        } catch (ValidationException $e) {
            ResponseService::errorResponse($e->getMessage());
        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e, "Exam Controller -> Store Bulk method");
            ResponseService::errorResponse();
        }
    }

    public function viewMarksindex()
    {
        ResponseService::noFeatureThenRedirect('Exam Management');
        ResponseService::noPermissionThenRedirect('exam-create');
        $classes = $this->class->all(['*'], ['stream', 'medium', 'stream']);
        $subjects = $this->subject->builder()->orderBy('id', 'DESC')->get();
        $session_year_all = $this->sessionYear->all();
        $mediums = $this->mediums->builder()->pluck('name', 'id');
        return response(view('exams.view_marks', compact('classes', 'subjects', 'session_year_all', 'mediums')));
    }

    public function viewMarksShow()
    {
        ResponseService::noFeatureThenRedirect('Exam Management');
        $offset = request('offset', 0);
        $limit = request('limit', 10);
        $sort = request('sort', 'id');
        $order = request('order', 'DESC');
        $search = request('search');
        $medium_id = request('medium_id');
        $schoolSettings = $this->cache->getSchoolSettings();

        $sql = $this->exam->builder()->with([
            'class.medium',
            'class.stream',
            'timetable.class_subject.subject',
            'timetable.exam_marks.user.student'
        ])
            ->with([
                'class.section' => function ($q) {
                    $q->whereNull('class_sections.deleted_at');
                }
            ])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('id', 'LIKE', "%$search%")
                        ->orWhere('name', 'LIKE', "%$search%")
                        ->orWhere('description', 'LIKE', "%$search%")
                        ->orWhere('created_at', 'LIKE', "%" . date('Y-m-d H:i:s', strtotime($search)) . "%")
                        ->orWhere('updated_at', 'LIKE', "%" . date('Y-m-d H:i:s', strtotime($search)) . "%")
                        ->orWhereHas('session_year', function ($subQuery) use ($search) {
                            $subQuery->where('name', 'LIKE', "%$search%");
                        });
                });
            })->when(request('session_year_id') != null, function ($query) {
                $query->where('session_year_id', request('session_year_id'));
            })->when($medium_id, function ($query) use ($medium_id) {
                $query->whereHas('class', function ($q) use ($medium_id) {
                    $q->where('medium_id', $medium_id);
                });
            })
            ->when(!empty($showDeleted), function ($query) {
                $query->onlyTrashed();
            });

        $total = $sql->count();
        if ($offset >= $total && $total > 0) {
            $lastPage = floor(($total - 1) / $limit) * $limit; // calculate last page offset
            $offset = $lastPage;
        }
        $sql->orderBy($sort, $order)->skip($offset)->take($limit);
        $res = $sql->get();

        $bulkData = [];
        $bulkData['total'] = $total;
        $rows = [];
        $no = 1;

        $classSections = $this->classSection->builder()->get()->toArray();

        foreach ($res as $row) {
            $tempRow = $row->toArray();
            $tempRow['no'] = $no++;
            $classSectionWiseStatus = [];

            foreach ($row->class->section as $section) {
                $sectionId = $section->id;
                $subjectWiseStatus = [];
                $processedSubjects = [];

                $classNameWithSection = $row->class->name . ' - ' . $section->name ?? '' . ' ' . $row->class->medium->name;

                // Get the class section ID for this section
                $class_section_id = $this->findClassSection($classSections, $row->class_id, $sectionId);

                if ($row->has_timetable) {
                    foreach ($row->timetable as $timetable) {
                        $subject = $timetable->subject_with_name;
                        $subjectId = $timetable->class_subject_id;

                        if (isset($processedSubjects[$subjectId])) {
                            continue;
                        }

                        // Filter marks by class section ID instead of section ID directly
                        $marks = collect([]);
                        if ($class_section_id) {
                            $marks = $timetable->exam_marks->where('user.student.class_section_id', $class_section_id);
                        }

                        $marksSubmitted = $marks->isNotEmpty();
                        $status = $marksSubmitted ? 'Submitted' : 'Pending';

                        $subjectWiseStatus[] = [
                            'subject_id' => $subjectId,
                            'subject' => $subject,
                            'status' => $status,
                            'marks_count' => $marksSubmitted ? $marks->count() : 0,
                        ];

                        $processedSubjects[$subjectId] = true;
                    }

                    $classSectionWiseStatus[] = [
                        'class_section_name' => $classNameWithSection,
                        'subject_wise_status' => $subjectWiseStatus,
                    ];
                }
            }
            $tempRow['classSectionWiseStatus'] = $classSectionWiseStatus;
            $tempRow['created_at'] = date($schoolSettings['date_format'], strtotime($row->created_at));
            $tempRow['updated_at'] = date($schoolSettings['date_format'], strtotime($row->updated_at));
            $rows[] = $tempRow;

        }
        $bulkData['rows'] = $rows;
        return response()->json($bulkData);
    }

    public function findClassSection($classSections, $class_id, $sectionId)
    {
        foreach ($classSections as $classSection) {
            if ($classSection['class_id'] == $class_id && $classSection['section_id'] == $sectionId) {
                return $classSection['id'];
            }
        }
    }

    public function getExamByClassId($class_section_id)
    {
        ResponseService::noFeatureThenRedirect('Exam Management');
        try {
            $sessionYear = $this->cache->getDefaultSessionYear();
            $class_id = $this->classSection->builder()->where('id', $class_section_id)->pluck('class_id');


            $exams = $this->exam->builder()->where('class_id', $class_id)->where('session_year_id', $sessionYear->id)->with([
                'timetable' => function ($query) {
                    $query->where('date', '<', date('Y-m-d'))->orWhere(function ($q) {
                        $q->whereDate('date', '=', date('Y-m-d'))->where('end_time', '<=', date('H:i:s'));
                    });
                }
            ])->where('publish', 0)->get();
            // dd($exams);

            ResponseService::successResponse('Data Fetched Successfully', $exams);
        } catch (Throwable $e) {

            ResponseService::logErrorResponse($e);
            ResponseService::errorResponse();
        }
    }
}
