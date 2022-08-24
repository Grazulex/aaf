<?php

namespace App\Console\Commands;

use App\Models\Party;
use App\Services\Sleeps\CreateSleepService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class sleepStartCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sleep:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start Sleep time after 2h';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $parties = Party::where('is_dead', false)->where('updated_at', '<=', Carbon::now()->subHours(2)->toDateTimeString())->whereNot('start_sleep')->get();
        foreach ($parties as $party) {
            $createSleepService = new CreateSleepService($party);
            $createSleepService->create();
        }
    }
}
