<div wire:poll.10s class="messages_list">
    <ul class="messages">
        @foreach ($messages as $message)
            <li>
                {{ $message->created_at }}:
                <x-members.card showDetails="min" :party="$message->from" :key="'message-from-' . $message->id" /> {{ $message->message }}
                @if ($message->to)
                    <x-members.card showDetails="min" :party="$message->to" :key="'message-to-' . $message->id" />
                @endif
            </li>
        @endforeach
    </ul>

</div>
