<?php

namespace App\Http\Livewire;

use App\Models\Party;
use App\Models\Room;
use App\Services\Treasures\CreateTreasureService;
use Livewire\Component;
use Carbon\Carbon;
use Carbon\CarbonInterval;

class RoomStats extends Component
{
    public function render()
    {
        $room = Room::where('id', 1)->withCount(['parties' => function ($query) {
            $query->where('is_dead', false);
        }])->first();
        $sleepers = Party::where('room_id', $room->id)->where('is_dead', false)->whereNotNull('start_sleep')->count();
        $deads = Party::where('room_id', $room->id)->where('is_dead', true)->count();
        $now = Carbon::now();
        $treasureService = new CreateTreasureService();
        $treasure = $treasureService->getCurrentTreasure();

        if (!$treasureService->isCurrentFinded()) {
            $dateTreasure = Carbon::parse($treasure->created_at)->addDays(1);
            $timer = CarbonInterval::seconds($dateTreasure->diffInRealSeconds(Carbon::now()))->cascade()->forHumans();
        } else {
            $timer = null;
        }

        return view('livewire.room-stats', compact('room', 'sleepers', 'now', 'treasure', 'timer', 'deads'));
    }
}
