<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FollowupAssigned extends Notification
{
    use Queueable;

    private $survey;

    /**
     * Create a new notification instance.
     */
    public function __construct($survey)
    {
        $this->survey = $survey;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'You have been assigned a new follow-up for ' . ($this->survey->namaLengkap ?? 'a prospect'),
            'url' => route('followups.index')
        ];
    }
}
