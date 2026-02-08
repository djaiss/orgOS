<div class="login-image relative hidden bg-gray-400 lg:block">
  <div class="absolute inset-0 flex items-center justify-center">
    <div x-data="{ showDescription: false }" @mouseenter="showDescription = true" @mouseleave="showDescription = false" class="relative rounded-lg border-2 border-white/70 bg-white/90 p-1 shadow-lg backdrop-blur-sm">
      <div class="max-w-lg rounded-lg bg-white/90 p-8 shadow-lg backdrop-blur-sm">
        <p class="mb-4 text-xl font-medium text-gray-900">"{{ $quote['sentence'] }}"</p>

        <div class="flex items-center gap-x-2">
          <x-image src="{{ asset('images/marketing/quotes/' . $quote['file'] . '.webp') }}" srcset="{{ asset('images/marketing/quotes/' . $quote['file'] . '.webp') }}, {{ asset('images/marketing/quotes/' . $quote['file'] . '@2x.webp') }} 2x" height="48" width="48" alt="{{ $quote['character'] }}" class="h-12 w-12 rounded-full border border-gray-200" />
          <div class="flex flex-col gap-y-1">
            <p class="text-sm font-semibold text-gray-600">{{ $quote['character'] }}</p>
            <p class="text-sm text-gray-600">
              <span class="italic">from</span>
              {{ $quote['tv_show'] }} ({{ $quote['season_episode'] }})
            </p>
          </div>
        </div>
      </div>

      <div x-show="showDescription" x-transition:enter="transition duration-200 ease-out" x-transition:enter-start="scale-95 transform opacity-0" x-transition:enter-end="scale-100 transform opacity-100" x-transition:leave="transition duration-100 ease-in" x-transition:leave-start="scale-100 transform opacity-100" x-transition:leave-end="scale-95 transform opacity-0" class="absolute -top-22 left-1/2 z-10 w-72 -translate-x-1/2 rounded-md bg-gray-800 p-4 shadow-lg" style="display: none">
        <div class="relative">
          <div class="absolute -bottom-4 left-1/2 h-4 w-4 -translate-x-1/2 rotate-45 bg-gray-800"></div>
          <p class="text-center text-sm text-white">{{ $quote['description'] }}</p>
        </div>
      </div>
    </div>
  </div>
</div>
