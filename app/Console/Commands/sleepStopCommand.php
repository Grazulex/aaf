<?php

namespace App\Console\Commands;

use App\Models\Party;
use App\Services\Messages\CreateMessageService;
use App\Services\Sleeps\CreateSleepService;
use App\Services\Sleeps\StopSleepService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class sleepStopCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sleep:stop';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stop Sleep time after 6h';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $parties = Party::where('is_dead', false)->where('start_sleep', '<=', Carbon::now()->subHours(6)->toDateTimeString())->get();
        foreach ($parties as $party) {
            $stopSleepService = new StopSleepService($party);
            $oldHealt = $party->healt;
            $healt = $stopSleepService->calculateNewHealt();
            $party->healt = $healt;
            $party->update();
            $stopSleepService->stop();

            $messageService = new CreateMessageService();
            $messageService->create(type: 21, from: $party, value: (int)($healt - $oldHealt));
        }
    }
}
