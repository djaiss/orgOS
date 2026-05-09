<x-marketing-docs-layout :breadcrumbItems="[
  ['label' => 'Home', 'route' => route('marketing.index')],
  ['label' => 'Documentation', 'route' => route('marketing.docs.index')],
  ['label' => 'Offices'],
]">
  <div class="py-16">
    <x-marketing.docs.h1 title="Offices" />

    <!-- overview -->
    <div class="mb-10 border-b border-gray-200 pb-10 dark:border-gray-700">
      <x-marketing.docs.h2 id="overview" title="Overview" />
      <p class="mb-4">
        An office is a physical or virtual location where your
        <a href="{{ route('marketing.docs.organizations.index') }}" class="text-blue-600 underline hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">organization</a>
        operates. Offices are organized using office types — reusable labels that classify what kind of location each office is.
      </p>
      <p>
        To learn how to create, edit, and delete offices and office types, see
        <a href="{{ route('marketing.docs.offices.manage') }}" class="text-blue-600 underline hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">Manage offices</a>
        .
      </p>
    </div>

    <!-- office types -->
    <div class="mb-10 border-b border-gray-200 pb-10 dark:border-gray-700">
      <x-marketing.docs.h2 id="office-types" title="Office types" />
      <p class="mb-4">
        An office type is a label that describes the nature of a location. Common examples include
        <strong>Headquarters</strong>
        ,
        <strong>Branch</strong>
        ,
        <strong>Warehouse</strong>
        , and
        <strong>Co-working Space</strong>
        .
      </p>
      <p class="mb-4">Office types are simple, reusable tags. You define the list once in Adminland, then assign a type to each office to keep your locations organized.</p>
      <p class="mb-4">Consider Dunder Mifflin Paper Company. They run several kinds of locations across the United States:</p>
      <ul class="mb-4 list-disc space-y-1 pl-6">
        <li>
          <strong>Branch</strong>
          — regional sales offices like Scranton, Stamford, and Utica
        </li>
        <li>
          <strong>Headquarters</strong>
          — the corporate office in New York
        </li>
        <li>
          <strong>Warehouse</strong>
          — distribution centers that hold paper stock
        </li>
      </ul>
      <p class="mb-4">Each office is tagged with one of these types. Anyone looking at the office list can immediately see what role a location plays in the company.</p>
      <p>An office type is optional on an office. Deleting an office type does not delete associated offices — those offices simply lose their classification and become untyped.</p>
    </div>

    <!-- offices -->
    <div class="mb-10 border-b border-gray-200 pb-10 dark:border-gray-700">
      <x-marketing.docs.h2 id="offices" title="Offices" />
      <p class="mb-4">An office can represent anything from a corporate building with a precise street address to a country-level entry for a fully remote team. Each office stores:</p>
      <ul class="mb-4 list-disc space-y-2 pl-6">
        <li>
          <strong>Name</strong>
          — a human-readable label, such as
          <em>Scranton Branch</em>
          or
          <em>New York HQ</em>
          .
        </li>
        <li>
          <strong>Address</strong>
          — up to two address lines, city, state or province, and postal code.
        </li>
        <li>
          <strong>Country</strong>
          — the country the office is located in.
        </li>
        <li>
          <strong>Time zone</strong>
          — the IANA time zone identifier for the office (for example,
          <code>America/New_York</code>
          or
          <code>Europe/London</code>
          ). This lets the rest of the organization know what local time applies to that location.
        </li>
        <li>
          <strong>Office type</strong>
          — an optional classification label.
        </li>
      </ul>
      <p class="mb-4">Only the name, the first address line, and the city are required. Everything else is optional, so you can create a minimal office record and fill in the details later.</p>
      <p class="mb-4">Consider Dunder Mifflin Paper Company's locations:</p>
      <ul class="mb-4 list-disc space-y-1 pl-6">
        <li>
          <strong>Scranton Branch</strong>
          — 1725 Slough Avenue, Scranton, PA 18505, America/New_York
        </li>
        <li>
          <strong>Stamford Branch</strong>
          — 200 Connecticut Ave, Stamford, CT 06902, America/New_York
        </li>
        <li>
          <strong>New York Headquarters</strong>
          — 99 Park Avenue, New York, NY 10016, America/New_York
        </li>
      </ul>
      <p>Offices belong to a single organization and are not shared across organizations.</p>
    </div>
  </div>

  <x-slot name="rightSidebar">
    <div class="flex">
      <div class="sticky bottom-0 w-full">
        <x-marketing.docs.on-this-page :items="[
          ['id' => 'overview', 'title' => 'Overview'],
          ['id' => 'office-types', 'title' => 'Office types'],
          ['id' => 'offices', 'title' => 'Offices'],
        ]" />
      </div>
    </div>
  </x-slot>
</x-marketing-docs-layout>
