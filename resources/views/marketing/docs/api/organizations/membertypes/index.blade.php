<x-marketing-docs-layout :breadcrumbItems="[
  ['label' => 'Home', 'route' => route('marketing.index')],
  ['label' => 'Documentation', 'route' => route('marketing.docs.api.index')],
  ['label' => 'Organizations', 'route' => route('marketing.docs.api.organizations.index')],
  ['label' => 'Member Types'],
]">
  <div class="py-16">
    <x-marketing.docs.h1 title="Member Types" />

    <x-marketing.docs.table-of-content :items="[
      [
        'id' => 'list-member-types',
        'title' => 'List all member types',
      ],
      [
        'id' => 'get-a-member-type',
        'title' => 'Get a specific member type',
      ],
      [
        'id' => 'create-a-member-type',
        'title' => 'Create a member type',
      ],
      [
        'id' => 'update-a-member-type',
        'title' => 'Update a member type',
      ],
      [
        'id' => 'delete-a-member-type',
        'title' => 'Delete a member type',
      ],
    ]" />

    <div class="mb-10 grid grid-cols-1 gap-6 border-b border-gray-200 pb-10 sm:grid-cols-2 dark:border-gray-700">
      <div>
        <p class="mb-2">Member types allow you to categorize your members within an organization (e.g. "Employee", "Freelance", "Student").</p>
        <p>
          All member type endpoints require you to be a member of the organization. Create, update, and delete operations additionally require the
          <strong>Owner</strong>
          or
          <strong>Administrator</strong>
          role.
        </p>
      </div>
      <div>
        <x-marketing.docs.code title="Endpoints">
          <div class="flex flex-col gap-y-2">
            <a href="#list-member-types">
              <span class="text-blue-700">GET</span>
              /api/organizations/{id}/adminland/membertypes
            </a>
          </div>
          <div class="flex flex-col gap-y-2">
            <a href="#get-a-member-type">
              <span class="text-blue-700">GET</span>
              /api/organizations/{id}/adminland/membertypes/{memberTypeId}
            </a>
          </div>
          <div class="flex flex-col gap-y-2">
            <a href="#create-a-member-type">
              <span class="text-green-700">POST</span>
              /api/organizations/{id}/adminland/membertypes
            </a>
          </div>
          <div class="flex flex-col gap-y-2">
            <a href="#update-a-member-type">
              <span class="text-yellow-700">PUT</span>
              /api/organizations/{id}/adminland/membertypes/{memberTypeId}
            </a>
          </div>
          <div class="flex flex-col gap-y-2">
            <a href="#delete-a-member-type">
              <span class="text-red-700">DELETE</span>
              /api/organizations/{id}/adminland/membertypes/{memberTypeId}
            </a>
          </div>
        </x-marketing.docs.code>
      </div>
    </div>

    <!-- GET /api/organizations/{id}/adminland/membertypes -->
    <div class="mb-10 grid grid-cols-1 gap-6 border-b border-gray-200 pb-10 sm:grid-cols-2 dark:border-gray-700">
      <div>
        <x-marketing.docs.h2 id="list-member-types" title="List all member types" />
        <p class="mb-2">This endpoint returns all member types belonging to the given organization, ordered by position.</p>
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
          <x-marketing.docs.attribute name="type" type="string" description="The type of the resource. Always 'member_type'." />
          <x-marketing.docs.attribute name="id" type="string" description="The ID of the member type." />
          <x-marketing.docs.attribute name="attributes" type="object" description="The attributes of the member type." />
          <x-marketing.docs.attribute name="attributes.name" type="string" description="The name of the member type." />
          <x-marketing.docs.attribute name="attributes.position" type="integer" description="The display order of the member type. Starts at 0." />
          <x-marketing.docs.attribute name="attributes.created_at" type="integer" description="The date and time the object was created, in Unix timestamp format." />
          <x-marketing.docs.attribute name="attributes.updated_at" type="integer" description="The date and time the object was last updated, in Unix timestamp format." />
          <x-marketing.docs.attribute name="links" type="object" description="The links to access the member type." />
          <x-marketing.docs.attribute name="links.self" type="string" description="The URL of the member type." />
        </x-marketing.docs.response-attributes>
      </div>
      <div>
        <x-marketing.docs.code title="/api/organizations/{id}/adminland/membertypes" verb="GET">
          <x-marketing.docs.json-section level="1" name="data">
            <x-marketing.docs.json-line level="2" key="type" value="member_type" type="string" comma />
            <x-marketing.docs.json-line level="2" key="id" value="1" type="string" comma />
            <x-marketing.docs.json-section level="2" name="attributes" comma>
              <x-marketing.docs.json-line level="3" key="name" value="Employee" type="string" comma />
              <x-marketing.docs.json-line level="3" key="position" value="0" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="created_at" value="1771898698" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="updated_at" value="1771898698" type="integer" />
            </x-marketing.docs.json-section>
            <x-marketing.docs.json-section level="2" name="links">
              <x-marketing.docs.json-line level="3" key="self" value="http://orgos.test/api/organizations/1/adminland/membertypes/1" type="string" />
            </x-marketing.docs.json-section>
          </x-marketing.docs.json-section>
        </x-marketing.docs.code>
      </div>
    </div>

    <!-- GET /api/organizations/{id}/adminland/membertypes/{memberTypeId} -->
    <div class="mb-10 grid grid-cols-1 gap-6 border-b border-gray-200 pb-10 sm:grid-cols-2 dark:border-gray-700">
      <div>
        <x-marketing.docs.h2 id="get-a-member-type" title="Get a specific member type" />
        <p class="mb-2">This endpoint returns a specific member type belonging to the given organization.</p>
        <p class="mb-10">
          <strong>Required permission:</strong>
          any member of the organization.
        </p>

        <!-- url parameters -->
        <x-marketing.docs.url-parameters>
          <x-marketing.docs.attribute required name="id" type="integer" description="The ID of the organization." />
          <x-marketing.docs.attribute required name="memberTypeId" type="integer" description="The ID of the member type." />
        </x-marketing.docs.url-parameters>

        <!-- query parameters -->
        <x-marketing.docs.query-parameters no-parameters></x-marketing.docs.query-parameters>

        <!-- response attributes -->
        <x-marketing.docs.response-attributes>
          <x-marketing.docs.attribute name="type" type="string" description="The type of the resource. Always 'member_type'." />
          <x-marketing.docs.attribute name="id" type="string" description="The ID of the member type." />
          <x-marketing.docs.attribute name="attributes" type="object" description="The attributes of the member type." />
          <x-marketing.docs.attribute name="attributes.name" type="string" description="The name of the member type." />
          <x-marketing.docs.attribute name="attributes.position" type="integer" description="The display order of the member type. Starts at 0." />
          <x-marketing.docs.attribute name="attributes.created_at" type="integer" description="The date and time the object was created, in Unix timestamp format." />
          <x-marketing.docs.attribute name="attributes.updated_at" type="integer" description="The date and time the object was last updated, in Unix timestamp format." />
          <x-marketing.docs.attribute name="links" type="object" description="The links to access the member type." />
          <x-marketing.docs.attribute name="links.self" type="string" description="The URL of the member type." />
        </x-marketing.docs.response-attributes>
      </div>
      <div>
        <x-marketing.docs.code title="/api/organizations/{id}/adminland/membertypes/{memberTypeId}" verb="GET">
          <x-marketing.docs.json-section level="1" name="data">
            <x-marketing.docs.json-line level="2" key="type" value="member_type" type="string" comma />
            <x-marketing.docs.json-line level="2" key="id" value="1" type="string" comma />
            <x-marketing.docs.json-section level="2" name="attributes" comma>
              <x-marketing.docs.json-line level="3" key="name" value="Employee" type="string" comma />
              <x-marketing.docs.json-line level="3" key="position" value="0" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="created_at" value="1771898698" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="updated_at" value="1771898698" type="integer" />
            </x-marketing.docs.json-section>
            <x-marketing.docs.json-section level="2" name="links">
              <x-marketing.docs.json-line level="3" key="self" value="http://orgos.test/api/organizations/1/adminland/membertypes/1" type="string" />
            </x-marketing.docs.json-section>
          </x-marketing.docs.json-section>
        </x-marketing.docs.code>
      </div>
    </div>

    <!-- POST /api/organizations/{id}/adminland/membertypes -->
    <div class="mb-10 grid grid-cols-1 gap-6 border-b border-gray-200 pb-10 sm:grid-cols-2 dark:border-gray-700">
      <div>
        <x-marketing.docs.h2 id="create-a-member-type" title="Create a member type" />
        <p class="mb-2">This endpoint creates a new member type for the given organization. If no position is provided, the new member type is appended at the end of the list. Providing a position will insert it at that position and shift existing types down.</p>
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
          <x-marketing.docs.attribute required name="name" type="string" description="The name of the member type. Maximum 255 characters." />
          <x-marketing.docs.attribute name="position" type="integer" description="The display position of the member type. Starts at 0. Defaults to last position." />
        </x-marketing.docs.query-parameters>

        <!-- response attributes -->
        <x-marketing.docs.response-attributes>
          <x-marketing.docs.attribute name="type" type="string" description="The type of the resource. Always 'member_type'." />
          <x-marketing.docs.attribute name="id" type="string" description="The ID of the newly created member type." />
          <x-marketing.docs.attribute name="attributes" type="object" description="The attributes of the member type." />
          <x-marketing.docs.attribute name="attributes.name" type="string" description="The name of the member type." />
          <x-marketing.docs.attribute name="attributes.position" type="integer" description="The display order of the member type." />
          <x-marketing.docs.attribute name="attributes.created_at" type="integer" description="The date and time the object was created, in Unix timestamp format." />
          <x-marketing.docs.attribute name="attributes.updated_at" type="integer" description="The date and time the object was last updated, in Unix timestamp format." />
          <x-marketing.docs.attribute name="links" type="object" description="The links to access the member type." />
          <x-marketing.docs.attribute name="links.self" type="string" description="The URL of the member type." />
        </x-marketing.docs.response-attributes>
      </div>
      <div>
        <x-marketing.docs.code title="/api/organizations/{id}/adminland/membertypes" verb="POST">
          <x-marketing.docs.json-section level="1" name="data">
            <x-marketing.docs.json-line level="2" key="type" value="member_type" type="string" comma />
            <x-marketing.docs.json-line level="2" key="id" value="1" type="string" comma />
            <x-marketing.docs.json-section level="2" name="attributes" comma>
              <x-marketing.docs.json-line level="3" key="name" value="Contractor" type="string" comma />
              <x-marketing.docs.json-line level="3" key="position" value="0" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="created_at" value="1771898698" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="updated_at" value="1771898698" type="integer" />
            </x-marketing.docs.json-section>
            <x-marketing.docs.json-section level="2" name="links">
              <x-marketing.docs.json-line level="3" key="self" value="http://orgos.test/api/organizations/1/adminland/membertypes/1" type="string" />
            </x-marketing.docs.json-section>
          </x-marketing.docs.json-section>
        </x-marketing.docs.code>
      </div>
    </div>

    <!-- PUT /api/organizations/{id}/adminland/membertypes/{memberTypeId} -->
    <div class="mb-10 grid grid-cols-1 gap-6 border-b border-gray-200 pb-10 sm:grid-cols-2 dark:border-gray-700">
      <div>
        <x-marketing.docs.h2 id="update-a-member-type" title="Update a member type" />
        <p class="mb-2">This endpoint updates an existing member type. You can rename it and optionally reorder it by supplying a new position. Changing the position will automatically shift other member types to preserve a consistent ordering.</p>
        <p class="mb-10">
          <strong>Required permission:</strong>
          Owner or Administrator.
        </p>

        <!-- url parameters -->
        <x-marketing.docs.url-parameters>
          <x-marketing.docs.attribute required name="id" type="integer" description="The ID of the organization." />
          <x-marketing.docs.attribute required name="memberTypeId" type="integer" description="The ID of the member type to update." />
        </x-marketing.docs.url-parameters>

        <!-- query parameters -->
        <x-marketing.docs.query-parameters>
          <x-marketing.docs.attribute required name="name" type="string" description="The new name of the member type. Maximum 255 characters." />
          <x-marketing.docs.attribute name="position" type="integer" description="The new display position of the member type. Starts at 0. Other member types will be reordered automatically." />
        </x-marketing.docs.query-parameters>

        <!-- response attributes -->
        <x-marketing.docs.response-attributes>
          <x-marketing.docs.attribute name="type" type="string" description="The type of the resource. Always 'member_type'." />
          <x-marketing.docs.attribute name="id" type="string" description="The ID of the member type." />
          <x-marketing.docs.attribute name="attributes" type="object" description="The attributes of the member type." />
          <x-marketing.docs.attribute name="attributes.name" type="string" description="The updated name of the member type." />
          <x-marketing.docs.attribute name="attributes.position" type="integer" description="The updated display order of the member type." />
          <x-marketing.docs.attribute name="attributes.created_at" type="integer" description="The date and time the object was created, in Unix timestamp format." />
          <x-marketing.docs.attribute name="attributes.updated_at" type="integer" description="The date and time the object was last updated, in Unix timestamp format." />
          <x-marketing.docs.attribute name="links" type="object" description="The links to access the member type." />
          <x-marketing.docs.attribute name="links.self" type="string" description="The URL of the member type." />
        </x-marketing.docs.response-attributes>
      </div>
      <div>
        <x-marketing.docs.code title="/api/organizations/{id}/adminland/membertypes/{memberTypeId}" verb="PUT">
          <x-marketing.docs.json-section level="1" name="data">
            <x-marketing.docs.json-line level="2" key="type" value="member_type" type="string" comma />
            <x-marketing.docs.json-line level="2" key="id" value="1" type="string" comma />
            <x-marketing.docs.json-section level="2" name="attributes" comma>
              <x-marketing.docs.json-line level="3" key="name" value="Intern" type="string" comma />
              <x-marketing.docs.json-line level="3" key="position" value="0" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="created_at" value="1771898698" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="updated_at" value="1771898699" type="integer" />
            </x-marketing.docs.json-section>
            <x-marketing.docs.json-section level="2" name="links">
              <x-marketing.docs.json-line level="3" key="self" value="http://orgos.test/api/organizations/1/adminland/membertypes/1" type="string" />
            </x-marketing.docs.json-section>
          </x-marketing.docs.json-section>
        </x-marketing.docs.code>
      </div>
    </div>

    <!-- DELETE /api/organizations/{id}/adminland/membertypes/{memberTypeId} -->
    <div class="mb-10 grid grid-cols-1 gap-6 sm:grid-cols-2">
      <div>
        <x-marketing.docs.h2 id="delete-a-member-type" title="Delete a member type" />
        <p class="mb-2">This endpoint permanently deletes a member type from the organization.</p>
        <p class="mb-10">
          <strong>Required permission:</strong>
          Owner or Administrator.
        </p>

        <!-- url parameters -->
        <x-marketing.docs.url-parameters>
          <x-marketing.docs.attribute required name="id" type="integer" description="The ID of the organization." />
          <x-marketing.docs.attribute required name="memberTypeId" type="integer" description="The ID of the member type to delete." />
        </x-marketing.docs.url-parameters>

        <!-- query parameters -->
        <x-marketing.docs.query-parameters no-parameters></x-marketing.docs.query-parameters>

        <!-- response attributes -->
        <x-marketing.docs.response-attributes no-parameters></x-marketing.docs.response-attributes>
      </div>
      <div>
        <x-marketing.docs.code title="/api/organizations/{id}/adminland/membertypes/{memberTypeId}" verb="DELETE">
          <div>No response body</div>
        </x-marketing.docs.code>
      </div>
    </div>
  </div>
</x-marketing-docs-layout>
