<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\User;
use App\Services\Battles\CreateAttackService;
use App\Services\Friends\CreateFriendService;
use App\Services\Parties\CreatePartyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BattleTest extends TestCase
{
    use RefreshDatabase;

    public function test_value_attack(): void
    {
        $member = User::factory()->create();
        $friend1 = User::factory()->create();
        $room = Room::factory()->create();
        $partyService = new CreatePartyService($room, $member);
        $partyAttacker = $partyService->create();
        $partyServiceFriend1 = new CreatePartyService($room, $friend1);
        $partyOpponent = $partyServiceFriend1->create();

        $battleService = new CreateAttackService($partyAttacker, $partyOpponent);
        $battle = $battleService->create();

        $this->assertGreaterThanOrEqual(50, $battle->attack);
        $this->assertLessThanOrEqual(2500, $battle->attack);
    }

    public function test_healt_attacker_without_friend(): void
    {
        $member = User::factory()->create();
        $friend1 = User::factory()->create();
        $room = Room::factory()->create();
        $partyService = new CreatePartyService($room, $member);
        $partyAttacker = $partyService->create();
        $partyServiceFriend1 = new CreatePartyService($room, $friend1);
        $partyOpponent = $partyServiceFriend1->create();

        $battleService = new CreateAttackService($partyAttacker, $partyOpponent, 1000);
        $battleService->create();
        $newHeatlAttacker = $battleService->calculatNewHealtAttacker();

        $this->assertEquals(101100, $newHeatlAttacker);
    }

    public function test_healt_attacker_with_opponent_armor_greather_than_attack(): void
    {
        $member = User::factory()->create();
        $friend1 = User::factory()->create();
        $room = Room::factory()->create();
        $partyService = new CreatePartyService($room, $member);
        $partyAttacker = $partyService->create();
        $partyServiceFriend1 = new CreatePartyService($room, $friend1, armor: 2000);
        $partyOpponent = $partyServiceFriend1->create();

        $battleService = new CreateAttackService($partyAttacker, $partyOpponent, 1000);
        $battleService->create();
        $newHeatlAttacker = $battleService->calculatNewHealtAttacker();

        $this->assertEquals(100100, $newHeatlAttacker);
    }

    public function test_healt_attacker_with_opponent_armor_lower_than_attack(): void
    {
        $member = User::factory()->create();
        $friend1 = User::factory()->create();
        $room = Room::factory()->create();
        $partyService = new CreatePartyService($room, $member);
        $partyAttacker = $partyService->create();
        $partyServiceFriend1 = new CreatePartyService($room, $friend1, armor: 500);
        $partyOpponent = $partyServiceFriend1->create();

        $battleService = new CreateAttackService($partyAttacker, $partyOpponent, 1000);
        $battleService->create();
        $newHeatlAttacker = $battleService->calculatNewHealtAttacker();

        $this->assertEquals(101100, $newHeatlAttacker);
    }

    public function test_healt_attacker_with_friend(): void
    {
        $member = User::factory()->create();
        $friend1 = User::factory()->create();
        $friend2 = User::factory()->create();
        $room = Room::factory()->create();
        $partyService = new CreatePartyService($room, $member);
        $partyAttacker = $partyService->create();
        $partyServiceFriend1 = new CreatePartyService($room, $friend1);
        $partyOpponent = $partyServiceFriend1->create();
        $partyServiceFriend2 = new CreatePartyService($room, $friend2);
        $partyfriend = $partyServiceFriend2->create();
        $friendService = new CreateFriendService($partyAttacker, $partyfriend);
        $friendService->create();

        $battleService = new CreateAttackService($partyAttacker, $partyOpponent, 1000);
        $battleService->create();
        $newHeatlAttacker = $battleService->calculatNewHealtAttacker();

        $this->assertEquals(100550, $newHeatlAttacker);
    }

    public function test_healt_opponent(): void
    {
        $member = User::factory()->create();
        $friend1 = User::factory()->create();
        $room = Room::factory()->create();
        $partyService = new CreatePartyService($room, $member);
        $partyAttacker = $partyService->create();
        $partyServiceFriend1 = new CreatePartyService($room, $friend1);
        $partyOpponent = $partyServiceFriend1->create();

        $battleService = new CreateAttackService($partyAttacker, $partyOpponent, 1000);
        $battleService->create();
        $newHeatlOpponent = $battleService->calculatNewHealtOpponent();

        $this->assertEquals(99000, $newHeatlOpponent);
    }

    public function test_healt_opponent_with_armor(): void
    {
        $member = User::factory()->create();
        $friend1 = User::factory()->create();
        $room = Room::factory()->create();
        $partyService = new CreatePartyService(room: $room, member: $member);
        $partyAttacker = $partyService->create();
        $partyServiceFriend1 = new CreatePartyService(room: $room, member: $friend1, armor: 3000);
        $partyOpponent = $partyServiceFriend1->create();

        $battleService = new CreateAttackService(attacker: $partyAttacker, opponent: $partyOpponent, attack: 1000);
        $battleService->create();
        $newHeatlOpponent = $battleService->calculatNewHealtOpponent();

        $this->assertEquals(100000, $newHeatlOpponent);
    }

    public function test_armor_opponent_when_attack_lower(): void
    {
        $member = User::factory()->create();
        $friend1 = User::factory()->create();
        $room = Room::factory()->create();
        $partyService = new CreatePartyService($room, $member);
        $partyAttacker = $partyService->create();
        $partyServiceFriend1 = new CreatePartyService($room, $friend1, armor: 500);
        $partyOpponent = $partyServiceFriend1->create();

        $battleService = new CreateAttackService($partyAttacker, $partyOpponent, 1000);
        $battleService->create();
        $newHeatlOpponent = $battleService->calculatNewArmorOpponent();

        $this->assertEquals(250, $newHeatlOpponent);
    }

    public function test_armor_opponent_when_attack_greater(): void
    {
        $member = User::factory()->create();
        $friend1 = User::factory()->create();
        $room = Room::factory()->create();
        $partyService = new CreatePartyService($room, $member);
        $partyAttacker = $partyService->create();
        $partyServiceFriend1 = new CreatePartyService($room, $friend1, armor: 2500);
        $partyOpponent = $partyServiceFriend1->create();

        $battleService = new CreateAttackService($partyAttacker, $partyOpponent, 1000);
        $battleService->create();
        $newHeatlOpponent = $battleService->calculatNewArmorOpponent();

        $this->assertEquals(2500, $newHeatlOpponent);
    }
}
