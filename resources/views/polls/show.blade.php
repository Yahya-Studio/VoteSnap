<x-app-layout>
    <x-slot name="title">{{ $poll->title }}</x-slot>

    <livewire:poll-vote :poll="$poll" />
</x-app-layout>
