import TypesenseInstantSearchAdapter from "typesense-instantsearch-adapter";
import { buildTypesenseConfigureProps } from "./filtering";

export function createTypesenseProvider(config = {}) {
  const typesense = config.typesense || {};
  const adapter = new TypesenseInstantSearchAdapter({
    server: {
      apiKey: typesense.apiKey || "",
      nodes: [
        {
          host: typesense.host || "",
          port: typesense.port || "",
          protocol: typesense.protocol || "http",
          path: typesense.path || "",
        },
      ],
    },
    additionalSearchParameters: {
      query_by: typesense.queryBy || "title",
    },
  });

  return {
    searchClient: adapter.searchClient,

    buildConfigureProps(options = {}) {
      return buildTypesenseConfigureProps(options);
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
      const configureProps = buildTypesenseConfigureProps({ filters });
      const requests = facets.map((facet) => ({
        indexName,
        params: {
          ...configureProps,
          query,
          facetName: facet,
          facetQuery: searchTerm,
          maxFacetHits,
        },
      }));

      const responses = await Promise.all(
        requests.map((request) => searchClient.searchForFacetValues([request])),
      );

      return responses.reduce((carry, response, index) => {
        const result = Array.isArray(response) ? response[0] : response;
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
