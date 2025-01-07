@props(['tags'])

@foreach ($tags as $tag)
{{-- wire:navigate href="{{ route('posts.index', ['tag' => $tag->slug]) }}" --}}
    <x-badge
        :textColor="$tag->text_color" :bgColor="$tag->bg_color">
        {{ $tag->title }}
    </x-badge>
@endforeach
