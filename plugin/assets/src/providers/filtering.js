function isEmptyValue(value) {
    return value === undefined || value === null || value === '';
}

function isNumeric(value) {
    return typeof value === 'number' || /^-?\d+(\.\d+)?$/.test(String(value));
}

// Shared by both providers. Keeps only the filter shapes this plugin actually emits:
// one field plus one value, or one field plus a small values array.
export function normalizeFilters(filters = []) {
    const seen = new Set();
    const entries = Array.isArray(filters) ? filters.flat() : [filters];
    const normalized = [];

    entries.forEach((filter) => {
        if (!filter || typeof filter !== 'object') {
            return;
        }

        const field = filter.field || filter.attribute || filter.title;
        const values = Array.isArray(filter.values) ? filter.values : [filter.value];

        if (!field) {
            return;
        }

        values.forEach((value) => {
            const normalizedValue = typeof value === 'string' ? value.trim() : value;

            if (isEmptyValue(normalizedValue)) {
                return;
            }

            const key = `${field}::${String(normalizedValue)}`;

            if (seen.has(key)) {
                return;
            }

            seen.add(key);
            normalized.push({
                field: String(field),
                value: normalizedValue,
            });
        });
    });

    return normalized;
}

function groupFilters(filters = []) {
    const grouped = new Map();

    normalizeFilters(filters).forEach(({field, value}) => {
        if (!grouped.has(field)) {
            grouped.set(field, []);
        }
        grouped.get(field).push(value);
    });

    return Array.from(grouped.entries()).map(([field, values]) => ({
        field,
        values,
    }));
}

function toTypesenseValue(value) {
    if (typeof value === 'boolean' || isNumeric(value)) {
        return String(value);
    }

    return `\`${String(value).replace(/`/g, '\\`')}\``;
}

export function buildTypesenseConfigureProps({filters = [], hitsPerPage} = {}) {
    const props = {};
    const grouped = groupFilters(filters);
    const finiteHits = Number(hitsPerPage);

    if (Number.isFinite(finiteHits) && finiteHits > 0) {
        props.hitsPerPage = finiteHits;
    }

    if (!grouped.length) {
        return props;
    }

    props.filters = grouped
        .map(({field, values}) => {
            const expression = values
                .map((value) => `${field}:=${toTypesenseValue(value)}`)
                .join(' || ');

            return values.length > 1 ? `(${expression})` : expression;
        })
        .join(' && ');

    return props;
}

export function buildAlgoliaConfigureProps({filters = [], hitsPerPage} = {}) {
    const props = {};
    const grouped = groupFilters(filters);
    const finiteHits = Number(hitsPerPage);

    if (Number.isFinite(finiteHits) && finiteHits > 0) {
        props.hitsPerPage = finiteHits;
    }

    if (!grouped.length) {
        return props;
    }

    props.facetFilters = grouped.map(({field, values}) => {
        const entries = values.map((value) => `${field}:${String(value)}`);
        return entries.length === 1 ? entries[0] : entries;
    });

    return props;
}
