<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\URL;

class ResetPassword extends Notification
{
    use Queueable;

    public $token;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */

    public function toMail($notifiable)
    {
<<<<<<< HEAD
    // Generate a normal (unsigned) route to the Livewire reset form. Include email as query param.
    $url = URL::route('password.reset', ['token' => $this->token, 'email' => $notifiable->email]);
=======
        $url = URL::temporarySignedRoute('reset-password', now()->addHours(12), ['id' => $this->token]);
>>>>>>> origin/draft
        return (new MailMessage)
            ->subject('Reset Password')
            ->line('Hello!')
            ->line('You are receiving this email because someone has requested to reset the password for your account.')
            ->action('Reset Password', $url)
            ->line("If you did not request this, please ignore this email.")
            ->line('Thank you!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
