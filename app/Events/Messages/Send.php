<?php

namespace App\Events\Messages;

use Illuminate\Broadcasting\{PrivateChannel, InteractsWithSockets, PresenceChannel, Channel};
use Illuminate\{Contracts\Broadcasting\ShouldBroadcast, Foundation\Events\Dispatchable, Queue\SerializesModels};

class Send implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function broadcastOn(): array
    {
        //App.User.{id}
        return [
            new PrivateChannel('App.User.2'),
        ];
    }
}
