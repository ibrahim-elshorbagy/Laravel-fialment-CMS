@props(['categories' => []])

@foreach ($categories as $category)

    <a wire:navigate href="{{ route('articles.list', ['category' => $category->slug]) }}">
        <x-badge :textColor="$category->text_color" :bgColor="$category->bg_color">
            {{ $category->title }}
        </x-badge>
    </a>
@endforeach

