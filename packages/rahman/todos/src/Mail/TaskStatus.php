<?php

namespace Rahman\Todos\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Rahman\Todos\Models\Task;

class TaskStatus extends Mailable
{
    use Queueable, SerializesModels;

    private $task;

    /**
     * Create a new message instance.
     *@param  Rahman\Todos\Models\Task
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
        return $this->from('info@taskmanager.com')
                ->with([
                    'task' => $this->task
                ])
                ->view('todos::mails.task.status');
            }
}
