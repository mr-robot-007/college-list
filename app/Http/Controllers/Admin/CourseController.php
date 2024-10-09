<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Libraries\Auth;
use App\Models\Course;
use App\Models\CourseVisitCount;
use App\Models\Institute;
use App\Models\User;
use App\Traits\Notifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    use Notifications;
    public function index(Request $request)
    {
        if(!is_admin()) {
            unauthorizedRedirect();
        }
        $institutes = Institute::where('status','Active')->get();
        $courses = Course::where('status','Active')->pluck('title')->toArray();
        return view('admin.courses.list', compact('institutes','courses'));
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
        // Log::info($request);
        $LISTING_PER_PAGE_LIMIT = getConfig('constants', 'LISTING_PER_PAGE_LIMIT');
        $data["draw"] = $request->input("draw");
        $data["recordsTotal"] = 0;
        $data["recordsFiltered"] = 0;
        $allCourses = array();
        $data["data"] = $allCourses;
        $getUserInfo = getRequestAttributes('currentUser');
        
        $ordableColumn = array("0"=> "c.title", "1"=>"c.duration","2"=>"c.status","3"=>"c.type","4"=>"c.visit","5"=>"c.passout_1","6"=>"c.passout_2","7"=>"c.passout_3","8"=>"c.passout_4","9"=>"c.passout_5","10"=>"c.passout_6","11"=>"c.passout_7","12"=>"c.passout_8","13"=>"c.passout_9","14"=>"c.passout_10","15"=>"c.fees_1","16"=>"c.fees_2","17"=>"c.fees_3","18"=>"c.fees_4","19"=>"c.fees_5","20"=>"c.fees_6","21"=>"c.fees_7","22"=>"c.fees_8","23"=>"c.fees_9","24"=>"c.fees_10");
        $start = $request->input("start");
        $length = $request->input("length");
        $order = $request->input("order");
        $columns = $request->input("columns");
        $instituteId = $request->input("institute_id");
        $course_name = $request->input("course_name");
        $orderedColumn = (isset($order[0]) && isset($order[0]["column"])) ? $order[0]["column"] : '0';
        $orderedPref = (isset($order[0]) && isset($order[0]["dir"])) ? $order[0]["dir"] : 'asc';

        $search = $request->input("search");
        $searchedText = (isset($search["value"]) && $search["value"]!='') ? $search["value"] : '';

        $defaultPerPage = $request->input("length", $LISTING_PER_PAGE_LIMIT);
        $pageNumber = (($start/$defaultPerPage) + '1');

        $tblcourses = resolve(Course::Class)->tablename();

        // $totalSQL = DB::table($tblcourses.' as c')->select('c.id');
        // $totalSQL->where("c.status", "Active");
        // if($searchedText!='')
        // {
        //     $totalSQL->where(function($searchedItems) use($searchedText) {
        //         $searchedItems->orWhere("c.title", "like", "%".$searchedText."%");
        //         $searchedItems->orWhere("c.status", "like", "%".$searchedText."%");
        //         $searchedItems->orWhere("c.description", "like", "%".$searchedText."%");
        //     });
        // }
        // for($col=0;$col<count($ordableColumn); $col++)
        // {
        //     if(isset($columns[$col]) && isset($columns[$col]["search"]) && isset($columns[$col]["search"]["value"]) && $columns[$col]["search"]["value"]!='')
        //     {
        //         $totalSQL->where($ordableColumn[$col], "like", "%".$columns[$col]["search"]["value"]."%");
        //     }
        // }
        // $total = $totalSQL->count();
        
        $coursesSQL = DB::table($tblcourses.' as c')->select('c.id', 'c.title', 'c.type', 'c.duration', 'c.visit', 'c.passout_1', 'c.passout_2', 'c.passout_3', 'c.passout_4', 'c.passout_5', 'c.passout_6', 'c.passout_7', 'c.passout_8', 'c.passout_9', 'c.passout_10', 'c.fees_1', 'c.fees_2', 'c.fees_3', 'c.fees_4', 'c.fees_5', 'c.fees_6', 'c.fees_7', 'c.fees_8', 'c.fees_9', 'c.fees_10', 'c.status', 'c.institute_id');
        // $coursesSQL->join('institutes as i', 'c.institute_id', '=', 'i.id');
        $coursesSQL->where('deleted_at',NULL);
        if($searchedText!='')
        {
            $coursesSQL->where(function($searchedItems) use($searchedText) {
                $searchedItems->orWhere("c.title", "like", "%".$searchedText."%");
                $searchedItems->orWhere("c.status", "like", "%".$searchedText."%");
                $searchedItems->orWhere("c.type", "like", "%".$searchedText."%");
                $searchedItems->orWhere("c.duration", "like", "%".$searchedText."%");
                $searchedItems->orWhere("c.visit", "like", "%".$searchedText."%");
                $searchedItems->orWhere("c.passout_1", "like", "%".$searchedText."%");
                $searchedItems->orWhere("c.passout_2", "like", "%".$searchedText."%");
                $searchedItems->orWhere("c.passout_3", "like", "%".$searchedText."%");
                $searchedItems->orWhere("c.passout_4", "like", "%".$searchedText."%");
                $searchedItems->orWhere("c.passout_5", "like", "%".$searchedText."%");
                $searchedItems->orWhere("c.passout_6", "like", "%".$searchedText."%");
                $searchedItems->orWhere("c.passout_7", "like", "%".$searchedText."%");
                $searchedItems->orWhere("c.passout_8", "like", "%".$searchedText."%");
                $searchedItems->orWhere("c.passout_9", "like", "%".$searchedText."%");
                $searchedItems->orWhere("c.passout_10", "like", "%".$searchedText."%");
                $searchedItems->orWhere("c.fees_1", "like", "%".$searchedText."%");
                $searchedItems->orWhere("c.fees_2", "like", "%".$searchedText."%");
                $searchedItems->orWhere("c.fees_3", "like", "%".$searchedText."%");
                $searchedItems->orWhere("c.fees_4", "like", "%".$searchedText."%");
                $searchedItems->orWhere("c.fees_5", "like", "%".$searchedText."%");
                $searchedItems->orWhere("c.fees_6", "like", "%".$searchedText."%");
                $searchedItems->orWhere("c.fees_7", "like", "%".$searchedText."%");
                $searchedItems->orWhere("c.fees_8", "like", "%".$searchedText."%");
                $searchedItems->orWhere("c.fees_9", "like", "%".$searchedText."%");
                $searchedItems->orWhere("c.fees_10", "like", "%".$searchedText."%");

            });
        }
        for($col=0;$col<count($ordableColumn); $col++)
        {
            if(isset($columns[$col]) && isset($columns[$col]["search"]) && isset($columns[$col]["search"]["value"]) && $columns[$col]["search"]["value"]!='')
            {
                $coursesSQL->where($ordableColumn[$col], "like", "%".$columns[$col]["search"]["value"]."%");
            }
        }
        // if(!is_admin())
        // {
        //     $coursesSQL->where('institute_id',$getUserInfo->institute_id);
        // }
        if ($course_name) {
            $coursesSQL->where("c.title", "like", "%".$course_name."%");
        }

        $total = $coursesSQL->orderBy($ordableColumn[$orderedColumn], $orderedPref)->count();
        $courses = $coursesSQL->orderBy($ordableColumn[$orderedColumn], $orderedPref)->paginate($defaultPerPage, ['*'], 'page', $pageNumber);

        $data["draw"] = $request->input("draw");
        $data["recordsTotal"] = $total;
        $data["recordsFiltered"] = $total;

        $currentUserId = decryptString(getRequestAttributes('currentUser')->id);

        if($course_name)
        {
            $course = Course::where('title','like', '%'.$course_name.'%')->first();
            // dd($course);
            $courseVisitCount = CourseVisitCount::where('user_id', $currentUserId)
            ->where('course_id', $course->id) // Assuming course_id is passed in the request
            ->where('status', 'Active') // Assuming there's an 'active' field to check
            ->first();

        
            if ($courseVisitCount) {
                // If an entry exists, increment the visit count
                $courseVisitCount->visit_count += 1;
                $courseVisitCount->save();
            } else {
                // If no entry exists, create a new one with count 1
                CourseVisitCount::create([
                    'user_id' => $currentUserId,
                    'course_id' => $course->id,
                    'visit_count' => 1,
                ]);
            }      
        }

        if($courses)
        {
            foreach($courses as $course)
            {
                $allCourses[] = array(
                    "hash"=> encryptString($course->id),
                    "title"=> $course->title,
                    "university_name"=> Institute::find($course->institute_id)->university_name,
                    "approved_by"=> Institute::find($course->institute_id)->approved_by,
                    "verification"=> Institute::find($course->institute_id)->verification,
                    "website"=> Institute::find($course->institute_id)->university_website,
                    "type"=> $course->type,
                    "duration"=> $course->duration,
                    "visit"=> $course->visit,
                    "passout-1"=> $course->passout_1,
                    "passout-2"=> $course->passout_2,
                    "passout-3"=> $course->passout_3,
                    "passout-4"=> $course->passout_4,
                    "passout-5"=> $course->passout_5,
                    "passout-6"=> $course->passout_6,
                    "passout-7"=> $course->passout_7,
                    "passout-8"=> $course->passout_8,
                    "passout-9"=> $course->passout_9,
                    "passout-10"=> $course->passout_10,
                    "fees-1"=> $course->fees_1,
                    "fees-2"=> $course->fees_2,
                    "fees-3"=> $course->fees_3,
                    "fees-4"=> $course->fees_4,
                    "fees-5"=> $course->fees_5,
                    "fees-6"=> $course->fees_6,
                    "fees-7"=> $course->fees_7,
                    "fees-8"=> $course->fees_8,
                    "fees-9"=> $course->fees_9,
                    "fees-10"=> $course->fees_10,
                    "status"=> $course->status,
                );
            }
        }

        $data["data"] = $allCourses;

        return response()->json($data);
    }
    public function filtervisits(Request $request)
    {
        // Log::info($request);
        $LISTING_PER_PAGE_LIMIT = getConfig('constants', 'LISTING_PER_PAGE_LIMIT');
        $data["draw"] = $request->input("draw");
        $data["recordsTotal"] = 0;
        $data["recordsFiltered"] = 0;
        $allCourses = array();
        $data["data"] = $allCourses;
        $getUserInfo = getRequestAttributes('currentUser');
        
        $ordableColumn = array("0"=> "c.user_id", "1"=>"c.course_id","2"=>"c.visit_count");
        $start = $request->input("start");
        $length = $request->input("length");
        $order = $request->input("order");
        $columns = $request->input("columns");
        $instituteId = $request->input("institute_id");
        $course_name = $request->input("course_name");
        $orderedColumn = (isset($order[0]) && isset($order[0]["column"])) ? $order[0]["column"] : '0';
        $orderedPref = (isset($order[0]) && isset($order[0]["dir"])) ? $order[0]["dir"] : 'asc';

        $search = $request->input("search");
        $searchedText = (isset($search["value"]) && $search["value"]!='') ? $search["value"] : '';

        $defaultPerPage = $request->input("length", $LISTING_PER_PAGE_LIMIT);
        $pageNumber = (($start/$defaultPerPage) + '1');

        $tblcourses = resolve(CourseVisitCount::Class)->tablename();

        // $totalSQL = DB::table($tblcourses.' as c')->select('c.id');
        // $totalSQL->where("c.status", "Active");
        // if($searchedText!='')
        // {
        //     $totalSQL->where(function($searchedItems) use($searchedText) {
        //         $searchedItems->orWhere("c.title", "like", "%".$searchedText."%");
        //         $searchedItems->orWhere("c.status", "like", "%".$searchedText."%");
        //         $searchedItems->orWhere("c.description", "like", "%".$searchedText."%");
        //     });
        // }
        // for($col=0;$col<count($ordableColumn); $col++)
        // {
        //     if(isset($columns[$col]) && isset($columns[$col]["search"]) && isset($columns[$col]["search"]["value"]) && $columns[$col]["search"]["value"]!='')
        //     {
        //         $totalSQL->where($ordableColumn[$col], "like", "%".$columns[$col]["search"]["value"]."%");
        //     }
        // }
        // $total = $totalSQL->count();
        
        $coursesSQL = DB::table($tblcourses.' as c')->select('c.user_id', 'c.course_id', 'c.visit_count');
        // $coursesSQL->join('institutes as i', 'c.institute_id', '=', 'i.id');
        $coursesSQL->where('deleted_at',NULL);

        for($col=0;$col<count($ordableColumn); $col++)
        {
            if(isset($columns[$col]) && isset($columns[$col]["search"]) && isset($columns[$col]["search"]["value"]) && $columns[$col]["search"]["value"]!='')
            {
                $coursesSQL->where($ordableColumn[$col], "like", "%".$columns[$col]["search"]["value"]."%");
            }
        }
        // if(!is_admin())
        // {
        //     $coursesSQL->where('institute_id',$getUserInfo->institute_id);
        // }

        $total = $coursesSQL->orderBy($ordableColumn[$orderedColumn], $orderedPref)->count();
        $courses = $coursesSQL->orderBy($ordableColumn[$orderedColumn], $orderedPref)->paginate($defaultPerPage, ['*'], 'page', $pageNumber);

        $data["draw"] = $request->input("draw");
        $data["recordsTotal"] = $total;
        $data["recordsFiltered"] = $total;

        $currentUserId = decryptString(getRequestAttributes('currentUser')->id);

        if ($course_name) {
            $coursesSQL->where("c.title", "like", "%".$course_name."%");
        }

        // Fetching the data
        $courses = $coursesSQL->orderBy($ordableColumn[$orderedColumn], $orderedPref)->paginate($defaultPerPage, ['*'], 'page', $pageNumber);
        // Prepare the response data
        if ($courses) {
            foreach ($courses as $course) {
                $user = User::find($course->user_id); // Fetch user details
                $courseDetails = Course::find($course->course_id); // Fetch course details

                $allCourses[] = array(
                    "center_name" => $user ? $user->first_name.' '.$user->last_name : null, // Get user's first name
                    "course_name" => $courseDetails ? $courseDetails->title : null, // Get course title
                    "visits"=>$course->visit_count
                    // ... other course details ...
                );
            }
        }
        $data["data"] = $allCourses;

        return response()->json($data);
    }


    public function create()
    {
        if(!hasPermission('add-course') && !is_customer()) {
            unauthorizedRedirect();
        }
        $authUser = getRequestAttributes('currentUser');
        $mode = 'Add';
        $institutes = Institute::where('status','Active')->get();
        // $courses = [
        //     'BCA', 'MCA', 'BBA', 'MBA', 'B.Tech', 'M.Tech',
        //     'B.Sc', 'M.Sc', 'B.A', 'M.A', 'B.Com', 'M.Com',
        //     'B.Ed', 'M.Ed','BPT',
        // ];
        return view('admin.courses.form', compact('mode','institutes'));
    }


    public function store(Request $request)
    {
        if(!hasPermission('add-course') && !is_customer()) {
            unauthorizedRedirect();
        }
        $authUser = getRequestAttributes('currentUser');
        $userId = decryptString($authUser->id);
        $validateFields = [
            'title' => 'required|max:255',
            'institute_id' => 'required',
            'type' => 'max:255',
            'duration' => 'max:255',
            'visit' => 'max:255',
            'passout_1' => 'max:255',
            'passout_2' => 'max:255',
            'passout_3' => 'max:255',
            'passout_4' => 'max:255',
            // 'passout_5' => 'max:255',
            // 'passout_6' => 'max:255',
            // 'passout_7' => 'max:255',
            // 'passout_8' => 'max:255',
            // 'passout_9' => 'max:255',
            // 'passout_10' => 'max:255',
            'fees_1' => 'max:255',
            'fees_2' => 'max:255',
            'fees_3' => 'max:255',
            'fees_4' => 'max:255',
            // 'fees_5' => 'max:255',
            // 'fees_6' => 'max:255',
            // 'fees_7' => 'max:255',
            // 'fees_8' => 'max:255',
            // 'fees_9' => 'max:255',
            // 'fees_10' => 'max:255',
        ];

        $validateFieldsMessages = [
            'title.required' => 'Title is required.',
            'title.max' => 'Title should be maximum of 255 characters.',
            'institute_id.required' => 'Institute is required.',
            'type.max' => 'Type should be maximum of 255 characters.',
            'duration.max' => 'Duration should be maximum of 255 characters.',
            'visit.max' => 'Visit should be maximum of 255 characters.',
            'passout_1.max' => 'Passout 1 should be maximum of 255 characters.',
            'passout_2.max' => 'Passout 2 should be maximum of 255 characters.',
            'passout_3.max' => 'Passout 3 should be maximum of 255 characters.',
            // 'passout_4.max' => 'Passout 4 should be maximum of 255 characters.',
            // 'passout_5.max' => 'Passout 5 should be maximum of 255 characters.',
            // 'passout_6.max' => 'Passout 6 should be maximum of 255 characters.',
            // 'passout_7.max' => 'Passout 7 should be maximum of 255 characters.',
            // 'passout_8.max' => 'Passout 8 should be maximum of 255 characters.',
            // 'passout_9.max' => 'Passout 9 should be maximum of 255 characters.',
            // 'passout_10.max' => 'Passout 10 should be maximum of 255 characters.',
            'fees_1.max' => 'Fees 1 should be maximum of 255 characters.',
            'fees_2.max' => 'Fees 2 should be maximum of 255 characters.',
            'fees_3.max' => 'Fees 3 should be maximum of 255 characters.',
            'fees_4.max' => 'Fees 4 should be maximum of 255 characters.',  
            // 'fees_5.max' => 'Fees 5 should be maximum of 255 characters.',
            // 'fees_6.max' => 'Fees 6 should be maximum of 255 characters.',
            // 'fees_7.max' => 'Fees 7 should be maximum of 255 characters.',
            // 'fees_8.max' => 'Fees 8 should be maximum of 255 characters.',
            // 'fees_9.max' => 'Fees 9 should be maximum of 255 characters.',
            // 'fees_10.max' => 'Fees 10 should be maximum of 255 characters.',
            
        ];

        $validator = $request->validate($validateFields, $validateFieldsMessages);

        $course = new Course();
        $course->title = $request->title;
        $course->type = $request->type;
        $course->duration = $request->duration;
        $course->visit = $request->visit;
        $course->passout_1 = $request->passout_1;
        $course->passout_2 = $request->passout_2;
        $course->passout_3 = $request->passout_3;
        $course->passout_4 = $request->passout_4;
        // $course->passout_5 = $request->passout_5;
        // $course->passout_6 = $request->passout_6;
        // $course->passout_7 = $request->passout_7;
        // $course->passout_8 = $request->passout_8;
        // $course->passout_9 = $request->passout_9;
        // $course->passout_10 = $request->passout_10;
        $course->fees_1 = $request->fees_1;
        $course->fees_2 = $request->fees_2;
        $course->fees_3 = $request->fees_3;
        $course->fees_4 = $request->fees_4;
        // $course->fees_5 = $request->fees_5;
        // $course->fees_6 = $request->fees_6;
        // $course->fees_7 = $request->fees_7;
        // $course->fees_8 = $request->fees_8;
        // $course->fees_9 = $request->fees_9;
        // $course->fees_10 = $request->fees_10;
        $course->institute_id = $request->institute_id;
        $course->status = "Active";
        $course->created_by = $userId;
        if( $course->save() )
        {
            session()->flash('success', $this->notifyuser("COURSE_ADDED"));
            return redirect()->route('admin.courses');
        }
        else
        {
            return back();
        }

    }


    public function show($id)
    {
        if(!hasPermission('view-courses') && !is_customer()) {
            unauthorizedRedirect();
        }
        $courseID = decryptString($id);
        $course = Course::find($courseID);
        $institute = Institute::find($course->institute_id);
        $passouts = [
            ['passout' => @$course->passout_1, 'fees' => @$course->fees_1],
            ['passout' => @$course->passout_2, 'fees' => @$course->fees_2],
            ['passout' => @$course->passout_3, 'fees' => @$course->fees_3],
            ['passout' => @$course->passout_4, 'fees' => @$course->fees_4],
        ];
        return view("admin.courses.detail", compact(["course","institute","passouts"]));
    }


    public function edit($id)
    {
        if(!hasPermission('edit-course') && !is_customer()) {
            unauthorizedRedirect();
        }
        $authUser = getRequestAttributes('currentUser');
        $mode = "Edit";
        $courseID = decryptString($id);
        $course = Course::find($courseID);
        $institutes = Institute::where('status','Active')->get();   
        $courses = [
            'BCA', 'MCA', 'BBA', 'MBA', 'B.Tech', 'M.Tech',
            'B.Sc', 'M.Sc', 'B.A', 'M.A', 'B.Com', 'M.Com',
            'B.Ed', 'M.Ed','BPT',
        ];
        return view("admin.courses.form", compact(["course", "mode","institutes","courses"]));
    }

    public function update(Request $request, $id)
    {
        if(!hasPermission('edit-course') && !is_customer()) {
            unauthorizedRedirect();
        }
        $authUser = getRequestAttributes('currentUser');
        $userId = decryptString($authUser->id);
        $courseId = decryptString($id);
        $validateFields = [
            'title' => 'required|max:255',
            'institute_id' => 'required',
            'type' => 'max:255',
            'duration' => 'max:255',
            'visit' => 'max:255',
            'passout_1' => 'max:255',
            'passout_2' => 'max:255',
            'passout_3' => 'max:255',
            'passout_4' => 'max:255',
            'passout_5' => 'max:255',
            'passout_6' => 'max:255',
            'passout_7' => 'max:255',
            'passout_8' => 'max:255',
            'passout_9' => 'max:255',
            'passout_10' => 'max:255',
            'fees_1' => 'max:255',
            'fees_2' => 'max:255',
            'fees_3' => 'max:255',
            'fees_4' => 'max:255',
            'fees_5' => 'max:255',
            'fees_6' => 'max:255',
            'fees_7' => 'max:255',
            'fees_8' => 'max:255',
            'fees_9' => 'max:255',
            'fees_10' => 'max:255',
        ];

        $validateFieldsMessages = [
            'title.required' => 'Title is required.',
            'title.max' => 'Title should be maximum of 255 characters.',
            'institute_id.required' => 'Institute is required.',
            'type.max' => 'Type should be maximum of 255 characters.',
            'duration.max' => 'Duration should be maximum of 255 characters.',
            'visit.max' => 'Visit should be maximum of 255 characters.',
            'passout_1.max' => 'Passout 1 should be maximum of 255 characters.',
            'passout_2.max' => 'Passout 2 should be maximum of 255 characters.',
            'passout_3.max' => 'Passout 3 should be maximum of 255 characters.',
            'passout_4.max' => 'Passout 4 should be maximum of 255 characters.',
            'passout_5.max' => 'Passout 5 should be maximum of 255 characters.',    
            'passout_6.max' => 'Passout 6 should be maximum of 255 characters.',
            'passout_7.max' => 'Passout 7 should be maximum of 255 characters.',
            'passout_8.max' => 'Passout 8 should be maximum of 255 characters.',
            'passout_9.max' => 'Passout 9 should be maximum of 255 characters.',
            'passout_10.max' => 'Passout 10 should be maximum of 255 characters.',
            'fees_1.max' => 'Fees 1 should be maximum of 255 characters.',
            'fees_2.max' => 'Fees 2 should be maximum of 255 characters.',
            'fees_3.max' => 'Fees 3 should be maximum of 255 characters.',
            'fees_4.max' => 'Fees 4 should be maximum of 255 characters.',  
            'fees_5.max' => 'Fees 5 should be maximum of 255 characters.',
            'fees_6.max' => 'Fees 6 should be maximum of 255 characters.',
            'fees_7.max' => 'Fees 7 should be maximum of 255 characters.',
            'fees_8.max' => 'Fees 8 should be maximum of 255 characters.',
            'fees_9.max' => 'Fees 9 should be maximum of 255 characters.',
            'fees_10.max' => 'Fees 10 should be maximum of 255 characters.',
        ];

        $validator = $request->validate($validateFields, $validateFieldsMessages);
        
        $course = Course::find($courseId);
        $course->title = $request->title;
        $course->type = $request->type;
        $course->duration = $request->duration;
        $course->visit = $request->visit;
        $course->passout_1 = $request->passout_1;
        $course->passout_2 = $request->passout_2;
        $course->passout_3 = $request->passout_3;
        $course->passout_4 = $request->passout_4;
        $course->passout_5 = $request->passout_5;
        $course->passout_6 = $request->passout_6;
        $course->passout_7 = $request->passout_7;
        $course->passout_8 = $request->passout_8;
        $course->passout_9 = $request->passout_9;
        $course->passout_10 = $request->passout_10;
        $course->fees_1 = $request->fees_1;
        $course->fees_2 = $request->fees_2;
        $course->fees_3 = $request->fees_3;
        $course->fees_4 = $request->fees_4;
        $course->fees_5 = $request->fees_5;
        $course->fees_6 = $request->fees_6;
        $course->fees_7 = $request->fees_7;
        $course->fees_8 = $request->fees_8;
        $course->fees_9 = $request->fees_9;
        $course->fees_10 = $request->fees_10;
        $course->status = $request->status;
        $course->updated_by = $userId;

        if($course->save())
        {
            session()->flash('success', $this->notifyuser("COURSE_UPDATED"));
            return redirect()->route('admin.courses');
        }
        else 
        {
            return back();
        }


    }

    public function destroy($id)
    {
        if(!hasPermission('delete-course') && !is_customer()) {
            unauthorizedRedirect();
        }
        $authUser = getRequestAttributes('currentUser');
        $userId = decryptString($authUser->id);
        $courseId = decryptString($id);
        $course = Course::find($courseId);
        $course->status = 'Deleted';
        $course->deleted_by = $userId;
        $course->save();

        $course->delete();
        session()->flash('success', $this->notifyuser("COURSE_DELETED"));

        return redirect()->route('admin.course.list');
    }
}
