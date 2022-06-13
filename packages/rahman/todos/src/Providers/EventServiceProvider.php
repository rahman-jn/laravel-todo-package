<?php

namespace Rahman\Todos\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Rahman\Todos\Events\TaskClosed;
use Rahman\Todos\Listeners\NotifyTaskClosed;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        TaskClosed::class => [
            NotifyTaskClosed::class,
        ],
        'Illuminate\Notifications\Events\NotificationSent' => [
            'Rahman\Todos\Listeners\LogNotification',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
