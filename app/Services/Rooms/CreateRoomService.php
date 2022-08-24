<?php

namespace App\Services\Rooms;

use App\Models\Room;

class CreateRoomService
{
    private Room $room;

    public function __construct(Room $room)
    {
        $this->room = $room;
    }

    public function create(): Room
    {
        return $this->room;
    }
}
