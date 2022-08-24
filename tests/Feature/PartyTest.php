<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\User;
use App\Services\Parties\CreatePartyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PartyTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_value_party(): void
    {
        $member = User::factory()->create();
        $room = Room::factory()->create();
        $partyService = new CreatePartyService($room, $member);
        $party = $partyService->create();
        $this->assertEquals(100000, $party->healt);
        $this->assertEquals(0, $party->armor);
        $this->assertEquals(0, $party->step);
        $this->assertEquals(3, $party->potion);
        $this->assertEquals(0, $party->is_closed);
    }

    public function test_relation_room(): void
    {
        $member = User::factory()->create();
        $room = Room::factory()->create();
        $partyService = new CreatePartyService($room, $member);
        $party = $partyService->create();
        $this->assertEquals($member->name, $party->member->name);
        $this->assertEquals($room->name, $party->room->name);
    }
}
