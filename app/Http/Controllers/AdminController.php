<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Keyword;
use App\Models\Question;
use App\Mail\SendPassword;
use App\Models\Department;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\AdminResponse;
use App\Imports\StudentsImport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;



class AdminController extends Controller
{

    // admin dashboard
    public function index()
    {
        return view('admin.index');
    }

    public function user_details()
    {
        $user = Auth::user();
        return view('admin.sidebar',compact('user'));
    }


    // admin dashboard
    public function admin_dashboard()
    {
        return view('admin.index');
    }

    // Go to Department Creation form
    public function add_department()
    {
        return view('admin.add_department');
    }

    public function create_department(Request $request)
    {
        // Validate the request data
        $request->validate([
            'department_name' => 'required|string|max:255',
            'department_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif', // Adjust the file size limit if needed
        ]);
    
        // Create a new department instance
        $department = new Department;
    
        // Assign the department name
        $department->department_name = $request->department_name;
    
        // Handle the department logo upload if provided
        if ($request->hasFile('department_logo')) {
            $image = $request->file('department_logo');
            $department_logo = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $department_logo);
            $department->department_logo = $department_logo; // Ensure this matches the fillable attribute
        }
    
        // Save the department to the database
        $department->save();
    
        sweetalert()->success('Department Created Successfully');
        return redirect()->back();
    }
    

        // Go to View Departments form
        public function view_departments()
        {
            $departments = Department::all(); // Fetch all departments from the database
            return view('admin.view_departments', compact('departments'));
        }


        public function choose_department()
        {  
            $departments = Department::all(); // Fetch all departments from the database
            return view('admin.choose_department' , compact('departments'));
        }

        public function add_course($id)
        {
            $department = Department::find($id); // Fetch department id from the database
      
            return view('admin.add_course', compact('department'));
        }

        public function create_course(Request $request, $id)
        {
            $department = Department::find($id);
        
            if (!$department) {
                toastr()->timeOut(5000)->closeButton()->addError('Department not found');
                return redirect()->back();
            }
        
            $request->validate([
                'course_name' => 'required|string|max:255',
                'course_acronym' => 'required|string|max:255',
            ]);
        
            $course = new Course();
            $course->department_id = $department->id; // Use the id instead of the entire object
            $course->course_name = $request->course_name;
            $course->course_acronym = $request->course_acronym;
        
            $course->save();
        
            sweetalert()->success('Course Created Successfully');
            return redirect()->back();
        }

        public function view_course_department()

        {  
            $departments = Department::all(); // Fetch all departments from the database
            return view('admin.view_course_department' , compact('departments'));
        }
        
        public function view_course($id)
        {
            $department = Department::find($id);
        
            if (!$department) {
                toastr()->timeOut(5000)->closeButton()->addError('Department not found');
                return redirect()->back();
            }
        
            // Get courses related to the specific department
            $courses = Course::where('department_id', $department->id)->get();
        
            return view('admin.view_course', compact('courses', 'department'));
        }
        

        public function add_admin()
        {
            $admins = User::where('role', 'admin')->get(); // Fetch admins from the database
            return view('admin.add_admin', compact('admins'));
        }


        
        public function create_admin(Request $request)
        {
            $request->validate([
                'school_id' => 'required|string',
                'email' => 'required|email',
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'middle_name' => 'nullable|string',
                'name_suffix' => 'nullable|string',
            ]);
        
            $password = Str::random(8);
        
            $user = User::create([
                'school_id' => $request->school_id,
                'name' => $request->first_name,
                'last_name' => $request->last_name,
                'middle_name' => $request->middle_name,
                'name_suffix' => $request->name_suffix,
                'email' => $request->email,
                'role' => 'admin',
                'password' => Hash::make($password),
                'user_status' => 'New User',
            ]);
        
        
            Mail::to($user->email)->send(new SendPassword($password, $user->email));
        
             sweetalert()->success('Admin account created and password sent via email.');
            return redirect()->back();
        }
        



        public function add_evaluator()
        {
            $departments = Department::all();
            $evaluators = User::where('role', 'evaluator')->get(); // Fetch evaluators from the database
        
            return view('admin.add_evaluator', compact('departments', 'evaluators'));
        }
        

        public function create_evaluator(Request $request)
        {
            $request->validate([
                'school_id' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'middle_name' => 'nullable|string',
                'name_suffix' => 'nullable|string',
                'department_ids' => 'required|array',
                'department_ids.*' => 'exists:departments,id',
            ], [
                'school_id.required' => 'The school ID is required.',
                'email.required' => 'The email is required.',
                'email.email' => 'The email must be a valid email address.',
                'email.unique' => 'This email is already taken.',
                // Add other custom messages as needed
            ]);
        
            $password = Str::random(8);
        
            $user = User::create([
                'school_id' => $request->school_id,
                'name' => $request->first_name,
                'last_name' => $request->last_name,
                'middle_name' => $request->middle_name,
                'name_suffix' => $request->name_suffix,
                'email' => $request->email,
                'role' => 'evaluator',
                'password' => Hash::make($password),
                'user_status' => 'New User',
            ]);
        
            $user->departments()->sync($request->input('department_ids', []));
        
            // Send email and check for success
            if (Mail::to($user->email)->send(new SendPassword($password, $user->email))) {
                sweetalert()->success('Evaluator account created and password sent via email.');
            } else {
                sweetalert()->error('Failed to send email. Please try again.');
            }
        
            sweetalert()->success('Evaluator account created and password sent via email.');
            return redirect()->route('add_evaluator'); // Redirect to the evaluator list
        }
        
        


        public function update_eval(Request $request, $id)
        {
            // Validate input
            $validatedData = $request->validate([
                'school_id' => 'required|string|max:255',
                'email' => 'required|email',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'name_suffix' => 'nullable|string|max:255',
                'department_ids' => 'array', // Optional if no departments are selected
            ]);
        
            // Find the evaluator by ID
            $evaluator = User::findOrFail($id);
        
            // Update evaluator's details
            $evaluator->update($validatedData);
        
            // Sync departments (update many-to-many relationship)
            $evaluator->departments()->sync($request->department_ids);
        
            sweetalert()->success('Evaluator account updated successfully.');
            return redirect()->back();
        }
        






        // update semester

        public function updateSemester(Request $request)
    {
        $semester = $request->input('semester');
        
        // Update the year_term for all students based on selected semester
        User::query()->update(['semester' => $semester]);


        sweetalert()->success('Semester updated successfully!');
        return redirect()->back();
    }


        


        public function add_secretary()
        {
            $departments = Department::all();
            $secretaries = User::where('role', 'secretary')->get(); // Fetch evaluators from the database
        
            return view('admin.add_secretary', compact('departments', 'secretaries'));
        }



        public function create_secretary(Request $request)
        {
            $request->validate([
                'school_id' => 'required|string',
                'email' => 'required|email',
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'middle_name' => 'nullable|string',
                'name_suffix' => 'nullable|string',
                'department_ids' => 'required|array',
                'department_ids.*' => 'exists:departments,id',
            ]);
        
            $password = Str::random(8);
        
            $user = User::create([
                'school_id' => $request->school_id,
                'name' => $request->first_name,
                'last_name' => $request->last_name,
                'middle_name' => $request->middle_name,
                'name_suffix' => $request->name_suffix,
                'email' => $request->email,
                'role' => 'secretary',
                'password' => Hash::make($password),
                'user_status' => 'New User',
            ]);
        
            $user->departments()->sync($request->input('department_ids', []));
        
            Mail::to($user->email)->send(new SendPassword($password, $user->email));
        
             sweetalert()->success('Secretary account created and password sent via email.');
            return redirect()->back();
        }
        

        public function update_secretary(Request $request)
{
    $request->validate([
        'id' => 'required|exists:users,id', // Ensure the user exists
        'school_id' => 'required|string',
        'email' => 'required|email',
        'first_name' => 'required|string',
        'last_name' => 'required|string',
        'middle_name' => 'nullable|string',
        'name_suffix' => 'nullable|string',
        'department_ids' => 'required|array',
        'department_ids.*' => 'exists:departments,id',
    ]);

    // Find the secretary
    $user = User::find($request->id);
    
    // Update user details
    $user->update([
        'school_id' => $request->school_id,
        'email' => $request->email,
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'middle_name' => $request->middle_name,
        'name_suffix' => $request->name_suffix,
    ]);

    // Sync the department IDs
    $user->departments()->sync($request->input('department_ids'));

    sweetalert()->success('Secretay Updated Successfully');
    return redirect()->back();
}

        

        












        public function add_students()
        {
            $departments = Department::all(); // Fetch all departments from the database
            return view('admin.add_students' ,compact('departments'));
        }


        public function add_student_course($id)
        {
                $department = Department::find($id);
            
                if (!$department) {
                    toastr()->timeOut(5000)->closeButton()->addError('Department not found');
                    return redirect()->back();
                }
            
                // Get courses related to the specific department
                $courses = Course::where('department_id', $department->id)->get();
            
                return view('admin.add_student_course', compact('courses', 'department'));
        }


        public function create_student_form($courseId)
        {
            // Retrieve the course by its ID
            $course = Course::find($courseId);
            
            if (!$course) {
                // Handle case where course is not found
                abort(404, 'Course not found');
            }
        
            // Retrieve the department associated with the course
            $department = Department::find($course->department_id);
            
            if (!$department) {
                // Handle case where department is not found
                abort(404, 'Department not found');
            }
        
            // Retrieve students associated with the course
            $students = User::where('course_id', $courseId)->get();
        
            // Pass course, department, and students to the view
            return view('admin.create_student_form', compact('course', 'department', 'students'));
        }
        
        
        

        public function create_student_account(Request $request)
        {
            $request->validate([
                'department_id' => 'required|integer',
                'course_id' => 'required|integer',
                'excel_file' => 'required|file|mimes:xlsx,xls',
            ]);
        
            // Retrieve form data
            $departmentId = $request->input('department_id');
            $courseId = $request->input('course_id');
            $year = $request->input('year');
        
            try {
                // Pass the data to the import class
                Excel::import(new StudentsImport($departmentId, $courseId, $year), $request->file('excel_file'));
        
                 sweetalert()->success('Student accounts created and credentials emailed.');
            } catch (\Exception $e) {
                // Handle any errors that occur during the import
                toastr()->timeOut(5000)->closeButton()->addError('An error occurred: ' . $e->getMessage());
            }

            return redirect()->back();
        }
        


        // CHAT BOT CONTROLER FOR ADMIN


        public function manage()
        {
            $keywords = Keyword::all();
            $questions = Question::all();
            return view('chat.manage', compact('keywords', 'questions'));
        }

    public function storeKeyword(Request $request)
    {
        $keyword = new Keyword();
        $keyword->keyword = $request->input('keyword');
        $keyword->save();

  
        sweetalert()->success('Keyword created successfully.');
        return redirect()->route('chat.manage');
    }

   
        public function storeQuestion(Request $request)
        {
            $question = new Question();
            $question->keyword_id = $request->input('keyword_id');
            $question->question = $request->input('question');
            $question->answer = $request->input('answer');
            $question->save();
    



                    
            sweetalert()->success('Question created successfully.');
            return redirect()->route('chat.manage');
        }

        


    public function updateKeyword(Request $request, $id)
    {
        $keyword = Keyword::find($id);
        $keyword->keyword = $request->input('keyword');
        $keyword->save();

                            
        sweetalert()->success('Keyword updated successfully.');
        return redirect()->route('chat.manage');
    }

  
    public function updateQuestion(Request $request, $id)
    {
        $question = Question::find($id);
        $question->keyword_id = $request->input('keyword_id');
        $question->question = $request->input('question');
        $question->answer = $request->input('answer');
        $question->save();

        sweetalert()->success('Question updated successfully.');
        return redirect()->route('chat.manage');
    }


    public function deleteKeyword($id)
    {
        $keyword = Keyword::find($id);
        $keyword->delete();

        
        sweetalert()->success('Keyword deleted successfully.');
        return redirect()->route('chat.manage');
    }


    public function deleteQuestion($id)
    {
        $question = Question::find($id);
        $question->delete();

    
        sweetalert()->success('Question deleted successfully.');
        return redirect()->route('chat.manage');
    }
          

// MANAGE USERS


}
