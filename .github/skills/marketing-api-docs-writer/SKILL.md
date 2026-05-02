---
name: marketing-api-docs-writer
description: Write or update the public-facing marketing documentation page for API methods. Use when a new API controller is created, routes are added or changed, or when documentation pages are missing or out of sync with the codebase.
---

# Marketing API Docs Writer

This skill creates and maintains the public-facing API documentation pages on the marketing site. Each controller's endpoints are documented in a dedicated Blade view, wired up through a dedicated marketing controller, a named route, and a sidebar entry.

## When to use this Skill

Use this Skill when:
- A new API controller or route group is added
- An existing route is renamed, moved, or removed
- A documentation page is missing for an implemented controller
- Documented parameters or response shapes change

---

## Architecture overview

```
routes/marketing.php                           ← named route for each docs page
app/Http/Controllers/Marketing/Docs/           ← thin controllers; one per docs page
resources/views/marketing/docs/api/            ← Blade views
resources/views/layouts/docs.blade.php         ← sidebar navigation (Alpine.js)
tests/Feature/Controllers/Marketing/Docs/      ← one test per docs controller
```

The URL path mirrors the API route hierarchy. For example, `organizations/{id}/adminland/officetypes` becomes `/docs/api/organizations/officetypes`.

---

## Step 1 — Research the API being documented

Before writing anything:
1. Read the relevant API controller (`app/Http/Controllers/Api/…`) to identify every public method.
2. Read `routes/api.php` to get the exact URL pattern, HTTP method, and route name for each endpoint.
3. Read the Eloquent Resource class (`app/Http/Resources/…`) to get the exact response shape.
4. Read the Actions involved to understand permission requirements (e.g. Owner/Admin-only vs any member).
5. Read sibling docs Blade views (e.g. `resources/views/marketing/docs/api/organizations/index.blade.php`) to follow established style and tone.

---

## Step 2 — Create the marketing controller

Create a thin controller in `app/Http/Controllers/Marketing/Docs/`. It extends `App\Http\Controllers\Controller` and has a single `index()` method returning a Blade view.

**Naming:** `Api{ResourceName}Controller` — e.g. `ApiOfficeTypeController`.

**View path convention:** `marketing.docs.api.{resource_segment}.index`
For nested/adminland resources: `marketing.docs.api.{parent}.{resource_segment}.index`

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers\Marketing\Docs;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ApiOfficeTypeController extends Controller
{
    public function index(): View
    {
        return view('marketing.docs.api.organizations.officetypes.index');
    }
}
```

Use `php artisan make:class "Http/Controllers/Marketing/Docs/ApiOfficeTypeController" --no-interaction` to scaffold, then overwrite with the correct content.

---

## Step 3 — Register the route

Add a `GET` route to `routes/marketing.php` inside the existing `marketing` middleware group, alongside the other docs routes.

**Route name convention:** `marketing.docs.api.{resource_segment}.index`
For nested resources: `marketing.docs.api.{parent}.{resource_segment}.index`

```php
use App\Http\Controllers\Marketing\Docs\ApiOfficeTypeController;

// inside the Route::middleware(['marketing'])->group(...) closure:
Route::get('/docs/api/organizations/officetypes', [ApiOfficeTypeController::class, 'index'])
    ->name('marketing.docs.api.organizations.officetypes.index');
```

Always add the `use` import at the top of the file in alphabetical order with the other `Api*Controller` imports.

---

## Step 4 — Update the sidebar

The sidebar lives in `resources/views/layouts/docs.blade.php`. It uses Alpine.js `x-data` for collapsible sections.

### 4a — Add an Alpine state variable

For each new docs section, add a boolean variable to the `x-data` object. Name it `{resourceName}Documentation` (camelCase).

Auto-expand it when the current route matches the section:

```php
officeTypesDocumentation:
  '{{ request()->routeIs('marketing.docs.api.organizations.officetypes.*') ? 'true' : 'false' }}' ===
  'true',
```

Use `request()->routeIs('….*')` (wildcard) so the section expands for any page under that prefix.

### 4b — Add the section to the sidebar HTML

The sidebar hierarchy mirrors the route hierarchy:

```
API Documentation (top-level collapsible)
  └─ Introduction
  └─ Organizations (collapsible)
       └─ Organizations (link)
       └─ Adminland (collapsible)
            └─ Office Types (link)
```

**Top-level section toggle** (e.g. "Organizations"):
```html
<div @click="organizationsDocumentation = !organizationsDocumentation"
     class="mb-3 flex cursor-pointer items-center justify-between rounded-md border border-transparent px-2 py-1 pl-3 text-xs text-gray-500 uppercase hover:border-gray-200 hover:bg-blue-50 dark:text-gray-400 dark:hover:border-gray-700 dark:hover:bg-gray-800">
  <h3>Organizations</h3>
  <x-phosphor-caret-right x-bind:class="organizationsDocumentation ? 'rotate-90' : ''"
                           class="h-4 w-4 text-gray-500 transition-transform duration-300" />
</div>
<div x-show="organizationsDocumentation" class="mb-3 flex flex-col gap-y-2">
  ...children...
</div>
```

**Nested sub-section toggle** (e.g. "Adminland" inside "Organizations"). Use `@click.stop` to prevent the parent from also toggling:
```html
<div @click.stop="officeTypesDocumentation = !officeTypesDocumentation"
     class="flex cursor-pointer items-center justify-between rounded-md border border-transparent px-2 py-1 pl-3 text-xs text-gray-500 uppercase hover:border-gray-200 hover:bg-blue-50 dark:text-gray-400 dark:hover:border-gray-700 dark:hover:bg-gray-800">
  <h3>Adminland</h3>
  <x-phosphor-caret-right x-bind:class="officeTypesDocumentation ? 'rotate-90' : ''"
                           class="h-4 w-4 text-gray-500 transition-transform duration-300" />
</div>
<div x-show="officeTypesDocumentation" class="flex flex-col gap-y-2">
  ...links...
</div>
```

**Active link** — use `border-l-blue-400` when on the target route, `border-l-transparent` otherwise. Nested links use more left padding (`pl-6`) than top-level links (`pl-3`):
```html
<a href="{{ route('marketing.docs.api.organizations.officetypes.index') }}"
   class="{{ request()->routeIs('marketing.docs.api.organizations.officetypes.index') ? 'border-l-blue-400' : 'border-l-transparent' }} block border-l-3 pl-6 hover:border-l-blue-400 hover:underline">
  Office Types
</a>
```

---

## Step 5 — Create the Blade view

Create the view at the path the controller returns. For example:
`resources/views/marketing/docs/api/organizations/officetypes/index.blade.php`

### Layout wrapper

```blade
<x-marketing-docs-layout :breadcrumbItems="[
  ['label' => 'Home',          'route' => route('marketing.index')],
  ['label' => 'Documentation', 'route' => route('marketing.docs.api.index')],
  ['label' => 'Organizations', 'route' => route('marketing.docs.api.organizations.index')],
  ['label' => 'Office Types'],  {{-- current page has no route --}}
]">
  <div class="py-16">
    ...
  </div>
</x-marketing-docs-layout>
```

The last breadcrumb item has no `route` key — it represents the current page.

### Page header and table of contents

```blade
<x-marketing.docs.h1 title="Office Types" />

<x-marketing.docs.table-of-content :items="[
  ['id' => 'list-office-types',   'title' => 'List all office types'],
  ['id' => 'get-an-office-type',  'title' => 'Get a specific office type'],
  ['id' => 'create-an-office-type', 'title' => 'Create an office type'],
  ['id' => 'update-an-office-type', 'title' => 'Update an office type'],
  ['id' => 'delete-an-office-type', 'title' => 'Delete an office type'],
]" />
```

### Endpoint overview block

Place an introductory block before the individual sections. Left column: a brief description of what the resource represents and any global permission note. Right column: all endpoint URLs listed with colored HTTP verbs. Verb color classes: `text-blue-700` (GET), `text-green-700` (POST), `text-yellow-700` (PUT), `text-red-700` (DELETE).

```blade
<div class="mb-10 grid grid-cols-1 gap-6 border-b border-gray-200 pb-10 sm:grid-cols-2 dark:border-gray-700">
  <div>
    <p class="mb-2">Brief description of the resource.</p>
    <p>Global permission note if applicable.</p>
  </div>
  <div>
    <x-marketing.docs.code title="Endpoints">
      <div class="flex flex-col gap-y-2">
        <a href="#list-office-types">
          <span class="text-blue-700">GET</span> /api/organizations/{id}/adminland/officetypes
        </a>
      </div>
      {{-- repeat for each endpoint --}}
    </x-marketing.docs.code>
  </div>
</div>
```

### Per-endpoint section

Each endpoint is a two-column grid. All sections except the last have a bottom border and padding.

```blade
{{-- Not last section --}}
<div class="mb-10 grid grid-cols-1 gap-6 border-b border-gray-200 pb-10 sm:grid-cols-2 dark:border-gray-700">

{{-- Last section (no border) --}}
<div class="mb-10 grid grid-cols-1 gap-6 sm:grid-cols-2">
```

**Left column** — description, permission note, then the three collapsible panels:

```blade
<div>
  <x-marketing.docs.h2 id="list-office-types" title="List all office types" />

  <p class="mb-2">Description of what this endpoint does.</p>
  <p class="mb-10"><strong>Required permission:</strong> Owner or Administrator.</p>

  {{-- URL parameters --}}
  <x-marketing.docs.url-parameters>
    <x-marketing.docs.attribute required name="id" type="integer" description="The ID of the organization." />
  </x-marketing.docs.url-parameters>
  {{-- Use no-parameters when none exist: --}}
  <x-marketing.docs.url-parameters no-parameters></x-marketing.docs.url-parameters>

  {{-- Body / query parameters --}}
  <x-marketing.docs.query-parameters>
    <x-marketing.docs.attribute required name="name" type="string" description="The name. Maximum 255 characters." />
    <x-marketing.docs.attribute name="position" type="integer" description="Optional. Display order, starting at 0." />
  </x-marketing.docs.query-parameters>
  {{-- Use no-parameters when none exist: --}}
  <x-marketing.docs.query-parameters no-parameters></x-marketing.docs.query-parameters>

  {{-- Response attributes --}}
  <x-marketing.docs.response-attributes>
    <x-marketing.docs.attribute name="type"   type="string"  description="Always 'office_type'." />
    <x-marketing.docs.attribute name="id"     type="string"  description="The ID of the resource." />
    ...
  </x-marketing.docs.response-attributes>
  {{-- Use no-parameters for 204 No Content endpoints: --}}
  <x-marketing.docs.response-attributes no-parameters></x-marketing.docs.response-attributes>
</div>
```

**`x-marketing.docs.attribute` props:**
| Prop | Required | Description |
|---|---|---|
| `name` | yes | Field name, e.g. `attributes.name` |
| `type` | yes | `string`, `integer`, `object`, etc. |
| `description` | yes | Human-readable explanation |
| `required` | no | Renders a red "required" badge when present |

**Right column** — the JSON code example:

```blade
<div>
  <x-marketing.docs.code title="/api/organizations/{id}/adminland/officetypes" verb="GET">
    <x-marketing.docs.json-section level="1" name="data">
      <x-marketing.docs.json-line level="2" key="type" value="office_type" type="string" comma />
      <x-marketing.docs.json-line level="2" key="id"   value="1"           type="string" comma />
      <x-marketing.docs.json-section level="2" name="attributes" comma>
        <x-marketing.docs.json-line level="3" key="name"       value="Remote"     type="string"  comma />
        <x-marketing.docs.json-line level="3" key="position"   value="0"          type="integer" comma />
        <x-marketing.docs.json-line level="3" key="created_at" value="1771898698" type="integer" comma />
        <x-marketing.docs.json-line level="3" key="updated_at" value="1771898698" type="integer" />
      </x-marketing.docs.json-section>
      <x-marketing.docs.json-section level="2" name="links">
        <x-marketing.docs.json-line level="3" key="self" value="http://orgos.test/api/organizations/1/adminland/officetypes/1" type="string" />
      </x-marketing.docs.json-section>
    </x-marketing.docs.json-section>
  </x-marketing.docs.code>
</div>
```

**`x-marketing.docs.json-section` props:**
| Prop | Default | Description |
|---|---|---|
| `name` | — | The JSON key for this object block |
| `level` | 0 | Indentation level (0–8); each level adds `pl-4` |
| `comma` | false | Adds a trailing comma after the closing `}` |

**`x-marketing.docs.json-line` props:**
| Prop | Default | Description |
|---|---|---|
| `key` | — | JSON key |
| `value` | — | JSON value (unquoted; component adds quotes for `type="string"`) |
| `type` | `string` | `string` (lime-700 color) or `integer` (rose-800 color) |
| `level` | 0 | Indentation level (0–8) |
| `comma` | false | Adds a trailing comma |

**`x-marketing.docs.code` props:**
| Prop | Default | Description |
|---|---|---|
| `title` | `Code` | Shown in the header bar (usually the URL path) |
| `verb` | — | HTTP verb; renders colored: `GET`=blue-700, `POST`=green-700, `PUT`=yellow-700, `DELETE`=red-700 |

For DELETE endpoints that return no body:
```blade
<x-marketing.docs.code title="/api/…/{id}" verb="DELETE">
  <div>No response body</div>
</x-marketing.docs.code>
```

---

## Step 6 — Write the test

Create a PHPUnit feature test in `tests/Feature/Controllers/Marketing/Docs/`. One test per docs page; it asserts the route returns HTTP 200.

```php
<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Marketing\Docs;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ApiOfficeTypeControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_renders_the_api_office_type_page(): void
    {
        $response = $this->get('/docs/api/organizations/officetypes');
        $response->assertOk();
    }
}
```

**Important:** `php artisan make:test` incorrectly nests the file under `tests/Feature/Feature/…`. Always move the generated file to the correct location:
```bash
mv tests/Feature/Feature/Controllers/Marketing/Docs/ApiOfficeTypeControllerTest.php \
   tests/Feature/Controllers/Marketing/Docs/ApiOfficeTypeControllerTest.php
rm -rf tests/Feature/Feature
```

---

## Step 7 — Run Pint and tests

```bash
vendor/bin/pint --dirty --format agent
php artisan test --compact tests/Feature/Controllers/Marketing/Docs/ApiOfficeTypeControllerTest.php
```

---

## Permission model reference

Consult the Actions involved to determine access rules. Standard patterns:

| Permission level | Which endpoints |
|---|---|
| Any organization member (Owner, Admin, Member, Guest) | `index`, `show` |
| Owner or Administrator only | `store`, `update`, `destroy` |

Always state the required permission in the description paragraph of each endpoint section using the pattern:
```
<strong>Required permission:</strong> Owner or Administrator.
```
or:
```
<strong>Required permission:</strong> any member of the organization.
```

---

## Validation checklist

- [ ] Marketing controller created in `app/Http/Controllers/Marketing/Docs/`
- [ ] Route added to `routes/marketing.php` inside the `marketing` middleware group
- [ ] Route name follows `marketing.docs.api.{segment(s)}.index` convention
- [ ] Blade view created at the path returned by the controller
- [ ] Breadcrumb chain is correct; last item has no `route` key
- [ ] Table of contents `id` values match the `id` props on `x-marketing.docs.h2`
- [ ] Endpoint overview block lists all endpoints with correct verb colors
- [ ] Each endpoint has: h2, description paragraph, permission note, url-parameters, query-parameters, response-attributes, code example
- [ ] `no-parameters` attribute used where there are no params (not an empty slot)
- [ ] Last endpoint section has no bottom border (no `border-b` class on the grid div)
- [ ] JSON code examples reflect the actual Eloquent Resource response shape
- [ ] Sidebar Alpine state variable added to `x-data` in `layouts/docs.blade.php`
- [ ] Sidebar link added with correct active class and indentation
- [ ] Test created in `tests/Feature/Controllers/Marketing/Docs/` and passes

---

## Output expectation

A fully wired docs page: route → controller → view → sidebar link → passing test. The docs page accurately documents all endpoints with correct URLs, HTTP verbs, parameters, response shapes, and permission requirements.
