# Modules and damianulan Packages

Use this reference to decide ownership first, then select the package API that already implements the platform concern.

## Source of Truth

Before using a package API, confirm the installed version with `composer show <package>` and inspect its source under `vendor/damianulan/`. This repository currently tracks development branches, so behavior can change without a tagged release.

The installed README files for `damianulan/laravel-modularis` and `damianulan/laravel-nexus` are stale copies for another package. Do not use them as documentation. Prefer, in order:

1. Current Solaris integrations and tests.
2. Installed package source and configuration.
3. Package tests, stubs, and data objects.

Never patch `vendor/`. If package behavior is wrong, isolate an application-level workaround or coordinate an upstream fix.

## Choose the Ownership Boundary

Put code in the core application when it is shared platform infrastructure or required by several unrelated capabilities. Examples include the application user model, global middleware, the primary authenticated shell, cross-module types, and application-wide authentication wiring.

Create or extend a module when the code forms a cohesive business capability that could be enabled, evolved, or tested as a unit. A module should own the complete vertical slice whenever practical:

- `manifest.json` and `composer.json`
- a service provider
- routes and configuration
- controllers, requests, policies, actions, models, and data tables
- migrations, factories, and seeders
- translations and tests
- feature-specific Vue pages and components

Do not create a module merely to hold generic helpers. Do not leave a module route pointing to an `App` controller when the controller is specific to that module. Cross-module collaboration must use a deliberately shared contract, event, or service; never import another module's internal classes casually.

## Module Shape and Loading

Root Composer configuration merges `modules/*/composer.json`. Each module therefore declares its own PSR-4 namespace, normally `Modules\\<Name>\\`, plus factory and seeder namespaces when needed.

`bootstrap/app.php` calls `Modularis\\ProvidersLoader::load()`. The loader scans immediate children of `modules_path()`, reads each `manifest.json`, orders providers by descending `priority`, and registers the declared `provider`. It does not currently enforce an enabled/disabled flag.

Use this manifest shape:

```json
{
  "name": "Capability Name",
  "slug": "capability-name",
  "description": "What the capability owns",
  "type": "module",
  "priority": 0,
  "provider": "Modules\\CapabilityName\\CapabilityNameServiceProvider"
}
```

The service provider is the module composition root. Register bindings and merge configuration in `register()`. Load routes, translations, migrations, views, publishing, and Nexus navigation in `boot()`. Prefer the package's `Modularis\\ModuleServiceProvider` base for new modules after checking its installed implementation; preserve an existing module's base class unless the task includes a safe migration.

When adding or renaming a module namespace, refresh Composer autoloading. Keep module priority intentional because it controls provider registration order.

## Modularis: Module Discovery Only

Use `damianulan/laravel-modularis` for:

- resolving the modules directory through `modules_path()`;
- discovering module manifests and Composer metadata;
- registering module service providers through `ProvidersLoader`;
- querying the module registry only after verifying the exact installed implementation.

Do not build a second provider scanner or hard-code module providers in `bootstrap/providers.php`. Do not assume module registry APIs control provider loading; provider discovery currently operates directly from manifests.

## Nexus: Application Platform Contracts

Use `damianulan/laravel-nexus` as the platform layer instead of duplicating these concerns.

### HTTP and authentication

- Keep the application Inertia middleware extending the Nexus Inertia middleware so shared props remain consistent.
- Extend `Nexus\\Http\\Controllers\\BaseController` for normal application controllers when its authorization, validation, dispatch, headers, and flash-message behavior is useful.
- Extend or bind Nexus authentication controllers at the application boundary, as the current authentication integration does.
- Use `Nexus\\Auth\\AuthorizableModel` as the application's authentication-capable Eloquent base where the existing model contract requires it.
- Use the `nexus.auth` middleware alias for the authentication behavior provided by Nexus.

### Navigation and page metadata

Register navigation in application or module service providers with `Nexus\\Facades\\Sidebar`, `NavigationItem`, and `NavigationGroup`. Use named routes, Bootstrap icon names, and explicit priorities. Do not construct sidebar arrays in Vue or directly edit navigation rendering components for a new feature.

Set route page metadata with the existing `header()` and `description()` route macros. Nexus shares page metadata, theme information, localization, sidebar entries, alerts, and snackbars through Inertia; Vue consumes those contracts.

### User feedback

Use the Nexus alert/snackbar APIs or the controller `FlashesMessages` behavior for feedback that must survive an Inertia response. Do not create page-specific flash-prop formats.

### Server-driven tables

Use a Nexus `DataTableBlueprint` for paginated or sortable server-driven listings. Define the resource, source query, and columns on the server, then pass its serialized result to the existing Vue `DataTable`. Use `DataColumn`, `ActionColumn`, and `SelectableColumn` instead of inventing a second table schema.

Keep authorization and filtering in the backend source query. Avoid loading an unbounded collection merely to sort or filter it in Vue.

## Sentinel: Context-Aware Authorization

Use `damianulan/laravel-sentinel` only when the feature needs stored roles and permissions, including context-specific access. It is not automatically provided by `Nexus\\Auth\\AuthorizableModel`; the current application user model does not use Sentinel's `HasRolesAndPermissions` trait. Explicitly design and test that integration before calling Sentinel methods.

When Sentinel is adopted:

- define roles and permissions in project Warden classes and synchronize them through the package command rather than seeding ad hoc duplicates;
- add `Sentinel\\Traits\\HasRolesAndPermissions` to the intended authenticatable model;
- prefer Laravel authorization calls such as `$user->can($ability, $context)` in policies, middleware, and controllers because Sentinel integrates through `Gate::before`;
- pass an Eloquent context model when permission scope is contextual; otherwise the package uses its system context;
- use direct role and permission mutation APIs only in explicit administration workflows;
- centralize permission slugs and do not scatter untyped strings through controllers and Vue pages.

Do not use navigation visibility as authorization. Protect the route or action on the server even when the corresponding Nexus navigation item is omitted.

## Cross-Layer Feature Sequence

For a new modular feature:

1. Put the domain and authorization boundary in the module.
2. Register module routes and Nexus navigation in its service provider.
3. Set Nexus route page metadata.
4. Use a Nexus data-table blueprint for server-driven listings when applicable.
5. Render the feature with the shared `AppLayout` and existing Vue components.
6. Add focused backend tests for routing, authorization, and behavior; add frontend checks where component logic warrants them.

Keep serialized keys aligned with `resources/js/lib/types.ts`, `resources/js/lib/forms.ts`, and `resources/js/env.d.ts`.
