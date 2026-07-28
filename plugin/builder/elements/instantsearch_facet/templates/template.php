<?php
$el = $this->el('div', [
    'data-search-widget' => 'facet',
    'data-search-facet-attribute' => $node->props['facet'] ?? '',
]);

// Button
$button = $this->el('a', [
    '@click'    => "toggleShowMore",
    ':disabled' => "!canToggleShowMore",
    'class'     => $this->expr([
        'el-content',
        'uk-width-1-1 {@fullwidth}',
        'uk-{button_style: link-\w+}'                                              => ['button_style' => $props['button_style']],
        'uk-button uk-button-{!button_style: |link-\w+} [uk-button-{button_size}]' => ['button_style' => $props['button_style']],
    ], $props),

    'title' => ['{link_title}'],

]);

// Title
$title = null;
if ($props['title'] ?? null) {
    $title = $this->el($props['title_element'], [

        'class' => [
            'el-title',
            'uk-{title_style}',
            'uk-heading-{title_decoration}',
            'uk-font-{title_font_family}',
            'uk-text-{title_color} {@!title_color: background}',
        ],
    ]);
}

$buttonAttrs = [
    'v-if="canToggleShowMore"'
];

$correctOrder = json_encode(array_map(fn($i) => $i->title, $node->props['correct_order'] ?? []));

?>

<?= $el($props, $attrs); ?>

<?php if ($props['facet_type'] === 'checkbox') : ?>

<ais-refinement-list
        operator="and"
        attribute="<?= $node->props['facet'] ?>"
        :limit="<?= $props['limit'] ?>"
        :sort-by="getFacetSortBy('<?= addslashes($props['sort_by']) ?>'<?= $props['sort_by_fallback'] ? ", '" . addslashes($props['sort_by_fallback']) . "'" : '' ?>)"

    <?php if ($props['show_more'] ?? true): ?>
        :show-more-limit="<?= $props['show_more_limit'] ?? 50; ?>"
        show-more
    <?php endif; ?>
>
    <template
        v-slot:default="{
          items,
          isShowingMore,
          isFromSearch,
          canToggleShowMore,
          refine,
          createURL,
          toggleShowMore,
          searchForItems,
          sendEvent,
        }"
    >

        <?php if ($title): ?>
            <?php $titleAttrs = ['v-if' => 'items.length > 0']; ?>
            <?= $title($props, $titleAttrs) ?>
                <?php if ($props['title_color'] == 'background') : ?>
                    <span class="uk-text-background"><?= $props['title'] ?></span>
                <?php elseif ($props['title_decoration'] == 'line') : ?>
                    <span><?= $props['title'] ?></span>
                <?php else : ?>
                    <?= $props['title'] ?>
                <?php endif ?>
            <?= $title->end() ?>
        <?php endif ?>
        <div class="uk-margin-xsmall-top">
            <ul class="uk-list uk-list-small facet-filters ">

            <?php if ($props['enable_all_button']) : ?>
                <li v-if="items.length">
                    <label class="uk-form-label uk-flex uk-flex-row uk-flex-middle">
                        <input
                                class="uk-radio uk-margin-small-right"
                                type="radio"
                                name="<?= $node->props['facet'] ?>"
                                :checked="!items.some((item) => item.isRefined)"
                                @change="items.filter((item) => item.isRefined).forEach((item) => refine(item.value))"
                        />
                        <span class="uk-flex-1"><?= $props['all_button_text'] ?></span>
                    </label>
               </li>
            <?php endif ?>

                <li v-for='item in items' :key="item.value">
                <label class="uk-form-label uk-flex uk-flex-row uk-flex-middle">
                    <input
                            class="uk-checkbox uk-margin-small-right"
                            type="checkbox"
                            :value="item.value"
                            :checked="item.isRefined"
                            @change="refine(item.value)"
                    />
                    <span class="uk-flex-1">{{ item.label }}</span>
                    <?php if ($props['item_count']) : ?>
                        <span class="uk-padding-small-left facet-count">{{ item.count }}</span>
                    <?php endif ?>
                </label>
            </li>
        </ul>

        <?php if ($props['show_more'] ?? true): ?>
            <?= $button($props, $buttonAttrs) ?>
            <?php if ($props['icon']) : ?>
                <?php if ($props['icon_align'] == 'left') : ?>
                    <span v-if="isShowingMore" uk-icon="<?= $props['icon'] ?>"></span>
                    <span v-else uk-icon="<?= $props['icon_less'] ?>"></span>
                <?php endif ?>

                <span class="uk-text-middle" v-if="isShowingMore">
                      <?php echo $props['content_less'] ?>
                </span>
                <span class="uk-text-middle" v-else>
                      <?php echo $props['content_more'] ?>
                </span>

                <?php if ($props['icon_align'] == 'right') : ?>
                    <span v-if="isShowingMore" uk-icon="<?= $props['icon'] ?>"></span>
                    <span v-else uk-icon="<?= $props['icon_less'] ?>"></span>
                <?php endif ?>

            <?php else : ?>
                <span class="uk-text-middle" v-if="isShowingMore">
                      <?php echo $props['content_less'] ?>
                </span>
                <span class="uk-text-middle" v-else>
                      <?php echo $props['content_more'] ?>
                </span>
            <?php endif ?>

            <?= $button->end(); ?>
        <?php endif; ?>
    </div>
    </template>
</ais-refinement-list>
<?php endif ?>

<?php if ($props['facet_type'] === 'radio') : ?>
<ais-menu
        attribute="<?= $node->props['facet'] ?>"
        :sort-by="getFacetSortBy('name:asc')"
    <?php if ($props['show_more'] ?? true): ?>
        :show-more-limit="<?= $props['show_more_limit'] ?? 50; ?>"
        show-more
    <?php endif; ?>
>
    <template
            v-slot:default="{
          items,
          isShowingMore,
          canToggleShowMore,
          refine,
          createURL,
          sendEvent,
        }"
    >

        <?php if ($title): ?>
            <?php $titleAttrs = ['v-if' => 'items.length > 0']; ?>
            <?= $title($props, $titleAttrs) ?>
            <?php if ($props['title_color'] == 'background') : ?>
                <span class="uk-text-background"><?= $props['title'] ?></span>
            <?php elseif ($props['title_decoration'] == 'line') : ?>
                <span><?= $props['title'] ?></span>
            <?php else : ?>
                <?= $props['title'] ?>
            <?php endif ?>
            <?= $title->end() ?>
        <?php endif ?>
        <ul class="uk-list uk-list-small facet-filters">

            <?php if ($props['enable_all_button']) : ?>
                <li v-if="items.length">
                    <label class="uk-form-label uk-flex uk-flex-row uk-flex-middle">
                        <input
                                class="uk-radio uk-margin-small-right"
                                type="radio"
                                name="<?= $node->props['facet'] ?>"
                                :checked="!items.some((item) => item.isRefined)"
                                @change="items.find((item) => item.isRefined) && refine(items.find((item) => item.isRefined).value)"
                        />
                        <span class="uk-flex-1"><?= $props['all_button_text'] ?></span>
                    </label>
                </li>
            <?php endif ?>

            <li v-for="item in items" :key="item.value">
                <label class="uk-form-label uk-flex uk-flex-row uk-flex-middle">
                    <input
                            class="uk-radio uk-margin-small-right"
                            type="radio"
                            name="<?= $node->props['facet'] ?>"
                            :value="item.value"
                            :checked="item.isRefined"
                            @change="refine(item.value)"
                    />
                    <span class="uk-flex-1">{{ item.label }}</span>
                    <?php if ($props['item_count']) : ?>
                        <span class="uk-padding-small-left facet-count">{{ item.count }}</span>
                    <?php endif ?>
                </label>
            </li>

        </ul>

        <?php if ($props['show_more'] ?? true): ?>
            <?= $button($props, $buttonAttrs) ?>
            <?php if ($props['icon']) : ?>
                <?php if ($props['icon_align'] == 'left') : ?>
                    <span v-if="isShowingMore" uk-icon="<?= $props['icon'] ?>"></span>
                    <span v-else uk-icon="<?= $props['icon_less'] ?>"></span>
                <?php endif ?>

                <span class="uk-text-middle" v-if="isShowingMore">
                      <?php echo $props['content_less'] ?>
                </span>
                <span class="uk-text-middle" v-else>
                      <?php echo $props['content_more'] ?>
                </span>

                <?php if ($props['icon_align'] == 'right') : ?>
                    <span v-if="isShowingMore" uk-icon="<?= $props['icon'] ?>"></span>
                    <span v-else uk-icon="<?= $props['icon_less'] ?>"></span>
                <?php endif ?>

            <?php else : ?>
                <span class="uk-text-middle" v-if="isShowingMore">
                      <?php echo $props['content_less'] ?>
                </span>
                <span class="uk-text-middle" v-else>
                      <?php echo $props['content_more'] ?>
                </span>
            <?php endif ?>

            <?= $button->end(); ?>
        <?php endif; ?>
    </template>
</ais-menu>
<?php endif ?>

<?= $el->end(); ?>
