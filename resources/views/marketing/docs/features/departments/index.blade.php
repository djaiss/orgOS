<x-marketing-docs-layout :breadcrumbItems="[
  ['label' => 'Home', 'route' => route('marketing.index')],
  ['label' => 'Documentation', 'route' => route('marketing.docs.index')],
  ['label' => 'Departments'],
]">
  <div class="py-16">
    <x-marketing.docs.h1 title="Departments" />

    <!-- overview -->
    <div class="mb-10 border-b border-gray-200 pb-10 dark:border-gray-700">
      <x-marketing.docs.h2 id="overview" title="Overview" />
      <p class="mb-4">
        A department is a named group that represents a functional area of your
        <a href="{{ route('marketing.docs.organizations.index') }}" class="text-blue-600 underline hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">organization</a>
        . Common examples are
        <strong>Engineering</strong>
        ,
        <strong>Sales</strong>
        ,
        <strong>Marketing</strong>
        , and
        <strong>Human Resources</strong>
        .
      </p>
      <p>
        Departments belong to a single organization. They are not shared across organizations. To learn how to create, edit, and delete departments, see
        <a href="{{ route('marketing.docs.departments.manage') }}" class="text-blue-600 underline hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">Manage departments</a>
        .
      </p>
    </div>

    <!-- how they work -->
    <div class="mb-10 border-b border-gray-200 pb-10 dark:border-gray-700">
      <x-marketing.docs.h2 id="how-they-work" title="How they work" />
      <p class="mb-4">Departments give your organization a single, authoritative list of the teams that exist. Rather than each person describing their group differently, departments create a consistent vocabulary used everywhere in orgOS.</p>
      <p class="mb-4">At Dunder Mifflin Paper Company, several departments co-exist under the same roof at the Scranton branch:</p>
      <ul class="mb-4 list-disc space-y-1 pl-6">
        <li>
          <strong>Sales</strong>
          — the paper-selling team led by Dwight Schrute
        </li>
        <li>
          <strong>Human Resources</strong>
          — managed by Toby Flenderson
        </li>
        <li>
          <strong>Accounting</strong>
          — where Angela Martin, Kevin Malone, and Oscar Martinez work
        </li>
        <li>
          <strong>Reception</strong>
          — Pam Beesly's domain
        </li>
      </ul>
      <p>Defining these as departments in orgOS means anyone can immediately see how the company is structured, who belongs where, and how many people are in each functional area.</p>
    </div>

    <!-- what departments are not -->
    <div class="mb-10 border-b border-gray-200 pb-10 dark:border-gray-700">
      <x-marketing.docs.h2 id="what-departments-are-not" title="What departments are not" />
      <p class="mb-4">Departments in orgOS are intentionally simple. They are flat labels — they do not enforce a reporting hierarchy, parent–child relationships, or budget structures. If you need a nested org chart, that is a separate concept.</p>
      <p>You can arrange departments in any display order by adjusting their position. The order you choose has no functional effect on the product; it only affects how the list appears in the UI.</p>
    </div>
  </div>

  <x-slot name="rightSidebar">
    <div class="flex">
      <div class="sticky bottom-0 w-full">
        <x-marketing.docs.on-this-page :items="[
          ['id' => 'overview', 'title' => 'Overview'],
          ['id' => 'how-they-work', 'title' => 'How they work'],
          ['id' => 'what-departments-are-not', 'title' => 'What departments are not'],
        ]" />
      </div>
    </div>
  </x-slot>
</x-marketing-docs-layout>
