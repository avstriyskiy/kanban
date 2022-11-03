<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Task;

class TaskOverdue extends Mailable
{
    use Queueable, SerializesModels;

    public $task;

    /**
     * Create a new message instance.
     * @param App\Models\Task $task
     * @return void
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('taskoverdue@example.com', 'kanban.wtf')
            ->view('emails.taskoverdue')
            ->with([
                'taskName' => $this->task->name,
                'taskDeadline' => $this->task->deadline,
                'taskId' => $this->task->id,
            ]);
    }

}
