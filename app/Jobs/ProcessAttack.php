<?php

namespace App\Jobs;

use App\Models\Party;
use App\Services\Battles\CreateAttackService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessAttack implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public Party $from;
    public Party $to;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Party $from, Party $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $createAttackService = new CreateAttackService($this->from, $this->to);
        $createAttackService->create();
    }
}
