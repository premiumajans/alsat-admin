<?php


namespace App\Listeners;

use App\Events\ChatMessageEvent;
use App\Events\Messages\Send;
use App\Events\NewMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class ProcessChatMessage implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param ChatMessageEvent $event
     * @return void
     */
    public function handle(Send $event): void
    {
        broadcast(new Send($event->message));
        Log::info('Chat message processed: ' . $event->message);
    }
}
