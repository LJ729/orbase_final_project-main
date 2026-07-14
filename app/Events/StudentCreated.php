<?php

namespace App\Events;

use App\Models\Student;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StudentCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Student $student) {}

    public function broadcastOn(): array
    {
        return [new Channel('students')];
    }

    public function broadcastAs(): string
    {
        return 'student.created';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->student->id,
            'student_number' => $this->student->student_number,
            'first_name' => $this->student->first_name,
            'last_name' => $this->student->last_name,
            'email' => $this->student->email,
            'course' => $this->student->course,
            'year_level' => $this->student->year_level,
        ];
    }
}