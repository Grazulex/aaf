<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\User;
use App\Services\Friends\CreateFriendService;
use App\Services\Parties\CreatePartyService;
use App\Services\Potions\GetPotionService;
use App\Services\Potions\UsePotionService;
use App\Services\Sleeps\CreateSleepService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PotionTest extends TestCase
{
    use RefreshDatabase;

    public function test_quantity_potion_when_get(): void
    {
        $member = User::factory()->create();
        $room = Room::factory()->create();
        $partyService = new CreatePartyService($room, $member);
        $party = $partyService->create();

        $potionService = new GetPotionService($party);
        $newPotionQuantity = $potionService->calculateNewPotionCount();

        $this->assertEquals(4, $newPotionQuantity);
    }

    public function test_have_potion_when_default(): void
    {
        $member = User::factory()->create();
        $room = Room::factory()->create();
        $partyService = new CreatePartyService($room, $member);
        $party = $partyService->create();
        $potionService = new UsePotionService(party: $party, potion: 5000);

        $this->assertEquals(1, $potionService->havePotion());
    }

    public function test_quantity_potion_when_used(): void
    {
        $member = User::factory()->create();
        $room = Room::factory()->create();
        $partyService = new CreatePartyService(room: $room, member: $member);
        $party = $partyService->create();
        $potionService = new UsePotionService(party: $party);

        $this->assertEquals(2, $potionService->calculateNewPotionCount());
    }

    public function test_healt_when_used_when_sleep(): void
    {
        $member = User::factory()->create();
        $room = Room::factory()->create();
        $partyService = new CreatePartyService(room: $room, member: $member);
        $party = $partyService->create();
        $sleepService = new CreateSleepService($party);
        $party = $sleepService->create();

        $potionService = new UsePotionService(party: $party, potion: 5000);

        $this->assertEquals(100000, $potionService->calculateNewHealt());
    }

    public function test_healt_when_used_without_friend(): void
    {
        $member = User::factory()->create();
        $room = Room::factory()->create();
        $partyService = new CreatePartyService(room: $room, member: $member);
        $party = $partyService->create();
        $potionService = new UsePotionService(party: $party, potion: 5000);

        $this->assertEquals(105000, $potionService->calculateNewHealt());
    }

    public function test_healt_when_used_with_friend(): void
    {
        $member = User::factory()->create();
        $friend1 = User::factory()->create();
        $friend2 = User::factory()->create();
        $room = Room::factory()->create();
        $partyService = new CreatePartyService($room, $member);
        $partyAttacker = $partyService->create();
        $partyServiceFriend1 = new CreatePartyService($room, $friend1);
        $partyServiceFriend1->create();
        $partyServiceFriend2 = new CreatePartyService($room, $friend2);
        $partyfriend = $partyServiceFriend2->create();
        $friendService = new CreateFriendService($partyAttacker, $partyfriend);
        $friendService->create();

        $potionService = new UsePotionService(party: $partyAttacker, potion: 5000);

        $this->assertEquals(102500, $potionService->calculateNewHealt());
    }
}
