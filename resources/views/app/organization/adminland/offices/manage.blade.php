<x-app-layout :organization="$organization">
  <x-slot:title>
    {{ __('Offices') }}
  </x-slot>

  <x-breadcrumb :items="[
    ['label' => __('Dashboard'), 'route' => route('organization.show', $organization)],
    ['label' => __('Adminland'), 'route' => route('organization.adminland.index', $organization)],
    ['label' => __('Offices')]
  ]" />

  <div class="grid grow bg-gray-50 sm:grid-cols-[220px_1fr] dark:bg-gray-950">
    @include('app.organization.adminland._sidebar')

    <section class="p-4 sm:p-8">
      <div class="mx-auto max-w-5xl space-y-6 sm:px-0">
        @include(
          'app.organization.adminland.offices._offices',
          [
            'offices' => $offices,
          ]
        )

        @include(
          'app.organization.adminland.offices._office_types',
          [
            'officeTypes' => $officeTypes,
          ]
        )
      </div>
    </section>
  </div>
</x-app-layout>
