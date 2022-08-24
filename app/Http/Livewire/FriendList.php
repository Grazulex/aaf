<?php

namespace App\Http\Livewire;

use App\Models\Friend;
use App\Models\Party;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FriendList extends Component
{
    protected $listeners = ['update-list-friend' => 'render'];

    public function render()
    {
        $me = Party::where('room_id', 1)
            ->where('member_id', Auth::user()->id)
            ->first();

        $friends = Friend::where('party_id', $me->id)
            ->with('friend')
            ->get();

        return view('livewire.friend-list', compact('friends'));
    }
}
