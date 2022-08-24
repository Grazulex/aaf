<?php

namespace App\Services\Potions;

use App\Models\Party;

class GetPotionService
{
    private Party $party;

    public function __construct(Party $party)
    {
        $this->party = $party;
    }

    public function calculateNewPotionCount(int $quantity = 1): int
    {
        return $this->party->potion + $quantity;
    }
}
