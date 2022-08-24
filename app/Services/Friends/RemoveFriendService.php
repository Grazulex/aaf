<?php

namespace App\Services\Friends;

use App\Models\Friend;
use App\Models\Message;
use App\Models\Party;
use App\Services\Messages\CreateMessageService;
use App\Services\Steps\CreateStepService;

class RemoveFriendService
{
    private Party $party;
    private Party $friend;

    public function __construct(Party $party, Party $friend)
    {
        $this->party = $party;
        $this->friend = $friend;
    }

    public function remove(): Party
    {
        $stepService = new CreateStepService($this->party);
        $stepService->create();

        $friend = Friend::where('party_id', $this->party->id)
            ->where('friend_id', $this->friend->id)
            ->first();
        $party = $friend->party;
        $friend->delete();

        $messageService = new CreateMessageService();
        $messageService->create(2, $this->party, $this->friend);

        return $party;
    }
}
