<x-box padding="p-0">
  <x-slot:title>{{ __('Member types') }}</x-slot>

  <x-slot:description>
    <p>{{ __('Member types allow you to categorize your members.') }}</p>
  </x-slot>

  <x-slot:actions>
    <div class="flex items-center gap-x-2">
      <x-button.secondary href="{{ route('organization.adminland.member_type.create', $organization->slug) }}" turbo="true" x-target="new-member-type-form">
        {{ __('Add') }}
      </x-button.secondary>
    </div>
  </x-slot>

  <div id="new-member-type-form"></div>

  <div id="member-type-list" x-data="{
    draggedId: null,
    dragOverId: null,
    start(id) {
      this.draggedId = id
    },
    over(id) {
      this.dragOverId = id
    },
    drop(id) {
      if (! this.draggedId || this.draggedId === id) {
        this.draggedId = null
        this.dragOverId = null
        return
      }
      const rows = [...$el.querySelectorAll('[data-member-type-id]')]
      const newPosition = rows.findIndex((r) => r.dataset.memberTypeId == id)
      const form = document.getElementById('sort-form-' + this.draggedId)
      form.querySelector('[name=position]').value = newPosition
      form.requestSubmit()
      this.draggedId = null
      this.dragOverId = null
    },
  }">
    @foreach ($memberTypes as $memberType)
      <div
        id="member-type-{{ $memberType->id }}"
        data-member-type-id="{{ $memberType->id }}"
        draggable="true"
        @dragstart="start({{ $memberType->id }})"
        @dragover.prevent="over({{ $memberType->id }})"
        @drop.prevent="drop({{ $memberType->id }})"
        @dragend="draggedId = null; dragOverId = null"
        :class="{
          'opacity-40': draggedId === {{ $memberType->id }},
          'border-t-2 border-t-blue-400': dragOverId === {{ $memberType->id }} && draggedId !== {{ $memberType->id }},
        }"
        class="group flex items-center justify-between border-b border-gray-200 p-3 first:rounded-t-lg last:rounded-b-lg last:border-b-0 hover:bg-blue-50 dark:border-gray-700 dark:hover:bg-gray-800">
        <div class="flex items-center gap-3">
          <div class="cursor-grab text-gray-500 hover:text-gray-700 active:cursor-grabbing dark:text-gray-600 dark:hover:text-gray-400">
            <x-phosphor-dots-six-vertical class="h-4 w-4" />
          </div>

          <div class="rounded-sm bg-zinc-100 p-2 group-hover:bg-zinc-200 dark:bg-zinc-700 dark:group-hover:bg-zinc-600">
            <x-phosphor-users class="h-4 w-4 text-zinc-500" />
          </div>

          <div class="flex flex-col">
            <p class="text-sm font-semibold">{{ $memberType->name }}</p>
          </div>
        </div>

        <div class="flex gap-2">
          <x-button.invisible x-target="member-type-{{ $memberType->id }}" href="{{ $memberType->edit_link }}" class="invisible text-sm group-hover:visible">
            {{ __('Edit') }}
          </x-button.invisible>

          <form x-target="member-type-list" x-on:ajax:before="
            confirm('Are you sure you want to proceed? This can not be undone.') ||
              $event.preventDefault()
          " action="{{ $memberType->destroy_link }}" method="POST">
            @csrf
            @method('DELETE')

            <x-button.invisible class="invisible text-sm group-hover:visible">
              {{ __('Delete') }}
            </x-button.invisible>
          </form>
        </div>

        <form id="sort-form-{{ $memberType->id }}" x-target="member-type-list" action="{{ $memberType->update_link }}" method="POST" class="hidden">
          @csrf
          @method('PUT')
          <input type="hidden" name="position" value="{{ $memberType->position }}" />
        </form>
      </div>
    @endforeach
  </div>
</x-box>
