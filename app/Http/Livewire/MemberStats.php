<?php

namespace App\Http\Livewire;

use App\Models\Party;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MemberStats extends Component
{
    public function render()
    {
        $user = User::where('id', Auth::user()->id)->first();
        $party = Party::where('room_id', 1)
            ->where('member_id', Auth::user()->id)
            ->with(['member', 'battles', 'thefts'])
            ->first();

        return view('livewire.member-stats', compact('user', 'party'));
    }
}
