<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdviserAccountCreated extends Notification
{
    use Queueable;

    protected string $password;

    /**
     * Create a new notification instance.
     */
    public function __construct($user_info)
    {
        $this->password = $user_info["temp_password"];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
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
                    ->line('Your temporary password is: ')
                    ->line( $this->password )
                    ->line( 'You will need to reset it the first time you sign in')
                    ->action('Click here to join', url(env('APP_URL')));
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
