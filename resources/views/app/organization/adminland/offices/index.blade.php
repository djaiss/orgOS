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
          </x-box>
        </div>
      </div>
    </section>
  </div>
</x-app-layout>
