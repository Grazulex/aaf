<div>
    @if ($party->member->id === Auth::user()->id)
        <span title="It's you">&#11088;</span>
    @endif
    @if ($party->healt >= 200000)
        <span title="King">&#128120;</span>
        <span class="king @if ($party->is_dead === true) is_dead @endif">{{ $party->member->name }}</span>
    @elseif ($party->healt >= 150000)
        <span title="Guest">&#129497;</span>
        <span class="guest @if ($party->is_dead === true) is_dead @endif">{{ $party->member->name }}</span>
    @elseif ($party->healt >= 85000)
        <span title="Newbie">&#128118;</span>
        <span class="newbie @if ($party->is_dead === true) is_dead @endif">{{ $party->member->name }}</span>
    @elseif ($party->healt >= 50000)
        <span title="Zombie">&#129503;</span>
        <span class="zombie @if ($party->is_dead === true) is_dead @endif">{{ $party->member->name }}</span>
    @else
        <span title="Dead is your friend">&#128128;</span>
        <span class="dead @if ($party->is_dead === true) is_dead @endif">{{ $party->member->name }}</span>
    @endif
    | <span class="healt_add">{{ $party->healt }}</span> <span title="Healt">&#128170;</span>
    @if ($showDetails != 'min')
        @if ($party->start_sleep)
            | <span title="Sleepers">&#127772;</span>
        @endif

        @if ($party->armor != 0)
            | <span class="armor_add">{{ $party->armor }}</span> <span title="Armor">&#128737;</span>
        @endif
        @if ($showDetails == 'full')
            @if ($party->potion != 0)
                | <span class="potion_add">{{ $party->potion }}</span> <span title="Potion">&#127994;</span>
            @endif
            | <span class="step_add">{{ $party->step }}</span> <span title="Steps">&#128099;</span>
        @endif
        @if (count($party->battles) > 0)
            | <span class="attack_add">{{ count($party->battles) }}</span> <span title="Attacks">&#128165;</span>
        @endif
        @if (count($party->thefts) > 0)
            | <span class="theft_add">{{ count($party->thefts) }}</span> <span title="Thefts">&#128375;</span>
        @endif
    @endif
</div>
