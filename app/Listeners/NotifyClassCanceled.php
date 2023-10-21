<?php

namespace App\Listeners;

use App\Events\ClassCanceled;
use App\Jobs\NotifyClassCanceledJob;
use App\Mail\ClassCanceledMail;
use App\Notifications\ClassCanceledNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifyClassCanceled
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ClassCanceled $event): void
    {
        $students = $event->scheduledClass->students()->get();

        $className = $event->scheduledClass->classType->name;
        $classDateTime = $event->scheduledClass->date_time;

        $details = compact('className','classDateTime');

        // $students->each(function($student) use ($details) {
        //     Mail::to($student)->send(new ClassCanceledMail($details));
        // });

        // Notification::send($students, new ClassCanceledNotification($details));
        NotifyClassCanceledJob::dispatch($students, $details);
    }
}
