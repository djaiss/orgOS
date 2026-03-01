---
name: test-writer
description: Use when writing tests. Follow testing best practices, use Laravel's testing tools, and ensure tests are clear, maintainable, and cover relevant scenarios.
---

# Test writer

## Rules

- Use PHPunit, not Pest.
- Use `#[Test]` attributes for test methods.

## Test for models

- Test relationships existence.
- Do not test casts.
- Test any custom methods in the model.
