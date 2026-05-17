<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SuspiciousLoginNotification extends Notification
{
    public function __construct(
        public string $ip,
        public ?string $previousIp,
        public $time,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('auth.suspicious_login_subject'))
            ->line(__('auth.suspicious_login_intro'))
            ->line(__('auth.suspicious_login_ip', ['ip' => $this->ip]))
            ->line(__('auth.suspicious_login_previous_ip', ['ip' => $this->previousIp ?? __('auth.unknown')]))
            ->line(__('auth.suspicious_login_time', ['time' => $this->time]))
            ->line(__('auth.suspicious_login_action'));
    }
}
