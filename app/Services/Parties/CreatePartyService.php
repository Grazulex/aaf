<?php

namespace App\Services\Parties;

use App\Models\Party;
use App\Models\Room;
use App\Models\User;

class CreatePartyService
{
    private Room $room;
    private User $member;
    private mixed $armor;
    private mixed $potion;
    private mixed $step;

    public function __construct(Room $room, User $member, int $armor = null, int $potion = null, int $step = null)
    {
        $this->room = $room;
        $this->member = $member;
        $this->armor = $armor;
        $this->potion = $potion;
        $this->step = $step;
    }

    public function create(): Party
    {
        $add = [];
        if ($this->armor) {
            $add['armor'] = $this->armor;
        }
        if ($this->potion) {
            $add['potion'] = $this->potion;
        }
        if ($this->step) {
            $add['step'] = $this->step;
        }
        return Party::create(array_merge([
            'room_id' => $this->room->id,
            'member_id' => $this->member->id
        ], $add));
    }
}
