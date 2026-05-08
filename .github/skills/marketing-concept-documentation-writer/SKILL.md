---
name: marketing-concept-documentation-writer
description: Write the documentation for an existing concept.
---

# Marketing concept documentation writer

This skill creates and maintains the public documentation pages on the marketing site about important concepts of the product.

## When to use this Skill

Use this Skill when:
- A new concept is introduced that requires explanation (e.g. "office types")
- A new feature is added to the product that requires explanation (e.g. "the ability to create custom office types in Adminland")
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
- Concepts are always documented in the `Getting started` subsection of the relevant section. For example, the concept of "office types" is documented in the "Getting started" subsection of the "Organize work with offices" section.

### How to structure the documentation files

```
routes/marketing.php                           ← named route for each docs page
app/Http/Controllers/Marketing/Docs/           ← thin controllers; one per docs page
resources/views/marketing/docs/                ← Blade views
resources/views/layouts/docs.blade.php         ← sidebar navigation (Alpine.js)
tests/Feature/Controllers/Marketing/Docs/      ← one test per docs controller
```

### Write the documentation

- At the top, write the title (ie `Manage projects`) and a brief introduction about the concept.
- Then, explain the concept in more detail, using simple language and examples. If the concept is related to a feature, explain how the feature works and how it relates to the concept.
- When necessary, use fake examples to demonstrate the feature without exposing real customer information. Use examples from the tv show The Office (US) when you need to provide examples.
- Cross reference other documentation pages when relevant using relative links.

### Write a test for the controller

- Add a test that asserts the route returns HTTP 200.
