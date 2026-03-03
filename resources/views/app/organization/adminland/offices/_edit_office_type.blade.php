<x-form id="office-type-{{ $officeType->id }}" x-target="office-type-list notifications office-type-{{ $officeType->id }}" x-target.back="office-type-{{ $officeType->id }}" action="{{ route('organization.adminland.office_type.update', [$organization->slug, $officeType->id]) }}" method="post" class="space-y-5 rounded-t-lg border-b border-gray-200 p-4 hover:bg-blue-50 dark:border-gray-700 dark:hover:bg-gray-800">
  @csrf
  @method('PUT')
  <div>
    <x-input id="name" :label="__('Name')" type="text" name="name" required autofocus :error="$errors->get('name')" value="{{ old('name', $officeType->name) }}" />
  </div>

  <div class="flex justify-between">
    <x-button.secondary href="{{ route('organization.adminland.office.index', $organization->slug) }}" turbo="true" x-target="office-type-{{ $officeType->id }}">
      {{ __('Cancel') }}
    </x-button.secondary>

    <x-button class="mr-2">
      {{ __('Update') }}
    </x-button>
  </div>
</x-form>
