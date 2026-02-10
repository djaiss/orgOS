<x-app-layout>
  <x-slot:title>
    {{ __('Dashboard') }}
  </x-slot>

  <div class="px-6 pt-6">
    <div class="mx-auto w-full max-w-4xl items-start justify-center">
      <div class="mb-4 flex items-center justify-between">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Your journals') }}</h2>

        <x-button.secondary href="" turbo="true">
          <x-slot:icon>
            <x-phosphor-plus-bold class="size-4" />
          </x-slot>
          {{ __('New organization') }}
        </x-button.secondary>
      </div>

      <x-box padding="p-0">
        @forelse ($organizations as $organization)
          <div class="rounded-0 flex items-center justify-between border-b border-gray-200 text-sm first:rounded-t-lg last:rounded-b-lg last:border-b-0 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800">
            <div class="flex items-center">
              <div class="mr-2 rounded-full p-3">
                <img src="{{ $organization->avatar }}" class="h-8 w-8" alt="Avatar" />
              </div>

              <x-link href="{{ $organization->link }}">{{ $organization->name }}</x-link>
            </div>
          </div>
        @empty
          <x-empty-state>
            <x-slot:icon>
              <x-phosphor-building-office class="size-6 text-gray-600" />
            </x-slot>

            {{ __('You are not a member of any organizations yet.') }}
          </x-empty-state>
        @endforelse
      </x-box>
    </div>
  </div>
</x-app-layout>
