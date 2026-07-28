import { createAlgoliaProvider } from "./algolia";
import { createTypesenseProvider } from "./typesense";

export function createProvider(config = {}) {
  if (config.provider === "algolia") {
    return createAlgoliaProvider(config);
  }

  return createTypesenseProvider(config);
}
