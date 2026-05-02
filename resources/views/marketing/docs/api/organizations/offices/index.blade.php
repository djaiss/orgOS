<x-marketing-docs-layout :breadcrumbItems="[
  ['label' => 'Home', 'route' => route('marketing.index')],
  ['label' => 'Documentation', 'route' => route('marketing.docs.api.index')],
  ['label' => 'Organizations', 'route' => route('marketing.docs.api.organizations.index')],
  ['label' => 'Offices'],
]">
  <div class="py-16">
    <x-marketing.docs.h1 title="Offices" />

    <x-marketing.docs.table-of-content :items="[
      [
        'id' => 'list-offices',
        'title' => 'List all offices',
      ],
      [
        'id' => 'get-an-office',
        'title' => 'Get a specific office',
      ],
      [
        'id' => 'create-an-office',
        'title' => 'Create an office',
      ],
      [
        'id' => 'update-an-office',
        'title' => 'Update an office',
      ],
      [
        'id' => 'delete-an-office',
        'title' => 'Delete an office',
      ],
    ]" />

    <div class="mb-10 grid grid-cols-1 gap-6 border-b border-gray-200 pb-10 sm:grid-cols-2 dark:border-gray-700">
      <div>
        <p class="mb-2">Offices represent physical or virtual locations belonging to an organization (e.g. "Scranton Branch", "London HQ").</p>
        <p>
          All office endpoints require you to be a member of the organization. Create, update, and delete operations additionally require the
          <strong>Owner</strong>
          or
          <strong>Administrator</strong>
          role.
        </p>
      </div>
      <div>
        <x-marketing.docs.code title="Endpoints">
          <div class="flex flex-col gap-y-2">
            <a href="#list-offices">
              <span class="text-blue-700">GET</span>
              /api/organizations/{id}/adminland/offices
            </a>
          </div>
          <div class="flex flex-col gap-y-2">
            <a href="#get-an-office">
              <span class="text-blue-700">GET</span>
              /api/organizations/{id}/adminland/offices/{officeId}
            </a>
          </div>
          <div class="flex flex-col gap-y-2">
            <a href="#create-an-office">
              <span class="text-green-700">POST</span>
              /api/organizations/{id}/adminland/offices
            </a>
          </div>
          <div class="flex flex-col gap-y-2">
            <a href="#update-an-office">
              <span class="text-yellow-700">PUT</span>
              /api/organizations/{id}/adminland/offices/{officeId}
            </a>
          </div>
          <div class="flex flex-col gap-y-2">
            <a href="#delete-an-office">
              <span class="text-red-700">DELETE</span>
              /api/organizations/{id}/adminland/offices/{officeId}
            </a>
          </div>
        </x-marketing.docs.code>
      </div>
    </div>

    <!-- GET /api/organizations/{id}/adminland/offices -->
    <div class="mb-10 grid grid-cols-1 gap-6 border-b border-gray-200 pb-10 sm:grid-cols-2 dark:border-gray-700">
      <div>
        <x-marketing.docs.h2 id="list-offices" title="List all offices" />
        <p class="mb-2">This endpoint returns all offices belonging to the given organization, ordered alphabetically by name.</p>
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
          <x-marketing.docs.attribute name="type" type="string" description="The type of the resource. Always 'office'." />
          <x-marketing.docs.attribute name="id" type="string" description="The ID of the office." />
          <x-marketing.docs.attribute name="attributes" type="object" description="The attributes of the office." />
          <x-marketing.docs.attribute name="attributes.name" type="string" description="The name of the office." />
          <x-marketing.docs.attribute name="attributes.address_line_1" type="string" description="The first line of the office address." />
          <x-marketing.docs.attribute name="attributes.address_line_2" type="string" description="The second line of the office address. Can be null." />
          <x-marketing.docs.attribute name="attributes.city" type="string" description="The city of the office." />
          <x-marketing.docs.attribute name="attributes.state_province" type="string" description="The state or province of the office. Can be null." />
          <x-marketing.docs.attribute name="attributes.postal_code" type="string" description="The postal code of the office. Can be null." />
          <x-marketing.docs.attribute name="attributes.timezone" type="string" description="The timezone of the office (e.g. 'America/New_York'). Can be null." />
          <x-marketing.docs.attribute name="attributes.country_id" type="integer" description="The ID of the country. Can be null." />
          <x-marketing.docs.attribute name="attributes.office_type_id" type="integer" description="The ID of the office type. Can be null." />
          <x-marketing.docs.attribute name="attributes.created_at" type="integer" description="The date and time the object was created, in Unix timestamp format." />
          <x-marketing.docs.attribute name="attributes.updated_at" type="integer" description="The date and time the object was last updated, in Unix timestamp format." />
          <x-marketing.docs.attribute name="links" type="object" description="The links to access the office." />
          <x-marketing.docs.attribute name="links.self" type="string" description="The URL of the office." />
        </x-marketing.docs.response-attributes>
      </div>
      <div>
        <x-marketing.docs.code title="/api/organizations/{id}/adminland/offices" verb="GET">
          <x-marketing.docs.json-section level="1" name="data">
            <x-marketing.docs.json-line level="2" key="type" value="office" type="string" comma />
            <x-marketing.docs.json-line level="2" key="id" value="1" type="string" comma />
            <x-marketing.docs.json-section level="2" name="attributes" comma>
              <x-marketing.docs.json-line level="3" key="name" value="Scranton Branch" type="string" comma />
              <x-marketing.docs.json-line level="3" key="address_line_1" value="1725 Slough Avenue" type="string" comma />
              <x-marketing.docs.json-line level="3" key="address_line_2" value="Suite 200" type="string" comma />
              <x-marketing.docs.json-line level="3" key="city" value="Scranton" type="string" comma />
              <x-marketing.docs.json-line level="3" key="state_province" value="Pennsylvania" type="string" comma />
              <x-marketing.docs.json-line level="3" key="postal_code" value="18505" type="string" comma />
              <x-marketing.docs.json-line level="3" key="timezone" value="America/New_York" type="string" comma />
              <x-marketing.docs.json-line level="3" key="country_id" value="1" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="office_type_id" value="1" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="created_at" value="1771898698" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="updated_at" value="1771898698" type="integer" />
            </x-marketing.docs.json-section>
            <x-marketing.docs.json-section level="2" name="links">
              <x-marketing.docs.json-line level="3" key="self" value="http://orgos.test/api/organizations/1/adminland/offices/1" type="string" />
            </x-marketing.docs.json-section>
          </x-marketing.docs.json-section>
        </x-marketing.docs.code>
      </div>
    </div>

    <!-- GET /api/organizations/{id}/adminland/offices/{officeId} -->
    <div class="mb-10 grid grid-cols-1 gap-6 border-b border-gray-200 pb-10 sm:grid-cols-2 dark:border-gray-700">
      <div>
        <x-marketing.docs.h2 id="get-an-office" title="Get a specific office" />
        <p class="mb-2">This endpoint returns a specific office belonging to the given organization.</p>
        <p class="mb-10">
          <strong>Required permission:</strong>
          any member of the organization.
        </p>

        <!-- url parameters -->
        <x-marketing.docs.url-parameters>
          <x-marketing.docs.attribute required name="id" type="integer" description="The ID of the organization." />
          <x-marketing.docs.attribute required name="officeId" type="integer" description="The ID of the office." />
        </x-marketing.docs.url-parameters>

        <!-- query parameters -->
        <x-marketing.docs.query-parameters no-parameters></x-marketing.docs.query-parameters>

        <!-- response attributes -->
        <x-marketing.docs.response-attributes>
          <x-marketing.docs.attribute name="type" type="string" description="The type of the resource. Always 'office'." />
          <x-marketing.docs.attribute name="id" type="string" description="The ID of the office." />
          <x-marketing.docs.attribute name="attributes" type="object" description="The attributes of the office." />
          <x-marketing.docs.attribute name="attributes.name" type="string" description="The name of the office." />
          <x-marketing.docs.attribute name="attributes.address_line_1" type="string" description="The first line of the office address." />
          <x-marketing.docs.attribute name="attributes.address_line_2" type="string" description="The second line of the office address. Can be null." />
          <x-marketing.docs.attribute name="attributes.city" type="string" description="The city of the office." />
          <x-marketing.docs.attribute name="attributes.state_province" type="string" description="The state or province of the office. Can be null." />
          <x-marketing.docs.attribute name="attributes.postal_code" type="string" description="The postal code of the office. Can be null." />
          <x-marketing.docs.attribute name="attributes.timezone" type="string" description="The timezone of the office. Can be null." />
          <x-marketing.docs.attribute name="attributes.country_id" type="integer" description="The ID of the country. Can be null." />
          <x-marketing.docs.attribute name="attributes.office_type_id" type="integer" description="The ID of the office type. Can be null." />
          <x-marketing.docs.attribute name="attributes.created_at" type="integer" description="The date and time the object was created, in Unix timestamp format." />
          <x-marketing.docs.attribute name="attributes.updated_at" type="integer" description="The date and time the object was last updated, in Unix timestamp format." />
          <x-marketing.docs.attribute name="links" type="object" description="The links to access the office." />
          <x-marketing.docs.attribute name="links.self" type="string" description="The URL of the office." />
        </x-marketing.docs.response-attributes>
      </div>
      <div>
        <x-marketing.docs.code title="/api/organizations/{id}/adminland/offices/{officeId}" verb="GET">
          <x-marketing.docs.json-section level="1" name="data">
            <x-marketing.docs.json-line level="2" key="type" value="office" type="string" comma />
            <x-marketing.docs.json-line level="2" key="id" value="1" type="string" comma />
            <x-marketing.docs.json-section level="2" name="attributes" comma>
              <x-marketing.docs.json-line level="3" key="name" value="Scranton Branch" type="string" comma />
              <x-marketing.docs.json-line level="3" key="address_line_1" value="1725 Slough Avenue" type="string" comma />
              <x-marketing.docs.json-line level="3" key="address_line_2" value="Suite 200" type="string" comma />
              <x-marketing.docs.json-line level="3" key="city" value="Scranton" type="string" comma />
              <x-marketing.docs.json-line level="3" key="state_province" value="Pennsylvania" type="string" comma />
              <x-marketing.docs.json-line level="3" key="postal_code" value="18505" type="string" comma />
              <x-marketing.docs.json-line level="3" key="timezone" value="America/New_York" type="string" comma />
              <x-marketing.docs.json-line level="3" key="country_id" value="1" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="office_type_id" value="1" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="created_at" value="1771898698" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="updated_at" value="1771898698" type="integer" />
            </x-marketing.docs.json-section>
            <x-marketing.docs.json-section level="2" name="links">
              <x-marketing.docs.json-line level="3" key="self" value="http://orgos.test/api/organizations/1/adminland/offices/1" type="string" />
            </x-marketing.docs.json-section>
          </x-marketing.docs.json-section>
        </x-marketing.docs.code>
      </div>
    </div>

    <!-- POST /api/organizations/{id}/adminland/offices -->
    <div class="mb-10 grid grid-cols-1 gap-6 border-b border-gray-200 pb-10 sm:grid-cols-2 dark:border-gray-700">
      <div>
        <x-marketing.docs.h2 id="create-an-office" title="Create an office" />
        <p class="mb-2">This endpoint creates a new office for the given organization.</p>
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
          <x-marketing.docs.attribute required name="name" type="string" description="The name of the office. Maximum 255 characters." />
          <x-marketing.docs.attribute required name="address_line_1" type="string" description="The first line of the office address. Maximum 255 characters." />
          <x-marketing.docs.attribute name="address_line_2" type="string" description="The second line of the office address. Maximum 255 characters." />
          <x-marketing.docs.attribute required name="city" type="string" description="The city of the office. Maximum 255 characters." />
          <x-marketing.docs.attribute name="state_province" type="string" description="The state or province of the office. Maximum 255 characters." />
          <x-marketing.docs.attribute name="postal_code" type="string" description="The postal code of the office. Maximum 255 characters." />
          <x-marketing.docs.attribute name="timezone" type="string" description="The timezone of the office (e.g. 'America/New_York'). Maximum 255 characters." />
          <x-marketing.docs.attribute name="country_id" type="integer" description="The ID of the country the office is located in." />
          <x-marketing.docs.attribute name="office_type_id" type="integer" description="The ID of the office type to assign to this office." />
        </x-marketing.docs.query-parameters>

        <!-- response attributes -->
        <x-marketing.docs.response-attributes>
          <x-marketing.docs.attribute name="type" type="string" description="The type of the resource. Always 'office'." />
          <x-marketing.docs.attribute name="id" type="string" description="The ID of the newly created office." />
          <x-marketing.docs.attribute name="attributes" type="object" description="The attributes of the office." />
          <x-marketing.docs.attribute name="attributes.name" type="string" description="The name of the office." />
          <x-marketing.docs.attribute name="attributes.address_line_1" type="string" description="The first line of the office address." />
          <x-marketing.docs.attribute name="attributes.address_line_2" type="string" description="The second line of the office address. Can be null." />
          <x-marketing.docs.attribute name="attributes.city" type="string" description="The city of the office." />
          <x-marketing.docs.attribute name="attributes.state_province" type="string" description="The state or province of the office. Can be null." />
          <x-marketing.docs.attribute name="attributes.postal_code" type="string" description="The postal code of the office. Can be null." />
          <x-marketing.docs.attribute name="attributes.timezone" type="string" description="The timezone of the office. Can be null." />
          <x-marketing.docs.attribute name="attributes.country_id" type="integer" description="The ID of the country. Can be null." />
          <x-marketing.docs.attribute name="attributes.office_type_id" type="integer" description="The ID of the office type. Can be null." />
          <x-marketing.docs.attribute name="attributes.created_at" type="integer" description="The date and time the object was created, in Unix timestamp format." />
          <x-marketing.docs.attribute name="attributes.updated_at" type="integer" description="The date and time the object was last updated, in Unix timestamp format." />
          <x-marketing.docs.attribute name="links" type="object" description="The links to access the office." />
          <x-marketing.docs.attribute name="links.self" type="string" description="The URL of the office." />
        </x-marketing.docs.response-attributes>
      </div>
      <div>
        <x-marketing.docs.code title="/api/organizations/{id}/adminland/offices" verb="POST">
          <x-marketing.docs.json-section level="1" name="data">
            <x-marketing.docs.json-line level="2" key="type" value="office" type="string" comma />
            <x-marketing.docs.json-line level="2" key="id" value="1" type="string" comma />
            <x-marketing.docs.json-section level="2" name="attributes" comma>
              <x-marketing.docs.json-line level="3" key="name" value="Scranton Branch" type="string" comma />
              <x-marketing.docs.json-line level="3" key="address_line_1" value="1725 Slough Avenue" type="string" comma />
              <x-marketing.docs.json-line level="3" key="address_line_2" value="Suite 200" type="string" comma />
              <x-marketing.docs.json-line level="3" key="city" value="Scranton" type="string" comma />
              <x-marketing.docs.json-line level="3" key="state_province" value="Pennsylvania" type="string" comma />
              <x-marketing.docs.json-line level="3" key="postal_code" value="18505" type="string" comma />
              <x-marketing.docs.json-line level="3" key="timezone" value="America/New_York" type="string" comma />
              <x-marketing.docs.json-line level="3" key="country_id" value="1" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="office_type_id" value="1" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="created_at" value="1771898698" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="updated_at" value="1771898698" type="integer" />
            </x-marketing.docs.json-section>
            <x-marketing.docs.json-section level="2" name="links">
              <x-marketing.docs.json-line level="3" key="self" value="http://orgos.test/api/organizations/1/adminland/offices/1" type="string" />
            </x-marketing.docs.json-section>
          </x-marketing.docs.json-section>
        </x-marketing.docs.code>
      </div>
    </div>

    <!-- PUT /api/organizations/{id}/adminland/offices/{officeId} -->
    <div class="mb-10 grid grid-cols-1 gap-6 border-b border-gray-200 pb-10 sm:grid-cols-2 dark:border-gray-700">
      <div>
        <x-marketing.docs.h2 id="update-an-office" title="Update an office" />
        <p class="mb-2">This endpoint updates an existing office.</p>
        <p class="mb-10">
          <strong>Required permission:</strong>
          Owner or Administrator.
        </p>

        <!-- url parameters -->
        <x-marketing.docs.url-parameters>
          <x-marketing.docs.attribute required name="id" type="integer" description="The ID of the organization." />
          <x-marketing.docs.attribute required name="officeId" type="integer" description="The ID of the office to update." />
        </x-marketing.docs.url-parameters>

        <!-- query parameters -->
        <x-marketing.docs.query-parameters>
          <x-marketing.docs.attribute required name="name" type="string" description="The name of the office. Maximum 255 characters." />
          <x-marketing.docs.attribute required name="address_line_1" type="string" description="The first line of the office address. Maximum 255 characters." />
          <x-marketing.docs.attribute name="address_line_2" type="string" description="The second line of the office address. Maximum 255 characters." />
          <x-marketing.docs.attribute required name="city" type="string" description="The city of the office. Maximum 255 characters." />
          <x-marketing.docs.attribute name="state_province" type="string" description="The state or province of the office. Maximum 255 characters." />
          <x-marketing.docs.attribute name="postal_code" type="string" description="The postal code of the office. Maximum 255 characters." />
          <x-marketing.docs.attribute name="timezone" type="string" description="The timezone of the office. Maximum 255 characters." />
          <x-marketing.docs.attribute name="country_id" type="integer" description="The ID of the country the office is located in." />
          <x-marketing.docs.attribute name="office_type_id" type="integer" description="The ID of the office type to assign to this office." />
        </x-marketing.docs.query-parameters>

        <!-- response attributes -->
        <x-marketing.docs.response-attributes>
          <x-marketing.docs.attribute name="type" type="string" description="The type of the resource. Always 'office'." />
          <x-marketing.docs.attribute name="id" type="string" description="The ID of the office." />
          <x-marketing.docs.attribute name="attributes" type="object" description="The attributes of the office." />
          <x-marketing.docs.attribute name="attributes.name" type="string" description="The updated name of the office." />
          <x-marketing.docs.attribute name="attributes.address_line_1" type="string" description="The updated first line of the office address." />
          <x-marketing.docs.attribute name="attributes.address_line_2" type="string" description="The updated second line of the office address. Can be null." />
          <x-marketing.docs.attribute name="attributes.city" type="string" description="The updated city of the office." />
          <x-marketing.docs.attribute name="attributes.state_province" type="string" description="The updated state or province. Can be null." />
          <x-marketing.docs.attribute name="attributes.postal_code" type="string" description="The updated postal code. Can be null." />
          <x-marketing.docs.attribute name="attributes.timezone" type="string" description="The updated timezone. Can be null." />
          <x-marketing.docs.attribute name="attributes.country_id" type="integer" description="The updated country ID. Can be null." />
          <x-marketing.docs.attribute name="attributes.office_type_id" type="integer" description="The updated office type ID. Can be null." />
          <x-marketing.docs.attribute name="attributes.created_at" type="integer" description="The date and time the object was created, in Unix timestamp format." />
          <x-marketing.docs.attribute name="attributes.updated_at" type="integer" description="The date and time the object was last updated, in Unix timestamp format." />
          <x-marketing.docs.attribute name="links" type="object" description="The links to access the office." />
          <x-marketing.docs.attribute name="links.self" type="string" description="The URL of the office." />
        </x-marketing.docs.response-attributes>
      </div>
      <div>
        <x-marketing.docs.code title="/api/organizations/{id}/adminland/offices/{officeId}" verb="PUT">
          <x-marketing.docs.json-section level="1" name="data">
            <x-marketing.docs.json-line level="2" key="type" value="office" type="string" comma />
            <x-marketing.docs.json-line level="2" key="id" value="1" type="string" comma />
            <x-marketing.docs.json-section level="2" name="attributes" comma>
              <x-marketing.docs.json-line level="3" key="name" value="Scranton Branch Updated" type="string" comma />
              <x-marketing.docs.json-line level="3" key="address_line_1" value="1725 Slough Avenue" type="string" comma />
              <x-marketing.docs.json-line level="3" key="address_line_2" value="Suite 200" type="string" comma />
              <x-marketing.docs.json-line level="3" key="city" value="Scranton" type="string" comma />
              <x-marketing.docs.json-line level="3" key="state_province" value="Pennsylvania" type="string" comma />
              <x-marketing.docs.json-line level="3" key="postal_code" value="18505" type="string" comma />
              <x-marketing.docs.json-line level="3" key="timezone" value="America/New_York" type="string" comma />
              <x-marketing.docs.json-line level="3" key="country_id" value="1" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="office_type_id" value="1" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="created_at" value="1771898698" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="updated_at" value="1771898699" type="integer" />
            </x-marketing.docs.json-section>
            <x-marketing.docs.json-section level="2" name="links">
              <x-marketing.docs.json-line level="3" key="self" value="http://orgos.test/api/organizations/1/adminland/offices/1" type="string" />
            </x-marketing.docs.json-section>
          </x-marketing.docs.json-section>
        </x-marketing.docs.code>
      </div>
    </div>

    <!-- DELETE /api/organizations/{id}/adminland/offices/{officeId} -->
    <div class="mb-10 grid grid-cols-1 gap-6 sm:grid-cols-2">
      <div>
        <x-marketing.docs.h2 id="delete-an-office" title="Delete an office" />
        <p class="mb-2">This endpoint permanently deletes an office from the organization.</p>
        <p class="mb-10">
          <strong>Required permission:</strong>
          Owner or Administrator.
        </p>

        <!-- url parameters -->
        <x-marketing.docs.url-parameters>
          <x-marketing.docs.attribute required name="id" type="integer" description="The ID of the organization." />
          <x-marketing.docs.attribute required name="officeId" type="integer" description="The ID of the office to delete." />
        </x-marketing.docs.url-parameters>

        <!-- query parameters -->
        <x-marketing.docs.query-parameters no-parameters></x-marketing.docs.query-parameters>

        <!-- response attributes -->
        <x-marketing.docs.response-attributes no-parameters></x-marketing.docs.response-attributes>
      </div>
      <div>
        <x-marketing.docs.code title="/api/organizations/{id}/adminland/offices/{officeId}" verb="DELETE">
          <div>No response body</div>
        </x-marketing.docs.code>
      </div>
    </div>
  </div>
</x-marketing-docs-layout>
