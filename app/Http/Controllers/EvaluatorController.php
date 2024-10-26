<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Grade;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Advising;
use App\Models\Curriculum;
use App\Models\Department;
use App\Models\TorRequest;
use Illuminate\Http\Request;
use App\Models\AdvisingDetail;
use App\Imports\StudentsImport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\CurriculumSubject;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Notifications\GradeUpdated;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AssignCurriculumImport;
use App\Imports\UpdateStudentAccountImport;
use App\Imports\UpdateStudentSemesterImport;

class EvaluatorController extends Controller
{
    public function index()
    {
        return view('evaluator.index');
    }

    public function add_curriculum_dep()
    {


        $user = Auth::user(); // Get the currently authenticated user

        // Fetch departments associated with the evaluator from the pivot table
        $departments = $user->departments()->get();

        return view('evaluator.add_curriculum_dep', compact('departments'));
    }

    public function add_curriculum_course($id)
    {
        $department = Department::find($id);
    
        if (!$department) {
            toastr()->timeOut(5000)->closeButton()->addError('Department not found');
            return redirect()->back();
        }
    
        // Get courses related to the specific department
        $courses = Course::where('department_id', $department->id)->get();
    
        return view('evaluator.add_curriculum_course', compact('courses', 'department'));
    }

    public function add_curriculum($id)
    {
        $courses = Course::find($id);
        $subjects = Subject::where('course_id', $courses->id)->get();
        $allSubjects = Subject::where('course_id', $courses->id)->get();
    
        return view('evaluator.add_curriculum', compact('allSubjects', 'courses', 'subjects'));
    }

    public function Generate_Curriculum(Request $request)
    {
        // Validate the input
        $request->validate([
            'curriculum_name' => 'required|string|max:255',
        ]);
    
        // Create a new curriculum
        $curriculum = Curriculum::create([
            'curriculum_name' => $request->input('curriculum_name'),
            'course_id' => $request->input('course_id'),
        ]);
    
        // Associate existing subjects with the new curriculum
        foreach ($request->input('subject_ids') as $index => $subjectId) {
            CurriculumSubject::create([
                'curriculum_id' => $curriculum->id,
                'subject_id' => $subjectId,
                'year' => $request->input('year')[$index],
                'year_term' => $request->input('year_term')[$index],
            ]);
        }
    
        sweetalert()->success('Curriculum and subjects created successfully.');
        return redirect()->back();
    }

    public function view_curriculum_dep()
    {
        $user = Auth::user(); // Get the currently authenticated user

        // Fetch departments associated with the evaluator from the pivot table
        $departments = $user->departments()->get();
        
        return view('evaluator.view_curriculum_dep', compact('departments', 'user'));
    }

    public function view_curriculum_course($id)
    {
        $department = Department::find($id);
    
        if (!$department) {
            toastr()->timeOut(5000)->closeButton()->addError('Department not found');
            return redirect()->back();
        }
    
        // Get courses related to the specific department
        $courses = Course::where('department_id', $department->id)->get();
    
        return view('evaluator.view_curriculum_course', compact('courses', 'department'));
    }

    public function view_curriculums($id)
    {
        $course = Course::find($id);
        $department = Department::find($course->department_id);
        $curriculums = Curriculum::with(['subjects'])->where('course_id', $id)->get();

        return view('evaluator.view_curriculums', compact('course', 'department', 'curriculums'));
    }

    public function view_curriculum_details($id)
    {
        $curriculum = Curriculum::with(['subjects.prerequisites', 'subjects.corequisites'])->find($id);
    
        // Initialize an empty array to hold the grouped subjects
        $groupedSubjects = [];
    
        // Group subjects by year and year_term using pivot data
        foreach ($curriculum->subjects as $subject) {
            $year = $subject->pivot->year;
            $term = $subject->pivot->year_term;
    
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
    
        // Sort the grouped subjects by year and term
        ksort($groupedSubjects);
        foreach ($groupedSubjects as &$terms) {
            ksort($terms);
        }
    
        // Pass the curriculum and grouped subjects to the view
        return view('evaluator.view_curriculum_details', compact('curriculum', 'groupedSubjects'));
    }
    

    public function manage_students_dep()
    {
        // Fetch departments associated with the evaluator from the pivot table
        $user = Auth::user();
        $departments = $user->departments()->get();
        
        return view('evaluator.manage_students_dep', compact('departments'));
    }

    
    

 
    
    public function manage_student_details($userId)
    {
        $user = User::with('curriculum.subjects', 'grades')->find($userId);
        
        if (!$user) {
            toastr()->timeOut(5000)->closeButton()->addError('User not found');
            return redirect()->back();
        }
    
        // Initialize an empty collection for subjects
        $subjects = collect();
    
        // Check if the user has a curriculum and subjects
        if ($user->curriculum && $user->curriculum->subjects) {
            // Define custom sorting for terms
            $termOrder = [
                '1st sem' => 1,
                '2nd sem' => 2,
                'summer' => 3
            ];
    
            // Group subjects by year and term
            $subjects = $user->curriculum->subjects
                ->sortBy(function($subject) use ($termOrder) {
                    $year = (int) $subject->pivot->year;
                    $term = $subject->pivot->year_term;
    
                    // Default term order if not specified
                    $termOrderValue = $termOrder[$term] ?? 999;
    
                    return [$year, $termOrderValue];
                })
                ->groupBy(function($subject) {
                    return $subject->pivot->year . ' ' . $subject->pivot->year_term;
                });
        }
    
        // Prepare grades, transfer grades, source schools, enrolled terms, and INC deadlines for easy access
        $grades = Grade::where('user_id', $user->id)
            ->pluck('grade', 'subject_id')
            ->toArray();
    
        $transferGrades = Grade::where('user_id', $user->id)
            ->pluck('is_transfer_grade', 'subject_id')
            ->toArray();
    
        $sourceSchools = Grade::where('user_id', $user->id)
            ->pluck('source_school', 'subject_id')
            ->toArray();
        
        // Pluck enrolled_term as well
        $enrolledTerms = Grade::where('user_id', $user->id)
            ->pluck('enrolled_term', 'subject_id')
            ->toArray();
        
        // Get distinct enrolled terms that have grades
        $distinctEnrolledTerms = Grade::where('user_id', $user->id)
            ->whereNotNull('enrolled_term') // Ensure the term is not null
            ->distinct()
            ->pluck('enrolled_term')
            ->filter() // Remove any empty values
            ->toArray();
    
        // Add INC deadlines
        $incDeadlines = Grade::where('user_id', $user->id)
            ->pluck('inc_deadline', 'subject_id')
            ->toArray();
    
        return view('evaluator.manage_student_details', compact('user', 'subjects', 'grades', 'transferGrades', 'sourceSchools', 'enrolledTerms', 'incDeadlines', 'distinctEnrolledTerms'));
    }
    
    
    
    
    

    public function add_student_curriculum($id)
    {
        $user = User::find($id);

        // Get all curriculums associated with the user's course
        $curriculums = Curriculum::where('course_id', $user->course_id)->get();

        return view('evaluator.add_student_curriculum', compact('user', 'curriculums'));
    }

    public function manage_student_curriculum($id)
    {
        $user = User::find($id);

        // Get all curriculums associated with the user's course
        $curriculums = Curriculum::where('course_id', $user->course_id)->get();

        return view('evaluator.manage_student_curriculum', compact('user', 'curriculums'));
    }

    public function give_student_curriculum(Request $request, $id)
    {
        // Validate the input
    
        try {
            // Find the user by ID
            $user = User::findOrFail($id);
    
            // Update the user's curriculum
            $user->curriculum_id = $request->input('curriculum_id');
            $user->semester = $request->input('semester');
            $user->save();
    
            // Notify success
            sweetalert()->success('Curriculum assigned successfully.');
        } catch (\Exception $e) {
            // Handle any errors that occur during the update
            toastr()->timeOut(5000)->closeButton()->addError('An error occurred: ' . $e->getMessage());
        }
    
        return redirect()->back();
    }

    public function manage_students($department_id)
    {
        // Fetch the authenticated user (evaluator)
        $user = Auth::user();
    
        // Check if the department_id is associated with the evaluator
        $authorizedDepartment = $user->departments()
            ->where('departments.id', $department_id) // Specify the table name for 'id'
            ->first();
    
        if (!$authorizedDepartment) {
            toastr()->timeOut(5000)->closeButton()->addError('Unauthorized access to department');
            return redirect()->back();
        }
    
        // Fetch the department
        $department = Department::find($department_id);
    
        if (!$department) {
            toastr()->timeOut(5000)->closeButton()->addError('Department not found');
            return redirect()->back();
        }
    
        // Get all course IDs related to the department
        $courseIds = Course::where('department_id', $department->id)->pluck('id');
    
        // Fetch curricula related to those courses
        $curricula = Curriculum::whereIn('course_id', $courseIds)->get();
    
        // Fetch students related to those courses
        $students = User::whereIn('course_id', $courseIds)
                        ->where('role', 'student') // Ensure we're only getting students
                        ->get();

        $currentYear = date('Y');
        $startYear = $currentYear - 1;  // Start from last year
        $academicYears = [];

        // Generate academic years from last year to the next 10 years
        for ($year = $startYear; $year <= $currentYear + 10; $year++) {
            $nextYear = $year + 1;
            $academicYears[] = "{$year}-{$nextYear}";
        }
    
        // Pass the curricula to the view
        return view('evaluator.manage_students', compact('students', 'department', 'curricula', 'academicYears'));
    }


public function update_grade(Request $request, $id)
{
    // Validate the request data
    $request->validate([
        'grades' => 'required|array',
        'grades.*' => 'nullable|string',
        'transfer_grades' => 'nullable|array',
        'source_schools' => 'nullable|array',
        'source_schools.*' => 'nullable|string',
        'inc_deadlines' => 'nullable|array',
        'inc_deadlines.*' => 'nullable|date',
    ]);

    // Get the user by ID
    $user = User::find($id);

    // Check if the user exists
    if (!$user) {
        return redirect()->back()->withErrors(['user' => 'User not found.']);
    }

    // Get all subjects from the user's curriculum
    $curriculumSubjects = $user->curriculum->curriculumSubjects()->with('subject')->get();

    // Use a transaction for safety
    DB::transaction(function () use ($request, $user, $curriculumSubjects) {
        foreach ($request->input('grades') as $subjectId => $grade) {
            // Find the subject by ID from the curriculum subjects
            $curriculumSubject = $curriculumSubjects->firstWhere('subject_id', $subjectId);

            if ($curriculumSubject) {
                // Handle the transfer grade checkbox
                $transferGrade = $request->input("transfer_grades.$subjectId") == 1;

                // Handle the INC deadline
                $incDeadline = $request->input("inc_deadlines.$subjectId");

                // Get the existing grade to check for changes
                $existingGrade = Grade::where('user_id', $user->id)
                    ->where('subject_id', $subjectId)
                    ->first();

                // Update or create the grade record
                Grade::updateOrCreate(
                    ['user_id' => $user->id, 'subject_id' => $subjectId],
                    [
                        'grade' => $grade,
                        'is_transfer_grade' => $transferGrade,
                        'source_school' => $request->input("source_schools.$subjectId"),
                        'inc_deadline' => ($grade === 'INC') ? $incDeadline : null,
                    ]
                );

                // Check if the grade has changed or if the INC deadline has changed
                $gradeChanged = $existingGrade && $existingGrade->grade !== $grade;
                $deadlineChanged = ($existingGrade && $existingGrade->inc_deadline !== $incDeadline);

                // Get the subject name
                $subjectName = $curriculumSubject->subject->descriptive_title;

                // Notify the user if the grade was changed or if the deadline has changed while the grade is 'INC'
                if (!$existingGrade && $grade !== null) {
                    // New grade added
                    $user->notify(new GradeUpdated($user->id, $subjectId, $subjectName, $grade, $incDeadline));
                } elseif ($gradeChanged) {
                    // Grade changed
                    $user->notify(new GradeUpdated($user->id, $subjectId, $subjectName, $grade, $incDeadline));
                } elseif ($deadlineChanged && $grade === 'INC') {
                    // Deadline changed but grade is still INC
                    $user->notify(new GradeUpdated($user->id, $subjectId, $subjectName, $grade, $incDeadline));
                }
            } else {
                throw new \Exception("Subject with ID $subjectId not found in the user's curriculum.");
            }
        }

        // After updating grades, determine the student's current standing based on passed subjects
        $this->update_student_year($user);
    });

    // Redirect back with a success message
    sweetalert()->success('Grades have been updated successfully.');
    return redirect()->back();


   
}

    

    


    private function update_student_year(User $user)
    {
        // Get all completed subjects for the user
        $completedGrades = Grade::where('user_id', $user->id)
                                ->whereNotIn('grade', ['5.0', 'W/F', 'W', 'UD', 'OD', 'INC']) // Exclude failing grades
                                ->pluck('subject_id');
    
        // Get subjects from the user's curriculum
        $curriculumSubjects = $user->curriculum->curriculumSubjects()->with('subject')->get();
    
        // Group the subjects by year
        $subjectGroups = $this->get_subject_groups($curriculumSubjects);
    
        $currentYear = '1st'; // Default to 1st year
    
        // Initialize an array to hold vacant and completed subject counts for each year
        $completedCount = [];
        $vacantCount = [];
    
        // Calculate completed and vacant subjects for each year
        foreach ($subjectGroups as $year => $subjects) {
            $completedCount[$year] = 0; // Initialize completed count for this year
            $vacantCount[$year] = 0; // Initialize vacant count for this year
    
            foreach ($subjects as $subject) {
                if ($completedGrades->contains($subject->id)) {
                    $completedCount[$year]++; // Increment completed count if completed
                } else {
                    $vacantCount[$year]++; // Increment vacant count if not completed
                }
            }
        }
    
        // Determine the current year based on the ratio of completed and vacant subjects
        foreach ($subjectGroups as $year => $subjects) {
            // If there are vacant subjects in this year and significant progress has been made in terms of completed subjects
            if ($completedCount[$year] > 0 && $vacantCount[$year] > 0) {
                $currentYear = $year; // Assign the student to the highest level where progress has been made
            }
        }
    
        // Update the user's year attribute (make sure you have a year field in your User model)
        $user->year = $currentYear;
        $user->save();
    }
    
    

    
    private function get_subject_groups($curriculumSubjects)
    {
        // This function groups subjects by year
        $groupedSubjects = [];
    
        foreach ($curriculumSubjects as $curriculumSubject) {
            $year = $curriculumSubject->year; // Assuming the curriculumSubject model has a year attribute
            $subject = $curriculumSubject->subject;
    
            // Initialize the array if it doesn't exist
            if (!isset($groupedSubjects[$year])) {
                $groupedSubjects[$year] = [];
            }
    
            // Add the subject to the appropriate year
            $groupedSubjects[$year][] = $subject;
        }
    
        return $groupedSubjects;
    }
    
    




















    

    
    public function update_multiple_curriculums_courses($id)
    {
        // Fetch the authenticated user (evaluator)
        $user = Auth::user();
    
        // Check if the department is associated with the evaluator
        $authorizedDepartment = $user->departments()
            ->where('departments.id', $id) // Check if the department is linked to the evaluator
            ->first();
    
        if (!$authorizedDepartment) {
            toastr()->timeOut(5000)->closeButton()->addError('Unauthorized access to department');
            return redirect()->back();
        }
    
        // Fetch the department
        $department = Department::find($id);
        
        if (!$department) {
            toastr()->timeOut(5000)->closeButton()->addError('Department not found');
            return redirect()->back();
        }
    
        // Get courses related to the specific department
        $courses = Course::where('department_id', $department->id)->get();
    
        return view('evaluator.update_multiple_curriculums_courses', compact('courses', 'department'));
    }



    
    public function update_multiple_students($course_id)
    {

        $user = Auth::user(); // Get the currently authenticated user

        // Get all curriculums associated with the user's course
        $curriculums = Curriculum::where('course_id', $course_id)->get();
    
        // Check if the course_id is associated with the department of the evaluator
        $authorizedCourse = $user->departments()
            ->join('courses', 'departments.id', '=', 'courses.department_id')
            ->where('courses.id', $course_id)
            ->first();
    
        if (!$authorizedCourse) {
            toastr()->timeOut(5000)->closeButton()->addError('Unauthorized access to course');
            return redirect()->back();
        }
    
        // Fetch the course
        $course = Course::find($course_id);
        
        if (!$course) {
            toastr()->timeOut(5000)->closeButton()->addError('Course not found');
            return redirect()->back();
        }
    
        // Fetch students related to the course
        $students = User::where('course_id', $course->id)
                        ->where('role', 'student')
                        ->get();
    
        // Pass the students and course to the view
        return view('evaluator.update_multiple_students', [
            'students' => $students,
            'course' => $course,
            'curriculums' => $curriculums
        ]);
    }
    

    public function give_multiple_curriculum(Request $request)
    {
        $request->validate([
            'curriculum_id' => 'required|string',
            'course_id' => 'required|integer',  // Ensure course_id is also sent
            'excel_file' => 'required|file|mimes:xlsx,xls',
        ]);
    
        $curriculumId = $request->input('curriculum_id');
        $courseId = $request->input('course_id'); // Get the selected course ID
        $userId = Auth::id(); // Get the currently authenticated user ID
    
        // Retrieve all department IDs the evaluator belongs to from the department_user table
        $evaluatorDepartmentIds = DB::table('department_user')
            ->where('user_id', $userId)
            ->pluck('department_id')->toArray();
    
        if (empty($evaluatorDepartmentIds)) {
            toastr()->timeOut(5000)->closeButton()->addError('Evaluator department not found.');
            return redirect()->back();
        }
    
        // Ensure the student belongs to the evaluator's department and the selected course
        $studentsInEvaluatorDepartmentsAndCourse = User::whereIn('department_id', $evaluatorDepartmentIds)
            ->where('course_id', $courseId) // Add condition to ensure the student is from the selected course
            ->pluck('school_id')
            ->toArray();
    
        $import = new AssignCurriculumImport($curriculumId, $studentsInEvaluatorDepartmentsAndCourse);
    
        try {
            Excel::import($import, $request->file('excel_file'));
    
            $updatedCount = $import->getUpdatedCount();
            if ($updatedCount > 0) {
                sweetalert()->success("Curriculum assigned to $updatedCount students successfully.");
            } else {
                toastr()->timeOut(5000)->closeButton()->addError('No students were updated. Ensure the students are in the selected course and department.');
            }
        } catch (\Exception $e) {
            toastr()->timeOut(5000)->closeButton()->addError('An error occurred: ' . $e->getMessage());
        }
    
        return redirect()->back();
    }
    


    
    

    public function update_student_account(Request $request)
    {
        // Validate the incoming request to ensure all necessary fields are present
        $request->validate([
            'academic_year' => 'required|string',
            'department_id' => 'required|integer',
            'excel_file' => 'required|file|mimes:xlsx,xls',
        ]);
    
        $file = $request->file('excel_file');
        $department_id = $request->input('department_id');
        $userId = Auth::id();
    
        // Get the list of departments the current user has access to
        $allowedDepartments = DB::table('department_user')
            ->where('user_id', $userId)
            ->pluck('department_id')
            ->toArray();
    
        // Check if the user has permission to update this department
        if (!in_array($department_id, $allowedDepartments)) {
            toastr()->timeOut(5000)->closeButton()->addError('Unauthorized access to department');
            return redirect()->back();
        }
    
        try {
            // Import logic focused only on updating the academic year
            $import = new UpdateStudentAccountImport(
                $request->academic_year,  // Pass only the academic year
                $department_id            // Pass the department_id
            );
            Excel::import($import, $file);
    
            // Retrieve the count of updated students from the import process
            $updatedCount = $import->getUpdatedCount();
    
            // Notify the user of success or if no records were updated
            if ($updatedCount > 0) {
                sweetalert()->success("Student records updated successfully. {$updatedCount} students had their academic year updated.");
            } else {
                toastr()->timeOut(5000)->closeButton()->addInfo('No students were updated. This might be because the students do not belong to the selected department.');
            }
        } catch (\Exception $e) {
            // Log any exceptions and notify the user
            toastr()->timeOut(5000)->closeButton()->addError('There was an error updating student records. Please try again.');
            Log::error($e->getMessage());
        }
    
        return redirect()->back();
    }
    
    

    
















    public function view_pending_enrollees()
    {
        $user = Auth::user();
    
        // Fetch the department IDs associated with the authenticated user
        $userDepartmentIds = $user->departments()->pluck('departments.id')->toArray();
    
        // Check if the user has any department IDs
        if (empty($userDepartmentIds)) {
            // If no department IDs, return an empty collection or handle as needed
            $advisings = collect(); // Or handle it differently based on your logic
        } else {
            // Fetch advising records with user relationships for the relevant departments
            $advisings = Advising::with('user')
                ->where('advising_status', 'Approved')
                ->whereHas('user', function ($query) use ($userDepartmentIds) {
                    $query->whereIn('department_id', $userDepartmentIds);
                })
                ->get();
        }
    
        return view('evaluator.view_pending_enrollees', compact('advisings'));
    }
    

    
    public function view_enrolled_students()
    {
        $user = Auth::user();
    
        // Fetch the department IDs associated with the authenticated user
        $userDepartmentIds = $user->departments()->pluck('departments.id')->toArray();
    
        // Check if the user has any department IDs
        if (empty($userDepartmentIds)) {
            // If no department IDs, return an empty collection or handle as needed
            $advisings = collect(); // Or handle it differently based on your logic
        } else {
            // Fetch advising records with user relationships for the relevant departments
            $advisings = Advising::with('user')
                ->where('advising_status', 'Enrolled')
                ->whereHas('user', function ($query) use ($userDepartmentIds) {
                    $query->whereIn('department_id', $userDepartmentIds);
                })
                ->get();
        }
    
        return view('evaluator.view_enrolled_students', compact('advisings'));
    }
    

    


    public function enrollee_advising_details($id)
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
            toastr()->timeOut(5000)->closeButton()->addError('Unauthorized access to this enrollee advising detail.');
            return redirect()->back();
        }
    
        $unitLimit = $advising->unit_limit;
    
        // Retrieve subjects with pivot data
        $withinLimitSubjects = $advising->subjects()->wherePivot('status', 'within_limit')->get();
        $exceedingLimitSubjects = $advising->subjects()->wherePivot('status', 'exceeding_limit')->get();
    
        return view('evaluator.enrollee_advising_details', compact(
            'advising',
            'withinLimitSubjects',
            'exceedingLimitSubjects',
            'unitLimit'
        ));
    }
    

    
    public function enrolled_student_details($id)
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
            toastr()->timeOut(5000)->closeButton()->addError('Unauthorized access to this enrolled student detail.');
            return redirect()->back();
        }
    
        $unitLimit = $advising->unit_limit;
    
        // Retrieve subjects with pivot data
        $withinLimitSubjects = $advising->subjects()->wherePivot('status', 'within_limit')->get();
        $exceedingLimitSubjects = $advising->subjects()->wherePivot('status', 'exceeding_limit')->get();
    
        return view('evaluator.enrolled_student_details', compact(
            'advising',
            'withinLimitSubjects',
            'exceedingLimitSubjects',
            'unitLimit'
        ));
    }
    


    


    // public function EnrollStudent(Request $request, $advisingId)
    // {
    //     // Fetch the advising instance
    //     $advising = Advising::findOrFail($advisingId);

    //     // Set the advising status to 'Cancelled'
    //     $advising->update(['advising_status' => 'Enrolled']);

    //     // Update grades to null for the corresponding subjects
    //     Grade::where('user_id', $advising->user_id)->update(['grade' => "Enrolled"]);

    //     sweetalert()->success('Student Enrolled successfully!');
    //     return redirect()->route('view_pending_enrollees');
    // }
    
    public function EnrollStudent(Request $request, $advisingId)
    {
        // Fetch the advising instance
        $advising = Advising::findOrFail($advisingId);
    
        // Set the advising status to 'Enrolled'
        $advising->update(['advising_status' => 'Enrolled']);
    
        // Update grades to 'Enrolled' only for subjects that are 'Approved'
        $approvedGrades = Grade::where('user_id', $advising->user_id)
                                ->where('grade', 'Approved') // Change this line to filter only approved subjects
                                ->get();
    
        foreach ($approvedGrades as $grade) {
            $grade->update(['grade' => 'Enrolled']);
        }
    
        // Get all approved subject IDs for the student from the grades table
        $subjectIds = $approvedGrades->pluck('subject_id');
    
        // Fetch the years for these subjects from the curriculum_subjects table
        $subjectYears = CurriculumSubject::whereIn('subject_id', $subjectIds)->pluck('year')->toArray(); // Convert to array
    
        // Filter out null or empty values
        $subjectYears = array_filter($subjectYears, function ($year) {
            return is_string($year) && !empty($year);
        });
    
        // Count the occurrences of each year
        $yearCounts = array_count_values($subjectYears);
    
        // Determine the year with the most subjects
        if (!empty($yearCounts)) {
            // Get the year with the highest count
            $mostCommonYear = array_search(max($yearCounts), $yearCounts);
    
            // Update the student's year level based on the most common year
            User::where('id', $advising->user_id)->update(['year' => $mostCommonYear]);
        }
    
        // Success message
        sweetalert()->success('Student enrolled successfully and year level updated!');
        
        return redirect()->route('view_pending_enrollees');
    }
    
    
    
            public function viewTorRequests()
        {
            $torRequests = TorRequest::with('user')->where('status', 'Pending')->get();

            return view('evaluator.view_tor_requests', compact('torRequests'));
        }

        


        public function printTOR($id)
        {
            // Fetch the TOR request data along with the user's curriculum
            $torRequest = TorRequest::with([
                'user',
                'user.curriculum.curriculumSubjects.subject' // Eager load the curriculum subjects with their corresponding subjects
            ])->find($id);
        
            // Check if the TOR request was found
            if (!$torRequest) {
                return redirect()->back()->withErrors(['error' => 'TOR request not found.']);
            }
        
            // Prepare the data for the PDF view
            $user = $torRequest->user;
        
            // Check if the user or their curriculum exists
            if (!$user || !$user->curriculum) {
                return redirect()->back()->withErrors(['error' => 'User or curriculum not found.']);
            }
        
            $curriculumSubjectsCollection = $user->curriculum->curriculumSubjects; // Retrieve curriculum subjects
            $grades = $user->grades; // Retrieve all grades
        
            // Group curriculum subjects by year and year_term
            $groupedSubjects = [];
            foreach ($curriculumSubjectsCollection as $curriculumSubject) {
                $year = $curriculumSubject->year;
                $yearTerm = $curriculumSubject->year_term;
        
                // Initialize the array for the year if it doesn't exist
                if (!isset($groupedSubjects[$year])) {
                    $groupedSubjects[$year] = [];
                }
        
                // Initialize the array for the semester if it doesn't exist
                if (!isset($groupedSubjects[$year][$yearTerm])) {
                    $groupedSubjects[$year][$yearTerm] = []; // Create the semester array if it doesn't exist
                }
        
                // Add the subject to the appropriate year and semester
                $groupedSubjects[$year][$yearTerm][] = $curriculumSubject;
            }
        
            // Use the PDF facade to include curriculum subjects and grades
            $pdf = PDF::loadView('pdfs.tor', compact('torRequest', 'groupedSubjects', 'grades', 'user'));
        
            // Return the generated PDF to the browser
            return $pdf->download('tor-request.pdf');
        }
        
        
}    
