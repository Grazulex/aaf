<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Services\Rooms\CreateRoomService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomTest extends TestCase
{
    use RefreshDatabase;

    public function test_if_room_closed(): void
    {
        $room = Room::factory()->make();
        $roomService = new CreateRoomService($room);
        $room = $roomService->create();

        $this->assertEquals(0, $room->is_closed);
    }
}
