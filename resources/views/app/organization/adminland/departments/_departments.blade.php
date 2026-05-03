<x-box padding="p-0">
  <x-slot:title>{{ __('Departments') }}</x-slot>

  <x-slot:description>
    <p>{{ __('Departments allow you to group members by team or function.') }}</p>
  </x-slot>

  <x-slot:actions>
    <div class="flex items-center gap-x-2">
      <x-button.secondary href="{{ route('organization.adminland.department.create', $organization->slug) }}" turbo="true" x-target="new-department-form">
        {{ __('Add') }}
      </x-button.secondary>
    </div>
  </x-slot>

  <div id="new-department-form"></div>

  <div id="department-list">
    @forelse ($departments as $department)
      <div id="department-{{ $department->id }}" class="group flex items-center justify-between border-b border-gray-200 p-3 first:rounded-t-lg last:rounded-b-lg last:border-b-0 hover:bg-blue-50 dark:border-gray-700 dark:hover:bg-gray-800">
        <div class="flex items-center gap-3">
          <div class="rounded-sm bg-zinc-100 p-2 group-hover:bg-zinc-200 dark:bg-zinc-700 dark:group-hover:bg-zinc-600">
            <x-phosphor-buildings class="h-4 w-4 text-zinc-500" />
          </div>

          <div class="flex flex-col">
            <p class="text-sm font-semibold">{{ $department->name }}</p>
          </div>
        </div>

        <div class="flex gap-2">
          <x-button.invisible x-target="department-{{ $department->id }}" href="{{ $department->edit_link }}" class="invisible text-sm group-hover:visible">
            {{ __('Edit') }}
          </x-button.invisible>

          <form x-target="department-list" x-on:ajax:before="
            confirm('Are you sure you want to proceed? This can not be undone.') ||
              $event.preventDefault()
          " action="{{ $department->destroy_link }}" method="POST">
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
          <x-phosphor-buildings class="h-6 w-6" />
        </x-slot>

        {{ __('No departments found. Start by adding your first department.') }}
      </x-empty-state>
    @endforelse
  </div>
</x-box>
