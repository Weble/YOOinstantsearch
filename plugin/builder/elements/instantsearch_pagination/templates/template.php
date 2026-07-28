<?php

use Joomla\CMS\Language\Text;

$nav = $this->el('nav', [
    'aria-label' => $props['pagination_type'] == 'numeric' ? Text::_('TPL_YOOTHEME_PAGINATION') : false,
]);

$list = $this->el('ul', [
    'class' => [
        'uk-pagination uk-margin-remove-bottom',
        'uk-flex-{text_align}[@{text_align_breakpoint} [uk-flex-{text_align_fallback}]]',
    ],
]);

?>

<?= $nav($props, $attrs) ?>
<ais-pagination
    @page-change="handlePageChange($event, <?= isset($props['scroll_to']) && $props['scroll_to'] !== '' ? "'" . addslashes($props['scroll_to']) . "'" : 'null' ?>)"
    <?php if (($props['pagination_type'] ?? 'previous/next') === 'numeric') : ?>
    show-first
    show-last
    <?php endif; ?>
>
    <template v-slot:default="{ refine, createURL, currentRefinement, nbPages, pages, isFirstPage, isLastPage }">
        <?= $list($props) ?>

        <?php if (($props['pagination_type'] ?? 'previous/next') === 'numeric') : ?>

            <li :class="{ 'uk-active': currentRefinement === 0, 'uk-disabled': isFirstPage }">
                <template v-if="isFirstPage">
                    <span aria-label="First Page">‹‹</span>
                </template>
                <template v-else>
                    <a href="#" aria-label="First Page" @click.exact.left.prevent="refine(0)">‹‹</a>
                </template>
            </li>

            <li :class="{ 'uk-disabled': isFirstPage }">
                <template v-if="isFirstPage">
                    <span aria-label="Previous Page">‹</span>
                </template>
                <template v-else>
                    <a href="#" aria-label="Previous Page" @click.exact.left.prevent="refine(currentRefinement - 1)">‹</a>
                </template>
            </li>

            <li v-for="page in pages" :key="page" :class="{ 'uk-active': currentRefinement === page }">
                <span v-if="currentRefinement === page" aria-current="page">{{ page + 1 }}</span>
                <a v-else :href="createURL(page)" :aria-label="`Page ${page + 1}`" @click.exact.left.prevent="refine(page)">{{ page + 1 }}</a>
            </li>

            <li :class="{ 'uk-disabled': isLastPage }">
                <template v-if="isLastPage">
                    <span aria-label="Next Page">›</span>
                </template>
                <template v-else>
                    <a :href="createURL(currentRefinement + 1)" aria-label="Next Page" @click.exact.left.prevent="refine(currentRefinement + 1)">›</a>
                </template>
            </li>

            <li :class="{ 'uk-disabled': isLastPage }">
                <template v-if="isLastPage">
                    <span :aria-label="`Last Page, Page ${nbPages}`">››</span>
                </template>
                <template v-else>
                    <a :href="createURL(nbPages - 1)" :aria-label="`Last Page, Page ${nbPages}`" @click.exact.left.prevent="refine(nbPages - 1)">››</a>
                </template>
            </li>

        <?php else : ?>

            <li :class="{ 'uk-margin-auto-right': <?= ($props['pagination_space_between'] ?? false) ? 'true' : 'false' ?>, 'uk-disabled': isFirstPage }">
                <template v-if="isFirstPage">
                    <span><span uk-pagination-previous></span> <?= Text::_('JPREV') ?></span>
                </template>
                <template v-else>
                    <a :href="createURL(currentRefinement - 1)" @click.exact.left.prevent="refine(currentRefinement - 1)"><span uk-pagination-previous></span> <?= Text::_('JPREV') ?></a>
                </template>
            </li>

            <li :class="{ 'uk-margin-auto-left': <?= ($props['pagination_space_between'] ?? false) ? 'true' : 'false' ?>, 'uk-disabled': isLastPage }">
                <template v-if="isLastPage">
                    <span><?= Text::_('JNEXT') ?> <span uk-pagination-next></span></span>
                </template>
                <template v-else>
                    <a :href="createURL(currentRefinement + 1)" @click.exact.left.prevent="refine(currentRefinement + 1)"><?= Text::_('JNEXT') ?> <span uk-pagination-next></span></a>
                </template>
            </li>

        <?php endif; ?>

        <?= $list->end() ?>
    </template>
</ais-pagination>
<?= $nav->end() ?>
