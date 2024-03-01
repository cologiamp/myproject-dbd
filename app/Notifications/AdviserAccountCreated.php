<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdviserAccountCreated extends Notification
{
    use Queueable;

    protected string $password;

    /**
     * Create a new notification instance.
     */
    public function __construct($temp_password)
    {
        $this->password = $temp_password;
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

//        ray($this)->purple();
        return (new MailMessage)
                    ->greeting('You have been invited to join Adviser Hub ')
                    ->line('Welcome to your new digital tool to significantly reduce administrative time for Advisers and Para-Planners. ')
                    ->line('Transforming the data-capture and efficiency of the report writing process for prospect clients. ')
                    ->line('What you’ll gain access to:')
                    ->lines([
                        '- Digital reports',
                        '- Automated PDF’s',
                        '- Interactive visual tools',
                    ])
                    ->line('Your temporary password is ' . $this->password. ', you will need to reset it the first time you sign in')
                    ->action('Click here to join', url(env('APP_URL').'/login'));
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
