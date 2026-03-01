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
        <div x-data="{ showInvite: false }">
          <x-box padding="p-0">
            <x-slot:title>{{ __('All the members') }}</x-slot>

            <x-slot:actions>
              <div class="flex items-center gap-x-2">
                <x-button.secondary @click="showInvite = !showInvite">
                  {{ __('Invite') }}
                </x-button.secondary>
              </div>
            </x-slot>

            <x-slot:additional-info>
              <div x-cloak x-show="showInvite" x-transition class="rounded-lg border border-gray-200 bg-white p-3 dark:border-gray-700 dark:bg-gray-900">
                <p class="mb-3 text-sm text-gray-600 dark:text-gray-400">{{ __('Send this code to your teammates so they can join your organization:') }}</p>
                <div x-data="{
                  copied: false,
                  copyToClipboard() {
                    const el = document.createElement('textarea')
                    el.value = '{{ $organization->invitation_code }}'
                    document.body.appendChild(el)
                    el.select()
                    document.execCommand('copy')
                    document.body.removeChild(el)
                    this.copied = true
                    setTimeout(() => {
                      this.copied = false
                    }, 2000)
                  },
                }" class="flex items-center gap-x-2">
                  <code class="flex-1 rounded border border-gray-200 bg-gray-50 px-3 py-2 text-center font-mono text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">{{ $organization->invitation_code }}</code>
                  <button @click="copyToClipboard()" class="inline-flex cursor-pointer items-center rounded-md border border-gray-200 bg-white px-3 py-2 text-sm font-semibold text-gray-600 shadow-sm hover:bg-gray-50 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-800">
                    <x-phosphor-check x-show="copied" class="mr-1 h-4 w-4" />
                    <x-phosphor-copy x-show="!copied" class="mr-1 h-4 w-4" />
                    <span x-text="copied ? '{{ __('Copied') }}' : '{{ __('Copy') }}'"></span>
                  </button>
                </div>
              </div>
            </x-slot>

            @foreach ($members as $member)
              <div class="flex items-center justify-between border-b border-gray-200 p-3 text-sm first:rounded-t-lg last:rounded-b-lg last:border-b-0 hover:bg-blue-50 dark:border-gray-700 dark:hover:bg-gray-800">
                <div class="flex items-center gap-3">
                  <x-phosphor-pulse class="size-3 min-w-3 text-zinc-600 dark:text-zinc-400" />
                  <p class="flex items-center gap-4">
                    <x-link href="">{{ $member->name }}</x-link>
                    <span class="font-mono text-xs">{{ $member->email }}</span>
                    <span>{{ $member->permission }}</span>
                  </p>
                </div>

                <x-tooltip text="{{ $member->joined_at?->format('Y-m-d H:i:s') }}">
                  <p class="font-mono text-xs">{{ $member->joined_at?->diffForHumans() }}</p>
                </x-tooltip>
              </div>
            @endforeach
          </x-box>
        </div>
      </div>
    </section>
  </div>
</x-app-layout>
