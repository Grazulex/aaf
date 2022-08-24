<?php

namespace App\Services\Treasures;

use App\Models\Treasure;

class CreateTreasureService
{

    public function create(): Treasure
    {
        return Treasure::create(['value' => rand(10000, 100000)]);
    }

    public function getCurrentTreasure(): Treasure
    {
        return Treasure::latest()->first();
    }

    public function isCurrentFinded(): bool
    {
        $treasure = $this->getCurrentTreasure();

        return $treasure->is_finded;
    }
}
