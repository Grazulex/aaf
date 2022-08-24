<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;


use App\Models\Party;
use App\Models\Room;
use App\Models\Treasure;
use App\Services\Armors\GetArmorService;
use App\Services\Battles\CreateAttackService;
use App\Services\Friends\CheckFriendService;
use App\Services\Friends\CreateFriendService;
use App\Services\Potions\GivePotionService;
use App\Services\Potions\UsePotionService;
use App\Services\Thefts\CreateTheftService;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(100)->create();

        Room::factory(1)->create();

        Treasure::create(['room_id' => 1]);

        for ($i = 1; $i <= 100; $i++) {
            Party::create([
                'room_id' => 1,
                'member_id' => $i
            ]);
        }


        for ($i = 1; $i <= 100; $i++) {
            $from = Party::where('id', $i)->first();
            $this->command->info($i);
            for ($f = rand(1, 51); $f <= 100; $f++) {
                if ($i != $f) {
                    $to = Party::where('id', $f)->first();
                    $friendCheckSercice = new CheckFriendService($from);
                    if ($friendCheckSercice->canAddFriend()) {
                        $friendService = new CreateFriendService($from, $to);
                        $friendService->create();
                    }

                    $givePotionService = new GivePotionService($from, $to);
                    $givePotionService->give();


                    $createBattleService = new CreateAttackService($from, $to);
                    $createBattleService->create();

                    $createTheftService = new CreateTheftService($from, $to);
                    $createTheftService->create();
                } else {
                    $usePotionService = new UsePotionService($from, rand(2500, 10000));
                    $usePotionService->use();

                    $getArmorService = new GetArmorService($from);
                    $getArmorService->get();
                }
            }
        }



        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
