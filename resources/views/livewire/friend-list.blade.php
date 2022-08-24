<div wire:poll.10s>
    <table style="width: 100%">
        @foreach ($friends as $friend)
            <tr>
                <td style="width: 75%">
                    <x-members.card :party="$friend->friend" showDetails="full" :key="'friend-' . $friend->friend->id" />
                <td>
                    <livewire:members.actions :party="$friend->friend" :wire:key="'friend-actions-'.$friend->friend->id"
                        :is_friend="true" />
                </td>
            </tr>
        @endforeach
    </table>
</div>
