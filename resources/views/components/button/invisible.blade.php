@props([
  'href',
  'turbo' => false,
])

@php
  $base = 'relative inline-flex h-8 cursor-pointer items-center justify-center gap-2 rounded-lg text-sm font-medium whitespace-nowrap transition-[transform,box-shadow] duration-150 focus-visible:ring-2 focus-visible:outline-none active:scale-[0.97] disabled:pointer-events-none disabled:cursor-default disabled:opacity-75 aria-pressed:z-10 dark:disabled:opacity-75 [:where(&)]:px-3';
  $visual = 'border border-transparent bg-white text-gray-700 hover:border-gray-300 hover:bg-gray-50 hover:shadow-xs focus-visible:ring-indigo-500/50 active:shadow-[inset_0_2px_4px_0_rgba(0,0,0,0.1)] dark:bg-transparent dark:text-gray-300 dark:hover:border-white/20 dark:hover:bg-white/5';
  $classes = $base . ' ' . $visual;
@endphp

@isset($href)
  <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }} @if($turbo) data-turbo="true" @endif>
    @isset($icon)
      <span class="shrink-0">{{ $icon }}</span>
    @endisset

    {{ $slot }}
  </a>
@else
  <button type="submit" {{ $attributes->merge(['class' => $classes]) }}>
    @isset($icon)
      <span class="shrink-0">{{ $icon }}</span>
    @endisset

    {{ $slot }}
  </button>
@endif
