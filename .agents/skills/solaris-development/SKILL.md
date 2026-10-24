---
name: solaris-development
description: Apply Solaris-specific architecture when adding, moving, or reviewing application features. Use for deciding whether code belongs in the core app or a module, integrating damianulan/laravel-modularis, damianulan/laravel-nexus, or damianulan/laravel-sentinel, and creating or changing Inertia Vue pages, layouts, navigation, forms, tables, dialogs, feedback, or shared components.
---

# Solaris Development

Keep features inside the correct boundary and reuse the project's platform packages and Vue component system instead of creating parallel abstractions.

## Start Every Task

1. Read `.ai/rules/index.md` and all matching rules when `.ai/rules` exists.
2. Inspect neighboring files and the relevant installed package version before choosing an API.
3. Decide whether the feature belongs to the core application or a module.
4. Search `resources/js/components` before adding Vue markup or wrapping a Vuetify primitive.
5. Preserve the existing PHP-to-Inertia-to-Vue data contracts.

For Laravel or PHP work, also apply `laravel-best-practices` and `spatie-laravel-php-standards`. For Inertia Vue work, also apply `inertia-vue-development`. For tests, also apply `testing-best-practices` and `pest-testing`.

## Route to the Relevant Reference

- Read [references/modules-and-vendor-packages.md](references/modules-and-vendor-packages.md) for module boundaries, module structure, or any use of Modularis, Nexus, or Sentinel.
- Read [references/vue-components.md](references/vue-components.md) for Vue pages, layouts, navigation, forms, data tables, dialogs, feedback, buttons, or shared frontend contracts.
- Read both references for a vertical feature that crosses backend and frontend boundaries.

## Non-Negotiable Boundaries

- Never edit files under `vendor/`. Implement application integration in `app/`, `modules/`, configuration, or service providers.
- Treat installed package source and current Solaris usage as authoritative. The installed Modularis and Nexus README files are stale and describe a different package.
- Keep business capabilities cohesive. A module should own its routes, controllers, models, policies, data tables, translations, configuration, tests, and Vue feature UI when those artifacts exist only for that capability.
- Keep genuinely shared platform behavior in the core application. Do not make one module depend on another module's private implementation.
- Prefer Nexus contracts for navigation, page metadata, notifications, authentication foundations, and server-driven data tables.
- Prefer existing shared Vue components for presentation. Pages compose components; they do not reimplement the design system.
- Do not introduce a new dependency, global abstraction, top-level directory, or cross-module contract without explicit approval.

## Finish the Task

- Run the narrowest relevant tests and frontend checks.
- Run `vendor/bin/pint --dirty --format agent` after modifying PHP.
- Run the existing TypeScript check and/or production build after modifying Vue or TypeScript.
- Report any package-source inconsistency that affects the implementation instead of silently depending on undocumented behavior.
