<x-marketing-docs-layout :breadcrumbItems="[
  ['label' => 'Home', 'route' => route('marketing.index')],
  ['label' => 'Documentation', 'route' => route('marketing.docs.api.index')],
  ['label' => 'Organizations'],
]" >
  <div class="py-16">
    <x-marketing.docs.h1 title="Organizations" />

    <x-marketing.docs.table-of-content :items="[
      [
        'id' => 'get-the-organizations-of-the-current-user',
        'title' => 'Get the organizations of the current user',
      ],
      [
        'id' => 'get-an-organization',
        'title' => 'Get a specific organization',
      ],
      [
        'id' => 'create-an-organization',
        'title' => 'Create an organization',
      ],
      [
        'id' => 'update-an-organization',
        'title' => 'Update an organization',
      ],
      [
        'id' => 'delete-an-organization',
        'title' => 'Delete an organization',
      ],
    ]" />

    <div class="mb-10 grid grid-cols-1 gap-6 border-b border-gray-200 pb-10 sm:grid-cols-2 dark:border-gray-700">
      <div>
        <p class="mb-2">This endpoint gets the organizations of the current user.</p>
      </div>
      <div>
        <x-marketing.docs.code title="Endpoints">
          <div class="flex flex-col gap-y-2">
            <a href="#get-the-organizations-of-the-current-user">
              <span class="text-blue-700">GET</span>
              /api/organizations
            </a>
          </div>
          <div class="flex flex-col gap-y-2">
            <a href="#get-an-organization">
              <span class="text-blue-700">GET</span>
              /api/organizations/{id}
            </a>
          </div>
          <div class="flex flex-col gap-y-2">
            <a href="#create-an-organization">
              <span class="text-green-700">POST</span>
              /api/organizations
            </a>
          </div>
          <div class="flex flex-col gap-y-2">
            <a href="#update-an-organization">
              <span class="text-green-700">PUT</span>
              /api/organizations/{id}
            </a>
          </div>
          <div class="flex flex-col gap-y-2">
            <a href="#delete-an-organization">
              <span class="text-red-700">DELETE</span>
              /api/organizations/{id}
            </a>
          </div>
        </x-marketing.docs.code>
      </div>
    </div>

    <!-- GET /api/organizations -->
    <div class="mb-10 grid grid-cols-1 gap-6 border-b border-gray-200 pb-10 sm:grid-cols-2 dark:border-gray-700">
      <div>
        <x-marketing.docs.h2 id="get-the-organizations-of-the-current-user" title="Get the organizations of the current user" />
        <p class="mb-2">This endpoint gets the organizations of the current user.</p>
        <p class="mb-10">
          This call is not
          <x-link href="{{ route('marketing.docs.api.index') }}#pagination">paginated</x-link>
          at the moment.
        </p>

        <!-- url parameters -->
        <x-marketing.docs.url-parameters no-parameters></x-marketing.docs.url-parameters>

        <!-- query parameters -->
        <x-marketing.docs.query-parameters no-parameters></x-marketing.docs.query-parameters>

        <!-- response attributes -->
        <x-marketing.docs.response-attributes>
          <x-marketing.docs.attribute name="type" type="string" description="The type of the resource." />
          <x-marketing.docs.attribute name="id" type="string" description="The ID of the organization." />
          <x-marketing.docs.attribute name="attributes" type="object" description="The attributes of the organization." />
          <x-marketing.docs.attribute name="attributes.name" type="string" description="The name of the organization." />
          <x-marketing.docs.attribute name="attributes.slug" type="string" description="The slug of the organization." />
          <x-marketing.docs.attribute name="attributes.avatar" type="string" description="The avatar of the organization." />
          <x-marketing.docs.attribute name="attributes.created_at" type="integer" description="The date and time the object was created, in Unix timestamp format." />
          <x-marketing.docs.attribute name="attributes.updated_at" type="integer" description="The date and time the object was last updated, in Unix timestamp format." />
          <x-marketing.docs.attribute name="links" type="object" description="The links to access the organization." />
        </x-marketing.docs.response-attributes>
      </div>
      <div>
        <x-marketing.docs.code title="/api/organizations" verb="GET">
          <x-marketing.docs.json-section level="1" name="data">
            <x-marketing.docs.json-line level="2" key="type" value="organization" type="string" comma />
            <x-marketing.docs.json-line level="2" key="id" value="1" type="string" comma />
            <x-marketing.docs.json-section level="2" name="attributes" comma>
              <x-marketing.docs.json-line level="3" key="name" value="test" type="string" comma />
              <x-marketing.docs.json-line level="3" key="slug" value="1-test" type="string" comma />
              <x-marketing.docs.json-line level="3" key="avatar" value="data:image/svg+xml;base64,..." type="string" comma />
              <x-marketing.docs.json-line level="3" key="created_at" value="1771898698" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="updated_at" value="1771898698" type="integer" />
            </x-marketing.docs.json-section>
            <x-marketing.docs.json-section level="2" name="links">
              <x-marketing.docs.json-line level="3" key="self" value="http://orgos.test/api/organizations/1" type="string" />
            </x-marketing.docs.json-section>
          </x-marketing.docs.json-section>
        </x-marketing.docs.code>
      </div>
    </div>

    <!-- GET /api/organizations/{id} -->
    <div class="mb-10 grid grid-cols-1 gap-6 border-b border-gray-200 pb-10 sm:grid-cols-2 dark:border-gray-700">
      <div>
        <x-marketing.docs.h2 id="get-an-organization" title="Get a specific organization" />
        <p class="mb-10">This endpoint gets a specific organization.</p>

        <!-- url parameters -->
        <x-marketing.docs.url-parameters>
          <x-marketing.docs.attribute required name="id" type="integer" description="The ID of the organization to get." />
        </x-marketing.docs.url-parameters>

        <!-- query parameters -->
        <x-marketing.docs.query-parameters no-parameters></x-marketing.docs.query-parameters>

        <!-- response attributes -->
        <x-marketing.docs.response-attributes>
          <x-marketing.docs.attribute name="type" type="string" description="The type of the resource." />
          <x-marketing.docs.attribute name="id" type="string" description="The ID of the organization." />
          <x-marketing.docs.attribute name="attributes" type="object" description="The attributes of the organization." />
          <x-marketing.docs.attribute name="attributes.name" type="string" description="The name of the organization." />
          <x-marketing.docs.attribute name="attributes.slug" type="string" description="The slug of the organization." />
          <x-marketing.docs.attribute name="attributes.avatar" type="string" description="The avatar of the organization." />
          <x-marketing.docs.attribute name="attributes.created_at" type="integer" description="The date and time the object was created, in Unix timestamp format." />
          <x-marketing.docs.attribute name="attributes.updated_at" type="integer" description="The date and time the object was last updated, in Unix timestamp format." />
          <x-marketing.docs.attribute name="links" type="object" description="The links to access the organization." />
        </x-marketing.docs.response-attributes>
      </div>
      <div>
        <x-marketing.docs.code title="/api/organizations/{id}" verb="GET">
          <x-marketing.docs.json-section level="1" name="data">
            <x-marketing.docs.json-line level="2" key="type" value="organization" type="string" comma />
            <x-marketing.docs.json-line level="2" key="id" value="1" type="string" comma />
            <x-marketing.docs.json-section level="2" name="attributes" comma>
              <x-marketing.docs.json-line level="3" key="name" value="test" type="string" comma />
              <x-marketing.docs.json-line level="3" key="slug" value="1-test" type="string" comma />
              <x-marketing.docs.json-line level="3" key="avatar" value="data:image/svg+xml;base64,..." type="string" comma />
              <x-marketing.docs.json-line level="3" key="created_at" value="1771898698" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="updated_at" value="1771898698" type="integer" />
            </x-marketing.docs.json-section>
            <x-marketing.docs.json-section level="2" name="links">
              <x-marketing.docs.json-line level="3" key="self" value="http://orgos.test/api/organizations/1" type="string" />
            </x-marketing.docs.json-section>
          </x-marketing.docs.json-section>
        </x-marketing.docs.code>
      </div>
    </div>

    <!-- POST /api/organizations -->
    <div class="mb-10 grid grid-cols-1 gap-6 border-b border-gray-200 pb-10 sm:grid-cols-2 dark:border-gray-700">
      <div>
        <x-marketing.docs.h2 id="create-an-organization" title="Create an organization" />
        <p class="mb-10">This endpoint creates a new organization. It will return the organization in the response. The avatar is automatically generated.</p>

        <!-- url parameters -->
        <x-marketing.docs.url-parameters no-parameters></x-marketing.docs.url-parameters>

        <!-- query parameters -->
        <x-marketing.docs.query-parameters>
          <x-marketing.docs.attribute required name="name" type="string" description="The name of the organization. Maximum 255 characters." />
        </x-marketing.docs.query-parameters>

        <!-- response attributes -->
        <x-marketing.docs.response-attributes>
          <x-marketing.docs.attribute name="type" type="string" description="The object type. Always 'organization'." />
          <x-marketing.docs.attribute name="id" type="integer" description="The ID of the organization." />
          <x-marketing.docs.attribute name="attributes" type="object" description="The attributes of the organization." />
          <x-marketing.docs.attribute name="attributes.name" type="string" description="The name of the organization." />
          <x-marketing.docs.attribute name="attributes.slug" type="string" description="The slug of the organization." />
          <x-marketing.docs.attribute name="attributes.avatar" type="string" description="The avatar of the organization." />
          <x-marketing.docs.attribute name="attributes.created_at" type="integer" description="The date and time the object was created, in Unix timestamp format." />
          <x-marketing.docs.attribute name="attributes.updated_at" type="integer" description="The date and time the object was last updated, in Unix timestamp format." />
          <x-marketing.docs.attribute name="links" type="object" description="The links of the organization." />
          <x-marketing.docs.attribute name="self" type="string" description="The URL of the organization." />
        </x-marketing.docs.response-attributes>
      </div>
      <div>
        <x-marketing.docs.code title="/api/organizations" verb="POST">
          <x-marketing.docs.json-section level="1" name="data">
            <x-marketing.docs.json-line level="2" key="type" value="organization" type="string" comma />
            <x-marketing.docs.json-line level="2" key="id" value="1" type="string" comma />
            <x-marketing.docs.json-section level="2" name="attributes" comma>
              <x-marketing.docs.json-line level="3" key="name" value="test" type="string" comma />
              <x-marketing.docs.json-line level="3" key="slug" value="1-test" type="string" comma />
              <x-marketing.docs.json-line level="3" key="avatar" value="data:image/svg+xml;base64,..." type="string" comma />
              <x-marketing.docs.json-line level="3" key="created_at" value="1771898698" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="updated_at" value="1771898698" type="integer" />
            </x-marketing.docs.json-section>
            <x-marketing.docs.json-section level="2" name="links">
              <x-marketing.docs.json-line level="3" key="self" value="http://orgos.test/api/organizations/1" type="string" />
            </x-marketing.docs.json-section>
          </x-marketing.docs.json-section>
        </x-marketing.docs.code>
      </div>
    </div>

    <!-- PUT /api/organizations/{id} -->
    <div class="mb-10 grid grid-cols-1 gap-6 sm:grid-cols-2">
      <div>
        <x-marketing.docs.h2 id="update-an-organization" title="Update an organization" />
        <p class="mb-10">This endpoint updates the name of the organization. It will return the organization in the response. The avatar is automatically generated.</p>

        <!-- url parameters -->
        <x-marketing.docs.url-parameters no-parameters></x-marketing.docs.url-parameters>

        <!-- query parameters -->
        <x-marketing.docs.query-parameters>
          <x-marketing.docs.attribute required name="name" type="string" description="The name of the organization. Maximum 255 characters." />
        </x-marketing.docs.query-parameters>

        <!-- response attributes -->
        <x-marketing.docs.response-attributes>
          <x-marketing.docs.attribute name="type" type="string" description="The object type. Always 'organization'." />
          <x-marketing.docs.attribute name="id" type="integer" description="The ID of the organization." />
          <x-marketing.docs.attribute name="attributes" type="object" description="The attributes of the organization." />
          <x-marketing.docs.attribute name="attributes.name" type="string" description="The name of the organization." />
          <x-marketing.docs.attribute name="attributes.slug" type="string" description="The slug of the organization." />
          <x-marketing.docs.attribute name="attributes.avatar" type="string" description="The avatar of the organization." />
          <x-marketing.docs.attribute name="attributes.created_at" type="integer" description="The date and time the object was created, in Unix timestamp format." />
          <x-marketing.docs.attribute name="attributes.updated_at" type="integer" description="The date and time the object was last updated, in Unix timestamp format." />
          <x-marketing.docs.attribute name="links" type="object" description="The links of the organization." />
          <x-marketing.docs.attribute name="self" type="string" description="The URL of the organization." />
        </x-marketing.docs.response-attributes>
      </div>
      <div>
        <x-marketing.docs.code title="/api/organizations/{id}" verb="PUT">
          <x-marketing.docs.json-section level="1" name="data">
            <x-marketing.docs.json-line level="2" key="type" value="organization" type="string" comma />
            <x-marketing.docs.json-line level="2" key="id" value="1" type="string" comma />
            <x-marketing.docs.json-section level="2" name="attributes" comma>
              <x-marketing.docs.json-line level="3" key="name" value="test" type="string" comma />
              <x-marketing.docs.json-line level="3" key="slug" value="1-test" type="string" comma />
              <x-marketing.docs.json-line level="3" key="avatar" value="data:image/svg+xml;base64,..." type="string" comma />
              <x-marketing.docs.json-line level="3" key="created_at" value="1771898698" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="updated_at" value="1771898698" type="integer" />
            </x-marketing.docs.json-section>
            <x-marketing.docs.json-section level="2" name="links">
              <x-marketing.docs.json-line level="3" key="self" value="http://orgos.test/api/organizations/1" type="string" />
            </x-marketing.docs.json-section>
          </x-marketing.docs.json-section>
        </x-marketing.docs.code>
      </div>
    </div>

    <!-- DELETE /api/organizations/{id} -->
    <div class="mb-10 grid grid-cols-1 gap-6 sm:grid-cols-2">
      <div>
        <x-marketing.docs.h2 id="delete-an-organization" title="Delete an organization" />
        <p class="mb-10">This endpoint deletes an organization. It will return a success message in the response.</p>

        <!-- url parameters -->
        <x-marketing.docs.url-parameters>
          <x-marketing.docs.attribute required name="id" type="integer" description="The ID of the organization." />
        </x-marketing.docs.url-parameters>

        <!-- query parameters -->
        <x-marketing.docs.query-parameters no-parameters></x-marketing.docs.query-parameters>

        <!-- response attributes -->
        <x-marketing.docs.response-attributes no-parameters></x-marketing.docs.response-attributes>
      </div>
      <div>
        <x-marketing.docs.code title="/api/organizations/{id}" verb="DELETE">
          <div>No response body</div>
        </x-marketing.docs.code>
      </div>
    </div>
  </div>
</x-marketing-docs-layout>
