<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AssignCurriculumImport implements ToModel, WithHeadingRow
{
    protected $curriculumId;
    protected $validSchoolIds;
    protected $updatedCount = 0;

    public function __construct($curriculumId, $validSchoolIds)
    {
        $this->curriculumId = $curriculumId;
        $this->validSchoolIds = $validSchoolIds;
    }

    public function model(array $row)
    {
        if (in_array($row['school_id'], $this->validSchoolIds)) {
            $user = User::where('school_id', $row['school_id'])->first();
            
            if ($user) {
                // Assign the curriculum_id to the student
                $user->curriculum_id = $this->curriculumId;
                $user->save();
                $this->updatedCount++;
            }
        }
    }

    public function getUpdatedCount()
    {
        return $this->updatedCount;
    }
}
