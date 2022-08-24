<div>
    @if ($party->is_dead === false)
        @if ($party->member->id !== Auth::user()->id)
            @if ($is_friend !== true)
                @if (!$isMyFriend)
                    <button wire:click="friend" class="action"><span title="Friend">&#128274;</span></button> |
                @endif
                @if (!$party->start_sleep)
                    <button wire:click="attack" class="action"><span title="Attack">&#128165;</span></button>
                @else
                    <button wire:click="theft" class="action"><span title="Thefts">&#128375;</span></button>
                @endif
            @else
                <button wire:click="unfriend" class="action"><span title="Friend">&#128275;</span></button>
                | <button wire:click="potion" class="action"><span title="Potion">&#127994;</span></button>
            @endif
        @else
            <button wire:click="potion" class="action"><span title="Potion">&#127994;</span></button>
            | <button wire:click="armor" class="action"><span title="Armor">&#128737;</span></button>
        @endif
    @endif
</div>
