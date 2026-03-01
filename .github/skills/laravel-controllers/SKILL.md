---
name: laravel-controllers
description: Thin HTTP layer controllers. Controllers contain zero domain logic, only HTTP concerns. Use when working with controllers, HTTP layer, web vs API patterns, or when user mentions controllers, routes, HTTP responses.
---

# Laravel Controllers

## Philosophy

**Controllers should ONLY:**
1. Type-hint dependencies
2. Validate
3. Call actions
4. Return responses (resources, redirects, views)

**Controllers should NEVER:**
- Contain domain logic
- Make database queries directly
- Perform calculations
- Handle complex business rules

## Rules

- ALWAYS use `$request->user()` and not `Auth::user()` to identify the current logged user.
- NEVER use FormRequests. Use validation within the controller.
- When testing a controller, you should never have to peak in the database. Database changes are tested by actions. Tests in a controller SHOULD ONLY make sure that a page can be shown and that the form has successfully been submitted.

## Controller Naming Conventions

**Controllers should be named using the SINGULAR form of the main resource:**

### Standard Resource Controllers

```php
// ✅ CORRECT - Plural resource names
CalendarController      // manages calendar resources
EventController         // manages event resources
OrderController         // manages order resources
```

```php
// ❌ INCORRECT - Singular form
CalendarsController
EventsController
```

## RESTful Methods Only

Controllers **must only** use Laravel's standard RESTful method names.

### Standard RESTful Methods

**For web applications (with forms):**
- `index` - Display a listing of the resource
- `create` - Show the form for creating a new resource
- `store` - Store a newly created resource
- `show` - Display the specified resource
- `edit` - Show the form for editing the resource
- `update` - Update the specified resource
- `destroy` - Remove the specified resource

**For APIs (no form views):**
- `index`, `show`, `store`, `update`, `destroy`
- **APIs must NOT include `create` or `edit` methods** (those are for HTML forms)

### Forbidden Method Names

Never use custom method names in resource controllers:

```php
// ❌ INCORRECT
class OrdersController extends Controller
{
    public function all() { }      // Use index
    public function get() { }      // Use show
    public function add() { }      // Use store
    public function remove() { }   // Use destroy
    public function cancel() { }   // Extract to CancelOrderController
}
```

## Full Controller Example

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers\App\Organization;

use App\Actions\CreateOrganization;
use App\Helpers\TextSanitizer;
use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrganizationController extends Controller
{
    public function index(Request $request): View
    {
        $organizations = $request
            ->user()
            ->organizations()
            ->get()
            ->map(fn(Organization $organization) => (object) [
                'name' => $organization->name,
                'link' => route('organization.show', $organization->slug),
                'avatar' => $organization->getAvatar(),
            ]);

        return view('app.organization.index', [
            'organizations' => $organizations,
        ]);
    }

    public function create(): View
    {
        return view('app.organization.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'organization_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9\s\-_]+$/',
            ],
        ]);

        $organization = new CreateOrganization(
            user: $request->user(),
            name: TextSanitizer::plainText($validated['organization_name']),
        )->execute();

        return to_route('organization.show', $organization->slug)
            ->with('status', __('Organization created successfully'));
    }

    public function show(Request $request): View
    {
        return view('app.organization.show', [
            'organization' => $request->attributes->get('organization'),
        ]);
    }
}
```

## Controller Testing

```php
<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\App\Organization;

use App\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrganizationControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_shows_the_list_of_organizations(): void
    {
        $user = $this->createUser();
        $this->addOrganization($user);

        $response = $this->actingAs($user)->get('/organizations');

        $response->assertStatus(200);
        $response->assertViewHas(
            'organizations',
            fn($organizations): bool => $organizations->count() === 1
            && $organizations->every(fn($org): bool => isset($org->name, $org->link, $org->avatar)),
        );
    }

    #[Test]
    public function it_shows_the_create_organization_page(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)->get('/organizations/create');

        $response->assertStatus(200);
        $response->assertViewIs('app.organization.create');
    }

    #[Test]
    public function it_creates_an_organization(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)->post('/organizations', [
            'organization_name' => 'My Organization',
        ]);

        $organization = Organization::query()->where('name', 'My Organization')->first();
        $response->assertRedirect('/organizations/' . $organization->slug);
        $response->assertSessionHas('status', 'Organization created successfully');
    }

    #[Test]
    public function it_shows_a_single_organization(): void
    {
        $user = $this->createUser();
        $organization = $this->addOrganization($user, 'Dunder Mifflin');

        $response = $this->actingAs($user)
            ->get('/organizations/' . $organization->slug);

        $response->assertStatus(200);
        $response->assertViewIs('app.organization.show');
        $response->assertViewHas('organization');
    }
}
```
