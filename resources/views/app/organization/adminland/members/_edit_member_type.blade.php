<x-form id="member-type-{{ $memberType->id }}" x-target="member-type-list notifications member-type-{{ $memberType->id }}" x-target.back="member-type-{{ $memberType->id }}" action="{{ route('organization.adminland.member_type.update', [$organization->slug, $memberType->id]) }}" method="post" class="space-y-5 rounded-t-lg border-b border-gray-200 p-4 hover:bg-blue-50 dark:border-gray-700 dark:hover:bg-gray-800">
  @csrf
  @method('PUT')
  <div>
    <x-input id="name" :label="__('Name')" type="text" name="name" required autofocus :error="$errors->get('name')" value="{{ old('name', $memberType->name) }}" />
  </div>

  <div class="flex justify-between">
    <x-button.secondary href="{{ route('organization.adminland.member.index', $organization->slug) }}" turbo="true" x-target="member-type-{{ $memberType->id }}">
      {{ __('Cancel') }}
    </x-button.secondary>

    <x-button class="mr-2">
      {{ __('Update') }}
    </x-button>
  </div>
</x-form>
