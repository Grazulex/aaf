<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\User;
use App\Services\Friends\CreateFriendService;
use App\Services\Friends\RemoveFriendService;
use App\Services\Parties\CreatePartyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FriendTest extends TestCase
{
    use RefreshDatabase;

    public function test_count_friends(): void
    {
        $member = User::factory()->create();
        $friend1 = User::factory()->create();
        $friend2 = User::factory()->create();
        $room = Room::factory()->create();
        $partyService = new CreatePartyService($room, $member);
        $party = $partyService->create();
        $partyServiceFriend1 = new CreatePartyService($room, $friend1);
        $partyFriend1 = $partyServiceFriend1->create();
        $partyServiceFriend2 = new CreatePartyService($room, $friend2);
        $partyFriend2 = $partyServiceFriend2->create();

        $friendService = new CreateFriendService($party, $partyFriend1);
        $friendService->create();
        $friendService = new CreateFriendService($party, $partyFriend2);
        $friendService->create();

        $this->assertEquals(2, count($party->friends));
    }


    public function test_relation_friend(): void
    {
        $member = User::factory()->create();
        $friend1 = User::factory()->create();
        $room = Room::factory()->create();
        $partyService = new CreatePartyService($room, $member);
        $party = $partyService->create();
        $partyServiceFriend1 = new CreatePartyService($room, $friend1);
        $partyFriend1 = $partyServiceFriend1->create();

        $friendService = new CreateFriendService($party, $partyFriend1);
        $friendService->create();

        $this->assertEquals($friend1->name, $party->friends[0]->friend->member->name);
    }

    public function test_remove_friend(): void
    {
        $member = User::factory()->create();
        $friend1 = User::factory()->create();
        $room = Room::factory()->create();
        $partyService = new CreatePartyService($room, $member);
        $party = $partyService->create();
        $partyServiceFriend1 = new CreatePartyService($room, $friend1);
        $partyFriend1 = $partyServiceFriend1->create();

        $friendService = new CreateFriendService($party, $partyFriend1);
        $friendService->create();

        $friendService = new RemoveFriendService($party, $partyFriend1);
        $party = $friendService->remove();

        $this->assertEquals(0, count($party->friends));
    }
}
