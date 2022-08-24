<?php

namespace App\Services\Thefts;

use App\Models\Party;
use App\Models\Theft;
use App\Services\Messages\CreateMessageService;
use App\Services\Sleeps\CreateSleepService;
use App\Services\Steps\CreateStepService;

class CreateTheftService
{
    private Party $theft;
    private Party $sleeper;
    private int $try;

    public function __construct(Party $theft, Party $sleeper, int $try = 1)
    {
        $this->theft = $theft;
        $this->sleeper = $sleeper;
        $this->try = $try;
    }

    public function create(): Party
    {

        $stepService = new CreateStepService($this->theft);
        $stepService->create();

        if ($this->canTheft()) {
            Theft::create([
                'theft_id' => $this->theft->id,
                'sleeper_id' => $this->sleeper->id
            ]);

            $messageService = new CreateMessageService();
            $messageService->create(19, $this->theft, $this->sleeper);

            if ($this->calculateCountTheft() === 3) {
                $this->theft->potion = $this->theft->potion + 1;
                $this->theft->update();
                $this->sleeper->potion = $this->sleeper->potion - 1;
                $this->sleeper->update();

                $messageService = new CreateMessageService();
                $messageService->create(20, $this->theft, $this->sleeper, value: 1);

                $resetTheftService = new ResetTheftService($this->sleeper);
                $resetTheftService->reset();
            }
        } else {
            $messageService = new CreateMessageService();
            $messageService->create(22, $this->theft, $this->sleeper);
        }
        return $this->theft;
    }

    private function canTheft(): bool
    {
        $sleepServiceSleeper = new CreateSleepService($this->sleeper);
        $sleepServiceTheft = new CreateSleepService($this->theft);

        if (!$this->sleeper->is_dead && !$this->theft->is_dead && $sleepServiceSleeper->isSleeping() && !$sleepServiceTheft->isSleeping()) {
            $lastTheft = Theft::where('theft_id', $this->theft->id)->where('sleeper_id', $this->sleeper->id)->latest()->first();
            if ($lastTheft) {
                if ($lastTheft['created_at']->diffInHours(now()) >= 3) {
                    return true;
                } else {
                    return false;
                }
            }
            return true;
        }

        return false;
    }

    public function resetTheft(): Party
    {
        Theft::where('theft_id', $this->theft->id)->where('sleeper_id', $this->sleeper->id)->delete();

        return $this->theft;
    }
    private function calculateCountTheft(): int
    {
        return (Theft::where('theft_id', $this->theft->id)->where('sleeper_id', $this->sleeper->id)->count());
    }
}
