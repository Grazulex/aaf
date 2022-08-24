<?php

namespace App\Services\Steps;

use App\Models\Party;
use App\Services\Messages\CreateMessageService;
use App\Services\Sleeps\CreateSleepService;

class CreateStepService
{
    private Party $party;
    private int $step;

    public function __construct(Party $party, int $step = 10)
    {
        $this->party = $party;
        $this->step = $step;
    }

    public function create(): Party
    {
        if ($this->calculateNewStep() <= $this->getMaximumStep()) {
            $this->party->step = $this->calculateNewStep();
            $this->party->update();

            $messageService = new CreateMessageService();
            $messageService->create(type: 8, from: $this->party, value: $this->step);
        } else {
            $createSleepService = new CreateSleepService($this->party);
            if ($createSleepService->needSleep()) {
                $createSleepService->create();
            }
        }
        return $this->party;
    }

    public function calculateNewStep(): int
    {
        return (int)$this->party->step + $this->step;
    }

    public function getMaximumStep(): int
    {
        return 100 + (25 * count($this->party->friends));
    }

    public function resetStep(): Party
    {
        $this->party->step = 0;

        return $this->party;
    }
}
