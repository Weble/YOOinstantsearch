import { liteClient } from "algoliasearch/lite";
import { buildAlgoliaConfigureProps } from "./filtering";

export function createAlgoliaProvider(config = {}) {
  const algolia = config.algolia || {};
  const searchClient = liteClient(algolia.appId || "", algolia.apiKey || "");

  return {
    searchClient,

    buildConfigureProps(options = {}) {
      return buildAlgoliaConfigureProps(options);
    },

    async searchForFacets({
      searchClient,
      indexName,
      facets = [],
      query = "",
      searchTerm = "",
      filters = [],
      maxFacetHits = 10,
    }) {
      const configureProps = buildAlgoliaConfigureProps({ filters });
      const response = await searchClient.search(
        facets.map((facet) => ({
          type: "facet",
          facet,
          indexName,
          params: {
            ...configureProps,
            query,
            facetQuery: searchTerm,
            maxFacetHits,
          },
        })),
      );

      const results = response && response.results ? response.results : [];

      return results.reduce((carry, result, index) => {
        const facetHits = result && result.facetHits ? result.facetHits : [];

        facetHits.forEach((hit) => {
          carry.push({
            ...hit,
            facet: facets[index],
          });
        });

        return carry;
      }, []);
    },
  };
}
