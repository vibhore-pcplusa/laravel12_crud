<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
// use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
// Import the Log facade
use Illuminate\Support\Facades\Log; // <-- ADD THIS

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $message;

    public function __construct($message)
    {
        // Validate message is not null or empty
        if (is_null($message) || (is_string($message) && trim($message) === '')) {
            Log::error('❌ MessageSent Event received null or empty message.', ['message' => $message]);
        }else
        {

        // --- 💡 Log: Event Instantiated ---
        Log::info('📦 MessageSent Event instantiated in PHP.', [
            'message_content' => $message,
            'channel' => 'chat-channel'
        ]);
        
        $this->message = $message;
        }
    }

    public function broadcastOn(): Channel
    {
        // --- 💡 Log: Channel determined ---
        // This confirms the method is called and the channel name is correct
        Log::debug('🔑 Broadcasting on channel: chat-channel');
        
        return new Channel('chat-channel');
    }

    public function broadcastWith()
    {
        // This log confirms the payload is being prepared correctly
        Log::debug('📋 Preparing broadcast payload.', ['payload' => ['message' => $this->message]]);

        return ['message' => $this->message];
    }

    // return a simple client-side event name
    public function broadcastAs(): string
    {
        return 'MessageSent';
    }
}