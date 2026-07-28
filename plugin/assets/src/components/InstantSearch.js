import VueSlider from "@vueform/slider";
import "@vueform/slider/themes/default.css";
import { history as historyRouter } from "instantsearch.js/es/lib/routers";

import {
  AisConfigure,
  AisCurrentRefinements,
  AisHits,
  AisInstantSearch,
  AisMenu,
  AisHierarchicalMenu,
  AisPagination,
  AisRefinementList,
  AisStats,
  AisSearchBox,
  AisSortBy,
  AisRangeInput,
  AisToggleRefinement,
  AisClearRefinements,
} from "vue-instantsearch/vue3/es";

import { createProvider } from "../providers";
import { normalizeFilters } from "../providers/filtering";

function parseJson(value, fallback = []) {
  if (!value) {
    return fallback;
  }

  if (Array.isArray(value) || typeof value === "object") {
    return value;
  }

  try {
    return JSON.parse(value);
  } catch (error) {
    return fallback;
  }
}

function normalizeLocale(tag) {
  if (!tag) {
    return null;
  }

  const parts = String(tag).replace("_", "-").split("-");
  const language = parts[0] ? parts[0].toLowerCase() : null;
  const region = parts[1] ? parts[1].toUpperCase() : null;

  if (!language) {
    return null;
  }

  return region ? `${language}-${region}` : language;
}

export default {
  props: {
    config: {
      type: Object,
      required: true,
    },
  },

  components: {
    AisInstantSearch,
    AisHits,
    AisConfigure,
    AisRefinementList,
    AisMenu,
    AisHierarchicalMenu,
    AisPagination,
    AisCurrentRefinements,
    AisStats,
    AisSearchBox,
    AisSortBy,
    VueSlider,
    AisRangeInput,
    AisToggleRefinement,
    AisClearRefinements,
  },

  data() {
    const config = this.config || {};
    const provider = createProvider(config);
    const staticConfigureFilters = normalizeFilters(config.configureFilters || []);

    return {
      provider,
      searchClient: provider.searchClient,
      routing: {
        router: historyRouter(),
      },
      staticConfigureFilters,
      searchableFacetScopes: {},
      facetSortCache: {},
      rootConfigureState: provider.buildConfigureProps({
        filters: staticConfigureFilters,
        hitsPerPage: config.hitsPerPage,
      }),
      rootConfigureSignature: "",
    };
  },

  computed: {
    indexName() {
      return this.config.indexName || "";
    },
  },

  mounted() {
    // Root area bootstrap. PHP templates write filter metadata into data attributes,
    // and the root component reads them once after mount.
    this.readBuilderState();
  },

  methods: {
    // instantsearch_facet: keep sort arrays stable so the widget is not recreated on render.
    getFacetSortBy(primary, fallback = null) {
      const values = [primary, fallback].filter(Boolean);
      const key = values.join("|");

      if (!this.facetSortCache[key]) {
        this.facetSortCache[key] = values;
      }

      return this.facetSortCache[key];
    },

    // Root area: static configure filters are collected before mount. Initialize
    // searchable facet scopes after their markup has been rendered.
    readBuilderState() {
      const searchableFacetNodes = Array.from(
        this.$el.querySelectorAll("[data-searchable-facet-id]"),
      );
      searchableFacetNodes.forEach((node) => {
        this.searchableFacet(
          node.getAttribute("data-searchable-facet-id"),
          parseJson(node.getAttribute("data-search-static-filters"), []),
        );
      });

      this.updateConfigureState();
    },

    // Root area: plugin-level filters that always apply to the search.
    baseFilters() {
      const filters = [...(this.config.defaultFilters || [])];
      const locale =
        normalizeLocale(document.documentElement.getAttribute("lang")) ||
        normalizeLocale(navigator.language) ||
        "en-US";

      if (this.config.localeField) {
        filters.push({
          field: this.config.localeField,
          value: locale,
        });
      }

      if (
        this.config.stateField &&
        this.config.stateValue !== undefined &&
        this.config.stateValue !== null &&
        this.config.stateValue !== ""
      ) {
        filters.push({
          field: this.config.stateField,
          value: this.config.stateValue,
        });
      }

      return normalizeFilters(filters);
    },

    // Root area: final filter list used by <ais-configure> and searchable facet requests.
    allFilters() {
      const searchableFacetFilters = Object.values(
        this.searchableFacetScopes,
      ).flatMap((scope) => [
        ...(scope.staticFilters || []),
        ...(scope.selectedFilters || []),
      ]);

      return normalizeFilters([
        ...this.baseFilters(),
        ...this.staticConfigureFilters,
        ...searchableFacetFilters,
      ]);
    },

    // Root area: keep <ais-configure> stable to avoid recursive searches.
    updateConfigureState() {
      const nextState = this.provider.buildConfigureProps({
        filters: this.allFilters(),
        hitsPerPage: this.config.hitsPerPage,
      });
      const nextSignature = JSON.stringify(nextState);

      if (nextSignature === this.rootConfigureSignature) {
        return;
      }

      this.rootConfigureState = nextState;
      this.rootConfigureSignature = nextSignature;
    },

    // instantsearch_searchable_facet: local state for one widget instance.
    searchableFacet(id, staticFilters = []) {
      if (!id) {
        return {
          staticFilters: [],
          selectedFilters: [],
          results: [],
          open: false,
        };
      }

      const normalizedStaticFilters = normalizeFilters(staticFilters);

      if (!this.searchableFacetScopes[id]) {
        this.searchableFacetScopes[id] = {
          staticFilters: normalizedStaticFilters,
          selectedFilters: [],
          results: [],
          open: false,
        };
      } else if (normalizedStaticFilters.length) {
        this.searchableFacetScopes[id].staticFilters = normalizedStaticFilters;
      }

      return this.searchableFacetScopes[id];
    },

    // instantsearch_searchable_facet: open or close the dropdown.
    setFacetDropdown(id, isOpen) {
      this.searchableFacet(id).open = Boolean(isOpen);
    },

    // instantsearch_searchable_facet: clear the free-text input after selection.
    clearFacetInput(id) {
      const input = this.$el.querySelector(
        `[data-searchable-facet-input="${id}"]`,
      );

      if (input) {
        input.value = "";
      }
    },

    // instantsearch_searchable_facet: read the main search box query so facet suggestions
    // stay aligned with the current result set.
    getCurrentQuery() {
      const input = this.$el.querySelector(".ais-SearchBox-input");
      return input ? input.value || "" : "";
    },

    // instantsearch_searchable_facet: backspace removes the last selected filter.
    popFacet(id, event) {
      if (event.key !== "Backspace" || event.target.value.length > 0) {
        return;
      }

      const scope = this.searchableFacet(id);

      if (scope.selectedFilters.length) {
        scope.selectedFilters.pop();
        this.updateConfigureState();
      }
    },

    // instantsearch_searchable_facet: fetch matching facet values from the active provider.
    async searchForFacets(id, facets, value) {
      const scope = this.searchableFacet(id);
      const searchTerm = (value || "").trim();

      if (!searchTerm || !Array.isArray(facets) || !facets.length) {
        scope.results = [];
        this.setFacetDropdown(id, false);
        return;
      }

      try {
        const results = await this.provider.searchForFacets({
          searchClient: this.searchClient,
          indexName: this.indexName,
          facets,
          query: this.getCurrentQuery(),
          searchTerm,
          filters: this.allFilters(),
        });

        scope.results = results;
        scope.open = results.length > 0;
      } catch (error) {
        console.error("[SearchRoot] Failed to search facet values", error);
        scope.results = [];
        this.setFacetDropdown(id, false);
      }
    },

    // instantsearch_searchable_facet: add or remove one selected facet filter.
    toggleFilter(id, field, value) {
      const scope = this.searchableFacet(id);
      const existingIndex = scope.selectedFilters.findIndex(
        (filter) => filter.field === field && filter.value === value,
      );

      if (existingIndex !== -1) {
        scope.selectedFilters.splice(existingIndex, 1);
      } else {
        scope.selectedFilters.push({ field, value });
      }

      this.updateConfigureState();
    },

    // instantsearch_active_filters and instantsearch_searchable_facet:
    // map raw attribute names to editor-facing labels.
    renameAttributes(attribute, data) {
      if (!data[attribute]) {
        return "";
      }

      return `${data[attribute]}: `;
    },

    // instantsearch_pagination: scroll back to the configured target after page changes.
    handlePageChange(_page, scrollTarget) {
      const UIkit = window.UIkit;
      if (!UIkit || !scrollTarget) {
        return;
      }

      const target = document.querySelector(scrollTarget);

      if (!target) {
        return;
      }

      UIkit.scroll(target).scrollTo(target);
    },

    // instantsearch_range_slider: normalize the current refinement for the slider component.
    toValue(value, range) {
      return [
        typeof value.min === "number" ? value.min : range.min,
        typeof value.max === "number" ? value.max : range.max,
      ];
    },

    // instantsearch_range: show empty input when refinement matches the range minimum.
    formatMinValue(minValue, minRange) {
      return minValue !== null && minValue !== minRange ? minValue : "";
    },

    // instantsearch_range: show empty input when refinement matches the range maximum.
    formatMaxValue(maxValue, maxRange) {
      return maxValue !== null && maxValue !== maxRange ? maxValue : "";
    },

    // instantsearch_table: group hits by one field for the custom table layout.
    groupBy(items = [], field) {
      return (items || []).reduce((groups, item) => {
        const value = item && field in item ? item[field] : undefined;
        const key = value || "undefined";

        if (!groups[key]) {
          groups[key] = [];
        }

        groups[key].push(item);
        return groups;
      }, {});
    },
  },
};
