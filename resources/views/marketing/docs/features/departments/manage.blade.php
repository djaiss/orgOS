<x-marketing-docs-layout :breadcrumbItems="[
  ['label' => 'Home', 'route' => route('marketing.index')],
  ['label' => 'Documentation', 'route' => route('marketing.docs.index')],
  ['label' => 'Manage departments'],
]">
  <div class="py-16">
    <x-marketing.docs.h1 title="Manage departments" />

    <p class="mb-10">
      This page explains how to create, edit, and delete
      <a href="{{ route('marketing.docs.departments.index') }}" class="text-blue-600 underline hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">departments</a>
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
        role can create, edit, or delete departments. Members and Guests can view the list but cannot make changes. For more information on roles, see
        <a href="{{ route('marketing.docs.organizations.index') }}#roles-and-permissions" class="text-blue-600 underline hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">Roles and permissions</a>
        .
      </p>
    </div>

    <!-- add a department -->
    <div class="mb-10 border-b border-gray-200 pb-10 dark:border-gray-700">
      <x-marketing.docs.h2 id="add-a-department" title="Add a department" />
      <ol class="list-decimal space-y-3 pl-6">
        <li>
          Open your organization's dashboard and click
          <strong>Adminland</strong>
          in the navigation.
        </li>
        <li>
          In the Adminland sidebar, click
          <strong>Departments</strong>
          .
        </li>
        <li>
          Click the
          <strong>Add</strong>
          button on the right side of the
          <strong>Departments</strong>
          section header.
        </li>
        <li>
          A form appears inline. Fill in the following field:
          <ol class="mt-2 list-decimal space-y-2 pl-6">
            <li>
              <strong>Name</strong>
              (required) — the name of the department. Example:
              <em>Engineering</em>
              ,
              <em>Sales</em>
              , or
              <em>Human Resources</em>
              .
            </li>
          </ol>
        </li>
        <li>
          Click
          <strong>Create</strong>
          to save. The new department appears at the bottom of the list.
        </li>
      </ol>
      <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">
        Each department is assigned a
        <strong>position</strong>
        automatically. Position controls the display order of departments in the list. You can change a department's position by editing it.
      </p>
    </div>

    <!-- edit a department -->
    <div class="mb-10 border-b border-gray-200 pb-10 dark:border-gray-700">
      <x-marketing.docs.h2 id="edit-a-department" title="Edit a department" />
      <ol class="list-decimal space-y-3 pl-6">
        <li>
          Open your organization's dashboard and click
          <strong>Adminland</strong>
          .
        </li>
        <li>
          In the Adminland sidebar, click
          <strong>Departments</strong>
          .
        </li>
        <li>
          Hover over the department you want to update. An
          <strong>Edit</strong>
          button appears on the right. Click it.
        </li>
        <li>
          An edit form replaces the row inline. You can update:
          <ol class="mt-2 list-decimal space-y-2 pl-6">
            <li>
              <strong>Name</strong>
              — the new name for the department.
            </li>
          </ol>
        </li>
        <li>
          Click
          <strong>Update</strong>
          to save your changes.
        </li>
      </ol>
    </div>

    <!-- delete a department -->
    <div class="mb-10 border-b border-gray-200 pb-10 dark:border-gray-700">
      <x-marketing.docs.h2 id="delete-a-department" title="Delete a department" />
      <p class="mb-4">Deleting a department permanently removes it from the organization. Members currently associated with that department lose the association but are not themselves deleted.</p>
      <ol class="list-decimal space-y-3 pl-6">
        <li>
          Open your organization's dashboard and click
          <strong>Adminland</strong>
          .
        </li>
        <li>
          In the Adminland sidebar, click
          <strong>Departments</strong>
          .
        </li>
        <li>
          Hover over the department you want to remove. A
          <strong>Delete</strong>
          button appears on the right. Click it.
        </li>
        <li>A confirmation prompt appears. Confirm to permanently delete the department.</li>
      </ol>
    </div>
  </div>

  <x-slot name="rightSidebar">
    <div class="flex">
      <div class="sticky bottom-0 w-full">
        <x-marketing.docs.on-this-page :items="[
          ['id' => 'requirements', 'title' => 'Requirements'],
          ['id' => 'add-a-department', 'title' => 'Add a department'],
          ['id' => 'edit-a-department', 'title' => 'Edit a department'],
          ['id' => 'delete-a-department', 'title' => 'Delete a department'],
        ]" />
      </div>
    </div>
  </x-slot>
</x-marketing-docs-layout>
