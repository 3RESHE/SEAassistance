<?php
namespace App\Imports;

use App\Models\User;
use App\Mail\SendPassword;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToModel, WithHeadingRow
{
    protected $departmentId;
    protected $courseId;
    protected $year;

    public function __construct($departmentId, $courseId, $year)
    {
        $this->departmentId = $departmentId;
        $this->courseId = $courseId;
        $this->year = $year;
    }

    public function model(array $row)
    {
        try {
            // Check if required columns are present and not empty
            if (empty($row['school_id']) || empty($row['email']) || empty($row['first_name']) || empty($row['last_name'])) {
                // Optionally, log the error or handle it as needed
                Log::warning('Missing required data in row: ' . json_encode($row));
                return null;
            }

            // Generate a random password
            $password = Str::random(8);

            // Create the user record with additional form data
            $user = User::create([
                'school_id' => $row['school_id'],
                'email' => $row['email'],
                'name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'middle_name' => $row['middle_name'] ?? null,
                'name_suffix' => $row['name_suffix'] ?? null,
                'role' => 'student',
                'user_status' => 'New User',
                'department_id' => $this->departmentId,
                'course_id' => $this->courseId,
                'year' => $this->year,
                'password' => Hash::make($password),
            ]);

            // Send the password to the user's email
            Mail::to($user->email)->send(new SendPassword($password, $user->email));

            return $user;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            Log::error('Import Error: ' . $e->getMessage());
            return null;
        }
    }
}
