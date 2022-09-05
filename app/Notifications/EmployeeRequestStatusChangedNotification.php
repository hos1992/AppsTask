<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmployeeRequestStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $req;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($req)
    {
        $this->req = $req;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line("request number {$this->req->id} status changed to '{$this->req->statusTxt}' ");
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => "request number {$this->req->id} status changed to '{$this->req->statusTxt}' ",
            'data' => $this->req,
        ];
    }
}
