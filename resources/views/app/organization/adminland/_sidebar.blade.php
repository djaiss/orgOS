<aside class="flex-col border-b border-gray-200 bg-white px-4 py-4 sm:flex sm:rounded-bl-lg sm:border-r sm:border-b-0 dark:border-gray-700 dark:bg-gray-900">
  <nav class="flex flex-col gap-4">
    <div class="flex flex-col gap-1">
      <a data-turbo="true" href="{{ route('settings.security.index') }}" class="{{ request()->routeIs('settings.security.index') ? 'bg-gray-100 font-medium text-gray-900 dark:bg-gray-800 dark:text-gray-100' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800' }} flex items-center gap-2 rounded-lg px-2 py-1">
        <x-phosphor-key class="h-4 w-4 {{ request()->routeIs('settings.security.index') ? 'text-emerald-700' : 'text-gray-500' }}" />
        {{ __('Preferences') }}
      </a>
      <a data-turbo="true" href="" class="{{ request()->routeIs('settings.account.index') ? 'bg-red-50 font-medium text-red-600' : 'text-red-600 hover:bg-red-50' }} flex items-center gap-2 rounded-lg px-2 py-1">
        <x-phosphor-trash class="h-4 w-4" />
        {{ __('Danger zone') }}
      </a>
    </div>
    <div class="flex flex-col gap-1">
      <p class="text-xs font-medium text-gray-500 uppercase">{{ __('Administration') }}</p>
      <a data-turbo="true" href="{{ route('settings.security.index') }}" class="{{ request()->routeIs('settings.security.index') ? 'bg-gray-100 font-medium text-gray-900 dark:bg-gray-800 dark:text-gray-100' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800' }} flex items-center gap-2 rounded-lg px-2 py-1">
        <x-phosphor-key class="h-4 w-4 {{ request()->routeIs('settings.security.index') ? 'text-emerald-700' : 'text-gray-500' }}" />
        {{ __('Organization') }}
      </a>
      <a data-turbo="true" href="{{ route('organization.adminland.member.index', $organization) }}" class="{{ request()->routeIs('organization.adminland.member.index') ? 'bg-gray-100 font-medium text-gray-900 dark:bg-gray-800 dark:text-gray-100' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800' }} flex items-center gap-2 rounded-lg px-2 py-1">
        <x-phosphor-users-three class="h-4 w-4 {{ request()->routeIs('organization.adminland.member.index') ? 'text-emerald-700' : 'text-gray-500' }}" />
        {{ __('Members') }}
      </a>
      <a data-turbo="true" href="{{ route('organization.adminland.office.index', $organization) }}" class="{{ request()->routeIs('organization.adminland.office.index') ? 'bg-gray-100 font-medium text-gray-900 dark:bg-gray-800 dark:text-gray-100' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800' }} flex items-center gap-2 rounded-lg px-2 py-1">
        <x-phosphor-building-office class="h-4 w-4 {{ request()->routeIs('organization.adminland.office.index') ? 'text-emerald-700' : 'text-gray-500' }}" />
        {{ __('Offices') }}
      </a>
      <a data-turbo="true" href="{{ route('organization.adminland.department.index', $organization) }}" class="{{ request()->routeIs('organization.adminland.department.*') ? 'bg-gray-100 font-medium text-gray-900 dark:bg-gray-800 dark:text-gray-100' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800' }} flex items-center gap-2 rounded-lg px-2 py-1">
        <x-phosphor-buildings class="h-4 w-4 {{ request()->routeIs('organization.adminland.department.*') ? 'text-emerald-700' : 'text-gray-500' }}" />
        {{ __('Departments') }}
      </a>
    </div>
  </nav>
</aside>
