<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassSection;
use App\Models\Exam;
use App\Models\User;
use App\Models\Student;
use App\Models\SessionYear;
use App\Models\ExamSeatNumber;
use App\Services\ResponseService;
use App\Services\CachingService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Throwable;
use PDF;

class SeatNumberController extends Controller
{
    private $cache;

    public function __construct(CachingService $cache)
    {
        $this->cache = $cache;
    }

    public function index()
    {
        if (!Auth::user()->can('exam-list')) { // Assuming exam-list permission is enough or check specific
            // ResponseService::noPermissionThenRedirect('exam-list'); // fallback
        }

        $classSections = ClassSection::with('class.stream', 'section', 'medium')->get();
        $exams = Exam::where('publish', 1)->get();

        return view('seat-number.index', compact('classSections', 'exams'));
    }

    public function generate(Request $request)
    {
        // Used for checking/listing students in the table (AJAX)
        $offset = request('offset', 0);
        $limit = request('limit', 10);
        $sort = request('sort', 'id');
        $order = request('order', 'DESC');
        $search = request('search');
        $class_section_id = request('class_section_id');
        $exam_id = request('exam_id');

        $sql = User::with([
            'student' => function ($q) {
                $q->with(['class_section.class.stream', 'class_section.section', 'class_section.medium', 'guardian']);
            }
        ])
            ->whereHas('student', function ($q) use ($class_section_id) {
                if ($class_section_id) {
                    $q->where('class_section_id', $class_section_id);
                }
            });

        if ($search) {
            $sql->where(function ($q) use ($search) {
                $q->where('first_name', 'LIKE', "%$search%")
                    ->orWhere('last_name', 'LIKE', "%$search%");
            });
        }

        $total = $sql->count();
        $res = $sql->skip($offset)->take($limit)->get();

        $rows = [];
        $no = $offset + 1;
        foreach ($res as $row) {
            $seatNumber = ' ';
            if ($exam_id && $row->student) {
                $examSeat = ExamSeatNumber::where('exam_id', $exam_id)
                    ->where('student_id', $row->student->id)
                    ->first();
                if ($examSeat) {
                    $seatNumber = $examSeat->seat_number;
                }
            }

            $rows[] = [
                'id' => $row->id,
                'no' => $no++,
                'user' => $row,
                'class_section' => $row->student->class_section,
                'class_section_name' => $row->student->class_section->full_name ?? '',
                'seat_number' => $seatNumber,
                'admission_no' => $row->student->admission_no,
                'roll_number' => $row->student->roll_number,
                'admission_date' => $row->student->admission_date,
                'guardian' => $row->student->guardian
            ];
        }

        return response()->json(['total' => $total, 'rows' => $rows]);
    }

    public function design()
    {
        $settings = $this->cache->getSchoolSettings();

        // Load template from file or create default
        if (Storage::exists('seat_number_template.json')) {
            $template = json_decode(Storage::get('seat_number_template.json'));
            $template->image_size = 50;
        } else {
            $template = (object) [
                'style' => json_encode([]),
                'fields' => [],
                'height' => 297, // A4 Height
                'width' => 210,  // A4 Width
                'image_size' => 50,
                'user_image_shape' => 'Square',
                'background_image' => null
            ];
        }

        // Default Styles
        $style = json_decode($template->style ?? '{}', true) ?? [];
        $defaultStyles = [
            'school_name' => 'style="position:absolute; left: 150px; top: 50px; font-size: 14px;"',
            'exam_name' => 'style="position:absolute; left: 150px; top: 100px; font-size: 12px;"',
            'student_name' => 'style="position:absolute; left: 150px; top: 150px; font-size: 12px;"',
            'seat_number' => 'style="position:absolute; left: 150px; top: 200px; font-size: 14px; font-weight: bold;"',
            'roll_number' => 'style="position:absolute; left: 150px; top: 250px; font-size: 12px;"',
            'class_section' => 'style="position:absolute; left: 150px; top: 300px; font-size: 12px;"',
            'user_image' => 'style="position:absolute; left: 50px; top: 50px;"',
            'school_address' => 'style="position:absolute; left: 150px; top: 350px; font-size: 12px;"',
            'issue_date' => 'style="position:absolute; left: 150px; top: 400px; font-size: 12px;"',
            'session_year' => 'style="position:absolute; left: 150px; top: 450px; font-size: 12px;"',
            'school_logo' => 'style="position:absolute; left: 350px; top: 50px;"',
        ];

        foreach ($defaultStyles as $key => $val) {
            if (!isset($style[$key])) {
                $style[$key] = $val;
            }
        }

        $layout = [
            'height' => ($template->height * 3.7795275591) . 'px',
            'width' => ($template->width * 3.7795275591) . 'px'
        ];

        return view('seat-number.design', compact('template', 'settings', 'style', 'layout'));
    }

    public function saveTemplate(Request $request)
    {
        try {

            $data = [
                'fields' => $request->school_data ?? [],
                'style' => json_encode($request->style),
                'height' => 297,
                'width' => 210,
                'image_size' => 50,
                'user_image_shape' => 'Square'
            ];

            Storage::put('seat_number_template.json', json_encode($data));

            return response()->json(['error' => false, 'message' => 'Template Saved Successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    public function print(Request $request)
    {
        $request->validate([
            'exam_id' => 'required',
            'user_id' => 'required', // Comma separated IDs
            'sort_by' => 'required'
        ]);

        // Fetch Template
        if (Storage::exists('seat_number_template.json')) {
            $template = json_decode(Storage::get('seat_number_template.json'));
            $template->image_size = 50;
        } else {
            return redirect()->back()->withErrors(['Template not found. Please design template first.']);
        }

        $userIds = explode(',', $request->user_id);
        $users = User::with([
            'student' => function ($q) {
                $q->with(['class_section.class.stream', 'class_section.section', 'class_section.medium', 'guardian']);
            }
        ])
            ->whereIn('id', $userIds)
            ->get();

        // Sorting
        $sortBy = $request->sort_by;
        if ($sortBy == 'gender') {
            $users = $users->sortBy('gender');
        } elseif ($sortBy == 'roll_number') {
            $users = $users->sortBy('student.roll_number');
        } elseif ($sortBy == 'admission_no') {
            $users = $users->sortBy('student.admission_no');
        } elseif ($sortBy == 'random') {
            $users = $users->shuffle();
        }

        $users = $users->values(); // Reset keys

        // Save/Retrieve Seat Numbers
        $exam = Exam::find($request->exam_id);
        $settings = $this->cache->getSchoolSettings();
        $sessionYear = $this->cache->getDefaultSessionYear();
        $style = json_decode($template->style, true);

        // Get current max seat number for this exam to continue sequence if needed
        // Use CAST to ensure numerical sorting for max value
        $maxSeat = ExamSeatNumber::where('exam_id', $exam->id)
            ->selectRaw('MAX(CAST(seat_number AS UNSIGNED)) as max_seat')
            ->value('max_seat');
            
        $nextSeat = $maxSeat ? ($maxSeat + 1) : 1;

        $studentsData = [];
        
        DB::beginTransaction();
        try {
            foreach ($users as $user) {
                if (!$user->student) continue;

                // Check if already generated
                $existing = ExamSeatNumber::where('exam_id', $exam->id)
                    ->where('student_id', $user->student->id)
                    ->first();

                if ($existing) {
                    $seatNumber = $existing->seat_number;
                    $issueDate = $existing->created_at;
                } else {
                    $seatNumber = str_pad($nextSeat++, 4, '0', STR_PAD_LEFT);
                    $examSeat = ExamSeatNumber::create([
                        'exam_id' => $exam->id,
                        'class_section_id' => $user->student->class_section_id,
                        'student_id' => $user->student->id,
                        'seat_number' => $seatNumber,
                        'roll_number' => $user->student->roll_number
                    ]);
                    $issueDate = $examSeat->created_at;
                }

                $studentsData[] = [
                    'user' => $user,
                    'seat_number' => $seatNumber,
                    'exam' => $exam,
                    'issue_date' => $issueDate
                ];
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['Error generating seat numbers: ' . $e->getMessage()]);
        }

        $layout = [
            'height' => ($template->height * 3.7795275591) . 'px',
            'width' => ($template->width * 3.7795275591) . 'px'
        ];

        return view('seat-number.print', compact('studentsData', 'template', 'style', 'settings', 'layout', 'sessionYear'));
    }
}
