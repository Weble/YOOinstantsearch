import {createApp} from 'vue/dist/vue.esm-bundler.js';
import InstantSearch from './components/InstantSearch';
import {normalizeFilters} from './providers/filtering';

function parseJson(value, fallback = []) {
    try {
        return value ? JSON.parse(value) : fallback;
    } catch (error) {
        return fallback;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // PHP renders the widget markup; Vue reads that markup as the root template per search area.
    document.querySelectorAll('[data-search-root]').forEach((element) => {
        const config = JSON.parse(element.dataset.searchConfig || '{}');
        config.configureFilters = normalizeFilters(
            Array.from(element.querySelectorAll('[data-search-configure]')).flatMap((node) =>
                parseJson(node.getAttribute('data-search-configure')),
            ),
        );
        const RootComponent = {
            ...InstantSearch,
            template: element.innerHTML.trim(),
        };

        createApp(RootComponent, {config}).mount(element);
    });
});
