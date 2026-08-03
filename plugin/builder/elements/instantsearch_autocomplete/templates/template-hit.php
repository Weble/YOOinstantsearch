<?php

$autocompleteId = 'search-autocomplete-' . preg_replace('/[^a-zA-Z0-9_-]/', '', (string) $node->id);
$listboxId = $autocompleteId . '-listbox';
$resultLimit = (int) $props['result_limit'];
$dropdownWidth = (int) $props['dropdown_width'];
$dropdownMaxHeight = (int) $props['dropdown_max_height'];
$dropdownAlign = $props['dropdown_align'];
$inputSize = $props['input_size'] ? ' uk-form-' . $props['input_size'] : '';

$el = $this->el('div', [
    'class' => ['el-element uk-position-relative'],
    'data-search-autocomplete-id' => $autocompleteId,
    'v-cloak' => true,
]);

$dropdown = $this->el('div', [
    'id' => $listboxId,
    'class' => [
        'el-dropdown',
        'uk-position-absolute',
        'uk-background-default',
        'uk-box-shadow-large',
        'uk-overflow-auto',
    ],
    'style' => sprintf(
        'top: calc(100%% + 8px); %s: 0; width: min(%dpx, calc(100vw - 30px)); max-height: %dpx; z-index: 1020;',
        $dropdownAlign,
        $dropdownWidth,
        $dropdownMaxHeight,
    ),
    'role' => 'listbox',
    'aria-label' => $props['input_label'],
    'v-show' => sprintf(
        "isAutocompleteOpen('%s', currentRefinement, indices, %d)",
        $autocompleteId,
        $resultLimit,
    ),
]);

?>

<?= $el($props, $attrs) ?>
<ais-autocomplete :escape-html="<?= json_encode((bool) $props['escape_html']) ?>">
    <template v-slot="{ currentRefinement, indices, refine }">
        <input
            class="el-input ais-Autocomplete-input uk-input<?= $inputSize ?>"
            data-search-autocomplete-input
            type="search"
            autocomplete="off"
            autocorrect="off"
            autocapitalize="off"
            spellcheck="false"
            role="combobox"
            aria-autocomplete="list"
            aria-haspopup="listbox"
            aria-label="<?= htmlspecialchars($props['input_label'], ENT_QUOTES, 'UTF-8') ?>"
            aria-controls="<?= $listboxId ?>"
            :aria-expanded="isAutocompleteOpen('<?= $autocompleteId ?>', currentRefinement, indices, <?= $resultLimit ?>)"
            :aria-activedescendant="autocomplete('<?= $autocompleteId ?>').activeIndex >= 0 ? '<?= $autocompleteId ?>-option-' + autocomplete('<?= $autocompleteId ?>').activeIndex : undefined"
            placeholder="<?= htmlspecialchars($props['placeholder'], ENT_QUOTES, 'UTF-8') ?>"
            :value="currentRefinement"
            <?php if ($props['autofocus']) : ?>autofocus<?php endif ?>
            @focus="openAutocomplete('<?= $autocompleteId ?>', currentRefinement)"
            @input="refineAutocomplete('<?= $autocompleteId ?>', $event, refine)"
            @keydown="handleAutocompleteKeydown('<?= $autocompleteId ?>', $event, indices, <?= $resultLimit ?>)"
        >

        <?= $dropdown($props) ?>
            <ul class="uk-list uk-list-divider uk-margin-remove" role="presentation">
                <li
                    v-for="(item, itemIndex) in autocompleteItems(indices, <?= $resultLimit ?>)"
                    :key="item.objectID"
                    :id="'<?= $autocompleteId ?>-option-' + itemIndex"
                    :data-search-autocomplete-option="itemIndex"
                    class="uk-margin-remove"
                    :class="{ 'uk-background-muted': autocomplete('<?= $autocompleteId ?>').activeIndex === itemIndex }"
                    role="option"
                    :aria-selected="autocomplete('<?= $autocompleteId ?>').activeIndex === itemIndex"
                    @mouseenter="autocomplete('<?= $autocompleteId ?>').activeIndex = itemIndex"
                    @click="trackAutocompleteSelection('<?= $autocompleteId ?>', item, indices[0].sendEvent)"
                >
                    <a :href="item.url" class="el-item uk-flex uk-flex-middle uk-padding-small uk-link-reset">
                        <div class="uk-width-expand">
                            <div class="el-title">{{ item.title }}</div>
                            <div class="el-meta uk-text-meta" v-if="item.meta">{{ item.meta }}</div>
                            <div class="el-content uk-text-small" v-if="item.content">{{ item.content }}</div>
                        </div>
                    </a>
                </li>
            </ul>
        <?= $dropdown->end() ?>
    </template>
</ais-autocomplete>
<?= $el->end() ?>
