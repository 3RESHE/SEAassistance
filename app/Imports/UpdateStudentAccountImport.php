<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UpdateStudentAccountImport implements ToModel, WithHeadingRow
{
    protected $year;
    protected $semester;
    protected $academicYear;
    protected $departmentId; // Add departmentId
    protected $updatedCount = 0;

    public function __construct($academicYear, $departmentId)
    {
        $this->academicYear = $academicYear;
        $this->departmentId = $departmentId; // Initialize departmentId
    }

    public function model(array $row)
    {
        try {
            if (empty($row['school_id'])) {
                Log::warning('Missing school_id in row: ' . json_encode($row));
                return null;
            }

            $user = User::where('school_id', $row['school_id'])
                ->where('department_id', $this->departmentId) // Filter by department
                ->first();

            if ($user) {
                $user->update([
                    'academic_year' => $this->academicYear,
                ]);

                $this->updatedCount++;
                return $user;
            } else {
                Log::warning('User not found or department mismatch for school_id: ' . $row['school_id']);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Update Error: ' . $e->getMessage());
            return null;
        }
    }

    public function getUpdatedCount()
    {
        return $this->updatedCount;
    }
}
