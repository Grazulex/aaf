<?php

namespace App\Services\Friends;

use App\Models\Friend;
use App\Models\Party;
use App\Services\Messages\CreateMessageService;
use App\Services\Steps\CreateStepService;

class CreateFriendService
{
    private Party $party;
    private Party $friend;

    public function __construct(Party $party, Party $friend)
    {
        $this->party = $party;
        $this->friend = $friend;
    }

    public function create(): Party
    {
        $stepService = new CreateStepService($this->party);
        $stepService->create();

        if (!$this->friend->is_dead) {
            $friend = Friend::create([
                'party_id' => $this->party->id,
                'friend_id' => $this->friend->id
            ]);

            $messageService = new CreateMessageService();
            $messageService->create(1, $this->party, $this->friend);
        }

        return $friend->party;
    }
}
