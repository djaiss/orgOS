@props([
  'name' => null,
  'level' => 0,
  'comma' => false,
])

@php
  $padding = [
    0 => 'pl-0',
    1 => 'pl-4',
    2 => 'pl-8',
    3 => 'pl-12',
    4 => 'pl-16',
    5 => 'pl-20',
    6 => 'pl-24',
    7 => 'pl-28',
    8 => 'pl-32',
  ];

  $pad = $padding[$level] ?? end($padding);
@endphp

<div {{ $attributes->class($pad) }}>
  "{{ $name }}":
  <span>{</span>
  {{ $slot }}
  <span>}{{ $comma ? ',' : '' }}</span>
</div>
