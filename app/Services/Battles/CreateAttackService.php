<?php

namespace App\Services\Battles;

use App\Models\Battle;
use App\Models\Party;
use App\Services\Deads\CreateDeadService;
use App\Services\Friends\CheckFriendService;
use App\Services\Messages\CreateMessageService;
use App\Services\Sleeps\CreateSleepService;
use App\Services\Steps\CreateStepService;

class CreateAttackService
{
    private Party $attacker;
    private Party $opponent;
    private int $attack;

    public function __construct(Party $attacker, Party $opponent, int $attack = null)
    {
        $this->attacker = $attacker;
        $this->opponent = $opponent;
        if ($attack) {
            $this->attack = $attack;
        } else {
            $this->attack = rand(50, 2500);
        }
    }

    public function create(): Battle|null
    {
        $stepService = new CreateStepService($this->attacker);
        $stepService->create();

        $battle = null;
        $friendCheckSercice = new CheckFriendService($this->attacker);
        $createSleepServiceOpponent = new CreateSleepService($this->opponent);
        $createSleepServiceAttacker = new CreateSleepService($this->attacker);
        if ((!$friendCheckSercice->isMyFriend($this->opponent)) && (!$createSleepServiceAttacker->isSleeping()) && (!$createSleepServiceOpponent->isSleeping()) && (!$this->opponent->is_dead) && (!$this->attacker->is_dead)) {
            $this->calculatNewHealtAttacker();
            $this->calculatNewArmorOpponent();
            $battle = Battle::create([
                'attacker_id' => $this->attacker->id,
                'opponent_id' => $this->opponent->id,
                'attack' => $this->attack,
                'is_original_attacker' => 1
            ]);
        }
        return $battle;
    }

    private function calculatNewHealtAttacker(): int
    {
        $currentAttackerHealt = $this->attacker->healt;
        $currentOpponentHealt = $this->opponent->healt;
        if ($currentAttackerHealt < $currentOpponentHealt) {
            $diff = (($currentAttackerHealt / $currentOpponentHealt) * 100) * 2;
        } else {
            $diff = (($currentOpponentHealt / $currentAttackerHealt) * 100);
        }
        if ($this->calculatNewHealtOpponent() === 0) {
            $diff = $diff * 2;
        }
        $point = $diff;
        if ($this->opponent->armor < $this->attack) {
            $point = $point + $this->attack;
        }

        if ((int)count($this->attacker->friends) > 0) {
            $point = $point / ((int)count($this->attacker->friends) + 1);
            foreach ($this->attacker->friends as $friend) {
                $friend->friend->healt = $friend->friend->healt + $point;
                $friend->friend->update();

                $messageService = new CreateMessageService();
                $messageService->create(type: 13, from: $this->attacker, to: $friend->friend, value: $point);

                $stepService = new CreateStepService($friend->friend);
                $stepService->create();
            }
        }

        $this->attacker->healt = $this->attacker->healt + (int)$point;
        $this->attacker->update();

        $messageService = new CreateMessageService();
        $messageService->create(type: 12, from: $this->attacker, to: $this->opponent, value: $point);


        return $this->attacker->healt;
    }

    private function calculatNewHealtOpponent(): int
    {
        if ($this->opponent->armor > $this->attack) {
            return $this->opponent->healt;
        }

        $this->opponent->healt = $this->opponent->healt - (int)$this->attack;
        $this->opponent->update();

        $messageService = new CreateMessageService();
        $messageService->create(type: 14, from: $this->opponent, to: $this->attacker, value: (int)$this->attack);

        $createDeadService = new CreateDeadService($this->opponent);
        $createDeadService->CanDead();


        return $this->opponent->healt;
    }

    private function calculatNewArmorOpponent(): int
    {
        if (($this->opponent->armor > 0) && ($this->opponent->armor < $this->attack)) {
            $armor = (int)($this->opponent->armor / 2);
            if ($armor <= 0) {
                $armor = 1;
            }
            $this->opponent->armor = $this->opponent->armor - $armor;
            $this->opponent->update();


            $messageService = new CreateMessageService();
            $messageService->create(type: 15, from: $this->opponent, value: $armor);
        }


        return $this->opponent->armor;
    }
}
