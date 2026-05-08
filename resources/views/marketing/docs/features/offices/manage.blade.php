<x-marketing-docs-layout :breadcrumbItems="[
  ['label' => 'Home', 'route' => route('marketing.index')],
  ['label' => 'Documentation', 'route' => route('marketing.docs.index')],
  ['label' => 'Manage offices'],
]">
  <div class="py-16">
    <x-marketing.docs.h1 title="Manage offices" />

    <p class="mb-10">
      This page explains how to create, edit, and delete
      <a href="{{ route('marketing.docs.offices.index') }}" class="text-blue-600 underline hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">offices and office types</a>
      in your organization.
    </p>

    <!-- requirements -->
    <div class="mb-10 border-b border-gray-200 pb-10 dark:border-gray-700">
      <x-marketing.docs.h2 id="requirements" title="Requirements" />
      <p>
        Only users with the
        <strong>Owner</strong>
        or
        <strong>Administrator</strong>
        role can create, edit, or delete offices and office types. Members and Guests can view the list but cannot make changes. For more information on roles, see
        <a href="{{ route('marketing.docs.organizations.index') }}#roles-and-permissions" class="text-blue-600 underline hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">Roles and permissions</a>
        .
      </p>
    </div>

    <!-- manage office types -->
    <div class="mb-10 border-b border-gray-200 pb-10 dark:border-gray-700">
      <x-marketing.docs.h2 id="manage-office-types" title="Manage office types" />

      <h3 id="add-an-office-type" class="mt-6 mb-3 text-lg font-semibold">Add an office type</h3>
      <ol class="mb-8 list-decimal space-y-3 pl-6">
        <li>
          Open your organization's dashboard and click
          <strong>Adminland</strong>
          in the navigation.
        </li>
        <li>
          In the Adminland sidebar, click
          <strong>Offices</strong>
          .
        </li>
        <li>
          On the Offices page you will see an
          <strong>Office types</strong>
          section at the top. Click the
          <strong>Add</strong>
          button on the right side of that section header.
        </li>
        <li>
          A form appears inline. Fill in the following field:
          <ol class="mt-2 list-decimal space-y-2 pl-6">
            <li>
              <strong>Name</strong>
              (required) — the name for the office type. Example:
              <em>Branch</em>
              ,
              <em>Headquarters</em>
              , or
              <em>Warehouse</em>
              .
            </li>
          </ol>
        </li>
        <li>
          Click
          <strong>Create</strong>
          to save. The new office type appears at the bottom of the list.
        </li>
      </ol>

      <h3 id="edit-an-office-type" class="mt-6 mb-3 text-lg font-semibold">Edit an office type</h3>
      <ol class="mb-8 list-decimal space-y-3 pl-6">
        <li>
          Open your organization's dashboard and click
          <strong>Adminland</strong>
          .
        </li>
        <li>
          In the Adminland sidebar, click
          <strong>Offices</strong>
          .
        </li>
        <li>
          In the
          <strong>Office types</strong>
          section, hover over the office type you want to change. An
          <strong>Edit</strong>
          button appears on the right. Click it.
        </li>
        <li>
          An edit form replaces the row inline. You can update:
          <ol class="mt-2 list-decimal space-y-2 pl-6">
            <li>
              <strong>Name</strong>
              — the new name for the office type.
            </li>
          </ol>
        </li>
        <li>
          Click
          <strong>Update</strong>
          to save your changes.
        </li>
      </ol>

      <h3 id="delete-an-office-type" class="mt-6 mb-3 text-lg font-semibold">Delete an office type</h3>
      <p class="mb-4">Deleting an office type removes the classification from the system. Any offices currently assigned that type are not deleted — they simply lose their office type and become unclassified.</p>
      <ol class="list-decimal space-y-3 pl-6">
        <li>
          Open your organization's dashboard and click
          <strong>Adminland</strong>
          .
        </li>
        <li>
          In the Adminland sidebar, click
          <strong>Offices</strong>
          .
        </li>
        <li>
          In the
          <strong>Office types</strong>
          section, hover over the office type you want to remove. A
          <strong>Delete</strong>
          button appears on the right. Click it.
        </li>
        <li>A confirmation prompt appears. Confirm to permanently delete the office type.</li>
      </ol>
    </div>

    <!-- manage offices -->
    <div class="mb-10 border-b border-gray-200 pb-10 dark:border-gray-700">
      <x-marketing.docs.h2 id="manage-offices" title="Manage offices" />

      <h3 id="add-an-office" class="mt-6 mb-3 text-lg font-semibold">Add an office</h3>
      <ol class="mb-8 list-decimal space-y-3 pl-6">
        <li>
          Open your organization's dashboard and click
          <strong>Adminland</strong>
          in the navigation.
        </li>
        <li>
          In the Adminland sidebar, click
          <strong>Offices</strong>
          .
        </li>
        <li>
          Scroll past the Office types section to the
          <strong>Offices</strong>
          section. Click the
          <strong>Add</strong>
          button on the right side of the section header.
        </li>
        <li>
          A form appears inline. Fill in the fields:
          <ol class="mt-2 list-decimal space-y-3 pl-6">
            <li>
              <strong>Name</strong>
              (required) — a recognizable label for the office. Example:
              <em>Scranton Branch</em>
              or
              <em>New York Headquarters</em>
              .
            </li>
            <li>
              <strong>Office type</strong>
              (optional) — select a classification from the dropdown. Leave blank if you do not need a classification.
            </li>
            <li>
              <strong>Address line 1</strong>
              (required) — the street address. Example:
              <em>1725 Slough Avenue</em>
              .
            </li>
            <li>
              <strong>Address line 2</strong>
              (optional) — suite number, floor, or building identifier.
            </li>
            <li>
              <strong>City</strong>
              (required) — the city where the office is located. Example:
              <em>Scranton</em>
              .
            </li>
            <li>
              <strong>State / Province</strong>
              (optional) — the state, province, or region. Example:
              <em>PA</em>
              .
            </li>
            <li>
              <strong>Postal code</strong>
              (optional) — the ZIP or postal code. Example:
              <em>18505</em>
              .
            </li>
            <li>
              <strong>Timezone</strong>
              (optional) — the IANA time zone identifier for this location. Example:
              <em>America/New_York</em>
              ,
              <em>Europe/London</em>
              , or
              <em>Asia/Tokyo</em>
              .
            </li>
            <li>
              <strong>Country</strong>
              (optional) — select the country from the dropdown list.
            </li>
          </ol>
        </li>
        <li>
          Click
          <strong>Create</strong>
          to save. The new office appears in the list.
        </li>
      </ol>

      <h3 id="edit-an-office" class="mt-6 mb-3 text-lg font-semibold">Edit an office</h3>
      <ol class="mb-8 list-decimal space-y-3 pl-6">
        <li>
          Open your organization's dashboard and click
          <strong>Adminland</strong>
          .
        </li>
        <li>
          In the Adminland sidebar, click
          <strong>Offices</strong>
          .
        </li>
        <li>
          In the
          <strong>Offices</strong>
          section, hover over the office you want to update. An
          <strong>Edit</strong>
          button appears on the right. Click it.
        </li>
        <li>
          An edit form replaces the row inline. Update any of the fields described in the
          <a href="#add-an-office" class="text-blue-600 underline hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">Add an office</a>
          section above.
        </li>
        <li>
          Click
          <strong>Update</strong>
          to save your changes.
        </li>
      </ol>

      <h3 id="delete-an-office" class="mt-6 mb-3 text-lg font-semibold">Delete an office</h3>
      <p class="mb-4">Deleting an office permanently removes the location record from the organization. Any members currently associated with that office lose the association and are not themselves deleted.</p>
      <ol class="list-decimal space-y-3 pl-6">
        <li>
          Open your organization's dashboard and click
          <strong>Adminland</strong>
          .
        </li>
        <li>
          In the Adminland sidebar, click
          <strong>Offices</strong>
          .
        </li>
        <li>
          In the
          <strong>Offices</strong>
          section, hover over the office you want to remove. A
          <strong>Delete</strong>
          button appears on the right. Click it.
        </li>
        <li>A confirmation prompt appears. Confirm to permanently delete the office.</li>
      </ol>
    </div>
  </div>

  <x-slot name="rightSidebar">
    <div class="flex">
      <div class="sticky bottom-0 w-full">
        <x-marketing.docs.on-this-page :items="[
          ['id' => 'requirements', 'title' => 'Requirements'],
          ['id' => 'manage-office-types', 'title' => 'Manage office types'],
          ['id' => 'add-an-office-type', 'title' => 'Add an office type'],
          ['id' => 'edit-an-office-type', 'title' => 'Edit an office type'],
          ['id' => 'delete-an-office-type', 'title' => 'Delete an office type'],
          ['id' => 'manage-offices', 'title' => 'Manage offices'],
          ['id' => 'add-an-office', 'title' => 'Add an office'],
          ['id' => 'edit-an-office', 'title' => 'Edit an office'],
          ['id' => 'delete-an-office', 'title' => 'Delete an office'],
        ]" />
      </div>
    </div>
  </x-slot>
</x-marketing-docs-layout>
