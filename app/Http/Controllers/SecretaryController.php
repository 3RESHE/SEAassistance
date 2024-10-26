<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Advising;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\AdvisingDetail;
use App\Imports\SubjectsImport;
use App\Models\CurriculumSubject;
use App\Models\SubjectCorequisite;
use App\Models\SubjectPrerequisite;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class SecretaryController extends Controller
{
    public function index()
    {
        return view('secretary.index');
    }

    public function view_departments_sec()
    {
        $user = Auth::user();
        $departments = $user->departments()->get();
        return view('secretary.view_departments_sec',compact('departments'));
    }

    public function view_course_sec($id)
    {
        $user = Auth::user();
        $department = Department::find($id);
        if (!$department) {
            toastr()->timeOut(5000)->closeButton()->addError('Department not found');
            return redirect()->back();
        }
    
        // Get courses related to the specific department
        $courses = Course::where('department_id', $department->id)->get();
    
        return view('secretary.view_course_sec', compact('courses', 'department'));
    }


public function add_subjects($id)
{
    $user = Auth::user();
    
    // Find the course by its ID
    $courses = Course::find($id);
    
    if (!$courses) {
        // Display toastr error and redirect if course is not found
        toastr()->timeOut(5000)->closeButton()->addError('Course not found');
        return redirect()->back();
    }
    
    // Check if the user has access to the course's department
    if (!$user->departments()->where('departments.id', $courses->department_id)->exists()) {
        // Display toastr error and redirect if user is not authorized to add subjects to this course
        toastr()->timeOut(5000)->closeButton()->addError('You do not have permission to add subjects to this course');
        return redirect()->back();
    }

    // Retrieve the department associated with the course
    $department = Department::find($courses->department_id);
    
    if (!$department) {
        // Display toastr error and redirect if department is not found
        toastr()->timeOut(5000)->closeButton()->addError('Department not found');
        return redirect()->back();
    }
    
    return view('secretary.add_subjects', compact('department', 'courses'));
}


public function create_subjects(Request $request)
{
    // Validate the file input
    $request->validate([
        'excel_file' => 'required|mimes:xls,xlsx',
    ], [
        'excel_file.required' => 'Please upload an Excel file.',
        'excel_file.mimes' => 'Only .xls and .xlsx file formats are allowed.',
    ]);

    try {
        // Import the Excel file, passing the course_id to SubjectsImport
        Excel::import(new SubjectsImport($request->input('course_id')), $request->file('excel_file'));

        // Success alert
        sweetalert()->success('Subjects Uploaded Successfully');
        return redirect()->back();

    } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
        // Handle validation errors
        sweetalert()->error('There was an error with your file. Please check the format or data.');
        return redirect()->back()->withErrors($e->failures());

    } catch (\Exception $e) {
        // Handle general exceptions (including duplicates)
        if ($e->getCode() === '23000') { // Code for integrity constraint violation (duplicate)
            sweetalert()->error('Duplicate entries found in the Excel file.');
        } else {
            sweetalert()->error('An unexpected error occurred: ' . $e->getMessage());
        }
        return redirect()->back();
    }
}


public function single_create_subjects(Request $request)
{
    // Validate the input fields
    $request->validate([
        'course_id' => 'required|integer',
        'subject_code' => 'required|string|max:255',
        'descriptive_title' => 'required|string|max:255',
        'units' => 'required|numeric|min:1',
    ], [
        'course_id.required' => 'The course ID is required.',
        'subject_code.required' => 'Please provide a subject code.',
        'descriptive_title.required' => 'Please provide a descriptive title.',
        'units.required' => 'Please specify the number of units.',
    ]);

    // Check for existing subject with the same subject code and course ID
    $existingSubject = Subject::where('subject_code', $request->input('subject_code'))
        ->where('course_id', $request->input('course_id'))
        ->first();

    if ($existingSubject) {
        // If a duplicate exists within the same course, show an error message
        sweetalert()->error('Duplicate entry found. The subject already exists in this course.');
        return redirect()->back();
    }

    // Create the new subject
    Subject::create([
        'course_id' => $request->input('course_id'),
        'subject_code' => $request->input('subject_code'),
        'descriptive_title' => $request->input('descriptive_title'),
        'units' => $request->input('units'),
    ]);

    // Success alert
    sweetalert()->success('Subject Added Successfully');
    return redirect()->back();
}



    public function manage_subjects_dep()
    {
        $user = Auth::user();
        $departments = $user->departments()->get();
        return view('secretary.manage_subjects_dep', compact('departments'));
    }


    public function subject_dep_course($id)
    {
        $user = Auth::user();
        $department = Department::find($id);
    
        if (!$department) {
            toastr()->timeOut(5000)->closeButton()->addError('Department not found');
            return redirect()->back();
        }
    
        // Check if the department belongs to the authenticated user
        if (!$user->departments->contains($department)) {
            toastr()->timeOut(5000)->closeButton()->addError('You do not have access to this department');
            return redirect()->back();
        }
    
        // Get courses related to the specific department
        $courses = Course::where('department_id', $department->id)->get();
    
        return view('secretary.subject_dep_course', compact('courses', 'department'));
    }
    
    

    public function manage_subjects($id)
    {
        $user = Auth::user();
        $course = Course::find($id);
    
        if (!$course) {
            toastr()->timeOut(5000)->closeButton()->addError('Course not found');
            return redirect()->back();
        }
    
        // Check if the course is related to the authenticated user's departments
        if (!$user->departments->pluck('id')->contains($course->department_id)) {
            toastr()->timeOut(5000)->closeButton()->addError('You do not have access to this course');
            return redirect()->back();
        }
    
        // Get subjects for the specific course only
        $subjects = Subject::with('curriculumSubjects')
                            ->where('course_id', $course->id)
                            ->get();
    
        $allSubjects = Subject::where('course_id', $course->id)->get();
    
        return view('secretary.manage_subjects', compact('course', 'subjects', 'allSubjects'));
    }
    
    


    public function updateSubjectRelations(Request $request)
    {
        $subjectIds = $request->input('subject_ids', []);
        
        foreach ($subjectIds as $subjectId) {
            $preRequisites = $request->input("pre_requisite_subjects.{$subjectId}", []);
            $coRequisites = $request->input("co_requisite_subjects.{$subjectId}", []);
            $requiredYearLevel = $request->input("required_year_level.{$subjectId}");
            
            // Ensure that $preRequisites and $coRequisites are arrays
            if (!is_array($preRequisites)) {
                $preRequisites = explode(',', $preRequisites);
            }
            if (!is_array($coRequisites)) {
                $coRequisites = explode(',', $coRequisites);
            }
    
            // Get existing prerequisites and corequisites for the subject
            $existingPreRequisites = SubjectPrerequisite::where('subject_id', $subjectId)->pluck('prerequisite_subject_id')->toArray();
            $existingCoRequisites = SubjectCorequisite::where('subject_id', $subjectId)->pluck('corequisite_subject_id')->toArray();
            
            // Determine which prerequisites and corequisites to delete
            $preRequisitesToDelete = array_diff($existingPreRequisites, $preRequisites);
            $coRequisitesToDelete = array_diff($existingCoRequisites, $coRequisites);
        
            // Only delete old prerequisites and corequisites if there is a change
            if (!empty($preRequisitesToDelete)) {
                SubjectPrerequisite::where('subject_id', $subjectId)
                    ->whereIn('prerequisite_subject_id', $preRequisitesToDelete)
                    ->delete();
            }
        
            if (!empty($coRequisitesToDelete)) {
                SubjectCorequisite::where('subject_id', $subjectId)
                    ->whereIn('corequisite_subject_id', $coRequisitesToDelete)
                    ->delete();
            }
        
            // Add new prerequisites
            foreach ($preRequisites as $preReqId) {
                if ($preReqId && !in_array($preReqId, $existingPreRequisites)) {
                    SubjectPrerequisite::create([
                        'subject_id' => $subjectId,
                        'prerequisite_subject_id' => $preReqId,
                    ]);
                }
            }
        
            // Add new corequisites
            foreach ($coRequisites as $coReqId) {
                if ($coReqId && !in_array($coReqId, $existingCoRequisites)) {
                    SubjectCorequisite::create([
                        'subject_id' => $subjectId,
                        'corequisite_subject_id' => $coReqId,
                    ]);
                }
            }
        
            // Update required year level in the curriculum_subjects table
            $curriculumSubject = CurriculumSubject::where('subject_id', $subjectId)->first();
            if ($curriculumSubject) {
                $curriculumSubject->required_year_level = $requiredYearLevel;
                $curriculumSubject->save();
            } else {
                // If no record found, create a new one (optional)
                CurriculumSubject::create([
                    'curriculum_id' => $request->input('curriculum_id'), // Assuming curriculum_id is provided in the request
                    'subject_id' => $subjectId,
                    'year' => $request->input('year'), // Assuming year is provided in the request
                    'year_term' => $request->input('year_term'), // Assuming year_term is provided in the request
                    'required_year_level' => $requiredYearLevel,
                ]);
            }
        }
    
         sweetalert()->success('Subject relations updated successfully.');
        return redirect()->back();
    }
    
    

    public function view_department_sub()
    {

        $user = Auth::user();
        $departments = $user->departments()->get();
        return view('secretary.view_department_sub', compact('departments'));
    }

    public function view_course_sub($id)
    {
        $user = Auth::user();
        $department = Department::find($id);
    
        if (!$department) {
            toastr()->timeOut(5000)->closeButton()->addError('Department not found');
            return redirect()->back();
        }
    
        // Check if the department belongs to the authenticated user
        if (!$user->departments->contains($department)) {
            toastr()->timeOut(5000)->closeButton()->addError('You do not have access to this department');
            return redirect()->back();
        }
    
        // Get courses related to the specific department
        $courses = Course::where('department_id', $department->id)->get();
    
        return view('secretary.view_course_sub', compact('courses', 'department'));
    }
    

    public function view_subjects($id)
    {
        $user = Auth::user();
        
        // Find the course by its ID
        $course = Course::find($id);
    
        if (!$course) {
            toastr()->timeOut(5000)->closeButton()->addError('Course not found');
            return redirect()->back();
        }
    
        // Check if the course is related to the authenticated user (assuming users have a relationship with courses through departments)
        if (!$user->departments->pluck('id')->contains($course->department_id)) {
            toastr()->timeOut(5000)->closeButton()->addError('You do not have access to this course');
            return redirect()->back();
        }
    
        // Retrieve and sort subjects by year and year_term with eager loading for prerequisites and corequisites
        $subjects = Subject::where('course_id', $course->id)
            ->with('prerequisites', 'corequisites') // Eager load prerequisites and corequisites
            ->orderBy('year', 'asc')
            ->orderByRaw("FIELD(year_term, '1st Sem', '2nd Sem', 'Summer') ASC")
            ->get();
    
        // Initialize an empty array to hold the grouped subjects
        $groupedSubjects = [];
    
        // Group subjects by year and year_term
        foreach ($subjects as $subject) {
            $year = $subject->year;
            $term = $subject->year_term;
    
            if (!isset($groupedSubjects[$year])) {
                $groupedSubjects[$year] = [];
            }
    
            if (!isset($groupedSubjects[$year][$term])) {
                $groupedSubjects[$year][$term] = [
                    'subjects' => [],
                    'total_units' => 0,
                ];
            }
    
            // Add the subject to the corresponding year and term group
            $groupedSubjects[$year][$term]['subjects'][] = $subject;
    
            // Add the units to the total units for that term
            $groupedSubjects[$year][$term]['total_units'] += $subject->units;
        }
    
        // Pass the course and grouped subjects to the view
        return view('secretary.view_subjects', compact('course', 'groupedSubjects'));
    }
    

    public function manage_advising_dep()
    {
        $departments = Department::all(); // Fetch all departments from the database
        return view('secretary.manage_advising_dep',compact('departments'));
    }


    public function manage_advising_course($id)
    {
        $department = Department::find($id);
    
        if (!$department) {
            toastr()->timeOut(5000)->closeButton()->addError('Department not found');
            return redirect()->back();
        }
    
        // Get courses related to the specific department
        $courses = Course::where('department_id', $department->id)->get();
    
        return view('secretary.manage_advising_course', compact('courses', 'department'));
    }


    public function manage_advising($id)
    {
        $courses = Course::find($id);
        $subjects = Subject::where('course_id', $courses->id)->get();
        $allSubjects = Subject::where('course_id', $courses->id)->get();
    
        return view('secretary.manage_advising', compact('courses', 'subjects', 'allSubjects'));
    }


    public function updateAdvising(Request $request)
{
    $subjectIds = $request->input('subject_ids', []);
    $years = $request->input('year', []);
    $yearTerms = $request->input('year_term', []);

    foreach ($subjectIds as $index => $subjectId) {
        $subject = Subject::find($subjectId);

        if ($subject) {
            // Update year and year_term for the subject
            $subject->year = $years[$index];
            $subject->year_term = $yearTerms[$index];
            $subject->save();
        }
    }

     sweetalert()->success('Subject details updated successfully.');
    return redirect()->back();
}

    public function view_advising_dep()
    {
        $departments = Department::all(); // Fetch all departments from the database
        return view('secretary.view_advising_dep',compact('departments'));
    }


    public function view_advising_course($id)
    {
        $department = Department::find($id);
    
        if (!$department) {
            toastr()->timeOut(5000)->closeButton()->addError('Department not found');
            return redirect()->back();
        }
    
        // Get courses related to the specific department
        $courses = Course::where('department_id', $department->id)->get();
    
        return view('secretary.view_advising_course', compact('courses', 'department'));
    }

    public function view_advising($id)
    {
        // Find the course by its ID
        $courses = Course::find($id);
    
        // Retrieve and sort subjects by year and year_term with eager loading for prerequisites and corequisites
        $subjects = Subject::where('course_id', $courses->id)
            ->with('prerequisites', 'corequisites') // Eager load prerequisites and corequisites
            ->orderBy('year', 'asc')
            ->orderByRaw("FIELD(year_term, '1st Sem', '2nd Sem', 'summer') ASC")
            ->get();
    
        // Initialize an empty array to hold the grouped subjects
        $groupedSubjects = [];
    
        // Group subjects by year and year_term
        foreach ($subjects as $subject) {
            $year = $subject->year;
            $term = $subject->year_term;
    
            if (!isset($groupedSubjects[$year])) {
                $groupedSubjects[$year] = [];
            }
    
            if (!isset($groupedSubjects[$year][$term])) {
                $groupedSubjects[$year][$term] = [
                    'subjects' => [],
                    'total_units' => 0,
                ];
            }
    
            // Add the subject to the corresponding year and term group
            $groupedSubjects[$year][$term]['subjects'][] = $subject;
    
            // Add the units to the total units for that term
            $groupedSubjects[$year][$term]['total_units'] += $subject->units;
        }
    
        // Pass the course and grouped subjects to the view
        return view('secretary.view_advising', compact('courses', 'groupedSubjects'));
    }
    

    public function advising_records()
    {
        // Get the authenticated user
        $user = Auth::user();
        
        // Get the user's department IDs, specifying the table name to avoid ambiguity
        $userDepartmentIds = $user->departments()->pluck('departments.id')->toArray();
        
        // Check if there are any department IDs
        if (empty($userDepartmentIds)) {
            // Return an empty collection or handle as needed
            $advisings = collect(); // Or handle it differently based on your logic
        } else {
            // Fetch advising records where the user belongs to the user's department_ids
            $advisings = Advising::with('user')
                ->whereHas('user', function($query) use ($userDepartmentIds) {
                    $query->whereIn('department_id', $userDepartmentIds);
                })
                ->get();
        }
    
        return view('secretary.advising_records', compact('advisings'));
    }
    

    
    public function manage_advising_request()
    {
   
        $user = Auth::user();

        $userDepartmentIds = $user->departments()->pluck('departments.id')->toArray(); 
        
        // Check if there are any department IDs
        if (empty($userDepartmentIds)) {
            // Return an empty collection or handle as needed
            $advisings = collect(); // Or handle it differently based on your logic
        } else {

            $advisings = Advising::with('user')
                ->where('advising_status', 'Processing')
                ->whereHas('user', function($query) use ($userDepartmentIds) {
                    // Filter users based on the user's department_ids
                    $query->whereIn('department_id', $userDepartmentIds);
                })
                ->get();
        }
    
        return view('secretary.manage_advising_request', compact('advisings'));
    }
    
    


    public function advising_details($id)
    {
        // Get the authenticated user
        $user = Auth::user();
    
        // Get the user's department IDs
        $userDepartmentIds = $user->departments()->pluck('departments.id')->toArray();
    
        // Find the advising record
        $advising = Advising::with('user')->find($id);
    
        // Check if advising exists
        if (!$advising) {
            toastr()->timeOut(5000)->closeButton()->addError('Advising Not Found');
            return redirect()->back();
        }
    
        // Check if the advising user belongs to the same department
        if (!in_array($advising->user->department_id, $userDepartmentIds)) {
            toastr()->timeOut(5000)->closeButton()->addError('Unauthorized access to this advising detail.');
            return redirect()->back();
        }
    
        // Proceed to retrieve unit limit and load status
        $unitLimit = $advising->unit_limit;
        $Load = $advising->load_status;
    
        // Retrieve subjects with pivot data, specifying the pivot table
        $withinLimitSubjects = $advising->subjects()
            ->wherePivot('status', 'within_limit')
            ->get(['subjects.*']);
    
        $exceedingLimitSubjects = $advising->subjects()
            ->wherePivot('status', 'exceeding_limit')
            ->get(['subjects.*']);
    
        $alreadyPassedSubjects = $advising->subjects()
            ->wherePivot('status', 'already_passed')
            ->get(['subjects.*']);
    
        $notQualifiedSubjects = $advising->subjects()
            ->wherePivot('status', 'not_qualified')
            ->get(['subjects.*']);
    
        $notOpenSubjects = $advising->subjects()
            ->wherePivot('status', 'not_open')
            ->get(['subjects.*']);
    
        $subjectsWithProblem = $advising->subjects()
            ->wherePivot('status', 'subjects_with_problems')
            ->get(['subjects.*']);
    
        // Return the view with the necessary data
        return view('secretary.advising_details', compact(
            'advising',
            'withinLimitSubjects',
            'exceedingLimitSubjects',
            'alreadyPassedSubjects',
            'notQualifiedSubjects',
            'notOpenSubjects',
            'Load',
            'subjectsWithProblem',
            'unitLimit'
        ));
    }
    
    

    public function cancelled_advising_details($id)
    {
        // Get the authenticated user
        $user = Auth::user();
    
        // Get the user's department IDs
        $userDepartmentIds = $user->departments()->pluck('departments.id')->toArray();
    
        try {
            // Find the advising record or throw a 404 error
            $advising = Advising::with('user')->findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            toastr()->timeOut(5000)->closeButton()->addError('Advising Not Found');
            return redirect()->back();
        }
    
        // Check if the advising user belongs to the same department
        if (!in_array($advising->user->department_id, $userDepartmentIds)) {
            toastr()->timeOut(5000)->closeButton()->addError('Unauthorized access to this cancelled advising detail.');
            return redirect()->back();
        }
    
        $unitLimit = $advising->unit_limit;
    
        // Retrieve subjects with pivot data for cancelled advising
        $withinLimitSubjects = $advising->subjects()->wherePivot('status', 'within_limit')->get();
        $exceedingLimitSubjects = $advising->subjects()->wherePivot('status', 'exceeding_limit')->get();
    
        return view('secretary.cancelled_advising_details', compact(
            'advising',
            'withinLimitSubjects',
            'exceedingLimitSubjects',
            'unitLimit'
        ));
    }
    
    

    public function enrolled_advising_details($id)
    {
        $advising = Advising::findOrFail($id);
        $unitLimit = $advising->unit_limit;
        
        // Retrieve subjects with pivot data
        $withinLimitSubjects = $advising->subjects()->wherePivot('status', 'within_limit')->get();
        $exceedingLimitSubjects = $advising->subjects()->wherePivot('status', 'exceeding_limit')->get();

        return view('secretary.enrolled_advising_details', compact(
            'advising',
            'withinLimitSubjects',
            'exceedingLimitSubjects',
            'unitLimit'
        ));
    }

    public function updateAdvisingDetails(Request $request)
    {
        $user = Auth::id();
        $advisingId = $request->input('advising_id');
        $withinLimitSubjects = explode(',', $request->input('within_limit_subjects'));
        $exceedingLimitSubjects = explode(',', $request->input('exceeding_limit_subjects'));
        $notQualifiedSubjects = explode(',', $request->input('not_qualified_subjects'));
        $notOpenSubjects = explode(',', $request->input('not_open_subjects'));
    
        // Fetch the advising instance
        $advising = Advising::findOrFail($advisingId);
    
        // Corrected assignment for advisor_id
        $advising->advisor_id = $user;
    
        // Clear existing advising details
        AdvisingDetail::where('advising_id', $advisingId)->delete();
    
        // Retrieve all subjects to avoid multiple queries
        $allSubjects = Subject::whereIn('id', array_merge($withinLimitSubjects, $exceedingLimitSubjects, $notQualifiedSubjects, $notOpenSubjects))->get()->keyBy('id');
    
        // Function to create advising details
        $createAdvisingDetail = function($status, $subjects) use ($advisingId, $allSubjects) {
            foreach ($subjects as $subjectId) {
                if (!empty($subjectId) && isset($allSubjects[$subjectId])) {
                    $subject = $allSubjects[$subjectId];
                    AdvisingDetail::create([
                        'advising_id' => $advisingId,
                        'subject_id' => $subjectId,
                        'status' => $status,
                        'units' => $subject->units,
                    ]);
                }
            }
        };
    
        // Add new advising details for subjects within the limit
        $createAdvisingDetail('within_limit', $withinLimitSubjects);
    
        // Handle exceeding limit subjects
        $createAdvisingDetail('exceeding_limit', $exceedingLimitSubjects);
    
        // Handle not qualified subjects
        $createAdvisingDetail('not_qualified', $notQualifiedSubjects);
    
        // Handle not open subjects
        $createAdvisingDetail('not_open', $notOpenSubjects);
    
        // Update the grades for subjects within the limit
        foreach ($withinLimitSubjects as $subjectId) {
            if (!empty($subjectId) && isset($allSubjects[$subjectId])) {
                $subject = $allSubjects[$subjectId];
                Grade::updateOrCreate(
                    ['user_id' => $advising->user_id, 'subject_id' => $subjectId],
                    [
                        'grade' => 'Approved',
                        'enrolled_term' => $advising->user->semester . ' ' . $advising->user->academic_year,
                    ]
                );
            }
        }
    
        // Update the advising status to 'Approved'
        $advising->update(['advising_status' => 'Approved']);
    
        sweetalert()->success('Advising details updated and approved successfully!');
        return redirect()->route('advising_records');
    }
    

    public function cancelAdvisingDetails(Request $request, $advisingId)
    {
        // Fetch the advising instance
        $advising = Advising::findOrFail($advisingId);

        // Set the advising status to 'Cancelled'
        $advising->update(['advising_status' => 'Cancelled']);

        // Update grades to null for the corresponding subjects
        Grade::where('user_id', $advising->user_id)->update(['grade' => null]);

         sweetalert()->success('Advising details cancelled successfully!');
        return redirect()->route('advising_records');
    }
    
    
    
}
