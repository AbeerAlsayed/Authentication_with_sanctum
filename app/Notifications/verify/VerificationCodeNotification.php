<?php

namespace App\Notifications\verify;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerificationCodeNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */

    public $code,$minutesRemaining;
    public function __construct($code , $minutesRemaining)
    {
        $this->code = $code;
        $this->minutesRemaining = $minutesRemaining;
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
        return (new MailMessage)
            ->subject("Verification Code")
            ->greeting("Hi {$notifiable->name} ,")
            ->line("please verify your email using this code ({$this->code}) it will be expired after {$this->minutesRemaining} minutes")
            ->action('verify Email', url('/dashboard'))
            ->line('Thank you for using our application');
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
