<x-marketing-docs-layout :breadcrumbItems="[
  ['label' => 'Home', 'route' => route('marketing.index')],
  ['label' => 'Documentation', 'route' => route('marketing.docs.index')],
  ['label' => 'Organizations'],
]">
  <div class="py-16">
    <x-marketing.docs.h1 title="Organizations" />

    <!-- what is an organization -->
    <div class="mb-10 border-b border-gray-200 pb-10 dark:border-gray-700">
      <x-marketing.docs.h2 id="what-is-an-organization" title="What is an organization?" />
      <p class="mb-4">An organization is the central workspace in orgOS. Everything — members, offices, departments, and all administrative settings — lives inside an organization. Think of it as your company's home inside the product.</p>
      <p class="mb-4">When Michael Scott sets up Dunder Mifflin Paper Company in orgOS, he is creating an organization. Every person he invites, every office he registers, and every department he defines all belong to that one organization.</p>
      <p>
        An organization has a
        <strong>name</strong>
        and a unique
        <strong>slug</strong>
        that is used in URLs. It also has an
        <strong>invitation code</strong>
        — a secret string that lets other people join it.
      </p>
    </div>

    <!-- roles and permissions -->
    <div class="mb-10 border-b border-gray-200 pb-10 dark:border-gray-700">
      <x-marketing.docs.h2 id="roles-and-permissions" title="Roles and permissions" />
      <p class="mb-6">Every person inside an organization has a role. The role determines what that person can see and do. orgOS defines four roles, from most to least privileged:</p>

      <div class="mb-6 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
        <table class="w-full text-sm">
          <thead class="bg-gray-50 dark:bg-gray-800">
            <tr>
              <th class="px-4 py-3 text-left font-semibold">Role</th>
              <th class="px-4 py-3 text-left font-semibold">What they can do</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            <tr>
              <td class="px-4 py-3 font-medium">Owner</td>
              <td class="px-4 py-3">Full access. Can manage everything in Adminland, including deleting the organization. Typically the person who created it.</td>
            </tr>
            <tr>
              <td class="px-4 py-3 font-medium">Administrator</td>
              <td class="px-4 py-3">Can manage most Adminland settings — offices, office types, departments, member types, and members — but cannot delete the organization.</td>
            </tr>
            <tr>
              <td class="px-4 py-3 font-medium">Member</td>
              <td class="px-4 py-3">Can view the organization and its data. Cannot modify Adminland settings. This is the default role when someone joins via an invitation code.</td>
            </tr>
            <tr>
              <td class="px-4 py-3 font-medium">Guest</td>
              <td class="px-4 py-3">Limited read-only access. Useful for contractors or external collaborators who need visibility without full membership.</td>
            </tr>
          </tbody>
        </table>
      </div>

      <p>
        The person who creates an organization is automatically given the
        <strong>Owner</strong>
        role. Owners and Administrators can update the roles of other members from Adminland.
      </p>
    </div>

    <!-- creating an organization -->
    <div class="mb-10 border-b border-gray-200 pb-10 dark:border-gray-700">
      <x-marketing.docs.h2 id="creating-an-organization" title="Creating an organization" />
      <p class="mb-4">
        Any authenticated user can create a new organization. Once created, that user becomes its
        <strong>Owner</strong>
        .
      </p>
      <ol class="mb-4 list-decimal space-y-2 pl-6">
        <li>
          From your dashboard, click
          <strong>Create an organization</strong>
          .
        </li>
        <li>Enter the organization name. Names may contain letters, numbers, spaces, hyphens, and underscores.</li>
        <li>Save. orgOS will automatically generate a unique URL slug and an invitation code for you.</li>
      </ol>
      <p>After creation, orgOS runs a background job to pre-populate the organization with sensible defaults so you can get started right away.</p>
    </div>

    <!-- joining an organization -->
    <div class="mb-10 border-b border-gray-200 pb-10 dark:border-gray-700">
      <x-marketing.docs.h2 id="joining-an-organization" title="Joining an organization" />
      <p class="mb-4">
        You do not need to create your own organization to use orgOS. If your company is already set up, an Owner or Administrator can share the organization's
        <strong>invitation code</strong>
        with you, and you can use it to join.
      </p>
      <p class="mb-4">
        When Pam Beesly joins Dunder Mifflin in orgOS, Michael simply gives her the invitation code. She enters it on the
        <em>Join an organization</em>
        screen, and she is immediately added as a
        <strong>Member</strong>
        .
      </p>
      <ol class="mb-4 list-decimal space-y-2 pl-6">
        <li>
          From your dashboard, click
          <strong>Join an organization</strong>
          .
        </li>
        <li>Paste or type the invitation code you received.</li>
        <li>Confirm. You will be added to the organization as a Member and taken to its dashboard.</li>
      </ol>
      <p class="mb-4">The invitation code is a long random string — keep it private. Anyone who has it can join the organization. If the code is ever compromised, an Owner or Administrator can regenerate it from Adminland.</p>
      <p>You cannot join an organization you are already a member of. orgOS will tell you so and prevent a duplicate membership from being created.</p>
    </div>

    <!-- multiple organizations -->
    <div class="mb-10 border-b border-gray-200 pb-10 dark:border-gray-700">
      <x-marketing.docs.h2 id="multiple-organizations" title="Belonging to multiple organizations" />
      <p class="mb-4">A single user account can belong to more than one organization simultaneously. This is useful for consultants, contractors, or anyone who works across multiple companies.</p>
      <p>Each membership is independent — your role in one organization has no effect on your role in another. You can switch between organizations from your dashboard at any time.</p>
    </div>

    <!-- adminland -->
    <div class="mb-10 border-b border-gray-200 pb-10 dark:border-gray-700">
      <x-marketing.docs.h2 id="adminland" title="Adminland" />
      <p class="mb-4">
        <strong>Adminland</strong>
        is the administration area of your organization. It is only accessible to users with the
        <strong>Owner</strong>
        or
        <strong>Administrator</strong>
        role.
      </p>
      <p class="mb-4">From Adminland you can manage:</p>
      <ul class="mb-4 list-disc space-y-1 pl-6">
        <li>
          <a href="{{ route('marketing.docs.offices.index') }}" class="text-blue-600 underline hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">Office types</a>
          — the categories used to classify your offices
        </li>
        <li>
          <a href="{{ route('marketing.docs.offices.index') }}" class="text-blue-600 underline hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">Offices</a>
          — the physical and virtual locations your organization operates from
        </li>
        <li>
          <a href="{{ route('marketing.docs.departments.index') }}" class="text-blue-600 underline hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">Departments</a>
          — the functional groups that make up your organization
        </li>
        <li>Members and their roles</li>
        <li>Member types</li>
      </ul>
      <p>Regular members can view the organization but cannot access or modify any Adminland settings.</p>
    </div>
  </div>

  <x-slot name="rightSidebar">
    <div class="flex">
      <div class="sticky bottom-0 w-full">
        <x-marketing.docs.on-this-page :items="[
          ['id' => 'what-is-an-organization', 'title' => 'What is an organization?'],
          ['id' => 'roles-and-permissions', 'title' => 'Roles and permissions'],
          ['id' => 'creating-an-organization', 'title' => 'Creating an organization'],
          ['id' => 'joining-an-organization', 'title' => 'Joining an organization'],
          ['id' => 'multiple-organizations', 'title' => 'Belonging to multiple organizations'],
          ['id' => 'adminland', 'title' => 'Adminland'],
        ]" />
      </div>
    </div>
  </x-slot>
</x-marketing-docs-layout>
