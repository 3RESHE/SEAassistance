<?php

namespace App\Imports;

use App\Models\Subject;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SubjectsImport implements ToModel, WithHeadingRow
{
    protected $course_id;

    // Constructor to pass course_id from the controller
    public function __construct($course_id)
    {
        $this->course_id = $course_id;
    }

    public function model(array $row)
    {
        // Check if the subject code already exists for the same course_id
        $existingSubject = Subject::where('subject_code', $row['course_code'])
            ->where('course_id', $this->course_id)
            ->first();
    
        if ($existingSubject) {
            throw new \Exception("Duplicate entry for this course: " . $row['course_code']);
        }
    
        return new Subject([
            'course_id' => $this->course_id, // Use the course_id passed in constructor
            'subject_code' => $row['course_code'], // Column in Excel
            'descriptive_title' => $row['descriptive_title'], // Column in Excel
            'units' => $row['units'], // Column in Excel
        ]);
    }
}
