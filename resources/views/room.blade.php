<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body>
    <livewire:room-stats />
    <div class="main">
        <div class="members_flex">
            <livewire:member-list />
        </div>
        <div class="messages_flex">
            <livewire:message-list />
        </div>
    </div>
    <footer>
        <livewire:member-stats />
    </footer>
    @livewireScripts
</body>

</html>
