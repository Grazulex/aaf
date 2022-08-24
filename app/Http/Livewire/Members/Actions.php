<?php

namespace App\Http\Livewire\Members;

use App\Jobs\ProcessAttack;
use App\Models\Friend;
use App\Models\Party;
use App\Services\Armors\GetArmorService;
use App\Services\Battles\CreateAttackService;
use App\Services\Friends\CheckFriendService;
use App\Services\Friends\CreateFriendService;
use App\Services\Friends\RemoveFriendService;
use App\Services\Potions\GivePotionService;
use App\Services\Potions\UsePotionService;
use App\Services\Thefts\CreateTheftService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Actions extends Component
{
    public $party;
    public $is_friend;


    public function friend()
    {
        $me = Party::where('room_id', 1)
            ->where('member_id', Auth::user()->id)
            ->first();

        $friendCheckSercice = new CheckFriendService($me);
        if ($friendCheckSercice->canAddFriend()) {
            $friendService = new CreateFriendService($me, $this->party);
            $friendService->create();

            $this->emit('update-list-friend');
            $this->emit('update-list-member');
        }
    }

    public function unfriend()
    {
        $me = Party::where('room_id', 1)
            ->where('member_id', Auth::user()->id)
            ->first();



        $friendService = new RemoveFriendService($me, $this->party);
        $friendService->remove();

        $this->emit('update-list-friend');
        $this->emit('update-list-member');
    }

    public function potion()
    {
        $me = Party::where('room_id', 1)
            ->where('member_id', Auth::user()->id)
            ->first();


        if ($me->id === $this->party->id) {
            $usePotionService = new UsePotionService($me, rand(2500, 10000));
            $usePotionService->use();
        } else {
            $givePotionService = new GivePotionService($me, $this->party);
            $givePotionService->give();
        }


        $this->emit('update-list-friend');
        $this->emit('update-list-member');
    }

    public function armor()
    {
        $me = Party::where('room_id', 1)
            ->where('member_id', Auth::user()->id)
            ->first();

        $getArmorService = new GetArmorService($me);
        $getArmorService->get();

        $this->emit('update-list-friend');
        $this->emit('update-list-member');
    }

    public function attack()
    {
        $me = Party::where('room_id', 1)
            ->where('member_id', Auth::user()->id)
            ->first();

        ProcessAttack::dispatch($me, $this->party);

        //$this->emit('update-list-friend');
        //$this->emit('update-list-member');
    }

    public function theft()
    {
        $me = Party::where('room_id', 1)
            ->where('member_id', Auth::user()->id)
            ->first();

        $createTheftService = new CreateTheftService($me, $this->party);
        $createTheftService->create();

        $this->emit('update-list-friend');
        $this->emit('update-list-member');
    }



    public function render()
    {

        $me = Party::where('room_id', 1)
            ->where('member_id', 1)
            ->first();

        $friendCheckSercice = new CheckFriendService($me);
        $isMyFriend = $friendCheckSercice->isMyFriend($this->party);

        return view('livewire.members.actions', compact('isMyFriend'));
    }
}
