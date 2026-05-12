<x-marketing-layout>
  @if (! empty($breadcrumbItems))
    <x-breadcrumb :items="$breadcrumbItems" />
  @endif

  <div class="relative mx-auto max-w-7xl px-6 lg:px-8 xl:px-0">
    <div class="grid grid-cols-1 gap-x-16 {{ isset($rightSidebar) ? 'lg:grid-cols-[300px_1fr_250px]' : 'lg:grid-cols-[300px_1fr]' }}">
      <!-- Sidebar -->
      <div class="hidden w-full shrink-0 flex-col justify-self-end sm:border-r sm:border-gray-200 sm:pr-3 lg:flex dark:sm:border-gray-700">
        <div
          x-data="{
            productDocumentation:
              '{{ request()->routeIs('marketing.docs.index') || request()->routeIs('marketing.docs.organizations.*') || request()->routeIs('marketing.docs.offices.*') || request()->routeIs('marketing.docs.departments.*') ? 'true' : 'false' }}' ===
              'true',
            manageYourOrganizationDocumentation:
              '{{ request()->routeIs('marketing.docs.organizations.*') || request()->routeIs('marketing.docs.offices.*') || request()->routeIs('marketing.docs.departments.*') ? 'true' : 'false' }}' ===
              'true',
            manageOfficesDocumentation:
              '{{ request()->routeIs('marketing.docs.offices.*') ? 'true' : 'false' }}' ===
              'true',
            manageDepartmentsDocumentation:
              '{{ request()->routeIs('marketing.docs.departments.*') ? 'true' : 'false' }}' ===
              'true',
            openApiDocumentation:
              '{{ str_starts_with( request()->route()->getName(),'marketing.docs.api.',) ? 'true' : 'false' }}' ===
              'true',
            organizationsDocumentation:
              '{{ str_starts_with( request()->route()->getName(),'marketing.docs.api.organizations.',) ? 'true' : 'false' }}' ===
              'true',
            officeTypesDocumentation:
              '{{ request()->routeIs('marketing.docs.api.organizations.officetypes.*') ? 'true' : 'false' }}' ===
              'true',
            officesDocumentation:
              '{{ request()->routeIs('marketing.docs.api.organizations.offices.*') ? 'true' : 'false' }}' ===
              'true',
            membersDocumentation:
              '{{ request()->routeIs('marketing.docs.api.organizations.members.*') ? 'true' : 'false' }}' ===
              'true',
            memberTypesDocumentation:
              '{{ request()->routeIs('marketing.docs.api.organizations.membertypes.*') ? 'true' : 'false' }}' ===
              'true',
            departmentsDocumentation:
              '{{ request()->routeIs('marketing.docs.api.organizations.departments.*') ? 'true' : 'false' }}' ===
              'true',
          }"
          class="bg-light dark:bg-dark z-10 pt-16">
          <!-- product documentation -->
          <div @click="productDocumentation = !productDocumentation" class="mb-2 flex cursor-pointer items-center justify-between rounded-md border border-transparent px-2 py-1 hover:border-gray-200 hover:bg-blue-50 dark:hover:border-gray-700 dark:hover:bg-gray-800">
            <h3>Product documentation</h3>
            <x-phosphor-caret-right x-bind:class="productDocumentation ? 'rotate-90' : ''" class="h-4 w-4 text-gray-500 transition-transform duration-300" />
          </div>

          <div x-show="productDocumentation" x-cloak class="mb-10 ml-3">
            <div class="mb-3 flex flex-col gap-y-2">
              <div>
                <a href="{{ route('marketing.docs.index') }}" class="{{ request()->routeIs('marketing.docs.index') ? 'border-l-blue-400' : 'border-l-transparent' }} block border-l-3 pl-3 hover:border-l-blue-400 hover:underline">Introduction</a>
              </div>
            </div>

            <!-- manage your organization -->
            <div @click.stop="manageYourOrganizationDocumentation = !manageYourOrganizationDocumentation" class="mb-3 flex cursor-pointer items-center justify-between rounded-md border border-transparent px-2 py-1 pl-3 text-xs text-gray-500 uppercase hover:border-gray-200 hover:bg-blue-50 dark:text-gray-400 dark:hover:border-gray-700 dark:hover:bg-gray-800">
              <h3>Manage your organization</h3>
              <x-phosphor-caret-right x-bind:class="manageYourOrganizationDocumentation ? 'rotate-90' : ''" class="h-4 w-4 text-gray-500 transition-transform duration-300" />
            </div>
            <div x-show="manageYourOrganizationDocumentation" class="mb-3 flex flex-col gap-y-2">
              {{-- getting started --}}
              <div>
                <a href="{{ route('marketing.docs.organizations.index') }}" class="{{ request()->routeIs('marketing.docs.organizations.index') ? 'border-l-blue-400' : 'border-l-transparent' }} block border-l-3 pl-6 hover:border-l-blue-400 hover:underline">Getting started</a>
              </div>

              {{-- manage offices --}}
              <p class="mt-2 pl-6 text-xs font-semibold tracking-widest text-gray-400 uppercase dark:text-gray-500">Manage offices</p>
              <div>
                <a href="{{ route('marketing.docs.offices.index') }}" class="{{ request()->routeIs('marketing.docs.offices.index') ? 'border-l-blue-400' : 'border-l-transparent' }} block border-l-3 pl-6 hover:border-l-blue-400 hover:underline">Getting started</a>
              </div>
              <div>
                <a href="{{ route('marketing.docs.offices.manage') }}" class="{{ request()->routeIs('marketing.docs.offices.manage') ? 'border-l-blue-400' : 'border-l-transparent' }} block border-l-3 pl-6 hover:border-l-blue-400 hover:underline">Manage offices</a>
              </div>

              {{-- manage departments --}}
              <p class="mt-2 pl-6 text-xs font-semibold tracking-widest text-gray-400 uppercase dark:text-gray-500">Manage departments</p>
              <div>
                <a href="{{ route('marketing.docs.departments.index') }}" class="{{ request()->routeIs('marketing.docs.departments.index') ? 'border-l-blue-400' : 'border-l-transparent' }} block border-l-3 pl-6 hover:border-l-blue-400 hover:underline">Getting started</a>
              </div>
              <div>
                <a href="{{ route('marketing.docs.departments.manage') }}" class="{{ request()->routeIs('marketing.docs.departments.manage') ? 'border-l-blue-400' : 'border-l-transparent' }} block border-l-3 pl-6 hover:border-l-blue-400 hover:underline">Manage departments</a>
              </div>
            </div>
          </div>

          <!-- api documentation -->
          <div @click="openApiDocumentation = !openApiDocumentation" class="mb-2 flex cursor-pointer items-center justify-between rounded-md border border-transparent px-2 py-1 hover:border-gray-200 hover:bg-blue-50 dark:hover:border-gray-700 dark:hover:bg-gray-800">
            <h3>API documentation</h3>
            <x-phosphor-caret-right x-bind:class="openApiDocumentation ? 'rotate-90' : ''" class="h-4 w-4 text-gray-500 transition-transform duration-300" />
          </div>

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
                <a href="{{ route('marketing.docs.api.organizations.index') }}" class="{{ request()->routeIs('marketing.docs.api.organizations.index') ? 'border-l-blue-400' : 'border-l-transparent' }} block border-l-3 pl-6 hover:border-l-blue-400 hover:underline">Organizations</a>
              </div>

              <!-- adminland (api) -->
              <div @click.stop="officeTypesDocumentation = !officeTypesDocumentation; officesDocumentation = !officesDocumentation; membersDocumentation = !membersDocumentation; memberTypesDocumentation = !memberTypesDocumentation; departmentsDocumentation = !departmentsDocumentation" class="flex cursor-pointer items-center justify-between rounded-md border border-transparent px-2 py-1 pl-6 text-xs text-gray-500 uppercase hover:border-gray-200 hover:bg-blue-50 dark:text-gray-400 dark:hover:border-gray-700 dark:hover:bg-gray-800">
                <h3>Adminland</h3>
                <x-phosphor-caret-right x-bind:class="officeTypesDocumentation || officesDocumentation || membersDocumentation || memberTypesDocumentation || departmentsDocumentation ? 'rotate-90' : ''" class="h-4 w-4 text-gray-500 transition-transform duration-300" />
              </div>
              <div x-show="officeTypesDocumentation || officesDocumentation || membersDocumentation || memberTypesDocumentation || departmentsDocumentation" class="flex flex-col gap-y-2">
                <div>
                  <a href="{{ route('marketing.docs.api.organizations.officetypes.index') }}" class="{{ request()->routeIs('marketing.docs.api.organizations.officetypes.index') ? 'border-l-blue-400' : 'border-l-transparent' }} block border-l-3 pl-9 hover:border-l-blue-400 hover:underline">Office Types</a>
                </div>
                <div>
                  <a href="{{ route('marketing.docs.api.organizations.offices.index') }}" class="{{ request()->routeIs('marketing.docs.api.organizations.offices.index') ? 'border-l-blue-400' : 'border-l-transparent' }} block border-l-3 pl-9 hover:border-l-blue-400 hover:underline">Offices</a>
                </div>
                <div>
                  <a href="{{ route('marketing.docs.api.organizations.members.index') }}" class="{{ request()->routeIs('marketing.docs.api.organizations.members.index') ? 'border-l-blue-400' : 'border-l-transparent' }} block border-l-3 pl-9 hover:border-l-blue-400 hover:underline">Members</a>
                </div>
                <div>
                  <a href="{{ route('marketing.docs.api.organizations.membertypes.index') }}" class="{{ request()->routeIs('marketing.docs.api.organizations.membertypes.index') ? 'border-l-blue-400' : 'border-l-transparent' }} block border-l-3 pl-9 hover:border-l-blue-400 hover:underline">Member Types</a>
                </div>
                <div>
                  <a href="{{ route('marketing.docs.api.organizations.departments.index') }}" class="{{ request()->routeIs('marketing.docs.api.organizations.departments.index') ? 'border-l-blue-400' : 'border-l-transparent' }} block border-l-3 pl-9 hover:border-l-blue-400 hover:underline">Departments</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Main content -->
      <div>
        {{ $slot }}
      </div>

      <!-- Sidebar -->
        @if ($rightSidebar ?? false)
          <div class="hidden w-full shrink-0 flex-col justify-self-end py-16 sm:border-l sm:border-gray-200 sm:pl-6 lg:flex">
            {{ $rightSidebar ?? '' }}
          </div>
        @endif
    </div>
  </div>
</x-marketing-layout>
