<x-marketing-layout>
  @if (! empty($breadcrumbItems))
    <x-breadcrumb :items="$breadcrumbItems" />
  @endif

  <div class="relative mx-auto max-w-7xl px-6 lg:px-8 xl:px-0">
    <div class="grid grid-cols-1 gap-x-16 lg:grid-cols-[250px_1fr]">
      <!-- Sidebar -->
      <div class="hidden w-full flex-shrink-0 flex-col justify-self-end sm:border-r sm:border-gray-200 sm:pr-3 lg:flex dark:sm:border-gray-700">
        <div x-data="{
          openApiDocumentation:
            '{{ str_starts_with( request()->route()->getName(),'marketing.docs.api.',) ? 'true' : 'false' }}' ===
            'true',
          organizationsDocumentation:
            '{{ str_starts_with( request()->route()->getName(),'marketing.docs.api.organizations.',) ? 'true' : 'false' }}' ===
            'true',
          officeTypesDocumentation:
            '{{ request()->routeIs('marketing.docs.api.organizations.officetypes.*') ? 'true' : 'false' }}' ===
            'true',
        }" class="bg-light dark:bg-dark z-10 pt-16">
          <!-- api documentation -->
          <div @click="openApiDocumentation = !openApiDocumentation" class="mb-2 flex cursor-pointer items-center justify-between rounded-md border border-transparent px-2 py-1 hover:border-gray-200 hover:bg-blue-50 dark:hover:border-gray-700 dark:hover:bg-gray-800">
            <h3>API documentation</h3>
            <x-phosphor-caret-right x-bind:class="openApiDocumentation ? 'rotate-90' : ''" class="h-4 w-4 text-gray-500 transition-transform duration-300" />
          </div>

          <!-- api documentation sub menu -->
          <div x-show="openApiDocumentation" x-cloak class="mb-10 ml-3">
            <div class="mb-3 flex flex-col gap-y-2">
              <div>
                <a href="{{ route('marketing.docs.api.index') }}" class="{{ request()->routeIs('marketing.docs.api.index') ? 'border-l-blue-400' : 'border-l-transparent' }} block border-l-3 pl-3 hover:border-l-blue-400 hover:underline">Introduction</a>
              </div>
            </div>

            <!-- organizations -->
            <div @click="organizationsDocumentation = !organizationsDocumentation" class="mb-3 flex cursor-pointer items-center justify-between rounded-md border border-transparent px-2 py-1 pl-3 text-xs text-gray-500 uppercase hover:border-gray-200 hover:bg-blue-50 dark:text-gray-400 dark:hover:border-gray-700 dark:hover:bg-gray-800">
              <h3>Organizations</h3>
              <x-phosphor-caret-right x-bind:class="organizationsDocumentation ? 'rotate-90' : ''" class="h-4 w-4 text-gray-500 transition-transform duration-300" />
            </div>
            <div x-show="organizationsDocumentation" class="mb-3 flex flex-col gap-y-2">
              <div>
                <a href="{{ route('marketing.docs.api.organizations.index') }}" class="{{ request()->routeIs('marketing.docs.api.organizations.index') ? 'border-l-blue-400' : 'border-l-transparent' }} block border-l-3 pl-3 hover:border-l-blue-400 hover:underline">Organizations</a>
              </div>

              <!-- adminland -->
              <div @click.stop="officeTypesDocumentation = !officeTypesDocumentation" class="flex cursor-pointer items-center justify-between rounded-md border border-transparent px-2 py-1 pl-3 text-xs text-gray-500 uppercase hover:border-gray-200 hover:bg-blue-50 dark:text-gray-400 dark:hover:border-gray-700 dark:hover:bg-gray-800">
                <h3>Adminland</h3>
                <x-phosphor-caret-right x-bind:class="officeTypesDocumentation ? 'rotate-90' : ''" class="h-4 w-4 text-gray-500 transition-transform duration-300" />
              </div>
              <div x-show="officeTypesDocumentation" class="flex flex-col gap-y-2">
                <div>
                  <a href="{{ route('marketing.docs.api.organizations.officetypes.index') }}" class="{{ request()->routeIs('marketing.docs.api.organizations.officetypes.index') ? 'border-l-blue-400' : 'border-l-transparent' }} block border-l-3 pl-6 hover:border-l-blue-400 hover:underline">Office Types</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Main content -->
      {{ $slot }}
    </div>
  </div>
</x-marketing-layout>
