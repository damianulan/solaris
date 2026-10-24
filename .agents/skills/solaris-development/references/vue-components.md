# Vue Components and Their Purpose

The Vue layer renders contracts produced by Laravel and Nexus. Reuse the components below before reaching for raw Vuetify markup. Inspect the component's current props and sibling usage before changing it.

## Page-Level Building Blocks

| Need | Use | Purpose and boundary |
| --- | --- | --- |
| Authenticated page shell | `layout/shell/AppLayout.vue` | Owns the header, system banner, navigation, alerts, snackbars, and footer. Select `navigation="vertical"` or `navigation="horizontal"`; do not rebuild the shell inside pages. |
| Authentication/public shell | `auth/AuthShell.vue` | Provides branded auth-page presentation, title copy, theme switching, and snackbars. Use for login, registration, password, and verification pages. |
| Server-driven listing | `data-tables/DataTable.vue` | Renders a Nexus `DataTableBlueprint`, including server pagination, sorting, actions, selection, and confirmation. Do not create a parallel table payload. |
| Standard form | `forms/SchemaForm.vue` | Builds an Inertia form from `FormSchema`, maps validation errors, supports HTTP method spoofing, upload progress, submit, and reset. Prefer it for ordinary CRUD forms. |
| Confirming an action | `dialogs/ConfirmationDialog.vue` | Provides the shared confirmation interaction for destructive or committing actions. Caller owns localized labels and handles the confirm event. |
| Informational modal | `dialogs/InfoDialog.vue` | Presents information that the user acknowledges without a destructive action. |
| Inline information | `layout/feedback/AppInfo.vue` | Presents contextual, inline informational content. Verify its current prop contract and typecheck before reuse. |

## Buttons

| Component | Use it for |
| --- | --- |
| `buttons/Button.vue` | Normal non-submit actions with standard Solaris shape, color, typography, loading state, and optional prepend/append content. |
| `buttons/SubmitButton.vue` | Primary form submission with submit semantics and loading state. |
| `buttons/ResetButton.vue` | Resetting a form with the standard error-colored outlined treatment. |
| `buttons/IconButton.vue` | Compact icon-only actions. Always provide its accessible label/tooltip; prefer it for row and toolbar actions. |

Do not replace these with locally styled `v-btn` instances unless their contract cannot express the required interaction. Extend a shared component only when the new behavior is broadly reusable and does not destabilize existing callers.

## Specialized and Shell-Owned Components

These components exist for composition inside shared components. Pages normally should not import them directly:

- `forms/FilepondInput.vue`: specialized, server-processed single-file upload. Prefer `SchemaForm` with its `filepond` field type; use directly only for a genuinely custom upload flow.
- `data-tables/DataTableActionButtons.vue`: internal renderer for Nexus action-column values.
- `layout/navigation/AppNavigation.vue`: vertical drawer/rail fed by `page.props.sidebar`.
- `layout/navigation/AppHorizontalNavigation.vue`: horizontal navigation fed by the same sidebar contract.
- `layout/navigation/AppNavigationItem.vue`, `AppHorizontalNavigationItem.vue`, and `AppNavigationLink.vue`: recursive navigation renderers; feature code registers Nexus navigation instead of importing these.
- `layout/branding/AppBrand.vue`: reusable brand aggregate. `BrandLogo.vue` and `BrandTitle.vue` are its lower-level parts.
- `layout/feedback/AppAlerts.vue`: renders Nexus alerts at the shell or designated auth boundary.
- `layout/feedback/AppSnackbars.vue`: renders Nexus snackbars at shell level.
- `layout/feedback/AppTopBanner.vue`: renders the shared application banner at shell level.
- `layout/feedback/AppLoadingOverlay.vue`: global Inertia navigation overlay mounted by the application entry point; never mount it per page.
- `layout/navigation/AppUserMenu.vue` and `ThemeSwitcher.vue`: shell/auth controls rather than feature-page widgets.
- `layout/shell/AppHeader.vue`, `AppTopbar.vue`, and `AppFooter.vue`: internals composed by `AppLayout`.

## Component Selection Rules

1. Search by purpose and inspect usages with `rg` before creating a component.
2. Compose a page from public building blocks. Do not import a shell internal merely to imitate one fragment of the shell.
3. Put a reusable component in the existing type folder: `buttons`, `data-tables`, `dialogs`, `forms`, or the matching `layout` subfolder.
4. Keep a capability-specific component with that capability's frontend code rather than promoting it into the shared component library prematurely.
5. Promote a component to shared only after its API is domain-neutral and at least two real callers need it.
6. Preserve a single root element in every Vue component.

## Backend-to-Vue Contracts

- `page.props.sidebar` belongs to Nexus. Register items in a PHP service provider; both navigation variants reload and render the same items automatically.
- `page.props.page` belongs to Nexus route metadata. Set the page header and description on the route rather than hard-coding shell headings.
- `page.props.alerts`, `snackbars`, `theme`, and `localization` belong to Nexus middleware. Reuse the corresponding shell components and composables.
- Data-table payloads come from Nexus data-table blueprints.
- Form schema types live in `resources/js/lib/forms.ts`.
- shared page and platform types live in `resources/js/lib/types.ts` and `resources/js/env.d.ts`.

When a backend payload changes, update its TypeScript type and every consumer in the same change. Do not weaken contracts with `any` to bypass a mismatch.

## Vue and Inertia Conventions

- Use Inertia `Link` for internal routes and preserve existing prefetch and view-transition behavior.
- Use `useTranslations` and established translation keys for user-facing text.
- Use Vuetify theme tokens and the existing Bootstrap `bi-*` icon vocabulary. Do not introduce a new icon library or hard-coded palette for a local feature.
- Keep browser-only APIs out of module initialization so SSR remains safe. Access `window`, `document`, and browser-only libraries inside lifecycle hooks or guarded client code.
- Keep state local unless it is truly cross-page. Prefer Inertia props and existing composables over a new global store.
- Do not import Vuetify component CSS from SSR entry code. Let the configured Vuetify Vite plugin and global stylesheet own framework styling.
- Maintain accessibility: labeled controls, keyboard-operable actions, meaningful dialog titles, and tooltip/ARIA text for icon-only buttons.

## Change Checklist

Before completing a Vue change:

1. Confirm no existing component already covers the use case.
2. Confirm the component is placed at the correct shared or capability-specific boundary.
3. Confirm all visible text is localized and internal links use Inertia.
4. Confirm SSR-safe imports and browser API access.
5. Run the repository's TypeScript check and production build, then address errors in changed code.
