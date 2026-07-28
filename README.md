# YOO InstantSearch

YOOtheme Pro Builder elements for an instant-search interface powered by either Typesense or Algolia.

## Requirements

- Joomla with YOOtheme Pro
- ZooLanders YOOessentials (used by the Builder element configuration)
- An Algolia index or Typesense collection containing the searchable records

Use only browser-safe search keys: provider credentials are sent to the visitor's browser. Never use an admin or write key.

## Install and configure

Install a plugin package that includes the compiled asset and Composer runtime files (`assets/yooinstantsearch.min.js` and `vendor/`), then enable **YOOtheme Pro - InstantSearch Elements** in Joomla.

In YOOtheme Pro, edit a section or row, enable **Instant Search** under Advanced, and open **Configuration**. Choose a provider and enter:

- **Typesense:** host, port, protocol/path, collection, search API key, and `query_by` fields.
- **Algolia:** application ID, index name, and search API key.

Add Instant Search elements inside that area. The available elements cover search input, hits, pagination, statistics, facets/menu/toggle filters, searchable facets, ranges and range sliders, sorting, active/clear filters, no-results content, and a table layout.

## Filters and indexed data

Facet/filter field names must match fields in the search index. Configure those fields as filterable/facetable in the chosen provider.

By default, results are filtered to `state = 1`. Optional locale filtering uses the document language (for example `en-US`). You can also filter by the current Joomla category title or article title. Adjust or disable these fields in the search-area configuration when they do not match the index schema.

## Development

```sh
cd plugin
composer install --no-dev --optimize-autoloader
npm ci
npm run build
```

Use `npm run watch` from `plugin/` while editing frontend assets. Commit the regenerated `plugin/assets/yooinstantsearch.min.js` with changes under `plugin/assets/src/`.

Create an installable plugin package with:

```sh
./build.sh
```

The archive is written to `dist/yooinstantsearch.zip`.

Release from `develop` with `./release.sh`. It uses the npm package version, synchronizes `VERSION` and the Joomla manifest, builds the package, merges into `main`, tags the release, and pushes `develop`, `main`, and the tag.

## Project layout

- `plugin/` — installable Joomla plugin source and dependency manifests.
- `plugin/src/` — Joomla plugin bootstrapping and search configuration.
- `plugin/builder/` — YOOtheme Pro Builder elements, templates, and customizer fields.
- `plugin/assets/src/` — Vue InstantSearch application and Algolia/Typesense adapters.
- `plugin/assets/yooinstantsearch.min.js` — compiled browser bundle loaded by the root element.
