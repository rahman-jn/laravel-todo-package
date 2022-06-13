<?php
 
namespace Rahman\Todos\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;

 /**
  * @group Listeners
  *This listener fired automatically after Email notification dispatched
  */
class LogNotification implements ShouldQueue
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
     * @param  \App\Events\OrderShipped  $event
     * @return void
     */
    public function handle()
    {
        
        Log::channel('application')->error("Listener");

    }
}