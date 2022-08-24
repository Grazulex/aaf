<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\User;
use App\Services\Friends\CreateFriendService;
use App\Services\Parties\CreatePartyService;
use App\Services\Sleeps\CreateSleepService;
use App\Services\Sleeps\StopSleepService;
use App\Services\Steps\CreateStepService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SleepTest extends TestCase
{
    use RefreshDatabase;

    public function test_if_need_sleep_when_max_step_without_firend(): void
    {
        $member = User::factory()->create();
        $room = Room::factory()->create();
        $partyService = new CreatePartyService($room, $member, step: 99);
        $party = $partyService->create();

        $stepService = new CreateStepService($party);
        $party = $stepService->create();

        $sleepService = new CreateSleepService($party);

        $this->assertEquals(true, $sleepService->needSleep());
    }

    public function test_if_need_sleep_when_max_step_with_friend(): void
    {
        $member = User::factory()->create();
        $friend1 = User::factory()->create();
        $friend2 = User::factory()->create();
        $room = Room::factory()->create();
        $partyService = new CreatePartyService($room, $member, step: 150);
        $party = $partyService->create();
        $partyServiceFriend1 = new CreatePartyService($room, $friend1);
        $partyFriend1 = $partyServiceFriend1->create();
        $partyServiceFriend2 = new CreatePartyService($room, $friend2);
        $partyFriend2 = $partyServiceFriend2->create();

        $friendService = new CreateFriendService($party, $partyFriend1);
        $friendService->create();
        $friendService = new CreateFriendService($party, $partyFriend2);
        $friendService->create();

        $sleepService = new CreateSleepService($party);

        $this->assertEquals(true, $sleepService->needSleep());
    }

    public function test_if_need_sleep_after_step_reset(): void
    {
        $member = User::factory()->create();
        $room = Room::factory()->create();
        $partyService = new CreatePartyService($room, $member, step: 99);
        $party = $partyService->create();

        $stepService = new CreateStepService($party);
        $stepService->create();
        $party = $stepService->resetStep();

        $sleepService = new CreateSleepService($party);

        $this->assertEquals(false, $sleepService->needSleep());
    }

    public function test_start_sleep_when_create(): void
    {
        $member = User::factory()->create();
        $room = Room::factory()->create();
        $partyService = new CreatePartyService($room, $member, step: 99);
        $party = $partyService->create();

        $sleepService = new CreateSleepService($party);
        $party = $sleepService->create();

        $this->assertGreaterThanOrEqual($party->start_sleep, now());
    }

    public function test_if_sleeping_when_create(): void
    {
        $member = User::factory()->create();
        $room = Room::factory()->create();
        $partyService = new CreatePartyService($room, $member, step: 99);
        $party = $partyService->create();

        $sleepService = new CreateSleepService($party);
        $party = $sleepService->create();

        $this->assertEquals(true, $sleepService->isSleeping());
    }

    public function test_if_not_sleeping_when_stop(): void
    {
        $member = User::factory()->create();
        $room = Room::factory()->create();
        $partyService = new CreatePartyService($room, $member, step: 99);
        $party = $partyService->create();

        $sleepService = new CreateSleepService($party);
        $party = $sleepService->create();

        $stopSleepService = new StopSleepService($party);
        $party = $stopSleepService->stop();
        $sleepService = new CreateSleepService($party);

        $this->assertEquals(false, $sleepService->isSleeping());
    }

    public function test_check_healt_when_stop(): void
    {
        $member = User::factory()->create();
        $room = Room::factory()->create();
        $partyService = new CreatePartyService($room, $member);
        $party = $partyService->create();

        $sleepService = new CreateSleepService(party: $party);
        $party = $sleepService->create();

        $stopSleepService = new StopSleepService(party: $party, healt: 50);
        $newHealt = $stopSleepService->calculateNewHealt();
        $party = $stopSleepService->stop();

        $this->assertEquals(100050, $newHealt);
        $this->assertEquals(false, $sleepService->isSleeping());
    }

    public function test_check_healt_when_more2500_and_stop(): void
    {
        $member = User::factory()->create();
        $room = Room::factory()->create();
        $partyService = new CreatePartyService($room, $member);
        $party = $partyService->create();

        $sleepService = new CreateSleepService(party: $party);
        $party = $sleepService->create();

        $stopSleepService = new StopSleepService(party: $party, healt: 3000);
        $newHealt = $stopSleepService->calculateNewHealt();
        $party = $stopSleepService->stop();

        $this->assertEquals(102500, $newHealt);
    }

    public function test_can_wakeup_when_sleep_1_hour(): void
    {
        $member = User::factory()->create();
        $room = Room::factory()->create();
        $partyService = new CreatePartyService($room, $member);
        $party = $partyService->create();

        $sleepService = new CreateSleepService($party);
        $party = $sleepService->create();

        $this->assertEquals(false, $sleepService->canWakeup());
    }

    public function test_can_wakeup_when_sleep_7_hours(): void
    {
        $member = User::factory()->create();
        $room = Room::factory()->create();
        $partyService = new CreatePartyService($room, $member);
        $party = $partyService->create();

        $sleepService = new CreateSleepService($party, -7);
        $party = $sleepService->create();

        $this->assertEquals(true, $sleepService->canWakeup());
    }

    public function test_can_wakeup_when_sleep_1_hours_and_step_100(): void
    {
        $member = User::factory()->create();
        $room = Room::factory()->create();
        $partyService = new CreatePartyService($room, $member, step: 100);
        $party = $partyService->create();

        $sleepService = new CreateSleepService($party, -3);
        $party = $sleepService->create();

        $this->assertEquals(true, $sleepService->canWakeup());
    }
}
