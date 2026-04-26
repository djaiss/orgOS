<x-box padding="p-0">
  <x-slot:title>{{ __('Offices') }}</x-slot>

  <x-slot:description>
    <p>{{ __('Manage your offices and keep address details up to date.') }}</p>
  </x-slot>

  <x-slot:actions>
    <div class="flex items-center gap-x-2">
      <x-button.secondary href="{{ route('organization.adminland.office.create', $organization->slug) }}" turbo="true" x-target="new-office-form">
        {{ __('Add') }}
      </x-button.secondary>
    </div>
  </x-slot>

  <div id="new-office-form"></div>

  <div id="office-list">
    @forelse ($offices as $office)
      <div id="office-{{ $office->id }}" class="group flex items-center justify-between border-b border-gray-200 p-3 first:rounded-t-lg last:rounded-b-lg last:border-b-0 hover:bg-blue-50 dark:border-gray-700 dark:hover:bg-gray-800">
        <div class="flex items-start justify-between gap-3">
          <div class="rounded-sm bg-zinc-100 p-2 group-hover:bg-zinc-200 dark:bg-zinc-700 dark:group-hover:bg-zinc-600">
            <x-phosphor-map-pin class="h-4 w-4 text-zinc-500" />
          </div>

          <div class="flex flex-col">
            <p class="text-sm font-semibold">{{ $office->name }}</p>
            <p class="text-xs text-zinc-500">{{ $office->office_type ?? '-' }}</p>
            <p class="text-xs text-zinc-500">{{ $office->address }}</p>
          </div>
        </div>

        <div class="flex gap-2">
          <x-button.invisible x-target="office-{{ $office->id }}" href="{{ $office->edit_link }}" class="invisible text-sm group-hover:visible">
            {{ __('Edit') }}
          </x-button.invisible>

          <form x-target="office-list" x-on:ajax:before="
            confirm('Are you sure you want to proceed? This can not be undone.') ||
              $event.preventDefault()
          " action="{{ $office->destroy_link }}" method="POST">
            @csrf
            @method('DELETE')

            <x-button.invisible class="invisible text-sm group-hover:visible">
              {{ __('Delete') }}
            </x-button.invisible>
          </form>
        </div>
      </div>
    @empty
      <x-empty-state>
        <x-slot:icon>
          <x-phosphor-map-pin class="h-6 w-6" />
        </x-slot>

        {{ __('No offices found. Start by adding your first office.') }}
      </x-empty-state>
    @endforelse
  </div>
</x-box>
