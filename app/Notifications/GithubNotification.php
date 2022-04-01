<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Channels\BroadcastChannel;
use Illuminate\Notifications\Channels\DatabaseChannel;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class GithubNotification extends Notification implements ShouldBroadcast
{
    use Queueable, SerializesModels, InteractsWithSockets;

    public function __construct(public object $notification)
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
        return [DatabaseChannel::class, BroadcastChannel::class];
    }

    public function toArray($notifiable)
    {
        $data = json_decode(json_encode($this->notification->subject), true);
        return array_merge($data, [
            'name' => $this->notification->repository->name,
            'full_name' => $this->notification->repository->full_name,
        ]);
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user.'.User::first()->id);
    }
}
