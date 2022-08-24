<?php

namespace App\Services\Potions;

use App\Models\Party;
use App\Services\Messages\CreateMessageService;
use App\Services\Sleeps\CreateSleepService;
use App\Services\Steps\CreateStepService;

class GivePotionService
{
    private Party $to;
    private Party $me;

    public function __construct(Party $me, Party $to)
    {
        $this->to = $to;
        $this->me = $me;
    }

    public function give()
    {

        $stepService = new CreateStepService($this->me);
        $stepService->create();

        $usePotionService = new UsePotionService($this->me);
        $createSleepService = new CreateSleepService($this->me);

        if ($usePotionService->havePotion() && !$createSleepService->isSleeping()) {

            $getPotionService = new GetPotionService($this->to);
            $this->to->potion = $getPotionService->calculateNewPotionCount();
            $this->to->update();

            $usePotionService = new UsePotionService($this->me);
            $this->me->potion = $usePotionService->calculateNewPotionCount();
            $this->me->update();

            $messageService = new CreateMessageService();
            $messageService->create(4, $this->me, $this->to);
        } else {
            $messageService = new CreateMessageService();
            $messageService->create(23, $this->me, $this->to);
        }
    }
}
