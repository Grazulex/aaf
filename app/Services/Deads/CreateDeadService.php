<?php

namespace App\Services\Deads;

use App\Models\Party;
use App\Services\Friends\RemoveFriendService;
use App\Services\Friends\ResetFriendService;
use App\Services\Messages\CreateMessageService;
use App\Services\Steps\CreateStepService;
use App\Services\Thefts\ResetTheftService;

class CreateDeadService
{
    private Party $party;

    public function __construct(Party $party)
    {
        $this->party = $party;
    }

    public function CanDead()
    {
        if ($this->party->healt <= 0) {
            $this->party->healt = 0;
            $this->party->start_sleep = null;
            $this->party->is_dead = true;
            $this->party->update();

            $resetTheftService = new ResetTheftService($this->party);
            $resetTheftService->reset();

            $resetFriendService = new ResetFriendService($this->party);
            $resetFriendService->reset();

            $messageService = new CreateMessageService();
            $messageService->create(type: 16, from: $this->party);
        }
    }
}
