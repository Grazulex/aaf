<?php

namespace App\Http\Livewire;

use App\Models\Party;
use Livewire\Component;
use Carbon\Carbon;

class MemberList extends Component
{
    protected $listeners = ['update-list-member' => 'render'];

    public function render()
    {
        $parties = Party::where('room_id', 1)
            ->with(['member', 'battles', 'thefts'])
            ->orderBy('is_dead', 'ASC')
            ->orderBy('healt', 'DESC')

            ->get();


        return view('livewire.member-list', compact('parties'));
    }
}
