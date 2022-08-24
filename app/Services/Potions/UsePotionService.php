<?php

namespace App\Services\Potions;

use App\Models\Party;
use App\Services\Messages\CreateMessageService;
use App\Services\Sleeps\StopSleepService;
use App\Services\Steps\CreateStepService;

class UsePotionService
{
    private Party $party;
    private mixed $potion;

    public function __construct(Party $party, int $potion = null)
    {
        $this->party = $party;
        $this->potion = $potion;
    }

    public function havePotion(): bool
    {
        if ((int)$this->party->potion > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function calculateNewPotionCount(int $quantity = 1): int
    {
        return $this->party->potion - $quantity;
    }

    public function calculateNewHealt(): int
    {
        if (!$this->party->start_sleep) {
            if ((int)count($this->party->friends) > 0) {
                $this->potion  = $this->potion / ((int)count($this->party->friends) + 1);
                foreach ($this->party->friends as $friend) {
                    $friend->friend->healt = $friend->friend->healt + $this->potion;
                    $friend->friend->update();

                    $messageService = new CreateMessageService();
                    $messageService->create(type: 6, from: $this->party, to: $friend->friend, value: $this->potion);

                    $stepService = new CreateStepService($friend->friend);
                    $stepService->create();
                }
                return $this->party->healt + $this->potion;
            }

            return $this->party->healt + $this->potion;
        } else {
            $stopSleepService = new StopSleepService($this->party);
            $stopSleepService->stop();


            $stepService = new CreateStepService($this->party);
            $stepService->create();

            return $this->party->healt;
        }
    }

    public function use()
    {
        if ($this->havePotion()) {
            $this->party->potion = $this->calculateNewPotionCount();
            $oldValue = $this->party->healt;
            $this->party->healt = $this->calculateNewHealt();
            $value = $this->party->healt - $oldValue;

            $this->party->update();

            $messageService = new CreateMessageService();
            $messageService->create(type: 5, from: $this->party, value: $value);

            $stepService = new CreateStepService($this->party);
            $stepService->create();
        }
    }
}
