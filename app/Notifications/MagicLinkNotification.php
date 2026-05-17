<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MagicLinkNotification extends Notification
{
    public function __construct(public string $token) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('login.magic.verify', ['token' => $this->token]);

        return (new MailMessage)
            ->subject(__('auth.magic_link_subject'))
            ->line(__('auth.magic_link_intro'))
            ->action(__('auth.magic_link_action'), $url)
            ->line(__('auth.magic_link_expires', ['minutes' => 15]))
            ->line(__('auth.magic_link_ignore'));
    }
}
