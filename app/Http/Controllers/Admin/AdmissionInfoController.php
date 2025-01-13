<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdmissionInfo;
use App\Models\Course;
use App\Models\Institute;
use App\Models\User;
use App\Traits\Notifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdmissionInfoController extends Controller
{
    use Notifications;
    public function index(Request $request)
    {
        if(!is_admin() && !is_center()) {
            unauthorizedRedirect();
        }
        $institutes = Institute::where('status','Active')->get();
        $centers = User::where('type','User')->where('status','Active')->get();
        $courses = Course::where('status','Active')->distinct('title')->pluck('title')->toArray();
        return view('admin.admissions.list', compact('institutes','courses','centers'));
    }
    public function coursevisits(Request $request)
    {
        if(!is_admin()) {
            unauthorizedRedirect();
        }
        $institutes = Institute::where('status','Active')->get();
        $courses = Course::where('status','Active')->pluck('title')->toArray();
        return view('admin.visits.list', compact('institutes','courses'));
    }

    public function filter(Request $request)
    {
        // dd($request->all());
        // Log::info($request);
        $LISTING_PER_PAGE_LIMIT = getConfig('constants', 'LISTING_PER_PAGE_LIMIT');
        $data["draw"] = $request->input("draw");
        $data["recordsTotal"] = 0;
        $data["recordsFiltered"] = 0;
        $allAdmissions = array();
        $data["data"] = $allAdmissions;
        $getUserInfo = getRequestAttributes('currentUser');
        
        $ordableColumn = array("0"=> "a.center_id", "1"=>"a.university_id","2"=>"a.course_id","3"=>"a.passout","4"=>"a.total","5"=>"a.student_name","6"=>"a.father_name ","7"=>"a.mother_name","8"=>"a.mobile","9"=>"a.email","10"=>"a.address","11"=>"a.fees1_amount","12"=>"a.fees2_amount","13"=>"a.fees3_amount","14"=>"a.fees4_amount","15"=>"a.fees5_amount","16"=>"a.id");
        $start = $request->input("start");
        $length = $request->input("length");
        $order = $request->input("order");
        $columns = $request->input("columns");
        $instituteId = $request->input("institute_id");
        $centerId = $request->input("center_id");
        $course_name = $request->input("course_name");
        $orderedColumn = (isset($order[0]) && isset($order[0]["column"])) ? $order[0]["column"] : '0';
        $orderedPref = (isset($order[0]) && isset($order[0]["dir"])) ? $order[0]["dir"] : 'asc';

        $search = $request->input("search");
        $searchedText = (isset($search["value"]) && $search["value"]!='') ? $search["value"] : '';

        $defaultPerPage = $request->input("length", $LISTING_PER_PAGE_LIMIT);
        $pageNumber = (($start/$defaultPerPage) + '1');

        $tblAdmissions = resolve(AdmissionInfo::Class)->tablename();

        
        $admissionsSQL = DB::table($tblAdmissions.' as a')->select('a.*');
        $admissionsSQL->join('institutes as i', 'a.university_id', '=', 'i.id');
        $admissionsSQL->join('courses as c','a.course_id','=','c.id');
        $admissionsSQL->where('i.status','Active');
        $admissionsSQL->where('a.deleted_at',NULL);
        $searchableColumns = ["a.id",
            "a.center_id", "a.university_id", "a.course_id", "a.total", "a.passout",
            "a.fees1_amount", "a.fees2_amount", "a.fees3_amount", "a.fees4_amount", "a.fees5_amount",
            "a.fees1_date", "a.fees2_date", "a.fees3_date", "a.fees4_date", "a.fees5_date",
            "a.fees1_trans_id", "a.fees2_trans_id", "a.fees3_trans_id", "a.fees4_trans_id", "a.fees5_trans_id",
            "a.fees1_status", "a.fees2_status", "a.fees3_status", "a.fees4_status", "a.fees5_status",
            "a.student_name", "a.father_name", "a.mother_name", "a.address", "a.mobile", "a.email", "a.status",
        ];
        if($searchedText!='')
        {
            // dd($searchedText);
            $admissionsSQL->where(function($searchedItems) use($searchedText,$searchableColumns) {
                foreach($searchableColumns as $col)
                {
                    $searchedItems->orWhere($col,"like","%".$searchedText."%");
                }

            });
        }
        for($col=0;$col<count($ordableColumn); $col++)
        {
            if(isset($columns[$col]) && isset($columns[$col]["search"]) && isset($columns[$col]["search"]["value"]) && $columns[$col]["search"]["value"]!='')
            {
                $admissionsSQL->where($ordableColumn[$col], "like", "%".$columns[$col]["search"]["value"]."%");
            }
        }
        if(!is_admin())
        {
            // dd($getUserInfo->id);
            $admissionsSQL->where('a.center_id',decryptString($getUserInfo->id));
        }
        if ($course_name) {
            // $admissionsSQL->where("a.title", "like", "%".$course_name."%");
            $admissionsSQL->where("c.title",  $course_name);
        }
        if($instituteId) {
            $admissionsSQL->where("a.university_id", $instituteId);
        }
        if($centerId) {
            $admissionsSQL->where("a.center_id", $centerId);
        }

        $total = $admissionsSQL->orderBy('a.id', $orderedPref)->count();
        $admissions = $admissionsSQL->orderBy('a.id', $orderedPref)->paginate($defaultPerPage, ['*'], 'page', $pageNumber);

        // dd($admissions);
        $data["draw"] = $request->input("draw");
        $data["recordsTotal"] = $total;
        $data["recordsFiltered"] = $total;

        $currentUserId = decryptString(getRequestAttributes('currentUser')->id);

        if($admissions)
        {
            // dd($admissions);
            foreach($admissions as $admission)
            {
                $allAdmissions[] = array(
                    'id'=>$admission->id,
                    "hash"=> encryptString($admission->id),
                    "center"=> User::find($admission->center_id)->first_name,
                    "university_name"=> Institute::find($admission->university_id)->university_name,
                    "course"=> Course::find($admission->course_id)->title.'-'.Course::find($admission->course_id)->subject,
                    "total"=> $admission->total,
                    "passout"=> $admission->passout,
                    "student_name"=> $admission->student_name,
                    "father_name"=> $admission->father_name,
                    "mother_name"=> $admission->mother_name,
                    "address"=> $admission->address,
                    "mobile"=> $admission->mobile,
                    "email"=> $admission->email,
                    "fees1_amount"=> $admission->fees1_amount,
                    "fees2_amount"=> $admission->fees2_amount,
                    "fees3_amount"=> $admission->fees3_amount,
                    "fees4_amount"=> $admission->fees4_amount,
                    "fees5_amount"=> $admission->fees5_amount,
                    "fees1_status"=> $admission->fees1_status,
                    "fees2_status"=> $admission->fees2_status,
                    "fees3_status"=> $admission->fees3_status,
                    "fees4_status"=> $admission->fees4_status,
                    "fees5_status"=> $admission->fees5_status,
                    "fees1_trans_id"=> $admission->fees1_trans_id,
                    "fees2_trans_id"=> $admission->fees2_trans_id,
                    "fees3_trans_id"=> $admission->fees3_trans_id,
                    "fees4_trans_id"=> $admission->fees4_trans_id,
                    "fees5_trans_id"=> $admission->fees5_trans_id,
                    "fees1_date"=> $admission->fees1_date,
                    "fees2_date"=> $admission->fees2_date,
                    "fees3_date"=> $admission->fees3_date,
                    "fees4_date"=> $admission->fees4_date,
                    "fees5_date"=> $admission->fees5_date,
                    "status"=> $admission->status,
                );
            }
        }

        $data["data"] = $allAdmissions;

        return response()->json($data);
    }

    public function create()
    {
        if(!hasPermission('add-course') && !is_center()) {
            unauthorizedRedirect();
        }
        $authUser = getRequestAttributes('currentUser');
        $mode = 'Add';
        $institutes = Institute::where('status','Active')->get();

        return view('admin.admissions.form', compact('mode','institutes'));
    }
    public function get_university_courses($id)
    {
        $courses = Course::where('institute_id',$id)->where('status','Active')->get();
        return response()->json([$courses]);
    }


    public function store(Request $request)
    {
        if(!hasPermission('add-course') && !is_center()) {
            unauthorizedRedirect();
        }
        // dd($request->all());
        $authUser = getRequestAttributes('currentUser');
        $userId = decryptString($authUser->id);
        $validateFields = [
            'university_id' => 'required|exists:institutes,id',
            'course_id' => 'required|exists:courses,id',
            'passout' => 'required|max:255',
            'total' => 'required|numeric',
            'student_name' => 'required|max:255',
            'father_name' => 'required|max:255',
            'mother_name' => 'required|max:255',
            'address' => 'required|max:500',
            'mobile' => 'required|digits:10',
            'email' => 'required|email',
            'fees1_amount'=>'nullable|numeric',
            'fees2_amount'=>'nullable|numeric',
            'fees3_amount'=>'nullable|numeric',
            'fees4_amount'=>'nullable|numeric',
            'fees5_amount'=>'nullable|numeric',
            'fees1_trans_id' => 'nullable|max:255',
            'fees2_trans_id' => 'nullable|max:255',
            'fees3_trans_id' => 'nullable|max:255',
            'fees4_trans_id' => 'nullable|max:255',
            'fees5_trans_id' => 'nullable|max:255',
            'fees1_date' => 'nullable|date',
            'fees2_date' => 'nullable|date',
            'fees3_date' => 'nullable|date',
            'fees4_date' => 'nullable|date',
            'fees5_date' => 'nullable|date',
        ];

        $validateFieldsMessages = [
            'title.required' => 'Title is required.',
            'title.max' => 'Title should be maximum of 255 characters.',
            'subject.required' => 'Subject is required.',
            'subject.max' => 'Subject should be maximum of 255 characters.',
            'institute_id.required' => 'Institute is required.',
            'type.max' => 'Type should be maximum of 255 characters.',
            'duration.max' => 'Duration should be maximum of 255 characters.',
            'visit.max' => 'Visit should be maximum of 255 characters.',
            'passout_1.max' => 'Passout 1 should be maximum of 255 characters.',
            'passout_2.max' => 'Passout 2 should be maximum of 255 characters.',
            'passout_3.max' => 'Passout 3 should be maximum of 255 characters.',
            'fees_1.max' => 'Fees 1 should be maximum of 255 characters.',
            'fees_2.max' => 'Fees 2 should be maximum of 255 characters.',
            'fees_3.max' => 'Fees 3 should be maximum of 255 characters.',
            
        ];

        $validator = $request->validate($validateFields, $validateFieldsMessages);
        // dd('hi');
        $admission = new AdmissionInfo();
        
        // $admission->center_id = $userId;
        $admission->university_id = $request->university_id;
        $admission->course_id = $request->course_id;
        $admission->passout = $request->passout;
        $admission->total = $request->total;
        $admission->student_name = $request->student_name;
        $admission->father_name = $request->father_name;
        $admission->mother_name = $request->mother_name;
        $admission->address = $request->address;
        $admission->mobile = $request->mobile;
        $admission->email = $request->email;
        $fees_amount = [
            ['amount' => 'fees1_amount', 'trans_id' => 'fees1_trans_id', 'status' => 'fees1_status','date'=>'fees1_date'],
            ['amount' => 'fees2_amount', 'trans_id' => 'fees2_trans_id', 'status' => 'fees2_status','date'=>'fees2_date'],
            ['amount' => 'fees3_amount', 'trans_id' => 'fees3_trans_id', 'status' => 'fees3_status','date'=>'fees3_date'],
            ['amount' => 'fees4_amount', 'trans_id' => 'fees4_trans_id', 'status' => 'fees4_status','date'=>'fees4_date'],
            ['amount' => 'fees5_amount', 'trans_id' => 'fees5_trans_id', 'status' => 'fees5_status','date'=>'fees5_date'],
        ];
        
        foreach ($fees_amount as $fee) {
            $admission->{$fee['amount']} = $request->{$fee['amount']}; // Assigning fee amounts
            $admission->{$fee['trans_id']} = $request->{$fee['trans_id']}; // Assigning transaction IDs
            $admission->{$fee['status']} = 'Pending'; // Assigning statuses
            $admission->{$fee['date']} = $request->{$fee['date']}; // Assigning statuses
        }
        $admission->status = "Active";
        $admission->created_by = $userId;
        if( $admission->save() )
        {
            session()->flash('success', $this->notifyuser("ADMISSION_ADDED"));
            return redirect()->route('admin.admissions');
        }
        else
        {
            return back();
        }

    }


    public function show($id)
    {
        if(!hasPermission('view-courses') && !is_center()) {
            unauthorizedRedirect();
        }
        $admissionId = decryptString($id);
        $admission = AdmissionInfo::find($admissionId);
        // dd($admission,$admissionId);
        $institute = Institute::find($admission->university_id);
        $center = User::find($admission->center_id);
        $course = Course::find($admission->course_id);
        $fees = [
            ['amount' => 'fees1_amount', 'trans_id' => 'fees1_trans_id', 'status' => 'fees1_status','date'=>'fees1_date'],
            ['amount' => 'fees2_amount', 'trans_id' => 'fees2_trans_id', 'status' => 'fees2_status','date'=>'fees2_date'],
            ['amount' => 'fees3_amount', 'trans_id' => 'fees3_trans_id', 'status' => 'fees3_status','date'=>'fees3_date'],
            ['amount' => 'fees4_amount', 'trans_id' => 'fees4_trans_id', 'status' => 'fees4_status','date'=>'fees4_date'],
            ['amount' => 'fees5_amount', 'trans_id' => 'fees5_trans_id', 'status' => 'fees5_status','date'=>'fees5_date'],
        ];
        return view("admin.admissions.detail", compact(["admission","institute","fees","center","course"]));
    }


    public function edit($id)
    {
        if(!hasPermission('edit-course') && !is_center()) {
            unauthorizedRedirect();
        }
        $authUser = getRequestAttributes('currentUser');
        $mode = "Edit";
        $admissionId = decryptString($id);
        $admission = AdmissionInfo::find($admissionId);
        // dd($admission);
        $institutes = Institute::where('status','Active')->get();   
        $bg_colors = [
            "Pending"=>"lightblue",
            "Paid"=>"yellow",
            "Approved"=>"lightgreen"
        ];
        return view("admin.admissions.form", compact(["admission", "mode","institutes","bg_colors"]));
    }

    public function update(Request $request, $id)
    {
        if(!hasPermission('add-course') && !is_center() || $id==null) {
            unauthorizedRedirect();
        }
        // dd($request->all());
        $authUser = getRequestAttributes('currentUser');
        $userId = decryptString($authUser->id);
        $validateFieldsForCenter = [
            'fees1_amount'=>'nullable|numeric',
            'fees2_amount'=>'nullable|numeric',
            'fees3_amount'=>'nullable|numeric',
            'fees4_amount'=>'nullable|numeric',
            'fees5_amount'=>'nullable|numeric',
            'fees1_trans_id' => 'nullable|max:255',
            'fees2_trans_id' => 'nullable|max:255',
            'fees3_trans_id' => 'nullable|max:255',
            'fees4_trans_id' => 'nullable|max:255',
            'fees5_trans_id' => 'nullable|max:255',
            'fees1_date' => 'nullable|date',
            'fees2_date' => 'nullable|date',
            'fees3_date' => 'nullable|date',
            'fees4_date' => 'nullable|date',
            'fees5_date' => 'nullable|date',
        ];
        $validateFieldsForAdmin = [
            'university_id' => 'required|exists:institutes,id',
            'course_id' => 'required|exists:courses,id',
            'passout' => 'required|max:255',
            'total' => 'required|numeric',
            'student_name' => 'required|max:255',
            'father_name' => 'required|max:255',
            'mother_name' => 'required|max:255',
            'address' => 'required|max:500',
            'mobile' => 'required|digits:10',
            'email' => 'required|email',
            'fees1_amount'=>'nullable|numeric',
            'fees2_amount'=>'nullable|numeric',
            'fees3_amount'=>'nullable|numeric',
            'fees4_amount'=>'nullable|numeric',
            'fees5_amount'=>'nullable|numeric',
            'fees1_trans_id' => 'nullable|max:255',
            'fees2_trans_id' => 'nullable|max:255',
            'fees3_trans_id' => 'nullable|max:255',
            'fees4_trans_id' => 'nullable|max:255',
            'fees5_trans_id' => 'nullable|max:255',
            'fees1_date' => 'nullable|date',
            'fees2_date' => 'nullable|date',
            'fees3_date' => 'nullable|date',
            'fees4_date' => 'nullable|date',
            'fees5_date' => 'nullable|date',
        ];

        $validateFieldsMessages = [
            'title.required' => 'Title is required.',
            'title.max' => 'Title should be maximum of 255 characters.',
            'subject.required' => 'Subject is required.',
            'subject.max' => 'Subject should be maximum of 255 characters.',
            'institute_id.required' => 'Institute is required.',
            'type.max' => 'Type should be maximum of 255 characters.',
            'duration.max' => 'Duration should be maximum of 255 characters.',
            'visit.max' => 'Visit should be maximum of 255 characters.',
            'passout_1.max' => 'Passout 1 should be maximum of 255 characters.',
            'passout_2.max' => 'Passout 2 should be maximum of 255 characters.',
            'passout_3.max' => 'Passout 3 should be maximum of 255 characters.',
            'fees_1.max' => 'Fees 1 should be maximum of 255 characters.',
            'fees_2.max' => 'Fees 2 should be maximum of 255 characters.',
            'fees_3.max' => 'Fees 3 should be maximum of 255 characters.',
            
        ];
        if(is_admin())
        {
            $validator = $request->validate($validateFieldsForAdmin, $validateFieldsMessages);
        }
        else {
            $validator = $request->validate($validateFieldsForCenter, $validateFieldsMessages);
        }
        
        $admission = AdmissionInfo::find(decryptString($id));
        // dd($admission);
        
        if(is_admin())
        {
            $admission->center_id = $userId;
            $admission->university_id = $request->university_id;
            $admission->course_id = $request->course_id;
            $admission->passout = $request->passout;
            $admission->total = $request->total;
            $admission->student_name = $request->student_name;
            $admission->father_name = $request->father_name;
            $admission->mother_name = $request->mother_name;
            $admission->address = $request->address;
            $admission->mobile = $request->mobile;
            $admission->email = $request->email;
            $admission->status = $request->status;
        }
        $fees = [
            ['amount' => 'fees1_amount', 'trans_id' => 'fees1_trans_id', 'status' => 'fees1_status','date'=>'fees1_date'],
            ['amount' => 'fees2_amount', 'trans_id' => 'fees2_trans_id', 'status' => 'fees2_status','date'=>'fees2_date'],
            ['amount' => 'fees3_amount', 'trans_id' => 'fees3_trans_id', 'status' => 'fees3_status','date'=>'fees3_date'],
            ['amount' => 'fees4_amount', 'trans_id' => 'fees4_trans_id', 'status' => 'fees4_status','date'=>'fees4_date'],
            ['amount' => 'fees5_amount', 'trans_id' => 'fees5_trans_id', 'status' => 'fees5_status','date'=>'fees5_date'],
        ];
        
        foreach ($fees as $fee) {
            // dd($admission->{'fees1_status'});
            if(is_admin() || (is_center() && $admission->{$fee['status']}=='Pending') )
            {
                $admission->{$fee['amount']} = $request->{$fee['amount']}; // Assigning fee amounts
                $admission->{$fee['trans_id']} = $request->{$fee['trans_id']}; // Assigning transaction IDs
                $admission->{$fee['status']} = $request->{$fee['status']}; // Assigning statuses
                $admission->{$fee['date']} = $request->{$fee['date']}; // Assigning statuses
            }
        }
        $admission->updated_by = $userId;
        if( $admission->save() )
        {
            session()->flash('success', $this->notifyuser("ADMISSION_UPDATED"));
            return redirect()->route('admin.admissions');
        }
        else
        {
            return back();
        }


    }

    public function destroy($id)
    {
        if(!hasPermission('delete-course') && !is_admin()) {
            unauthorizedRedirect();
        }
        $authUser = getRequestAttributes('currentUser');
        $userId = decryptString($authUser->id);
        $courseId = decryptString($id);
        $course = AdmissionInfo::find($courseId);
        $course->status = 'Deleted';
        $course->deleted_by = $userId;
        $course->save();

        $course->delete();
        session()->flash('success', $this->notifyuser("COURSE_DELETED"));

        return redirect()->route('admin.courses');
    }

    // public function download_csv(Request $request)
    // {
    //     $instituteId = $request->input("institute_id");
    //     $course_name = $request->input("course_name");

    //     $courses = Course::query()
    //     ->join('institutes as i', 'courses.institute_id', '=', 'i.id') // Join with institutes
    //     ->where('i.status', 'Active');
    //     if($course_name)
    //     {
    //         $courses->where('courses.title',$course_name);
    //     }
    //     if($instituteId)
    //     {
    //         $courses->where('courses.institute_id',$instituteId);
    //     }
    //     $courses->where('courses.status','Active');
    //     $all_courses = $courses->get();

    //     $allCourses=[];



    //     if($all_courses)
    //     {
    //         foreach($all_courses as $course)
    //         {
    //             // dd('hii');
    //             $allCourses[] = array(
    //                 "hash"=> encryptString($course->id),
    //                 "title"=> $course->title,
    //                 "subject"=> $course->subject,
    //                 "university_name"=> Institute::find($course->institute_id)->university_name,
    //                 "approved_by"=> Institute::find($course->institute_id)->approved_by,
    //                 "verification"=> Institute::find($course->institute_id)->verification,
    //                 "website"=> Institute::find($course->institute_id)->university_website,
    //                 "type"=> $course->type,
    //                 "duration"=> $course->duration,
    //                 "visit"=> $course->visit,
    //                 "eligibility"=> $course->eligibility,
    //                 "passout-1"=> $course->passout_1,
    //                 "passout-2"=> $course->passout_2,
    //                 "passout-3"=> $course->passout_3,
    //                 "passout-4"=> $course->passout_4,
    //                 "fees-1"=> $course->fees_1,
    //                 "fees-2"=> $course->fees_2,
    //                 "fees-3"=> $course->fees_3,
    //                 "fees-4"=> $course->fees_4,
    //                 "status"=> $course->status,
    //             );
    //         }
    //     }
    //     // dd($allCourses);

    //     $fileName =  'courses.xls';
    
    //     // Define the Excel headers
    //     $headers = [
    //         'Content-Type' => 'application/vnd.ms-excel',
    //         'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
    //         'Cache-Control' => 'max-age=0',
    //     ];
    
    //     // Create the Excel file
    //     $spreadsheet = new Spreadsheet();
    //     $sheet = $spreadsheet->getActiveSheet();
    
    //     // Add a header row for quizzes
    //     $sheet->setCellValue('A1', 'S No.');
    //     $sheet->setCellValue('B1', 'University');
    //     $sheet->setCellValue('C1', 'Approved By');
    //     $sheet->setCellValue('D1', 'Verification');
    //     $sheet->setCellValue('E1', 'Course');
    //     $sheet->setCellValue('F1', 'Subject');
    //     $sheet->setCellValue('G1','Duration');
    //     $sheet->setCellValue('H1','Eligibity');
    //     $sheet->setCellValue('I1','Visit');
    //     $sheet->setCellValue('J1','Passout/Fees');
    //     $sheet->setCellValue('K1','Passout/Fees');
    //     $sheet->setCellValue('L1','Passout/Fees');
    //     $sheet->setCellValue('M1','Passout/Fees');
    
    //     $row = 2;
    //     // dd($allCourses);
    
    //     // Loop through each quiz
    //     foreach ($allCourses as $course) {
    //         $sheet->setCellValue('A' . $row, $row );
    //         $sheet->setCellValue('B' . $row, $course['university_name']);
    //         $sheet->setCellValue('C' . $row, $course['approved_by']);
    //         $sheet->setCellValue('D' . $row, $course['verification']);
    //         $sheet->setCellValue('E' . $row, $course['title']);
    //         $sheet->setCellValue('F' . $row, $course['subject']);
    //         $sheet->setCellValue('G' . $row, $course['duration']);
    //         $sheet->setCellValue('H' . $row, $course['eligibility']);
    //         $sheet->setCellValue('I' . $row, $course['visit']);
    //         $sheet->setCellValue('J' . $row, $course['passout-1'].'/'.$course['fees-1']);
    //         $sheet->setCellValue('K' . $row, $course['passout-2'].'/'.$course['fees-2']);
    //         $sheet->setCellValue('L' . $row, $course['passout-3'].'/'.$course['fees-3']);
    //         $sheet->setCellValue('M' . $row, $course['passout-4'].'/'.$course['fees-4']);


    //         $row++;
    //     }
    
    //     // Stream the Excel file as a download
    //     $writer = new Xls($spreadsheet);
    //     $callback = function() use ($writer) {
    //         $writer->save('php://output');
    //     };

    //     return response()->stream($callback, 200, $headers);
    // }
}