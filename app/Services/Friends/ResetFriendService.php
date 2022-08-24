<?php

namespace App\Services\Friends;

use App\Models\Friend;
use App\Models\Message;
use App\Models\Party;
use App\Services\Messages\CreateMessageService;

class ResetFriendService
{
    private Party $party;

    public function __construct(Party $party)
    {
        $this->party = $party;
    }

    public function reset(): Party
    {
        Friend::where('party_id', $this->party->id)->delete();
        Friend::where('friend_id', $this->party->id)->delete();

        $messageService = new CreateMessageService();
        $messageService->create(18, $this->party);

        return $this->party;
    }
}
