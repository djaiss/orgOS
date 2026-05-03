<x-form id="department-{{ $department->id }}" x-target="department-list notifications department-{{ $department->id }}" x-target.back="department-{{ $department->id }}" action="{{ route('organization.adminland.department.update', [$organization->slug, $department->id]) }}" method="post" class="space-y-5 rounded-t-lg border-b border-gray-200 p-4 hover:bg-blue-50 dark:border-gray-700 dark:hover:bg-gray-800">
  @csrf
  @method('PUT')
  <div>
    <x-input id="name" :label="__('Name')" type="text" name="name" required autofocus :error="$errors->get('name')" value="{{ old('name', $department->name) }}" />
  </div>

  <div class="flex justify-between">
    <x-button.secondary href="{{ route('organization.adminland.department.index', $organization->slug) }}" turbo="true" x-target="department-{{ $department->id }}">
      {{ __('Cancel') }}
    </x-button.secondary>

    <x-button class="mr-2">
      {{ __('Update') }}
    </x-button>
  </div>
</x-form>
