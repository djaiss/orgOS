<x-app-layout>
  <x-slot:title>
    {{ __('Invite Member') }}
  </x-slot>

  <x-breadcrumb :items="[
    ['label' => __('Dashboard'), 'route' => route('organization.show', $organization)],
    ['label' => __('Adminland'), 'route' => route('organization.adminland.index', $organization)],
    ['label' => __('Members'), 'route' => route('organization.adminland.member.index', $organization)],
    ['label' => __('Invite')]
  ]" />

  <div class="px-6 pt-12">
    <div class="mx-auto w-full max-w-xl items-start justify-center">
      <x-box title="{{ __('Invite Member') }}">
        <x-form method="post" :action="route('organization.adminland.member.invite.store', $organization)" class="space-y-4">
          <x-input id="email_address" name="email" :label="__('Email')" :help="__('An email will be sent to the email address of the member you want to invite.')" :error="$errors->get('email')" required placeholder="example@domain.com" autofocus />

          <div class="flex items-center justify-between">
            <x-button.secondary href="{{ route('organization.adminland.member.index', $organization) }}" turbo="true">
              {{ __('Cancel') }}
            </x-button.secondary>

            <x-button type="submit">
              {{ __('Invite') }}
            </x-button>
          </div>
        </x-form>
      </x-box>
    </div>
  </div>
</x-app-layout>
