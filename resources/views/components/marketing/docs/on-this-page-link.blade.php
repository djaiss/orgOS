@props([
  'anchor' => '',
])

<a href="#{{ $anchor }}" class="group flex items-center gap-x-2 rounded-sm border border-b-3 border-transparent px-2 py-1 text-gray-600 transition-colors duration-50 hover:border-gray-400 hover:bg-white">{{ $slot }}</a>
