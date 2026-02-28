<x-app-layout>
  <x-slot:title>
    {{ __('Join organization') }}
  </x-slot>

  <x-breadcrumb :items="[
    ['label' => __('Dashboard'), 'route' => route('organization.index')],
    ['label' => __('Join organization')]
  ]" />

  <div class="px-6 pt-12">
    <div class="mx-auto w-full max-w-xl items-start justify-center">
      <x-box title="{{ __('Join organization') }}">
        <x-form method="post" :action="route('organization.join.store')" class="space-y-4">
          <x-input id="invitation_code" name="invitation_code" :label="__('Paste the invitation code')" :help="__('The invitation code is given by the organization administrator.')" :error="$errors->get('invitation_code')" required autofocus />

          <div class="flex items-center justify-between">
            <x-button.secondary href="{{ route('organization.index') }}" turbo="true">
              {{ __('Cancel') }}
            </x-button.secondary>

            <x-button type="submit">
              {{ __('Join') }}
            </x-button>
          </div>
        </x-form>
      </x-box>
    </div>
  </div>
</x-app-layout>
