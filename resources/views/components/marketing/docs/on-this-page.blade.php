@props([
  'items' => [],
])

<!-- On this page -->
<div class="mb-4">
  <h4 class="mb-1 text-xs font-semibold text-gray-500 uppercase">On this page</h4>
  <nav class="space-y-1 text-sm">
    @foreach ($items as $item)
      <x-marketing.docs.on-this-page-link anchor="{{ $item['id'] }}">{{ $item['title'] }}</x-marketing.docs.on-this-page-link>
    @endforeach
  </nav>
</div>
