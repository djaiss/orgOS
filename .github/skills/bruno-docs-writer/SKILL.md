---
name: bruno-docs-writer
description: Write or update Bruno API documentation files for a given API controller. Use when a new API controller is created, routes are added or changed, or Bruno docs are missing or out of sync with the codebase.
---

# Bruno Docs Writer

This Skill creates and maintains Bruno (`.bru`) request files that document the API surface of the application. Bruno files live under `docs/bruno/OrgOS/` and are organized to mirror the route hierarchy.

## When to use this Skill

Use this Skill when:
- A new API controller or route group is added
- An existing route is renamed, moved, or removed
- Bruno files are missing for implemented endpoints
- Route parameters or request bodies change

## Project structure

```
docs/bruno/OrgOS/
├── collection.bru          # Collection-wide headers and bearer auth
├── environments/
│   └── Local.bru           # {{URL}} variable — do not change
├── Health.bru
└── Organizations/
    ├── folder.bru
    ├── List organizations.bru
    ├── Create organization.bru
    ├── Get organization.bru
    ├── Update organization.bru
    ├── Delete journal.bru
    └── Adminland/
        ├── folder.bru
        └── Office Types/
            ├── folder.bru
            ├── List office types.bru
            ├── Get office type.bru
            ├── Update office type.bru
            └── Delete office type.bru
```

- Folder nesting mirrors the route hierarchy.
- Every folder requires a `folder.bru` file.
- File names use Title Case and match the human-readable name of the operation.

## File formats

### folder.bru

Every folder (including nested ones) needs a `folder.bru`. It declares the folder's display name, sort position, and inherits auth from the collection.

```
meta {
  name: <Folder Display Name>
  seq: <integer>
}

auth {
  mode: inherit
}
```

### Request file (.bru)

Each endpoint gets its own file. `seq` controls the display order within the folder — start at 1 and increment.

**GET request (no body):**
```
meta {
  name: List office types
  type: http
  seq: 1
}

get {
  url: {{URL}}/organizations/1/adminland/officetypes
  body: none
  auth: inherit
}
```

**PUT / POST request (with JSON body):**
```
meta {
  name: Update office type
  type: http
  seq: 3
}

put {
  url: {{URL}}/organizations/1/adminland/officetypes/1
  body: json
  auth: inherit
}

body:json {
  {
    "name": "Remote"
  }
}
```

**DELETE request:**
```
meta {
  name: Delete office type
  type: http
  seq: 4
}

delete {
  url: {{URL}}/organizations/1/adminland/officetypes/1
  body: none
  auth: inherit
}
```

## Rules

- Always use `auth: inherit` on request files. Auth is configured once in `collection.bru`.
- Always use `{{URL}}` for the base URL. Never hardcode a domain or port.
- URL path segments should use representative integer IDs (e.g. `1`) as placeholders.
- Use lowercase HTTP method keywords: `get`, `post`, `put`, `patch`, `delete`.
- `body: none` for requests with no body; `body: json` for JSON payloads.
- The `body:json` block must contain a realistic example payload — not empty, not `{}`.
- File names must be human-readable Title Case matching the `name` field in `meta`.
- `seq` values must be unique within a folder and reflect a logical read order (list → get → create → update → delete).
- Do not include headers or auth blocks in individual request files unless they differ from the collection default.

## Instructions

### Step 1: Identify endpoints to document

1. Read the relevant controller file.
2. List every public method (`index`, `show`, `store`, `update`, `destroy`).
3. Cross-reference `routes/api.php` to get the exact URL pattern for each method.

### Step 2: Determine folder placement

1. Map the route path to a folder path under `docs/bruno/OrgOS/`.
   - Example: `organizations/{id}/adminland/officetypes` → `Organizations/Adminland/Office Types/`
2. Check whether the target folder and its `folder.bru` already exist.
3. Create any missing folders and `folder.bru` files before adding request files.

### Step 3: Write request files

For each endpoint:
1. Choose the correct HTTP method block (`get`, `post`, `put`, `patch`, `delete`).
2. Set `seq` to reflect the natural CRUD order within the folder.
3. Include a `body:json` block with a realistic example for any POST/PUT/PATCH endpoint.
4. Name the file to match the `name` in `meta` (e.g. `Update office type.bru`).

### Step 4: Verify completeness

Confirm that:
- Every route in the controller has a corresponding `.bru` file.
- No stale files remain for removed routes.
- `seq` values within each folder are contiguous and start at 1.

## Validation checklist

- [ ] All endpoints in the controller have a corresponding `.bru` file
- [ ] All new folders have a `folder.bru`
- [ ] All files use `auth: inherit` and `{{URL}}`
- [ ] JSON bodies contain realistic example data
- [ ] File names match the `name` field in `meta`
- [ ] `seq` values are unique and ordered within each folder

## Output expectation

All API endpoints for the given controller are documented with correct Bruno request files, placed in the right folder hierarchy, with realistic example payloads and consistent formatting.
