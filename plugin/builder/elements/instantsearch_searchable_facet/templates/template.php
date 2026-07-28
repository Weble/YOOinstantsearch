<?php
$facetId = $node->facet_id;
$searchableFacets = $node->searchable_facets ?: '[]';
$staticFilters = htmlspecialchars($node->static_filters ?: '[]', ENT_QUOTES, 'UTF-8');
$attributeLabels = $node->attribute_labels ?: '{}';

$el = $this->el('div', [
    'class' => ['searchableRefinements'],
    'data-searchable-facet-id' => $facetId,
    'data-search-static-filters' => $staticFilters,
]);

$search = $this->el('input', [
    'class' => ['uk-input', 'refinementSearch'],
    'data-searchable-facet-input' => $facetId,
    '@focus' => "setFacetDropdown('{$facetId}', true)",
    '@input' => "searchForFacets('{$facetId}', {$searchableFacets}, \$event.currentTarget.value)",
    '@keydown.delete' => "popFacet('{$facetId}', \$event)",
    'placeholder' => $props['placeholder'] ?? '',
]);
?>

<?= $el($props, $attrs); ?>
    <ul class="uk-flex uk-subnav">
        <li v-for="filter in searchableFacet('<?= $facetId ?>').selectedFilters" :key="`${filter.field}:${filter.value}`">
            <a @click.prevent="toggleFilter('<?= $facetId ?>', filter.field, filter.value)">
                {{ renameAttributes(filter.field, <?= $attributeLabels ?>) }} {{ filter.value }}
            </a>
        </li>
        <li><?= $search($props, $attrs); ?></li>
    </ul>

    <div class="uk-dropdown uk-open" v-show="searchableFacet('<?= $facetId ?>').results.length && searchableFacet('<?= $facetId ?>').open">
        <ul class="uk-list">
            <li v-for="facet in searchableFacet('<?= $facetId ?>').results" :key="`${facet.facet}:${facet.value}`">
                <a @click.prevent="toggleFilter('<?= $facetId ?>', facet.facet, facet.value); setFacetDropdown('<?= $facetId ?>', false); clearFacetInput('<?= $facetId ?>')">
                    {{ renameAttributes(facet.facet, <?= $attributeLabels ?>) }} {{ facet.value }}
                </a>
            </li>
        </ul>
    </div>
<?= $el->end(); ?>
