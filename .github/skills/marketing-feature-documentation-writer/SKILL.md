---
name: marketing-feature-documentation-writer
description: Write the documentation for an existing feature.
---

# Marketing feature documentation writer

This skill creates and maintains the public documentation pages on the marketing site about important features of the product.

## When to use this Skill

Use this Skill when:
- A new feature is added to the product that requires explanation (e.g. "the ability to create custom office types in Adminland")
- A new feature is added to the product (e.g. "the ability to create custom office types in Adminland")
- An existing feature is updated or changed
- A documentation page is missing for an implemented feature

## Workflow

Execute these steps **in order**. Use the todo list to track progress.

### Read context sources

Check for and read (if they exist) the following to build a complete understanding of how it works in the codebase before writing anything:

1. The model that implements the feature or concept (in /app/Models)
1. The controller that handles the feature (in /app/Http/Controllers)
1. The views that render the feature (in /resources/views)
1. The routes that expose the feature (in /routes)
1. The Actions involved to understand permission requirements (e.g. Owner/Admin-only vs any member) and the business logic.

### Understand where to add the documentation in the structure of the marketing docs

- The sidebar (resources/views/layouts/docs.blade.php) reflects the structure of the documentation. Identify where the new documentation should be added based on the existing hierarchy and related topics.
- Group related features under common sections. Make sure that these sections are understandable and intuitive for users navigating the documentation. For instance, here is how Gitlab organizes their documentation around sections and subsections:

```
> Manage your organization
v Organize work with projects
  |
  |-- [ Getting started ]
  |
  |   Create a project
  |
  |   Manage projects
  |
  |   Project visibility
  |
  |   Project settings
```

### How to structure the documentation files

```
routes/marketing.php                           ← named route for each docs page
app/Http/Controllers/Marketing/Docs/           ← thin controllers; one per docs page
resources/views/marketing/docs/                ← Blade views
resources/views/layouts/docs.blade.php         ← sidebar navigation (Alpine.js)
tests/Feature/Controllers/Marketing/Docs/      ← one test per docs controller
```

### Write the documentation

- At the top, write the title (ie `Manage projects`) and a brief introduction about the feature.
- Write subsections for each important aspect of the feature. For example, "Creating a project", "Project settings", "Permissions", etc.
- In each subsection, use numbered steps for each step of the process. Add sub steps when needed, also using numbered steps.
- On each step, explain exactly every single field to fill, button to click, or setting to toggle. Example: `Project name: Enter the name of your project. For more information, see naming rules.`
- Cross reference other documentation pages when relevant using relative links.

### Write a test for the controller

- Add a test that asserts the route returns HTTP 200.
