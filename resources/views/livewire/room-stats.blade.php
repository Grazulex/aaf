<div wire:poll.10s>
    <div class="header">
        <div class="room_stats">
            <span title="Room">&#127757;</span> <span class="room_name">{{ $room->name }}</span> | <span
                title="Guest">&#129497;</span> <span class="guest">{{ $room->parties_count }}</span> | <span
                title="Deads">&#128128;</span> <span class="dead">{{ $deads }}</span> | <span
                title="Sleepers">&#127772;</span>
            <span class="sleeper">{{ $sleepers }}</span> | <span title="Treasure">&#128176;</span>
            @if ($treasure->is_finded)
                Finded by <span class="treasure">{{ $treasure->member->name }}</span>
                (<span class="healt_add">+ {{ $treasure->value }}</span> <span title="Healt">&#128170;</span>)
            @else
                <span class="treasure">{{ $timer }}</span>
            @endif
        </div>
        <div class="server">
            <span title="Date">&#8987;</span> <span class="date">{{ $now }}</span>
        </div>
    </div>
</div>
