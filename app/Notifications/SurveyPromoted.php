<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SurveyPromoted extends Notification
{
    use Queueable;

    private $customer;
    private $survey;

    /**
     * Create a new notification instance.
     */
    public function __construct($customer, $survey)
    {
        $this->customer = $customer;
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
            'message' => 'Survey for ' . $this->customer->name . ' has been promoted to Prospect.',
            'url' => route('prospects')
        ];
    }
}
