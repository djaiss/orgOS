<x-box padding="p-0">
  <x-slot:title>{{ __('Office types') }}</x-slot>

  <x-slot:description>
    <p>{{ __('Office types allow you to categorize your offices.') }}</p>
  </x-slot>

  <x-slot:actions>
    <div class="flex items-center gap-x-2">
      <x-button.secondary href="{{ route('organization.adminland.office_type.create', $organization->slug) }}" turbo="true" x-target="new-office-type-form">
        {{ __('Add') }}
      </x-button.secondary>
    </div>
  </x-slot>

  <div id="new-office-type-form"></div>

  <div id="office-type-list">
    @foreach ($officeTypes as $officeType)
      <div id="office-type-{{ $officeType->id }}" class="group flex items-center justify-between border-b border-gray-200 p-3 first:rounded-t-lg last:rounded-b-lg last:border-b-0 hover:bg-blue-50 dark:border-gray-700 dark:hover:bg-gray-800">
        <div class="flex items-center justify-between gap-3">
          <div class="rounded-sm bg-zinc-100 p-2 group-hover:bg-zinc-200 dark:bg-zinc-700 dark:group-hover:bg-zinc-600">
            <x-phosphor-building-office class="h-4 w-4 text-zinc-500" />
          </div>

          <div class="flex flex-col">
            <p class="text-sm font-semibold">{{ $officeType->name }}</p>
          </div>
        </div>

        <div class="flex gap-2">
          <x-button.invisible x-target="office-type-{{ $officeType->id }}" href="{{ $officeType->edit_link }}" class="invisible text-sm group-hover:visible">
            {{ __('Edit') }}
          </x-button.invisible>

          <form x-target="office-type-list" x-on:ajax:before="
            confirm('Are you sure you want to proceed? This can not be undone.') ||
              $event.preventDefault()
          " action="{{ $officeType->destroy_link }}" method="POST">
            @csrf
            @method('DELETE')

            <x-button.invisible class="invisible text-sm group-hover:visible">
              {{ __('Delete') }}
            </x-button.invisible>
          </form>
        </div>
      </div>
    @endforeach
  </div>
</x-box>
