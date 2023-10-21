<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClassCanceledNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Array $details)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $name = ucfirst($notifiable->name);

        return (new MailMessage)
                    ->subject('Sorry, your class was canceled')
                    ->greeting("Hey {$name},")
                    ->line("We're sorry to inform you that your {$this->details['className']} class on {$this->details['classDateTime']->format('F jS')} at {$this->details['classDateTime']->format('g:i a')} was canceled by the instructor.")
                    ->line("Check the schedule and book another, thank you!")
                    //->line('We\'re sorry to inform you that your class was canceled by the instructor. Check the schedule and book another, thank you!')
                    ->action('Book another class', url('/student/book'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
