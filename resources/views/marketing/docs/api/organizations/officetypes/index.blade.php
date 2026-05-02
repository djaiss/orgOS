<x-marketing-docs-layout :breadcrumbItems="[
  ['label' => 'Home', 'route' => route('marketing.index')],
  ['label' => 'Documentation', 'route' => route('marketing.docs.api.index')],
  ['label' => 'Organizations', 'route' => route('marketing.docs.api.organizations.index')],
  ['label' => 'Office Types'],
]">
  <div class="py-16">
    <x-marketing.docs.h1 title="Office Types" />

    <x-marketing.docs.table-of-content :items="[
      [
        'id' => 'list-office-types',
        'title' => 'List all office types',
      ],
      [
        'id' => 'get-an-office-type',
        'title' => 'Get a specific office type',
      ],
      [
        'id' => 'create-an-office-type',
        'title' => 'Create an office type',
      ],
      [
        'id' => 'update-an-office-type',
        'title' => 'Update an office type',
      ],
      [
        'id' => 'delete-an-office-type',
        'title' => 'Delete an office type',
      ],
    ]" />

    <div class="mb-10 grid grid-cols-1 gap-6 border-b border-gray-200 pb-10 sm:grid-cols-2 dark:border-gray-700">
      <div>
        <p class="mb-2">Office types allow you to categorize your offices within an organization (e.g. "Remote", "Headquarters", "Satellite").</p>
        <p>
          All office type endpoints require you to be a member of the organization. Create, update, and delete operations additionally require the
          <strong>Owner</strong>
          or
          <strong>Administrator</strong>
          role.
        </p>
      </div>
      <div>
        <x-marketing.docs.code title="Endpoints">
          <div class="flex flex-col gap-y-2">
            <a href="#list-office-types">
              <span class="text-blue-700">GET</span>
              /api/organizations/{id}/adminland/officetypes
            </a>
          </div>
          <div class="flex flex-col gap-y-2">
            <a href="#get-an-office-type">
              <span class="text-blue-700">GET</span>
              /api/organizations/{id}/adminland/officetypes/{officeTypeId}
            </a>
          </div>
          <div class="flex flex-col gap-y-2">
            <a href="#create-an-office-type">
              <span class="text-green-700">POST</span>
              /api/organizations/{id}/adminland/officetypes
            </a>
          </div>
          <div class="flex flex-col gap-y-2">
            <a href="#update-an-office-type">
              <span class="text-yellow-700">PUT</span>
              /api/organizations/{id}/adminland/officetypes/{officeTypeId}
            </a>
          </div>
          <div class="flex flex-col gap-y-2">
            <a href="#delete-an-office-type">
              <span class="text-red-700">DELETE</span>
              /api/organizations/{id}/adminland/officetypes/{officeTypeId}
            </a>
          </div>
        </x-marketing.docs.code>
      </div>
    </div>

    <!-- GET /api/organizations/{id}/adminland/officetypes -->
    <div class="mb-10 grid grid-cols-1 gap-6 border-b border-gray-200 pb-10 sm:grid-cols-2 dark:border-gray-700">
      <div>
        <x-marketing.docs.h2 id="list-office-types" title="List all office types" />
        <p class="mb-2">This endpoint returns all office types belonging to the given organization, ordered by position.</p>
        <p class="mb-10">
          <strong>Required permission:</strong>
          any member of the organization. This call is not
          <x-link href="{{ route('marketing.docs.api.index') }}#pagination">paginated</x-link>
          at the moment.
        </p>

        <!-- url parameters -->
        <x-marketing.docs.url-parameters>
          <x-marketing.docs.attribute required name="id" type="integer" description="The ID of the organization." />
        </x-marketing.docs.url-parameters>

        <!-- query parameters -->
        <x-marketing.docs.query-parameters no-parameters></x-marketing.docs.query-parameters>

        <!-- response attributes -->
        <x-marketing.docs.response-attributes>
          <x-marketing.docs.attribute name="type" type="string" description="The type of the resource. Always 'office_type'." />
          <x-marketing.docs.attribute name="id" type="string" description="The ID of the office type." />
          <x-marketing.docs.attribute name="attributes" type="object" description="The attributes of the office type." />
          <x-marketing.docs.attribute name="attributes.name" type="string" description="The name of the office type." />
          <x-marketing.docs.attribute name="attributes.position" type="integer" description="The display order of the office type. Starts at 0." />
          <x-marketing.docs.attribute name="attributes.created_at" type="integer" description="The date and time the object was created, in Unix timestamp format." />
          <x-marketing.docs.attribute name="attributes.updated_at" type="integer" description="The date and time the object was last updated, in Unix timestamp format." />
          <x-marketing.docs.attribute name="links" type="object" description="The links to access the office type." />
          <x-marketing.docs.attribute name="links.self" type="string" description="The URL of the office type." />
        </x-marketing.docs.response-attributes>
      </div>
      <div>
        <x-marketing.docs.code title="/api/organizations/{id}/adminland/officetypes" verb="GET">
          <x-marketing.docs.json-section level="1" name="data">
            <x-marketing.docs.json-line level="2" key="type" value="office_type" type="string" comma />
            <x-marketing.docs.json-line level="2" key="id" value="1" type="string" comma />
            <x-marketing.docs.json-section level="2" name="attributes" comma>
              <x-marketing.docs.json-line level="3" key="name" value="Remote" type="string" comma />
              <x-marketing.docs.json-line level="3" key="position" value="0" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="created_at" value="1771898698" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="updated_at" value="1771898698" type="integer" />
            </x-marketing.docs.json-section>
            <x-marketing.docs.json-section level="2" name="links">
              <x-marketing.docs.json-line level="3" key="self" value="http://orgos.test/api/organizations/1/adminland/officetypes/1" type="string" />
            </x-marketing.docs.json-section>
          </x-marketing.docs.json-section>
        </x-marketing.docs.code>
      </div>
    </div>

    <!-- GET /api/organizations/{id}/adminland/officetypes/{officeTypeId} -->
    <div class="mb-10 grid grid-cols-1 gap-6 border-b border-gray-200 pb-10 sm:grid-cols-2 dark:border-gray-700">
      <div>
        <x-marketing.docs.h2 id="get-an-office-type" title="Get a specific office type" />
        <p class="mb-2">This endpoint returns a specific office type belonging to the given organization.</p>
        <p class="mb-10">
          <strong>Required permission:</strong>
          any member of the organization.
        </p>

        <!-- url parameters -->
        <x-marketing.docs.url-parameters>
          <x-marketing.docs.attribute required name="id" type="integer" description="The ID of the organization." />
          <x-marketing.docs.attribute required name="officeTypeId" type="integer" description="The ID of the office type." />
        </x-marketing.docs.url-parameters>

        <!-- query parameters -->
        <x-marketing.docs.query-parameters no-parameters></x-marketing.docs.query-parameters>

        <!-- response attributes -->
        <x-marketing.docs.response-attributes>
          <x-marketing.docs.attribute name="type" type="string" description="The type of the resource. Always 'office_type'." />
          <x-marketing.docs.attribute name="id" type="string" description="The ID of the office type." />
          <x-marketing.docs.attribute name="attributes" type="object" description="The attributes of the office type." />
          <x-marketing.docs.attribute name="attributes.name" type="string" description="The name of the office type." />
          <x-marketing.docs.attribute name="attributes.position" type="integer" description="The display order of the office type. Starts at 0." />
          <x-marketing.docs.attribute name="attributes.created_at" type="integer" description="The date and time the object was created, in Unix timestamp format." />
          <x-marketing.docs.attribute name="attributes.updated_at" type="integer" description="The date and time the object was last updated, in Unix timestamp format." />
          <x-marketing.docs.attribute name="links" type="object" description="The links to access the office type." />
          <x-marketing.docs.attribute name="links.self" type="string" description="The URL of the office type." />
        </x-marketing.docs.response-attributes>
      </div>
      <div>
        <x-marketing.docs.code title="/api/organizations/{id}/adminland/officetypes/{officeTypeId}" verb="GET">
          <x-marketing.docs.json-section level="1" name="data">
            <x-marketing.docs.json-line level="2" key="type" value="office_type" type="string" comma />
            <x-marketing.docs.json-line level="2" key="id" value="1" type="string" comma />
            <x-marketing.docs.json-section level="2" name="attributes" comma>
              <x-marketing.docs.json-line level="3" key="name" value="Remote" type="string" comma />
              <x-marketing.docs.json-line level="3" key="position" value="0" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="created_at" value="1771898698" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="updated_at" value="1771898698" type="integer" />
            </x-marketing.docs.json-section>
            <x-marketing.docs.json-section level="2" name="links">
              <x-marketing.docs.json-line level="3" key="self" value="http://orgos.test/api/organizations/1/adminland/officetypes/1" type="string" />
            </x-marketing.docs.json-section>
          </x-marketing.docs.json-section>
        </x-marketing.docs.code>
      </div>
    </div>

    <!-- POST /api/organizations/{id}/adminland/officetypes -->
    <div class="mb-10 grid grid-cols-1 gap-6 border-b border-gray-200 pb-10 sm:grid-cols-2 dark:border-gray-700">
      <div>
        <x-marketing.docs.h2 id="create-an-office-type" title="Create an office type" />
        <p class="mb-2">This endpoint creates a new office type for the given organization. If no position is provided, the new office type is appended at the end of the list. Providing a position will insert it at that position and shift existing types down.</p>
        <p class="mb-10">
          <strong>Required permission:</strong>
          Owner or Administrator.
        </p>

        <!-- url parameters -->
        <x-marketing.docs.url-parameters>
          <x-marketing.docs.attribute required name="id" type="integer" description="The ID of the organization." />
        </x-marketing.docs.url-parameters>

        <!-- query parameters -->
        <x-marketing.docs.query-parameters>
          <x-marketing.docs.attribute required name="name" type="string" description="The name of the office type. Maximum 255 characters." />
          <x-marketing.docs.attribute name="position" type="integer" description="The display position of the office type. Starts at 0. Defaults to last position." />
        </x-marketing.docs.query-parameters>

        <!-- response attributes -->
        <x-marketing.docs.response-attributes>
          <x-marketing.docs.attribute name="type" type="string" description="The type of the resource. Always 'office_type'." />
          <x-marketing.docs.attribute name="id" type="string" description="The ID of the newly created office type." />
          <x-marketing.docs.attribute name="attributes" type="object" description="The attributes of the office type." />
          <x-marketing.docs.attribute name="attributes.name" type="string" description="The name of the office type." />
          <x-marketing.docs.attribute name="attributes.position" type="integer" description="The display order of the office type." />
          <x-marketing.docs.attribute name="attributes.created_at" type="integer" description="The date and time the object was created, in Unix timestamp format." />
          <x-marketing.docs.attribute name="attributes.updated_at" type="integer" description="The date and time the object was last updated, in Unix timestamp format." />
          <x-marketing.docs.attribute name="links" type="object" description="The links to access the office type." />
          <x-marketing.docs.attribute name="links.self" type="string" description="The URL of the office type." />
        </x-marketing.docs.response-attributes>
      </div>
      <div>
        <x-marketing.docs.code title="/api/organizations/{id}/adminland/officetypes" verb="POST">
          <x-marketing.docs.json-section level="1" name="data">
            <x-marketing.docs.json-line level="2" key="type" value="office_type" type="string" comma />
            <x-marketing.docs.json-line level="2" key="id" value="1" type="string" comma />
            <x-marketing.docs.json-section level="2" name="attributes" comma>
              <x-marketing.docs.json-line level="3" key="name" value="Remote" type="string" comma />
              <x-marketing.docs.json-line level="3" key="position" value="0" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="created_at" value="1771898698" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="updated_at" value="1771898698" type="integer" />
            </x-marketing.docs.json-section>
            <x-marketing.docs.json-section level="2" name="links">
              <x-marketing.docs.json-line level="3" key="self" value="http://orgos.test/api/organizations/1/adminland/officetypes/1" type="string" />
            </x-marketing.docs.json-section>
          </x-marketing.docs.json-section>
        </x-marketing.docs.code>
      </div>
    </div>

    <!-- PUT /api/organizations/{id}/adminland/officetypes/{officeTypeId} -->
    <div class="mb-10 grid grid-cols-1 gap-6 border-b border-gray-200 pb-10 sm:grid-cols-2 dark:border-gray-700">
      <div>
        <x-marketing.docs.h2 id="update-an-office-type" title="Update an office type" />
        <p class="mb-2">This endpoint updates an existing office type. You can rename it and optionally reorder it by supplying a new position. Changing the position will automatically shift other office types to preserve a consistent ordering.</p>
        <p class="mb-10">
          <strong>Required permission:</strong>
          Owner or Administrator.
        </p>

        <!-- url parameters -->
        <x-marketing.docs.url-parameters>
          <x-marketing.docs.attribute required name="id" type="integer" description="The ID of the organization." />
          <x-marketing.docs.attribute required name="officeTypeId" type="integer" description="The ID of the office type to update." />
        </x-marketing.docs.url-parameters>

        <!-- query parameters -->
        <x-marketing.docs.query-parameters>
          <x-marketing.docs.attribute required name="name" type="string" description="The new name of the office type. Maximum 255 characters." />
          <x-marketing.docs.attribute name="position" type="integer" description="The new display position of the office type. Starts at 0. Other office types will be reordered automatically." />
        </x-marketing.docs.query-parameters>

        <!-- response attributes -->
        <x-marketing.docs.response-attributes>
          <x-marketing.docs.attribute name="type" type="string" description="The type of the resource. Always 'office_type'." />
          <x-marketing.docs.attribute name="id" type="string" description="The ID of the office type." />
          <x-marketing.docs.attribute name="attributes" type="object" description="The attributes of the office type." />
          <x-marketing.docs.attribute name="attributes.name" type="string" description="The updated name of the office type." />
          <x-marketing.docs.attribute name="attributes.position" type="integer" description="The updated display order of the office type." />
          <x-marketing.docs.attribute name="attributes.created_at" type="integer" description="The date and time the object was created, in Unix timestamp format." />
          <x-marketing.docs.attribute name="attributes.updated_at" type="integer" description="The date and time the object was last updated, in Unix timestamp format." />
          <x-marketing.docs.attribute name="links" type="object" description="The links to access the office type." />
          <x-marketing.docs.attribute name="links.self" type="string" description="The URL of the office type." />
        </x-marketing.docs.response-attributes>
      </div>
      <div>
        <x-marketing.docs.code title="/api/organizations/{id}/adminland/officetypes/{officeTypeId}" verb="PUT">
          <x-marketing.docs.json-section level="1" name="data">
            <x-marketing.docs.json-line level="2" key="type" value="office_type" type="string" comma />
            <x-marketing.docs.json-line level="2" key="id" value="1" type="string" comma />
            <x-marketing.docs.json-section level="2" name="attributes" comma>
              <x-marketing.docs.json-line level="3" key="name" value="Headquarters" type="string" comma />
              <x-marketing.docs.json-line level="3" key="position" value="0" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="created_at" value="1771898698" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="updated_at" value="1771898699" type="integer" />
            </x-marketing.docs.json-section>
            <x-marketing.docs.json-section level="2" name="links">
              <x-marketing.docs.json-line level="3" key="self" value="http://orgos.test/api/organizations/1/adminland/officetypes/1" type="string" />
            </x-marketing.docs.json-section>
          </x-marketing.docs.json-section>
        </x-marketing.docs.code>
      </div>
    </div>

    <!-- DELETE /api/organizations/{id}/adminland/officetypes/{officeTypeId} -->
    <div class="mb-10 grid grid-cols-1 gap-6 sm:grid-cols-2">
      <div>
        <x-marketing.docs.h2 id="delete-an-office-type" title="Delete an office type" />
        <p class="mb-2">This endpoint permanently deletes an office type from the organization.</p>
        <p class="mb-10">
          <strong>Required permission:</strong>
          Owner or Administrator.
        </p>

        <!-- url parameters -->
        <x-marketing.docs.url-parameters>
          <x-marketing.docs.attribute required name="id" type="integer" description="The ID of the organization." />
          <x-marketing.docs.attribute required name="officeTypeId" type="integer" description="The ID of the office type to delete." />
        </x-marketing.docs.url-parameters>

        <!-- query parameters -->
        <x-marketing.docs.query-parameters no-parameters></x-marketing.docs.query-parameters>

        <!-- response attributes -->
        <x-marketing.docs.response-attributes no-parameters></x-marketing.docs.response-attributes>
      </div>
      <div>
        <x-marketing.docs.code title="/api/organizations/{id}/adminland/officetypes/{officeTypeId}" verb="DELETE">
          <div>No response body</div>
        </x-marketing.docs.code>
      </div>
    </div>
  </div>
</x-marketing-docs-layout>
