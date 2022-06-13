<?php

namespace Rahman\Todos\Listeners;


use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Rahman\Todos\Events\TaskClosed;

/**
 * @group Listeners
 * This listener dispatches after task status changed to closed.
 */
class NotifyTaskClosed
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TaskClosed  $event
     * @return void
     */
    public function handle(TaskClosed $event)
    {
        Log::channel('application')->info("Your task closed: ".$event->task->title);

    }
}
