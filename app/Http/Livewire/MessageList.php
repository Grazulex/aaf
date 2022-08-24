<?php

namespace App\Http\Livewire;

use App\Models\Message;
use Livewire\Component;

class MessageList extends Component
{
    public function render()
    {
        $messages = Message::where('room_id', 1)
            ->latest()
            ->limit(100)
            ->get();

        return view('livewire.message-list', compact('messages'));
    }
}
