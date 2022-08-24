<?php

namespace App\Services\Sleeps;

use App\Models\Party;
use App\Services\Messages\CreateMessageService;
use App\Services\Thefts\ResetTheftService;

class StopSleepService
{
    private Party $party;
    private mixed $healt;

    public function __construct(Party $party, int $healt = null)
    {
        $this->party = $party;
        $this->healt = $healt;
    }

    public function stop(): Party
    {
        $this->party->start_sleep = null;
        $this->party->update();
        $resetTheftService = new ResetTheftService($this->party);
        $this->party = $resetTheftService->reset();

        $messageService = new CreateMessageService();
        $messageService->create(7, $this->party);

        return $this->party;
    }

    public function calculateNewHealt(): int
    {
        if (!$this->healt) {
            $healt = rand(10, 100);
        } else {
            $healt = $this->healt;
        }
        $diff = $this->party->start_sleep->diffInHours(now());
        if ($diff === 0) {
            $diff = 1;
        }
        $healt = (int)$healt * $diff;
        if ($healt > 2500) {
            $healt = 2500;
        }

        return $this->party->healt + $healt;
    }
}
