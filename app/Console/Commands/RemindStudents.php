<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\RemindStudentsNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class RemindStudents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:remind-students';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remind students to book a class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $students = User::where('role', 'student')
            ->whereDoesntHave('bookings', function ($query) {
                $query->where('date_time', '>', now());
            })->select('name', 'email')->get();

        Notification::send($students, new RemindStudentsNotification);
    }
}
