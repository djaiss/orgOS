<x-marketing-docs-layout :breadcrumbItems="[
  ['label' => 'Home', 'route' => route('marketing.index')],
  ['label' => 'Documentation', 'route' => route('marketing.docs.api.index')],
  ['label' => 'Introduction'],
]">
  <div class="py-16">
    <x-marketing.docs.h1 title="API reference" />

    <x-marketing.docs.table-of-content :items="[
      [
        'id' => 'test-the-api-yourself',
        'title' => 'Test the API yourself',
      ],
      [
        'id' => 'conventions-of-the-api',
        'title' => 'Conventions of the API',
      ],
      [
        'id' => 'pagination',
        'title' => 'Pagination',
      ],
      [
        'id' => 'health',
        'title' => 'Health',
      ],
    ]" />

    <div class="mb-10 grid grid-cols-1 gap-6 border-b border-gray-200 pb-10 sm:grid-cols-2 dark:border-gray-700">
      <div>
        <p class="mb-2">The {{ config('app.name') }} API is organized around REST. Our API has predictable resource-oriented URLs.</p>
        <p class="mb-2">
          You
          <strong>can not</strong>
          use the {{ config('app.name') }} API in test mode. This means all requests will be processed towards your production account. Please be cautious.
        </p>
        <p>The {{ config('app.name') }} API doesn’t support bulk updates. You can work on only one object per request.</p>
      </div>

      <div>
        <h2 class="mb-2 text-lg font-bold">Base URL</h2>
        <x-marketing.docs.code>{{ config('app.url') }}/api</x-marketing.docs.code>
      </div>
    </div>

    <div class="mb-10 border-b border-gray-200 pb-10 sm:grid-cols-2 dark:border-gray-700">
      <x-marketing.docs.h2 id="test-the-api-yourself" title="Test the API yourself" />
      <p class="mb-2">
        If you want to test the API yourself, we provide two convenient tools for you to use:
        <x-link href="https://www.usebruno.com/" target="_blank">Bruno</x-link>
        .
      </p>
      <p class="mb-2">
        The documentation is included in the GitHub repository, under the
        <x-link href="https://github.com/djaiss/journalOS/tree/main/docs" target="_blank">docs</x-link>
        folder.
      </p>
      <p>Why these tools? Because they're fresh, new, free and open source under the MIT license, and I really like their ethos.</p>
    </div>

    <div class="mb-10 border-b border-gray-200 pb-10 sm:grid-cols-2 dark:border-gray-700">
      <x-marketing.docs.h2 id="conventions-of-the-api" title="Conventions of the API" />
      <p class="mb-2">
        There is no strict standard for JSON payloads, but we do try to follow
        <x-link href="https://jsonapi.org/" target="_blank">the JSON:API specification</x-link>
        , which defines a structured format for responses.
      </p>
    </div>

    <div class="mb-10 grid grid-cols-1 gap-6 border-b border-gray-200 pb-10 sm:grid-cols-2 dark:border-gray-700">
      <div>
        <x-marketing.docs.h2 id="pagination" title="Pagination" />
        <p class="mb-2">All endpoints that return a collection of resources support pagination.</p>
        <p class="mb-2">
          The default value for
          <code>per_page</code>
          is 10. This can not be changed at the moment.
        </p>
        <p class="mb-2">All responses will include links to navigate to the next and previous pages.</p>
      </div>
      <div>
        <x-marketing.docs.code title="Example of pagination" verb="GET">
          <x-marketing.docs.json-section level="1" name="meta">
            <x-marketing.docs.json-line level="2" key="current_page" value="1" type="integer" comma />
            <x-marketing.docs.json-line level="2" key="from" value="1" type="integer" comma />
            <x-marketing.docs.json-line level="2" key="last_page" value="1" type="integer" comma />
            <div class="pl-8">"links": [</div>
            <div class="pl-12">{</div>
            <x-marketing.docs.json-line level="4" key="url" value="null" comma />
            <x-marketing.docs.json-line level="4" key="label" value="&laquo; Previous" type="string" comma />
            <x-marketing.docs.json-line level="4" key="page" value="null" comma />
            <x-marketing.docs.json-line level="4" key="active" value="false" />
            <div class="pl-12">},</div>
            <div class="pl-12">{</div>
            <x-marketing.docs.json-line level="4" key="url" value="{{ config('app.url') }}/api/settings/logs?page=1" type="string" comma />
            <x-marketing.docs.json-line level="4" key="label" value="1" type="string" comma />
            <x-marketing.docs.json-line level="4" key="page" value="1" type="integer" comma />
            <x-marketing.docs.json-line level="4" key="active" value="true" />
            <div class="pl-12">},</div>
            <div class="pl-12">{</div>
            <x-marketing.docs.json-line level="4" key="url" value="null" comma />
            <x-marketing.docs.json-line level="4" key="label" value="Next &raquo;" type="string" comma />
            <x-marketing.docs.json-line level="4" key="page" value="null" comma />
            <x-marketing.docs.json-line level="4" key="active" value="false" />
            <div class="pl-12">}],</div>
            <x-marketing.docs.json-line level="2" key="path" value="{{ config('app.url') }}/api/settings/logs" type="string" comma />
            <x-marketing.docs.json-line level="2" key="per_page" value="10" type="integer" comma />
            <x-marketing.docs.json-line level="2" key="to" value="1" type="integer" comma />
            <x-marketing.docs.json-line level="2" key="total" value="1" type="integer" />
          </x-marketing.docs.json-section>
        </x-marketing.docs.code>
      </div>
    </div>

    <!-- GET /api/health -->
    <div class="mb-10 grid grid-cols-1 gap-6 sm:grid-cols-2">
      <div>
        <x-marketing.docs.h2 id="health" title="Health" />
        <p class="mb-10">This endpoint checks the health of the application and returns a simple "ok" message. It lets you know if the application is running and if the database is connected.</p>

        <!-- url parameters -->
        <x-marketing.docs.url-parameters no-parameters></x-marketing.docs.url-parameters>

        <!-- query parameters -->
        <x-marketing.docs.query-parameters no-parameters></x-marketing.docs.query-parameters>

        <!-- response attributes -->
        <x-marketing.docs.response-attributes>
          <x-marketing.docs.attribute name="message" type="string" description="The message of the response." />
          <x-marketing.docs.attribute name="services" type="object" description="The status of the application's services." />
          <x-marketing.docs.attribute name="services.database" type="string" description="The status of the database connection." />
        </x-marketing.docs.response-attributes>
      </div>
      <div>
        <x-marketing.docs.code title="/api/health" verb="GET">
          <x-marketing.docs.json-line level="1" key="message" value="ok" type="string" comma />
          <x-marketing.docs.json-section level="1" name="services">
            <x-marketing.docs.json-line level="2" key="database" value="up" type="string" />
          </x-marketing.docs.json-section>
        </x-marketing.docs.code>
      </div>
    </div>
  </div>
</x-marketing-docs-layout>
