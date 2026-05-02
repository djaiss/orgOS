@props([
  'title' => 'Code',
  'verb' => '',
])

@php
  $verbClass = match (strtolower($verb)) {
    'get' => 'text-blue-700',
    'post' => 'text-green-700',
    'put' => 'text-yellow-700',
    'delete' => 'text-red-700',
    default => '',
  };
@endphp

<div class="rounded-lg border border-gray-200 dark:border-gray-700">
  <div class="rounded-t-lg border-b border-gray-200 bg-gray-50 p-2 text-sm font-light dark:border-gray-700">{!! $verb ? "<span class='font-normal {$verbClass}'>{$verb}</span> " : '' !!}{{ $title }}</div>
  <div class="overflow-x-auto rounded-b-lg bg-gray-100 p-2 dark:bg-gray-800">
    <code class="text-sm whitespace-nowrap">
      {{ $slot }}
    </code>
  </div>
</div>
