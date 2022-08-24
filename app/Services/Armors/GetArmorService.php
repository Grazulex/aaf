<?php

namespace App\Services\Armors;

use App\Models\Party;
use App\Services\Deads\CreateDeadService;
use App\Services\Messages\CreateMessageService;
use App\Services\Steps\CreateStepService;

class GetArmorService
{
    private Party $party;

    public function __construct(Party $party)
    {
        $this->party = $party;
    }

    public function get(int $armor = null): int
    {
        $stepService = new CreateStepService($this->party);
        $stepService->create();

        if (!$armor) {
            $armor = rand(150, 3000);
        }
        if ($this->party->healt > $armor) {
            $this->party->armor = $this->party->armor + $armor;
            $this->party->healt =  $this->party->healt - $armor;
            $this->party->update();

            $messageService = new CreateMessageService();
            $messageService->create(type: 10, from: $this->party, value: $armor);

            $createDeadService = new CreateDeadService($this->party);
            $createDeadService->CanDead();
        } else {
            $messageService = new CreateMessageService();
            $messageService->create(type: 11, from: $this->party, value: $armor);
        }

        $stepService = new CreateStepService($this->party);
        $stepService->create();

        return $armor;
    }
}
