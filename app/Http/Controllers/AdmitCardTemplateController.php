<?php

namespace App\Http\Controllers;

use App\Repositories\AdmitCardTemplate\AdmitCardTemplateInterface;
use App\Repositories\ClassSection\ClassSectionInterface;
use App\Repositories\Exam\ExamInterface;
use App\Repositories\ExamTimetable\ExamTimetableInterface;
use App\Repositories\FormField\FormFieldsInterface;
use App\Repositories\SessionYear\SessionYearInterface;
use App\Repositories\User\UserInterface;
use App\Services\BootstrapTableService;
use App\Services\CachingService;
use App\Services\ResponseService;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Throwable;
use Illuminate\Support\Facades\Auth;

class AdmitCardTemplateController extends Controller
{
    private AdmitCardTemplateInterface $admitCardTemplate;
    private CachingService $cache;
    private UserInterface $user;
    private ClassSectionInterface $classSection;
    private ExamInterface $exam;
    private ExamTimetableInterface $examTimetable;
    private SessionYearInterface $sessionYear;
    private FormFieldsInterface $formFields;

    public function __construct(
        AdmitCardTemplateInterface $admitCardTemplate, 
        CachingService $cache, 
        UserInterface $user, 
        ClassSectionInterface $classSection, 
        ExamInterface $exam,
        ExamTimetableInterface $examTimetable,
        SessionYearInterface $sessionYear, 
        FormFieldsInterface $formFields
    ) {
        $this->admitCardTemplate = $admitCardTemplate;
        $this->cache = $cache;
        $this->user = $user;
        $this->classSection = $classSection;
        $this->exam = $exam;
        $this->examTimetable = $examTimetable;
        $this->sessionYear = $sessionYear;
        $this->formFields = $formFields;
    }

    public function index()
    {
        ResponseService::noFeatureThenRedirect('ID Card - Certificate Generation');
        ResponseService::noAnyPermissionThenRedirect(['certificate-create', 'certificate-list']);

        $formFields = $this->formFields->builder()->whereNot('type', 'file')->get();

        return view('admit-card.template', compact('formFields'));
    }

    public function store(Request $request)
    {
        ResponseService::noFeatureThenSendJson('ID Card - Certificate Generation');
        ResponseService::noPermissionThenSendJson('certificate-create');

        $request->validate([
            'name' => 'required',
            'page_layout' => 'required',
            'height' => 'required',
            'width' => 'required',
            'user_image_shape' => 'required',
            'image_size' => 'required',
            'description' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $page_layout = 'A4 Landscape';
            if ($request->height == 210 && $request->width == 297) {
                $page_layout = 'A4 Landscape';
            } else if ($request->height == 297 && $request->width == 210) {
                $page_layout = 'A4 Portrait';
            } else {
                $page_layout = 'Custom';
            }

            $data = [
                'name' => $request->name,
                'page_layout' => $page_layout,
                'height' => $request->height,
                'width' => $request->width,
                'user_image_shape' => $request->user_image_shape,
                'image_size' => $request->image_size,
                'description' => $request->description,
            ];
            
            if ($request->hasFile('background_image')) {
                $data['background_image'] = $request->background_image;
            }
            
            $this->admitCardTemplate->create($data);
            DB::commit();
            ResponseService::successResponse('Data Stored Successfully');
        } catch (Throwable $e) {
            DB::rollBack();
            ResponseService::logErrorResponse($e, "Admit Card Template Controller -> Store Method");
            ResponseService::errorResponse();
        }
    }

    public function show($id)
    {
        ResponseService::noFeatureThenRedirect('ID Card - Certificate Generation');
        ResponseService::noPermissionThenSendJson('certificate-list');
        $offset = request('offset', 0);
        $limit = request('limit', 10);
        $sort = request('sort', 'id');
        $order = request('order', 'DESC');
        $search = request('search');

        $sql = $this->admitCardTemplate->builder()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('id', 'LIKE', "%$search%")
                        ->orwhere('name', 'LIKE', "%$search%");
                });
            });
        $total = $sql->count();
        if ($offset >= $total && $total > 0) {
            $lastPage = floor(($total - 1) / $limit) * $limit;
            $offset = $lastPage;
        }
        $sql->orderBy($sort, $order)->skip($offset)->take($limit);
        $res = $sql->get();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $no = 1;
        foreach ($res as $row) {
            $operate = BootstrapTableService::button('fa fa-edit', route('admit-card-template.edit', $row->id), ['btn-gradient-primary'], ['title' => trans('edit')]);
            $operate .= BootstrapTableService::button('fa fa-table-layout', route('admit-card-template.design', $row->id), ['btn-gradient-info'], ['title' => trans('layout')]);
            $operate .= BootstrapTableService::deleteButton(route('admit-card-template.destroy', $row->id));
            $tempRow = $row->toArray();
            $tempRow['no'] = $no++;
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        return response()->json($bulkData);
    }

    public function edit($id)
    {
        ResponseService::noFeatureThenRedirect('ID Card - Certificate Generation');
        ResponseService::noPermissionThenRedirect('certificate-edit');
        $admitCardTemplate = $this->admitCardTemplate->findById($id);
        $formFields = $this->formFields->builder()->whereNot('type', 'file')->get();

        return view('admit-card.edit-template', compact('admitCardTemplate', 'formFields'));
    }

    public function update(Request $request, $id)
    {
        ResponseService::noFeatureThenSendJson('ID Card - Certificate Generation');
        ResponseService::noPermissionThenSendJson('certificate-edit');

        $request->validate([
            'name' => 'required',
            'page_layout' => 'required',
            'height' => 'required',
            'width' => 'required',
            'user_image_shape' => 'required',
            'image_size' => 'required',
            'description' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $page_layout = 'A4 Landscape';
            if ($request->height == 210 && $request->width == 297) {
                $page_layout = 'A4 Landscape';
            } else if ($request->height == 297 && $request->width == 210) {
                $page_layout = 'A4 Portrait';
            } else {
                $page_layout = 'Custom';
            }
            
            $data = [
                'name' => $request->name,
                'page_layout' => $page_layout,
                'height' => $request->height,
                'width' => $request->width,
                'user_image_shape' => $request->user_image_shape,
                'image_size' => $request->image_size,
                'description' => $request->description,
            ];

            if ($request->hasFile('background_image')) {
                $data['background_image'] = $request->background_image;
            }

            $this->admitCardTemplate->update($id, $data);
            DB::commit();
            ResponseService::successResponse('Data Updated Successfully');
        } catch (Throwable $e) {
            DB::rollBack();
            ResponseService::logErrorResponse($e, "Admit Card Template Controller -> Update Method");
            ResponseService::errorResponse();
        }
    }

    public function destroy($id)
    {
        ResponseService::noFeatureThenRedirect('ID Card - Certificate Generation');
        ResponseService::noPermissionThenSendJson('certificate-delete');
        try {
            DB::beginTransaction();
            $this->admitCardTemplate->deleteById($id);
            DB::commit();
            ResponseService::successResponse('Data Deleted Successfully');
        } catch (Throwable $e) {
            DB::rollBack();
            ResponseService::logErrorResponse($e, "Admit Card Template Controller -> Destroy Method");
            ResponseService::errorResponse();
        }
    }

    public function design($id)
    {
        ResponseService::noFeatureThenRedirect('ID Card - Certificate Generation');
        ResponseService::noPermissionThenRedirect('certificate-edit');
        try {
            $admitCardTemplate = $this->admitCardTemplate->findById($id);
            $settings = $this->cache->getSchoolSettings();

            $style = json_decode($admitCardTemplate->style, true) ?? [];

            if (!isset($style['description'])) {
                $style['description'] = 'style="position:absolute; left: 145px;top: 255px"';
            }

            if (!isset($style['title'])) {
                $style['title'] = 'style="position:absolute; left: 145px;top: 290px"';
            }

            if (!isset($style['issue_date'])) {
                $style['issue_date'] = 'style="position:absolute; left: 100px;top: 100px"';
            }

            if (!isset($style['signature'])) {
                $style['signature'] = 'style="position:absolute; left: 150px;top: 150px"';
            }

            if (!isset($style['school_name'])) {
                $style['school_name'] = 'style="position:absolute; left: 480px;top: 60px"';
            }

            if (!isset($style['school_address'])) {
                $style['school_address'] = 'style="position:absolute; left: 125px;top: 85px"';
            }

            if (!isset($style['school_mobile'])) {
                $style['school_mobile'] = 'style="position:absolute; left: 125px;top: 130px"';
            }

            if (!isset($style['school_email'])) {
                $style['school_email'] = 'style="position:absolute; left: 125px;top: 175px"';
            }

            if (!isset($style['school_logo'])) {
                $style['school_logo'] = 'style="position:absolute; left: 525px;top: 75px"';
            }

            if (!isset($style['user_image'])) {
                $style['user_image'] = 'style="position:absolute; left: 525px;top: 125px"';
            }

            if (!isset($style['exam_timetable'])) {
                $style['exam_timetable'] = 'style="position:absolute; left: 100px;top: 500px"';
            }

            $height = $admitCardTemplate->height * 3.7795275591;
            $width = $admitCardTemplate->width * 3.7795275591;

            $layout = [
                'height' => $height . 'px',
                'width' => $width . 'px'
            ];

            return view('admit-card.design', compact('admitCardTemplate', 'settings', 'style', 'layout'));
        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e, "Admit Card Template Controller -> Design Method");
            ResponseService::errorResponse();
        }
    }

    public function design_store(Request $request, $id)
    {
        try {
            // Permission check for AJAX - return JSON instead of redirect
            if (!Auth::user()) {
                return response()->json([
                    'success' => false,
                    'status' => 'error',
                    'code' => 401,
                    'message' => 'Unauthorized access'
                ], 401);
            }
            
            DB::beginTransaction();
            
            $fields = '';
            if ($request->school_data) {
                $fields = implode(",", $request->school_data);
            }

            $style = array();
            if ($request->has('style')) {
                foreach ($request->style as $key => $value) {
                    if (!empty($value)) {
                        $style[$key] = $value;
                    }
                }
            }
            
            // Handle signature file upload
            if ($request->hasFile('signature')) {
                try {
                    $schoolSettings = app('App\Repositories\SchoolSetting\SchoolSettingInterface');
                    $schoolSettingData = [
                        [
                            "name" => "signature",
                            "data" => $request->file('signature'),
                            "type" => "file"
                        ]
                    ];
                    
                    $schoolSettings->upsert($schoolSettingData, ["name"], ["data"]);
                    $this->cache->removeSchoolCache(config('constants.CACHE.SCHOOL.SETTINGS'));
                    
                    \Log::info("Signature uploaded successfully");
                } catch (\Exception $fileError) {
                    \Log::error("Signature upload failed: " . $fileError->getMessage());
                    // Don't fail the whole request if signature upload fails
                }
            }

            // Handle principal signature file upload
            if ($request->hasFile('principal_signature')) {
                try {
                    $schoolSettings = app('App\Repositories\SchoolSetting\SchoolSettingInterface');
                    $schoolSettingData = [
                        [
                            "name" => "principal_signature",
                            "data" => $request->file('principal_signature'),
                            "type" => "file"
                        ]
                    ];
                    
                    $schoolSettings->upsert($schoolSettingData, ["name"], ["data"]);
                    $this->cache->removeSchoolCache(config('constants.CACHE.SCHOOL.SETTINGS'));
                    
                    \Log::info("Principal signature uploaded successfully");
                } catch (\Exception $fileError) {
                    \Log::error("Principal signature upload failed: " . $fileError->getMessage());
                    // Don't fail the whole request if principal signature upload fails
                }
            }
            
            $value = [
                'style' => json_encode($style),
                'fields' => $fields
            ];
            $this->admitCardTemplate->update($id, $value);
            
            DB::commit();
            
            \Log::info("Design saved successfully for admit card template: " . $id);
            
            return response()->json([
                'success' => true,
                'status' => 'success',
                'code' => 200,
                'message' => 'Design saved successfully!'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Design Store Error: " . $e->getMessage() . " | Trace: " . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'status' => 'error',
                'code' => 500,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function admitCard()
    {
        ResponseService::noFeatureThenRedirect('ID Card - Certificate Generation');
        ResponseService::noPermissionThenRedirect('certificate-list');
        try {
         $classSections = $this->classSection
    ->builder()
    ->with('class', 'section')
    ->get()
    ->mapWithKeys(function ($cs) {
        $name = $cs->class->name . ' ' . $cs->section->name;   // Two + A
        return [$cs->id => $name];
    });


            $exams = $this->exam->builder()->with('class.medium')->where('publish', 1)->get()->append(['prefix_name']);
            $admitCardTemplates = $this->admitCardTemplate->builder()->whereNotNull('style')->pluck('name', 'id');

            $sessionYears = $this->sessionYear->builder()->pluck('name', 'id');

            return view('admit-card.student-list', compact('classSections', 'exams', 'admitCardTemplates', 'sessionYears'));
        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e, "Admit Card Template Controller -> Admit Card Method");
            ResponseService::errorResponse($e->getMessage());
        }
    }

    public function admitCard_generate(Request $request)
    {
        ResponseService::noFeatureThenRedirect('ID Card - Certificate Generation');
        ResponseService::noPermissionThenRedirect('certificate-list');

        $request->validate([
            'admit_card_template_id' => 'required',
            'user_id' => 'required',
            'exam_id' => 'required'
        ], [
            'admit_card_template_id.required' => 'The admit card template field is required',
            'user_id.required' => 'Please select at least one record.',
            'exam_id.required' => 'Please select an exam.'
        ]);

        try {
            $admitCardTemplate = $this->admitCardTemplate->findById($request->admit_card_template_id);
            $exam = $this->exam->findById($request->exam_id);

            $height = $admitCardTemplate->height * 3.7795275591;
            $width = $admitCardTemplate->width * 3.7795275591;

            $layout = [
                'height' => $height . 'px',
                'width' => $width . 'px'
            ];

            $user_id = explode(",", $request->user_id);

            // Get students with exam timetable data
            $users = $this->user->builder()->with([
                'student' => function ($q) {
                    $q->with('class_section.class.stream', 'class_section.section', 'class_section.medium', 'guardian');
                }
            ])->whereIn('id', $user_id)->with('extra_student_details.form_field')->get();

            // Get exam timetable for the selected exam
            $examTimetables = $this->examTimetable->builder()
                ->where('exam_id', $request->exam_id)
                ->with('class_subject.subject')
                ->orderBy('date')
                ->orderBy('start_time')
                ->get();

            $user_data = array();
            foreach ($users as $key => $user) {
                $user_data[] = [
                    'image' => $user->image,
                    'description' => $this->replacePlaceholders($admitCardTemplate->description, $user, $request->exam_id),
                    'exam_timetable' => $examTimetables
                ];
            }
            $users = $user_data;
            $style = json_decode($admitCardTemplate->style, true) ?? [];
            $settings = $this->cache->getSchoolSettings();

            return view('admit-card.admit-card-pdf', compact('admitCardTemplate', 'layout', 'users', 'style', 'settings', 'exam'));

        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e, "Admit Card Template Controller -> Admit Card Generate Method");
            ResponseService::errorResponse($e->getMessage());
        }
    }

    private function replacePlaceholders($templateContent, $user, $exam_id = null)
    {
        $settings = $this->cache->getSchoolSettings();
        $sessionYear = $this->cache->getDefaultSessionYear();
            $className   = $user->student->class_section->class->name ?? '';
    $sectionName = $user->student->class_section->section->name ?? '';
    $classSection = trim($className . ' ' . $sectionName); 
        // Define the placeholders and their replacements
        $placeholders = [
            '{full_name}' => $user->full_name,
            '{first_name}' => $user->first_name,
            '{last_name}' => $user->last_name,       
  '{class_section}'     => $classSection,
            '{student_mobile}' => $user->mobile,
            '{dob}' => $user->dob,
            '{roll_no}' => $user->student->roll_number ?? '',
            '{admission_no}' => $user->student->admission_no ?? '',
            '{current_address}' => $user->current_address,
            '{permanent_address}' => $user->permanent_address,
            '{gender}' => $user->gender,
            '{admission_date}' => $user->student->admission_date ?? '',
            '{guardian_name}' => $user->student->guardian->full_name ?? '',
            '{guardian_mobile}' => $user->student->guardian->mobile ?? '',
            '{guardian_email}' => $user->student->guardian->email ?? '',
            '{session_year}' => $sessionYear->name,
            ...$this->extraFormFields($user)
        ];

        $exam_data = array();
        if ($exam_id) {
            $exam = $this->exam->findById($exam_id);
            $exam_data = [
                '{exam}' => $exam->name ?? '',
                '{exam_description}' => $exam->description ?? '',
            ];
        }

        $placeholders = array_merge($placeholders, $exam_data);

        // Replace the placeholders in the template content
        foreach ($placeholders as $placeholder => $replacement) {
            $templateContent = str_replace($placeholder, $replacement, $templateContent);
        }

        return $templateContent;
    }

    public function extraFormFields($user)
    {
        $extraStudentDetails = array();
        foreach ($user->extra_student_details as $key => $formField) {
            if (in_array($formField->form_field->type, ['radio', 'text', 'number', 'textarea'])) {
                $extraStudentDetails['{' . $formField->form_field->name . '}'] = $formField->data;
            }
            if ($formField->form_field->type == 'checkbox') {
                $data = json_decode($formField->data);
                if ($data) {
                    $extraStudentDetails['{' . $formField->form_field->name . '}'] = implode(", ", $data);
                }
            }
            if ($formField->form_field->type == 'dropdown') {
                if ($formField->form_field && isset($formField->form_field->default_values[$formField->data])) {
                    $extraStudentDetails['{' . $formField->form_field->name . '}'] = $formField->form_field->default_values[$formField->data];
                }
            }
        }
        return $extraStudentDetails;
    }
}
