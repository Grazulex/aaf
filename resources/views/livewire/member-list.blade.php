<div wire:poll.10s class="members_list">
    <table class="lifes">
        @foreach ($parties as $party)
            <tr>
                <td style="width: 75%">
                    <x-members.card showDetails="default" :party="$party" :key="'member-' . $party->id" />
                </td>
                <td>
                    <livewire:members.actions :party="$party" :wire:key="'member-actions-'.$party->id" />
                </td>
            </tr>
        @endforeach
    </table>
</div>
