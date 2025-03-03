<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketRequest implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $ticket;
    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('my-channel'),
        ];
    }

    public function broadcastAs()
    {
        return 'my-event';
    }

    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->ticket['user_id'], 
            'message' => 'A new ticket has been created!',
        ];
    }
}
