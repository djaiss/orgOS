<x-app-layout :organization="$organization">
  <x-slot:title>
    {{ __('Members') }}
  </x-slot>

  <x-breadcrumb :items="[
    ['label' => __('Dashboard'), 'route' => route('organization.show', $organization)],
    ['label' => __('Adminland'), 'route' => route('organization.adminland.index', $organization)],
    ['label' => __('Members')]
  ]" />

  <!-- settings layout -->
  <div class="grid grow bg-gray-50 sm:grid-cols-[220px_1fr] dark:bg-gray-950">
    <!-- Sidebar -->
    @include('app.organization.adminland._sidebar')

    <!-- Main content -->
    <section class="p-4 sm:p-8">
      <div class="mx-auto max-w-5xl space-y-6 sm:px-0">
        <x-box padding="p-0">
          <x-slot:title>{{ __('All the members') }}</x-slot>

          <x-slot:actions>
            <div class="flex items-center gap-x-2">
            <x-button.secondary href="{{ route('organization.adminland.member.invite.index', $organization) }}" turbo="true">
              {{ __('Invite') }}
            </x-button.secondary>
            </div>
          </x-slot:actions>

          @foreach ($members as $member)
            <div class="flex items-center justify-between border-b border-gray-200 p-3 text-sm first:rounded-t-lg last:rounded-b-lg last:border-b-0 hover:bg-blue-50 dark:border-gray-700 dark:hover:bg-gray-800">
              <div class="flex items-center gap-3">
                <x-phosphor-pulse class="size-3 min-w-3 text-zinc-600 dark:text-zinc-400" />
                <div class="flex flex-col gap-y-2">
                  <p class="flex items-center gap-2">
                    <x-link href="">{{ $member->name }}</x-link>

                    <span class="font-mono text-xs">{{ $member->email }}</span>
                  </p>
                </div>
              </div>

              <x-tooltip text="{{ $member->joined_at?->format('Y-m-d H:i:s') }}">
                <p class="font-mono text-xs">{{ $member->joined_at?->diffForHumans() }}</p>
              </x-tooltip>
            </div>
          @endforeach
        </x-box>
      </div>
    </section>
  </div>
</x-app-layout>
