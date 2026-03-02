<x-app-layout :organization="$organization">
  <x-slot:title>
    {{ __('Members') }}
  </x-slot>

  <x-breadcrumb :items="[
    ['label' => __('Dashboard'), 'route' => route('organization.show', $organization)],
    ['label' => __('Adminland'), 'route' => route('organization.adminland.index', $organization)],
    ['label' => __('Offices')]
  ]" />

  <!-- settings layout -->
  <div class="grid grow bg-gray-50 sm:grid-cols-[220px_1fr] dark:bg-gray-950">
    <!-- Sidebar -->
    @include('app.organization.adminland._sidebar')

    <!-- Main content -->
    <section class="p-4 sm:p-8">
      <div class="mx-auto max-w-5xl space-y-6 sm:px-0">
        <div x-data="{ showInvite: false }">
          <x-box padding="p-0">
            <x-slot:title>{{ __('Office types') }}</x-slot>

            <x-slot:description>
              <p>{{ __('Office types allow you to categorize your offices.') }}</p>
            </x-slot>

            <x-slot:actions>
              <div class="flex items-center gap-x-2">
                <x-button.secondary>
                  {{ __('Add') }}
                </x-button.secondary>
              </div>
            </x-slot>

            <div id="office-type-list">
              @foreach ($officeTypes as $officeType)
                <div class="group flex items-center justify-between border-b border-gray-200 p-3 first:border-t last:rounded-b-lg last:border-b-0">
                  <div class="flex items-center justify-between gap-3">
                    <div class="rounded-sm bg-zinc-100 p-2">
                      <x-phosphor-building-office class="h-4 w-4 text-zinc-500" />
                    </div>

                    <div class="flex flex-col">
                      <p class="text-sm font-semibold">{{ $officeType->name }}</p>
                    </div>
                  </div>

                  <div class="flex gap-2">
                    <x-button.invisible x-target="office-type-{{ $officeType->id }}" href="{{ $officeType->edit_link }}" class="hidden text-sm group-hover:block">
                      {{ __('Edit') }}
                    </x-button.invisible>

                    <form x-target="office-type-list" x-on:ajax:before="
                      confirm('Are you sure you want to proceed? This can not be undone.') ||
                        $event.preventDefault()
                    " action="{{ $officeType->destroy_link }}" method="POST">
                      @csrf
                      @method('DELETE')

                      <x-button.invisible class="hidden text-sm group-hover:block">
                        {{ __('Delete') }}
                      </x-button.invisible>
                    </form>
                  </div>
                </div>
              @endforeach
            </div>
          </x-box>
        </div>
      </div>
    </section>
  </div>
</x-app-layout>
