---
name: api-from-web-controller
description: Build a complete API surface from an existing web controller. Use when the user asks to add API methods, expose a resource via API, or mirror a web controller as an API, especially for adminland resources. Activates when user mentions API methods, API controller, API routes, or wants to expose an existing web resource via API.
---

# API from Web Controller

This skill walks through creating a full API surface for a resource that already has a web controller. The canonical example is an adminland resource like Offices or Office Types.

## Before you start

Load these skills immediately — they apply to every step:
- `laravel-controllers` — API controller rules and conventions
- `test-writer` — test structure and PHPUnit patterns

At the end, load and run both documentation skills in order:
- `bruno-docs-writer` — after all code is complete and tests pass
- `marketing-api-docs-writer` — after Bruno docs are written

---

## Step 1 — Study the web controller

Read the existing web controller (`app/Http/Controllers/App/…`). Extract:

- The **resource name** (singular, e.g. `Office`)
- Which **actions** are used (`CreateXxx`, `UpdateXxx`, `DestroyXxx`)
- The **validated fields** and their rules
- The **permission model** (who can do what — check the action's `validate()` method)

---

## Step 2 — Create the Eloquent Resource

Create `app/Http/Resources/{Resource}Resource.php`.

Rules:
- `type` field is snake_case resource name (e.g. `'office'`)
- `id` is cast to string
- `attributes` contains all relevant model fields; timestamps as Unix integers via `->timestamp`
- `links.self` points to the `show` route for this resource

```php
<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Office
 */
class OfficeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'office',
            'id' => (string) $this->id,
            'attributes' => [
                'name' => $this->name,
                // ... other fields ...
                'created_at' => $this->created_at->timestamp,
                'updated_at' => $this->updated_at->timestamp,
            ],
            'links' => [
                'self' => route('api.organization.adminland.office.show', [
                    'id' => $this->organization_id,
                    'officeId' => $this->id,
                ]),
            ],
        ];
    }
}
```

---

## Step 3 — Create the API controller

Create `app/Http/Controllers/Api/Adminland/{Resource}Controller.php`.

Rules (from `laravel-controllers` skill):
- Only these methods: `index`, `show`, `store`, `update`, `destroy`
- No domain logic — call Actions
- Validate inline (no FormRequests)
- Use `$request->attributes->get('organization')` to get the organization (set by `organization.api` middleware)
- Use `$request->user()` — never `Auth::user()`
- Use `TextSanitizer::plainText()` / `nullablePlainText()` on string inputs before passing to actions
- Return `AnonymousResourceCollection` from `index`, `JsonResponse` from `show`/`store`/`update`, `Response` (204) from `destroy`
- Scope all resource lookups to the organization: `$organization->offices()->findOrFail($officeId)`

```php
public function index(Request $request): AnonymousResourceCollection
{
    $organization = $request->attributes->get('organization');
    $offices = $organization->offices()->orderBy('name')->get();
    return OfficeResource::collection($offices);
}

public function store(Request $request, int $id): JsonResponse
{
    $organization = $request->attributes->get('organization');
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        // ...
    ]);
    $office = new CreateOffice(
        user: $request->user(),
        organization: $organization,
        name: TextSanitizer::plainText($validated['name']),
        // ...
    )->execute();
    return new OfficeResource($office)->response()->setStatusCode(201);
}

public function destroy(Request $request, int $id, int $officeId): Response
{
    $organization = $request->attributes->get('organization');
    $office = $organization->offices()->findOrFail($officeId);
    new DestroyOffice(user: $request->user(), organization: $organization, office: $office)->execute();
    return response()->noContent(204);
}
```

---

## Step 4 — Register routes

Add routes to `routes/api.php` inside the `organization.api` middleware group. Follow the existing pattern exactly.

```php
// adminland - offices
Route::get('organizations/{id}/adminland/offices', [OfficeController::class, 'index'])->name('organization.adminland.office.index');
Route::post('organizations/{id}/adminland/offices', [OfficeController::class, 'store'])->name('organization.adminland.office.store');
Route::get('organizations/{id}/adminland/offices/{officeId}', [OfficeController::class, 'show'])->name('organization.adminland.office.show');
Route::put('organizations/{id}/adminland/offices/{officeId}', [OfficeController::class, 'update'])->name('organization.adminland.office.update');
Route::delete('organizations/{id}/adminland/offices/{officeId}', [OfficeController::class, 'destroy'])->name('organization.adminland.office.destroy');
```

Add the `use` import in alphabetical order with other `Adminland` controller imports.

---

## Step 5 — Write tests

Create `tests/Feature/Controllers/Api/{Resource}ControllerTest.php`.

Rules (from `test-writer` skill):
- Use `RefreshDatabase`
- Use `#[Test]` attributes
- Use `Sanctum::actingAs($user)` for auth
- Use `$this->json('METHOD', '/api/…', $data)` — never `$this->actingAs()->…`
- Never call `for()` on factories. Pass explicit `['key' => $value]` arrays
- Never peek in the database — only assert HTTP status and JSON structure
- Define a `$jsonStructure` property once and reuse it

**Required test cases:**
1. `it_lists_{resources}_for_an_organization` — 200 + count
2. `it_returns_empty_collection_when_no_{resources}_exist` — 200 + count 0
3. `it_restricts_listing_{resources}_to_organization_members` — 403 for non-member
4. `it_can_show_a_{resource}` — 200 + structure
5. `it_returns_404_when_showing_a_{resource}_from_another_organization` — 404
6. `it_can_create_a_{resource}` — 201 + structure
7. `it_requires_[field]_when_creating_a_{resource}` — 422 (one per required field)
8. `it_returns_404_when_a_non_admin_tries_to_create_a_{resource}` — 404
9. `it_can_update_a_{resource}` — 200 + structure
10. `it_requires_[field]_when_updating_a_{resource}` — 422 (one per required field)
11. `it_returns_404_when_a_non_admin_tries_to_update_a_{resource}` — 404
12. `it_can_destroy_a_{resource}` — 204 no content
13. `it_returns_404_when_a_non_admin_tries_to_destroy_a_{resource}` — 404

For non-admin permission tests, create a `Member::factory()->create([…, 'permission' => Permission::Member])` manually — do not use `addOrganization()`.

---

## Step 6 — Run Pint and tests

```bash
vendor/bin/pint --dirty --format agent
php artisan test --compact tests/Feature/Controllers/Api/{Resource}ControllerTest.php
```

All tests must pass before proceeding.

---

## Step 7 — Bruno API docs

Load and follow the **`bruno-docs-writer`** skill.

Summary:
- Create folder `docs/bruno/OrgOS/Organizations/Adminland/{Resources}/`
- Add `folder.bru` with `seq` after the last existing adminland folder
- Add one `.bru` file per endpoint, `seq` 1–5 in CRUD order (List, Get, Create, Update, Delete)
- POST/PUT files must include a `body:json` block with a realistic payload

---

## Step 8 — Marketing docs

Load and follow the **`marketing-api-docs-writer`** skill.

Summary:
- Create `app/Http/Controllers/Marketing/Docs/Api{Resource}Controller.php`
- Add route to `routes/marketing.php` (import alphabetically)
- Update `resources/views/layouts/docs.blade.php`:
  - Add Alpine state variable `{resource}Documentation` to the `x-data` block
  - Add a link inside the Adminland section, expanding when the route matches
- Create `resources/views/marketing/docs/api/organizations/{resources}/index.blade.php`
- Create `tests/Feature/Controllers/Marketing/Docs/Api{Resource}ControllerTest.php` asserting HTTP 200

---

## Validation checklist

- [ ] `{Resource}Resource.php` returns correct type, id (string), attributes, links.self
- [ ] API controller uses only `index`, `show`, `store`, `update`, `destroy`
- [ ] All string inputs pass through `TextSanitizer`
- [ ] Resource lookups are scoped to the organization (`$organization->offices()->findOrFail(…)`)
- [ ] 5 routes registered with correct names
- [ ] All test cases listed in Step 5 are present and passing
- [ ] Pint reports no violations
- [ ] Bruno folder + 5 `.bru` files created
- [ ] Marketing controller, route, sidebar link, Blade view, and test created
