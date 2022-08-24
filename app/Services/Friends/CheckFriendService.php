<?php

namespace App\Services\Friends;

use App\Models\Friend;
use App\Models\Party;
use App\Services\Messages\CreateMessageService;

class CheckFriendService
{
    private Party $party;

    public function __construct(Party $party)
    {
        $this->party = $party;
    }

    public function canAddFriend(): bool
    {
        if ((Friend::where('party_id', $this->party->id)->count() >= 5) || ($this->party->is_dead)) {
            $messageService = new CreateMessageService();
            $messageService->create(3, $this->party);

            return false;
        }

        return true;
    }

    public function isMyFriend(Party $friend): bool
    {
        if (Friend::where('party_id', $this->party->id)->where('friend_id', $friend->id)->count() > 0) {
            return true;
        }

        return false;
    }
}
