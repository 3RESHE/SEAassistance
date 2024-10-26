<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\Advising;
use App\Models\TorRequest;
use Illuminate\Http\Request;
use App\Models\AdvisingDetail;
use Illuminate\Support\Carbon;
use App\Models\CurriculumSubject;
use App\Models\SubjectCorequisite;
use Illuminate\Support\Collection;
use App\Models\SubjectPrerequisite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Console\View\Components\Alert;

class StudentController extends Controller
{

public function index()
{
    // Retrieve notifications for the authenticated user
    $notifications = auth()->user()->notifications()->orderBy('created_at', 'desc')->get();
    
    return view('student.index', compact('notifications'));
}





public function view_subjects()
{
    $user = Auth::user();
    
    // Retrieve the curriculum and related subjects
    $curriculum = $user->curriculum;

    // Initialize an empty collection for subjects
    $subjects = collect();

    // Check if the user has a curriculum and subjects
    if ($curriculum && $curriculum->subjects) {
        // Define custom sorting for terms
        $termOrder = [
            '1st_semester' => 1,
            '2nd_semester' => 2,
            'summer' => 3
        ];

        // Group subjects by year and term
        $subjects = $curriculum->subjects
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

    // Prepare grades for easy access
    // Ensure the enrolled_term is included in the grades data
    $grades = $user->grades->keyBy('subject_id');

    // Optionally, ensure that enrolled_term is accessible in the grades
    foreach ($grades as $grade) {
        // Assuming $grade has an enrolled_term property
        $grade->enrolled_term = $grade->enrolled_term ?? ''; // Default value if not set
    }

    return view('student.view_subjects', [
        'user' => $user,
        'subjects' => $subjects,
        'grades' => $grades,
        'curriculum' => $curriculum
    ]);
}

    
    // Change the visibility of this method
    protected function getTermOrder($term)
    {
        $termOrder = [
            '1st sem' => 1,
            '2nd sem' => 2,
            'summer' => 3
        ];
    
        return $termOrder[$term] ?? 4;
    }
    



    public function advise(Request $request)
    {
        $studentId = Auth::id();
    
        // Check if there is an advising record with status 'Processing'
        $existingAdvising = Advising::where('user_id', $studentId)
            ->where('advising_status', 'Processing')
            ->exists();
    
        $curriculumId = User::find($studentId)->curriculum_id;
        $currentYear = User::find($studentId)->year; // e.g., '1st', '2nd', '3rd', '4th'
        $currentSemester = User::find($studentId)->semester; // e.g., '1st_semester', '2nd_semester', 'summer'
    
        // Retrieve all subjects in the student's curriculum
        $curriculumSubjects = CurriculumSubject::where('curriculum_id', $curriculumId)->get();
    
        // Get the student's passed subjects
        $passedSubjectIds = Grade::where('user_id', $studentId)
            ->whereBetween('grade', [1.0, 3.0])
            ->pluck('subject_id')
            ->toArray();
    
        // Get subjects with grades not in the range [1.0, 3.0] (failed subjects)
        $problematicSubjectIds = Grade::where('user_id', $studentId)
            ->whereNotBetween('grade', [1.0, 3.0])
            ->pluck('subject_id')
            ->toArray();
    
        // Get subjects with grade == 'Approved'
        $approvedSubjects = Grade::where('user_id', $studentId)
            ->where('grade', 'Approved')
            ->pluck('subject_id')
            ->map(function ($subjectId) {
                return Subject::find($subjectId);
            })
            ->filter()
            ->values();
    
        // Calculate total units for each year and term in the curriculum
        $unitsPerYearTerm = [];
        foreach ($curriculumSubjects as $curriculumSubject) {
            $yearTermKey = $curriculumSubject->year . '_' . $curriculumSubject->year_term; // e.g., '1st_1st_semester'
            if (!isset($unitsPerYearTerm[$yearTermKey])) {
                $unitsPerYearTerm[$yearTermKey] = 0;
            }
            $unitsPerYearTerm[$yearTermKey] += $curriculumSubject->subject->units;
        }
    
        // Determine the unit limit for the student's current year and term
        $currentYearTermKey = $currentYear . '_' . $currentSemester;
        $unitLimit = $unitsPerYearTerm[$currentYearTermKey] ?? 0;
    
        // Initialize subject categorization arrays
        $advisableSubjects = [];
        $notOpenSubjects = []; // Subjects not open for this semester
        $notQualifiedSubjects = []; // Subjects not qualified for prerequisites
        $alreadyPassedSubjects = Subject::whereIn('id', $passedSubjectIds)->get(); // Already passed subjects
    
        foreach ($curriculumSubjects as $curriculumSubject) {
            $subjectTerm = $curriculumSubject->year_term;
    
            // Exclude already passed subjects
            if (in_array($curriculumSubject->subject_id, $passedSubjectIds)) {
                continue; // Skip if subject is already passed
            }
    
            // Check if the subject has been failed (problematic grade)
            if (in_array($curriculumSubject->subject_id, $problematicSubjectIds)) {
                // If the subject has been failed, allow it to be advised again
                $advisableSubjects[] = $curriculumSubject->subject;
                continue; // No need to check further for pre-requisites if it’s a retake
            }
    
            // Check if the subject belongs to the current semester
            if ($subjectTerm === $currentSemester) {
                // Check if the subject has pre-requisites or co-requisites
                $prerequisites = $curriculumSubject->subject->prerequisites;
                $corequisites = $curriculumSubject->subject->corequisites; // Co-requisites
    
                $canAdvise = true;
    
                // Check all pre-requisites
                foreach ($prerequisites as $prerequisite) {
                    if (!in_array($prerequisite->id, $passedSubjectIds)) {
                        $canAdvise = false;
                        $notQualifiedSubjects[] = $curriculumSubject->subject; // Add to not qualified
                        break;
                    }
                }
    
                // Check co-requisites if pre-requisites are satisfied
                if ($canAdvise) {
                    foreach ($corequisites as $corequisite) {
                        // Co-requisite should be either passed or also being advised
                        if (!in_array($corequisite->id, $passedSubjectIds) &&
                            !in_array($corequisite->id, array_column($advisableSubjects, 'id'))) {
                            $canAdvise = false;
                            $notQualifiedSubjects[] = $curriculumSubject->subject; // Add to not qualified
                            break;
                        }
                    }
                }
    
                // If all pre-requisites and co-requisites are satisfied, add subject to advisable subjects
                if ($canAdvise) {
                    $advisableSubjects[] = $curriculumSubject->subject;
                }
            } else {
                // Subject is not open for this semester
                $notOpenSubjects[] = $curriculumSubject->subject;
            }
        }
    
        // Get subjects with problem grades (failed subjects)
        $subjectsWithProblem = Subject::whereIn('id', $problematicSubjectIds)->get();
    
        // Separate advisable subjects based on the unit limits
        $withinLimitSubjects = [];
        $exceedingLimitSubjects = [];
        $accumulatedUnits = 0;
    
        foreach ($advisableSubjects as $subject) {
            if ($accumulatedUnits + $subject->units <= $unitLimit) {
                $withinLimitSubjects[] = $subject;
                $accumulatedUnits += $subject->units;
            } else {
                $exceedingLimitSubjects[] = $subject;
            }
        }
    
        if ($existingAdvising) {
            // If advising exists with status 'Processing', get the subjects from AdvisingDetail with status 'within_limit'
            $advisingId = Advising::where('user_id', $studentId)
                ->where('advising_status', 'Processing')
                ->first()->id;
            $withinLimitSubjectsFromAdvising = AdvisingDetail::where('advising_id', $advisingId)
                ->where('status', 'within_limit')
                ->get()
                ->pluck('subject');
    
            // Fetch enrolled subjects
            $enrolledSubjects = Grade::where('user_id', $studentId)
                ->where('grade', 'Enrolled')
                ->pluck('subject_id')
                ->map(function ($subjectId) {
                    return Subject::find($subjectId);
                })
                ->filter()
                ->values();
    
            return view('student.advising', [
                'withinLimitSubjects' => $withinLimitSubjectsFromAdvising,
                'exceedingLimitSubjects' => $exceedingLimitSubjects,
                'notOpenSubjects' => $notOpenSubjects,
                'notQualifiedSubjects' => $notQualifiedSubjects,
                'alreadyPassedSubjects' => $alreadyPassedSubjects,
                'enrolledSubjects' => $enrolledSubjects,
                'approvedSubjects' => $approvedSubjects, // Add this to pass approved subjects
                'totalUnitsAccumulated' => $accumulatedUnits,
                'unitLimit' => $unitLimit,
                'curriculum' => $curriculumId,
                'existingAdvising' => $existingAdvising,
                'advisingDetails' => $withinLimitSubjectsFromAdvising,
                'subjectsWithProblem' => $subjectsWithProblem,
            ]);
        } else {
            // If no advising record exists, get the subjects with grade 'Enrolled'
            $enrolledSubjects = Grade::where('user_id', $studentId)
                ->where('grade', 'Enrolled')
                ->pluck('subject_id')
                ->map(function ($subjectId) {
                    return Subject::find($subjectId);
                })
                ->filter()
                ->values();
    
            return view('student.advising', [
                'withinLimitSubjects' => $withinLimitSubjects,
                'exceedingLimitSubjects' => $exceedingLimitSubjects,
                'notOpenSubjects' => $notOpenSubjects,
                'notQualifiedSubjects' => $notQualifiedSubjects,
                'alreadyPassedSubjects' => $alreadyPassedSubjects,
                'enrolledSubjects' => $enrolledSubjects,
                'approvedSubjects' => $approvedSubjects, // Add this to pass approved subjects
                'totalUnitsAccumulated' => $accumulatedUnits,
                'unitLimit' => $unitLimit,
                'curriculum' => $curriculumId,
                'existingAdvising' => $existingAdvising,
                'advisingDetails' => null,
                'subjectsWithProblem' => $subjectsWithProblem,
            ]);
        }
    }
    
    
    
    

    
    
    

    public function submitAdvising(Request $request)
    {
        $request->validate([
            'unit_limit' => 'required|integer',
            'within_limit' => 'array',
            'exceeding_limit' => 'array',
            'already_passed' => 'array',
            'not_qualified' => 'array',
            'not_open' => 'array',
            'subjects_with_problems' => 'array',
        ]);
    
        // Create a new advising record
        $advising = Advising::create([
            'user_id' => Auth::id(),
            'unit_limit' => $request->input('unit_limit'),
            'advising_status' => 'Processing',
        ]);
    
        // Add subjects to AdvisingDetails
        $withinLimitSubjects = $request->input('within_limit', []);
        $exceedingLimitSubjects = $request->input('exceeding_limit', []);
        $alreadyPassedSubjects = $request->input('already_passed', []);
        $notQualifiedSubjects = $request->input('not_qualified', []);
        $notOpenSubjects = $request->input('not_open', []);
        $subjectsWithProblems = $request->input('subjects_with_problems', []);
    
        foreach ($withinLimitSubjects as $subjectId) {
            $subject = Subject::find($subjectId);
            if ($subject) {
                AdvisingDetail::create([
                    'advising_id' => $advising->id,
                    'subject_id' => $subjectId,
                    'status' => 'within_limit',
                    'units' => $subject->units,
                ]);
            }
        }
    
        foreach ($exceedingLimitSubjects as $subjectId) {
            $subject = Subject::find($subjectId);
            if ($subject) {
                AdvisingDetail::create([
                    'advising_id' => $advising->id,
                    'subject_id' => $subjectId,
                    'status' => 'exceeding_limit',
                    'units' => $subject->units,
                ]);
            }
        }
    
        foreach ($alreadyPassedSubjects as $subjectId) {
            $subject = Subject::find($subjectId);
            if ($subject) {
                AdvisingDetail::create([
                    'advising_id' => $advising->id,
                    'subject_id' => $subjectId,
                    'status' => 'already_passed',
                    'units' => $subject->units,
                ]);
            }
        }
    
        foreach ($notQualifiedSubjects as $subjectId) {
            $subject = Subject::find($subjectId);
            if ($subject) {
                AdvisingDetail::create([
                    'advising_id' => $advising->id,
                    'subject_id' => $subjectId,
                    'status' => 'not_qualified',
                    'units' => $subject->units,
                ]);
            }
        }
    
        foreach ($notOpenSubjects as $subjectId) {
            $subject = Subject::find($subjectId);
            if ($subject) {
                AdvisingDetail::create([
                    'advising_id' => $advising->id,
                    'subject_id' => $subjectId,
                    'status' => 'not_open',
                    'units' => $subject->units,
                ]);
            }
        }
    
        foreach ($subjectsWithProblems as $subjectId) {
            $subject = Subject::find($subjectId);
            if ($subject) {
                AdvisingDetail::create([
                    'advising_id' => $advising->id,
                    'subject_id' => $subjectId,
                    'status' => 'subjects_with_problems',
                    'units' => $subject->units,
                ]);
            }
        }
    
 
        sweetalert()->success('Advising successfully submitted!');
        return redirect()->back();
    }
    

    public function submitOverloadAdvising(Request $request)
    {
        $request->validate([
            'unit_limit' => 'required|integer',
            'within_limit' => 'array',
            'exceeding_limit' => 'array',
            'already_passed' => 'array',
            'not_qualified' => 'array',
            'not_open' => 'array',
            'subjects_with_problems' => 'array',
        ]);
    
        // Create a new advising record
        $advising = Advising::create([
            'user_id' => Auth::id(),
            'unit_limit' => $request->input('unit_limit'),
            'advising_status' => 'Processing',
            'load_status' => 'Overload',
        ]);
    
        // Add subjects to AdvisingDetails
        $withinLimitSubjects = $request->input('within_limit', []);
        $exceedingLimitSubjects = $request->input('exceeding_limit', []);
        $alreadyPassedSubjects = $request->input('already_passed', []);
        $notQualifiedSubjects = $request->input('not_qualified', []);
        $notOpenSubjects = $request->input('not_open', []);
        $subjectsWithProblems = $request->input('subjects_with_problems', []);
    
        foreach ($withinLimitSubjects as $subjectId) {
            $subject = Subject::find($subjectId);
            if ($subject) {
                AdvisingDetail::create([
                    'advising_id' => $advising->id,
                    'subject_id' => $subjectId,
                    'status' => 'within_limit',
                    'units' => $subject->units,
                ]);
            }
        }
    
        foreach ($exceedingLimitSubjects as $subjectId) {
            $subject = Subject::find($subjectId);
            if ($subject) {
                AdvisingDetail::create([
                    'advising_id' => $advising->id,
                    'subject_id' => $subjectId,
                    'status' => 'exceeding_limit',
                    'units' => $subject->units,
                ]);
            }
        }
    
        foreach ($alreadyPassedSubjects as $subjectId) {
            $subject = Subject::find($subjectId);
            if ($subject) {
                AdvisingDetail::create([
                    'advising_id' => $advising->id,
                    'subject_id' => $subjectId,
                    'status' => 'already_passed',
                    'units' => $subject->units,
                ]);
            }
        }
    
        foreach ($notQualifiedSubjects as $subjectId) {
            $subject = Subject::find($subjectId);
            if ($subject) {
                AdvisingDetail::create([
                    'advising_id' => $advising->id,
                    'subject_id' => $subjectId,
                    'status' => 'not_qualified',
                    'units' => $subject->units,
                ]);
            }
        }
    
        foreach ($notOpenSubjects as $subjectId) {
            $subject = Subject::find($subjectId);
            if ($subject) {
                AdvisingDetail::create([
                    'advising_id' => $advising->id,
                    'subject_id' => $subjectId,
                    'status' => 'not_open',
                    'units' => $subject->units,
                ]);
            }
        }
    
        foreach ($subjectsWithProblems as $subjectId) {
            $subject = Subject::find($subjectId);
            if ($subject) {
                AdvisingDetail::create([
                    'advising_id' => $advising->id,
                    'subject_id' => $subjectId,
                    'status' => 'subjects_with_problems',
                    'units' => $subject->units,
                ]);
            }
        }
    
 
        sweetalert()->success('Advising successfully submitted!');
        return redirect()->back();
    }


    // TO REQUEST

    public function requestTor(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();
    
        // Check if the user has made a TOR request within the last 30 days
        $lastRequest = $user->torRequests()->latest()->first(); // assuming 'torRequests' relationship
    
        if ($lastRequest && $lastRequest->created_at->gt(Carbon::now()->subDays(30))) {
            // If a request was made within the last 30 days, show an error message with Toastr
            toastr()->timeOut(5000)->closeButton()->addError('You can only request a grade evaluation once every 30 days.');
            return redirect()->back();
        }
    
        // Create a new TOR request with the user ID and status
        TorRequest::create([
            'user_id' => $user->id,
            'status' => 'Pending', // Set the request status as 'Pending'
        ]);
    
        sweetalert()->success('Your request has been submitted successfully.');
        return redirect()->back();
    }
    
}
