<?php

namespace App\Services\Thefts;

use App\Models\Party;
use App\Models\Theft;
use App\Services\Messages\CreateMessageService;
use App\Services\Sleeps\CreateSleepService;

class ResetTheftService
{
    private Party $sleeper;

    public function __construct(Party $sleeper)
    {
        $this->sleeper = $sleeper;
    }

    public function reset(): Party
    {
        Theft::where('sleeper_id', $this->sleeper->id)->delete();

        $messageService = new CreateMessageService();
        $messageService->create(type: 17, from: $this->sleeper);

        return $this->sleeper;
    }
}
