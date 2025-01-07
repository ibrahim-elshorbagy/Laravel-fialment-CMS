@props(['categories'])

@foreach ($categories as $category)
{{-- wire:navigate href="{{ route('posts.index', ['category' => $category->slug]) }}" --}}
    <x-badge
        :textColor="$category->text_color" :bgColor="$category->bg_color">
        {{ $category->title }}
    </x-badge>
@endforeach
