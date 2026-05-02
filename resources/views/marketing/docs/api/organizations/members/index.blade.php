<x-marketing-docs-layout :breadcrumbItems="[
  ['label' => 'Home', 'route' => route('marketing.index')],
  ['label' => 'Documentation', 'route' => route('marketing.docs.api.index')],
  ['label' => 'Organizations', 'route' => route('marketing.docs.api.organizations.index')],
  ['label' => 'Members'],
]">
  <div class="py-16">
    <x-marketing.docs.h1 title="Members" />

    <x-marketing.docs.table-of-content :items="[
      [
        'id' => 'list-members',
        'title' => 'List all members',
      ],
      [
        'id' => 'get-a-member',
        'title' => 'Get a specific member',
      ],
    ]" />

    <div class="mb-10 grid grid-cols-1 gap-6 border-b border-gray-200 pb-10 sm:grid-cols-2 dark:border-gray-700">
      <div>
        <p class="mb-2">Members represent the people who belong to an organization. Each member is linked to a user account and has a permission level that determines what they can do within the organization.</p>
        <p>All member endpoints require you to be a member of the organization.</p>
      </div>
      <div>
        <x-marketing.docs.code title="Endpoints">
          <div class="flex flex-col gap-y-2">
            <a href="#list-members">
              <span class="text-blue-700">GET</span>
              /api/organizations/{id}/adminland/members
            </a>
          </div>
          <div class="flex flex-col gap-y-2">
            <a href="#get-a-member">
              <span class="text-blue-700">GET</span>
              /api/organizations/{id}/adminland/members/{memberId}
            </a>
          </div>
        </x-marketing.docs.code>
      </div>
    </div>

    <!-- GET /api/organizations/{id}/adminland/members -->
    <div class="mb-10 grid grid-cols-1 gap-6 border-b border-gray-200 pb-10 sm:grid-cols-2 dark:border-gray-700">
      <div>
        <x-marketing.docs.h2 id="list-members" title="List all members" />
        <p class="mb-2">This endpoint returns all members belonging to the given organization, ordered by most recently joined.</p>
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
          <x-marketing.docs.attribute name="type" type="string" description="The type of the resource. Always 'member'." />
          <x-marketing.docs.attribute name="id" type="string" description="The ID of the member." />
          <x-marketing.docs.attribute name="attributes" type="object" description="The attributes of the member." />
          <x-marketing.docs.attribute name="attributes.user_id" type="integer" description="The ID of the user associated with this member." />
          <x-marketing.docs.attribute name="attributes.name" type="string" description="The full name of the user." />
          <x-marketing.docs.attribute name="attributes.email" type="string" description="The email address of the user." />
          <x-marketing.docs.attribute name="attributes.permission" type="string" description="The permission level of the member. One of: owner, admin, member, guest." />
          <x-marketing.docs.attribute name="attributes.timezone" type="string" description="The timezone of the member." />
          <x-marketing.docs.attribute name="attributes.birthdate" type="integer" description="The birthdate of the member, in Unix timestamp format. Null if not set." />
          <x-marketing.docs.attribute name="attributes.joined_at" type="integer" description="The date and time the member joined the organization, in Unix timestamp format. Null if not set." />
          <x-marketing.docs.attribute name="attributes.created_at" type="integer" description="The date and time the object was created, in Unix timestamp format." />
          <x-marketing.docs.attribute name="attributes.updated_at" type="integer" description="The date and time the object was last updated, in Unix timestamp format." />
          <x-marketing.docs.attribute name="links" type="object" description="The links to access the member." />
          <x-marketing.docs.attribute name="links.self" type="string" description="The URL of the member." />
        </x-marketing.docs.response-attributes>
      </div>
      <div>
        <x-marketing.docs.code title="/api/organizations/{id}/adminland/members" verb="GET">
          <x-marketing.docs.json-section level="1" name="data">
            <x-marketing.docs.json-line level="2" key="type" value="member" type="string" comma />
            <x-marketing.docs.json-line level="2" key="id" value="1" type="string" comma />
            <x-marketing.docs.json-section level="2" name="attributes" comma>
              <x-marketing.docs.json-line level="3" key="user_id" value="4" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="name" value="Jim Halpert" type="string" comma />
              <x-marketing.docs.json-line level="3" key="email" value="jim@dundermifflin.com" type="string" comma />
              <x-marketing.docs.json-line level="3" key="permission" value="admin" type="string" comma />
              <x-marketing.docs.json-line level="3" key="timezone" value="America/New_York" type="string" comma />
              <x-marketing.docs.json-line level="3" key="birthdate" value="null" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="joined_at" value="1771898698" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="created_at" value="1771898698" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="updated_at" value="1771898698" type="integer" />
            </x-marketing.docs.json-section>
            <x-marketing.docs.json-section level="2" name="links">
              <x-marketing.docs.json-line level="3" key="self" value="http://orgos.test/api/organizations/1/adminland/members/1" type="string" />
            </x-marketing.docs.json-section>
          </x-marketing.docs.json-section>
        </x-marketing.docs.code>
      </div>
    </div>

    <!-- GET /api/organizations/{id}/adminland/members/{memberId} -->
    <div class="mb-10 grid grid-cols-1 gap-6 sm:grid-cols-2">
      <div>
        <x-marketing.docs.h2 id="get-a-member" title="Get a specific member" />
        <p class="mb-2">This endpoint returns a specific member belonging to the given organization.</p>
        <p class="mb-10">
          <strong>Required permission:</strong>
          any member of the organization.
        </p>

        <!-- url parameters -->
        <x-marketing.docs.url-parameters>
          <x-marketing.docs.attribute required name="id" type="integer" description="The ID of the organization." />
          <x-marketing.docs.attribute required name="memberId" type="integer" description="The ID of the member." />
        </x-marketing.docs.url-parameters>

        <!-- query parameters -->
        <x-marketing.docs.query-parameters no-parameters></x-marketing.docs.query-parameters>

        <!-- response attributes -->
        <x-marketing.docs.response-attributes>
          <x-marketing.docs.attribute name="type" type="string" description="The type of the resource. Always 'member'." />
          <x-marketing.docs.attribute name="id" type="string" description="The ID of the member." />
          <x-marketing.docs.attribute name="attributes" type="object" description="The attributes of the member." />
          <x-marketing.docs.attribute name="attributes.user_id" type="integer" description="The ID of the user associated with this member." />
          <x-marketing.docs.attribute name="attributes.name" type="string" description="The full name of the user." />
          <x-marketing.docs.attribute name="attributes.email" type="string" description="The email address of the user." />
          <x-marketing.docs.attribute name="attributes.permission" type="string" description="The permission level of the member. One of: owner, admin, member, guest." />
          <x-marketing.docs.attribute name="attributes.timezone" type="string" description="The timezone of the member." />
          <x-marketing.docs.attribute name="attributes.birthdate" type="integer" description="The birthdate of the member, in Unix timestamp format. Null if not set." />
          <x-marketing.docs.attribute name="attributes.joined_at" type="integer" description="The date and time the member joined the organization, in Unix timestamp format. Null if not set." />
          <x-marketing.docs.attribute name="attributes.created_at" type="integer" description="The date and time the object was created, in Unix timestamp format." />
          <x-marketing.docs.attribute name="attributes.updated_at" type="integer" description="The date and time the object was last updated, in Unix timestamp format." />
          <x-marketing.docs.attribute name="links" type="object" description="The links to access the member." />
          <x-marketing.docs.attribute name="links.self" type="string" description="The URL of the member." />
        </x-marketing.docs.response-attributes>
      </div>
      <div>
        <x-marketing.docs.code title="/api/organizations/{id}/adminland/members/{memberId}" verb="GET">
          <x-marketing.docs.json-section level="1" name="data">
            <x-marketing.docs.json-line level="2" key="type" value="member" type="string" comma />
            <x-marketing.docs.json-line level="2" key="id" value="1" type="string" comma />
            <x-marketing.docs.json-section level="2" name="attributes" comma>
              <x-marketing.docs.json-line level="3" key="user_id" value="4" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="name" value="Jim Halpert" type="string" comma />
              <x-marketing.docs.json-line level="3" key="email" value="jim@dundermifflin.com" type="string" comma />
              <x-marketing.docs.json-line level="3" key="permission" value="admin" type="string" comma />
              <x-marketing.docs.json-line level="3" key="timezone" value="America/New_York" type="string" comma />
              <x-marketing.docs.json-line level="3" key="birthdate" value="null" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="joined_at" value="1771898698" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="created_at" value="1771898698" type="integer" comma />
              <x-marketing.docs.json-line level="3" key="updated_at" value="1771898698" type="integer" />
            </x-marketing.docs.json-section>
            <x-marketing.docs.json-section level="2" name="links">
              <x-marketing.docs.json-line level="3" key="self" value="http://orgos.test/api/organizations/1/adminland/members/1" type="string" />
            </x-marketing.docs.json-section>
          </x-marketing.docs.json-section>
        </x-marketing.docs.code>
      </div>
    </div>
  </div>
</x-marketing-docs-layout>
