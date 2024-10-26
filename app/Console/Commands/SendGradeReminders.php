<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Console\Command;
use App\Notifications\GradeUpdated;

class SendGradeReminders extends Command
{
    protected $signature = 'grades:send-reminders';
    protected $description = 'Send reminders for incomplete grades';

    public function handle()
    {
        // Get the current date
        $now = Carbon::now();

        // Get users with incomplete grades
        $users = User::whereHas('grades', function($query) {
            $query->where('grade', 'INC');
        })->with('grades.subject')->get(); // Eager load grades and subjects

        foreach ($users as $user) {
            foreach ($user->grades as $grade) {
                // Check if the grade is incomplete and the inc_deadline is set
                if ($grade->grade === 'INC' && $grade->inc_deadline) {
                    $daysRemaining = $now->diffInDays($grade->inc_deadline);

                    // Check if the notification has already been sent in the last 7 days
                    $lastReminder = $user->notifications()
                        ->where('type', GradeUpdated::class)
                        ->where('data->subject_id', $grade->subject_id)
                        ->where('created_at', '>=', $now->subDays(7)) // Get reminders sent in the last 7 days
                        ->exists();

                    // Send reminder if there are days remaining and no recent reminder was sent
                    if ($daysRemaining > 0 && !$lastReminder) {
                        $user->notify(new GradeUpdated(
                            $user->id,
                            $grade->subject_id,
                            $grade->subject->descriptive_title,
                            $grade->grade,
                            $grade->inc_deadline // Pass the Carbon instance directly
                        ));
                    }
                }
            }
        }
    }
}
