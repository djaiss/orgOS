<x-form id="new-office-type-form" x-target="office-type-list new-office-type-form notifications" x-target.back="new-office-type-form" action="{{ route('organization.adminland.office_type.store', $organization->slug) }}" method="post" class="space-y-5 rounded-t-lg border-b border-gray-200 p-4 hover:bg-blue-50 dark:border-gray-700 dark:hover:bg-gray-800">
  <div>
    <x-input id="name" :label="__('Name')" type="text" name="name" required autofocus :error="$errors->get('name')" />
  </div>

  <div class="flex justify-between">
    <x-button.secondary href="{{ route('organization.adminland.office.index', $organization->slug) }}" turbo="true" x-target="new-office-type-form">
      {{ __('Cancel') }}
    </x-button.secondary>

    <x-button class="mr-2">
      {{ __('Create') }}
    </x-button>
  </div>
</x-form>
