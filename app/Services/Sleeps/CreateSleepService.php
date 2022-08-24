<?php

namespace App\Services\Sleeps;

use App\Models\Party;
use App\Services\Messages\CreateMessageService;
use App\Services\Steps\CreateStepService;

class CreateSleepService
{
    private Party $party;
    private int $hour;

    public function __construct(Party $party, int $hour = 6)
    {
        $this->party = $party;
        $this->hour = $hour;
    }

    public function create(): Party
    {
        $this->party->start_sleep = now();
        $this->party->update();

        $messageService = new CreateMessageService();
        $messageService->create(type: 9, from: $this->party);

        return $this->party;
    }

    public function needSleep(): bool
    {
        $stepService = new CreateStepService($this->party);
        if ($this->party->step >= $stepService->getMaximumStep()) {
            return true;
        }

        return false;
    }

    public function isSleeping(): bool
    {
        if ($this->party->start_sleep) {
            return true;
        }

        return false;
    }

    public function canWakeup(): bool
    {
        if ($this->party->start_sleep) {
            $diff = $this->party->start_sleep->diffInHours(now());
            $stepService = new CreateStepService($this->party);
            if ($this->party->step >= $stepService->getMaximumStep()) {
                echo $this->hour / 3;
                if ($diff >= $this->hour / 3) {
                    return true;
                }
            } else {
                if ($diff >= $this->hour) {
                    return true;
                }
            }
        }

        return false;
    }
}
