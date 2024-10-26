<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class GradeUpdated extends Notification
{
    use Queueable;

    protected $userId;
    protected $subjectId;
    protected $subjectName;
    protected $grade;
    protected $inc_deadline;

    public function __construct($userId, $subjectId, $subjectName, $grade, $inc_deadline = null)
    {
        $this->userId = $userId;
        $this->subjectId = $subjectId;
        $this->subjectName = $subjectName;
        $this->grade = $grade;
        $this->inc_deadline = $inc_deadline ? Carbon::parse($inc_deadline) : null; // Ensure it's a Carbon instance
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        // Prepare message based on the grade and deadline
        if ($this->grade === 'INC') {
            // Calculate the remaining days as an integer
            $daysRemaining = $this->inc_deadline ? intval(Carbon::now()->diffInDays($this->inc_deadline)) : null;

            // Create a dynamic message
            if ($daysRemaining > 0) {
                $message = "Your {$this->subjectName} grade is incomplete. Please fix it by {$this->inc_deadline->format('Y-m-d')}. You have {$daysRemaining} days to complete it.";
            } else {
                $message = "Your {$this->subjectName} grade is incomplete. The deadline has passed.";
            }
        } else {
            $message = "Your {$this->subjectName} has a grade now.";
        }

        return [
            'user_id' => $this->userId,
            'subject_id' => $this->subjectId,
            'message' => $message,
            'url' => url("view_subjects/{$this->subjectId}"), // Provide a specific URL based on subject ID
            'created_at' => now(),
        ];
    }
}
