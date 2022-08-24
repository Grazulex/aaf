<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\User;
use App\Services\Friends\CreateFriendService;
use App\Services\Parties\CreatePartyService;
use App\Services\Sleeps\CreateSleepService;
use App\Services\Steps\CreateStepService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StepTest extends TestCase
{
    use RefreshDatabase;

    public function test_maximum_step_whithout_friends(): void
    {
        $member = User::factory()->create();
        $room = Room::factory()->create();
        $partyService = new CreatePartyService($room, $member);
        $party = $partyService->create();

        $stepService = new CreateStepService($party);

        $this->assertEquals(100, $stepService->getMaximumStep());
    }

    public function test_maximum_step_whit_friends(): void
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

        $stepService = new CreateStepService($party);

        $this->assertEquals(150, $stepService->getMaximumStep());
    }

    public function test_quantity_step_when_battle(): void
    {
        $member = User::factory()->create();
        $room = Room::factory()->create();
        $partyService = new CreatePartyService($room, $member);
        $party = $partyService->create();

        $stepService = new CreateStepService($party);
        $newStepQuantity = $stepService->create();

        $this->assertEquals(1, $newStepQuantity->step);
    }

    public function test_check_quantity_after_reset(): void
    {
        $member = User::factory()->create();
        $room = Room::factory()->create();
        $partyService = new CreatePartyService($room, $member, step: 99);
        $party = $partyService->create();

        $stepService = new CreateStepService($party);
        $stepService->create();
        $party = $stepService->resetStep();

        $sleepService = new CreateSleepService($party);

        $this->assertEquals(0, $party->step);
    }
}
