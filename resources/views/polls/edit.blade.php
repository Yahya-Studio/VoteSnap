<x-app-layout>
    <x-slot name="title">{{ __('تعديل التصويت') }} — {{ $poll->title }}</x-slot>

    <livewire:edit-poll :poll="$poll" />
</x-app-layout>
