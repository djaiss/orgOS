@php
  $officeTypeOptions = ['' => '-'] + $officeTypes->mapWithKeys(fn ($officeType) => [(string) $officeType->id => $officeType->name])->all();
  $countryOptions = ['' => '-'] + $countries->mapWithKeys(fn ($country) => [(string) $country->id => $country->name])->all();
@endphp

<x-form id="office-{{ $office->id }}" x-target="office-list notifications office-{{ $office->id }}" x-target.back="office-{{ $office->id }}" action="{{ route('organization.adminland.office.update', [$organization->slug, $office->id]) }}" method="post" class="space-y-5 rounded-t-lg border-b border-gray-200 p-4 hover:bg-blue-50 dark:border-gray-700 dark:hover:bg-gray-800">
  @csrf
  @method('PUT')

  <div>
    <x-input id="name" :label="__('Name')" type="text" name="name" required autofocus :error="$errors->get('name')" value="{{ old('name', $office->name) }}" />
  </div>

  <div>
    <x-select id="office_type_id" :label="__('Office type')" :options="$officeTypeOptions" :selected="old('office_type_id', $office->office_type_id === null ? '' : (string) $office->office_type_id)" :error="$errors->get('office_type_id')" />
  </div>

  <div>
    <x-input id="address_line_1" :label="__('Address line 1')" type="text" name="address_line_1" required :error="$errors->get('address_line_1')" value="{{ old('address_line_1', $office->address_line_1) }}" />
  </div>

  <div>
    <x-input id="address_line_2" :label="__('Address line 2')" type="text" name="address_line_2" :error="$errors->get('address_line_2')" value="{{ old('address_line_2', $office->address_line_2) }}" />
  </div>

  <div class="grid gap-4 sm:grid-cols-2">
    <x-input id="city" :label="__('City')" type="text" name="city" required :error="$errors->get('city')" value="{{ old('city', $office->city) }}" />
    <x-input id="state_province" :label="__('State / Province')" type="text" name="state_province" :error="$errors->get('state_province')" value="{{ old('state_province', $office->state_province) }}" />
  </div>

  <div class="grid gap-4 sm:grid-cols-2">
    <x-input id="postal_code" :label="__('Postal code')" type="text" name="postal_code" :error="$errors->get('postal_code')" value="{{ old('postal_code', $office->postal_code) }}" />
    <x-input id="timezone" :label="__('Timezone')" type="text" name="timezone" :error="$errors->get('timezone')" value="{{ old('timezone', $office->timezone) }}" />
  </div>

  <div>
    <x-select id="country_id" :label="__('Country')" :options="$countryOptions" :selected="old('country_id', $office->country_id === null ? '' : (string) $office->country_id)" :error="$errors->get('country_id')" />
  </div>

  <div class="flex justify-between">
    <x-button.secondary href="{{ route('organization.adminland.office.index', $organization->slug) }}" turbo="true" x-target="office-{{ $office->id }}">
      {{ __('Cancel') }}
    </x-button.secondary>

    <x-button class="mr-2">
      {{ __('Update') }}
    </x-button>
  </div>
</x-form>
