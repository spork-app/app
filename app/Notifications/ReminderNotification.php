<?php

namespace App\Notifications;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;
use Spork\Calendar\Models\RepeatEvent;

class ReminderNotification extends Notification implements ShouldBroadcast
{
    use Queueable, SerializesModels, InteractsWithSockets;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(public RepeatEvent $repeatEvent)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line(sprintf('You have a reminder for %s', $this->repeatEvent->name))
                    ->action('View the event', url('/'))
                    ->line('It occurs at: ' . $this->repeatEvent->nextOccurrence()->format('F j, Y H:i a'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->repeatEvent->toArray();
    }

    
    public function broadcastOn()
    {
        return new PrivateChannel('user.'.$this->repeatEvent->user_id);
    }
}
