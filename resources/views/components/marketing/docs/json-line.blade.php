@props([
  'level' => 0,
  'comma' => false,
  'key' => null,
  'value' => null,
  'type' => 'string',
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

  $valueClass = match ($type) {
    'string' => 'text-lime-700',
    'integer' => 'text-rose-800',
    default => '',
  };

  $displayValue = $type === 'string' ? '"' . $value . '"' : $value;
@endphp

<div {{ $attributes->class($pad) }}>
  "{{ $key }}":
  <span class="{{ $valueClass }}">{{ $displayValue }}</span>
  @if ($comma)
    <span>,</span>
  @endif
</div>
