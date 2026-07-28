# YOO InstantSearch agent guide

## Purpose and stack

Joomla system plugin under `plugin/` that adds YOOtheme Pro Builder instant-search areas. PHP registers Builder types; Vue 3 + Vue InstantSearch runs in the browser. Supported providers are Typesense and Algolia. Builder configuration also depends on ZooLanders YOOessentials.

## Runtime flow

1. `plugin/src/Extension/YOOinstantsearch.php` aliases the plugin path and loads `plugin/builder/bootstrap.php`.
2. `CustomizerListener` adds the search-area controls to YOOtheme sections and rows.
3. `BuilderTransform` replaces an enabled section/row's children with the `instantsearch` root.
4. `SearchService` serializes provider, index, default filters, and browser search credentials into root markup.
5. `plugin/assets/src/index.js` mounts Vue on each `[data-search-root]`; `InstantSearch.js` renders the Builder-provided template.

## Key locations

- `plugin/builder/config/customizer.json` — provider and root filter settings.
- `plugin/builder/elements/*/element.php` — Builder field definitions and PHP transforms.
- `plugin/builder/elements/*/templates/` — Vue-aware PHP markup.
- `plugin/assets/src/providers/filtering.js` — shared filter normalization and provider-specific query syntax.
- `plugin/assets/src/providers/algolia.js`, `typesense.js` — provider clients and facet lookups.
- `plugin/webpack.mix.js` — builds `plugin/assets/src/index.js` to `plugin/assets/yooinstantsearch.min.js`.

## Working rules

- Keep Algolia and Typesense filtering behaviour aligned; change shared filter handling in `plugin/assets/src/providers/filtering.js`.
- Rebuild and commit `plugin/assets/yooinstantsearch.min.js` after any `plugin/assets/src/` change: `cd plugin && npm ci && npm run build`.
- Run `cd plugin && composer install --no-dev --optimize-autoloader` before packaging; the Joomla manifest includes `vendor/`.
- Browser-visible keys must be search-only. Do not add indexing/admin credentials to Builder data or the bundle.
- Index field names must match configured facets, locale/state filters, and dynamic filters. Default state filtering is `state = 1`.
- There is no configured automated test suite. Validate changed PHP against a Joomla/YOOtheme site and changed UI against both providers when applicable.
