<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OtpLoginNotification extends Notification
{
    public function __construct(public string $otp) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('auth.otp_subject'))
            ->greeting(__('Hello!'))
            ->line(__('auth.otp_intro'))
            ->line(__('auth.otp_code'))
            ->line('**'.$this->otp.'**')
            ->line(__('auth.otp_expires'))
            ->line(__('auth.otp_ignore'));
    }
}
