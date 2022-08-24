<div wire:poll.10s>
    <div class="member_stats">
        <div class="member_card">
            <x-members.card :party="$party" showDetails="full" :key="'me-' . $party->id" />
            <livewire:members.actions :party="$party" :wire:key="'friend-actions-'.$party->id" :is_friend="true" />
        </div>
        <div class="friend_list">
            <livewire:friend-list />
        </div>
    </div>
</div>
