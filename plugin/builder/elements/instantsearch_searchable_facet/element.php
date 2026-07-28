<?php

use YOOtheme\Builder\Source\SourceTransform;
use function YOOtheme\app;

$templates = [
    'render' => __DIR__ . '/templates/template.php',
    'content' => __DIR__ . '/templates/content.php',
];

$generalFields = ['position', 'position_left', 'position_right', 'position_top', 'position_bottom', 'position_z_index', 'margin_top', 'margin_bottom', 'maxwidth', 'maxwidth_breakpoint', 'block_align', 'block_align_breakpoint', 'block_align_fallback', 'text_align', 'text_align_breakpoint', 'text_align_fallback', 'animation', '_parallax_button', 'visibility'];

$fields = [
    'placeholder' => ['label' => 'Placeholder'],
    'facets' => ['label' => 'Facets', 'type' => 'yooessentials-settings-panel', 'panel' => 'instantsearch-configure-searchable-facets', 'emptyMsg' => 'No Facets Yet', 'button' => 'Add Facet'],
    'attributes_override' => ['label' => 'Facet Names Override', 'type' => 'yooessentials-settings-panel', 'panel' => 'instantsearch-configure-attributes-override', 'emptyMsg' => 'No Override Yet', 'button' => 'Add Override'],
    'filters' => ['label' => 'Set Configure Filters', 'type' => 'yooessentials-settings-panel', 'panel' => 'instantsearch-configure-filters', 'emptyMsg' => 'No Configuration Yet', 'button' => 'Add Configuration'],
    'position' => '${builder.position}',
    'position_left' => '${builder.position_left}',
    'position_right' => '${builder.position_right}',
    'position_top' => '${builder.position_top}',
    'position_bottom' => '${builder.position_bottom}',
    'position_z_index' => '${builder.position_z_index}',
    'margin_top' => '${builder.margin_top}',
    'margin_bottom' => '${builder.margin_bottom}',
    'maxwidth' => '${builder.maxwidth}',
    'maxwidth_breakpoint' => '${builder.maxwidth_breakpoint}',
    'block_align' => '${builder.block_align}',
    'block_align_breakpoint' => '${builder.block_align_breakpoint}',
    'block_align_fallback' => '${builder.block_align_fallback}',
    'text_align' => '${builder.text_align_justify}',
    'text_align_breakpoint' => '${builder.text_align_breakpoint}',
    'text_align_fallback' => '${builder.text_align_justify_fallback}',
    'animation' => '${builder.animation}',
    '_parallax_button' => '${builder._parallax_button}',
    'visibility' => '${builder.visibility}',
    'name' => '${builder.name}',
    'status' => '${builder.status}',
    'id' => '${builder.id}',
    'class' => '${builder.cls}',
    'attributes' => '${builder.attrs}',
    'css' => ['label' => 'CSS', 'description' => 'Enter your own custom CSS. The following selectors will be prefixed automatically for this element: <code>.el-element</code>', 'type' => 'editor', 'editor' => 'code', 'mode' => 'css', 'attrs' => ['debounce' => 500, 'hints' => ['.el-element']], 'source' => true],
];

return [
    'name' => 'instantsearch_searchable_facet',
    'title' => 'Searchable Facet Filter',
    'group' => 'Instant Search',
    'icon' => '${url:svg/icon.svg}',
    'iconSmall' => '${url:svg/iconSmall.svg}',
    'element' => true,
    'width' => 500,
    'templates' => $templates,
    'defaults' => [
        'margin_top' => 'default',
        'margin_bottom' => 'default',
        'title_element' => 'h6',
        'limit' => 10,
    ],
    'fields' => $fields,
    'fieldset' => [
        'default' => [
            'type' => 'tabs',
            'fields' => [
                [
                    'title' => 'Settings',
                    'fields' => [
                        ['label' => 'Facet', 'type' => 'group', 'divider' => true, 'fields' => ['placeholder', 'facets', 'filters', 'attributes_override']],
                        ['label' => 'General', 'type' => 'group', 'divider' => false, 'fields' => $generalFields],
                    ],
                ],
                '${builder.advanced}',
            ],
        ],
    ],
    'panels' => [
        'instantsearch-configure-filters' => [
            'title' => 'Filters',
            'fields' => [
                'title' => [
                    'label' => 'Field',
                ],
                'value' => [
                    'label' => 'Value',
                    'source' => true,
                ],
                'source' => [
                    'type' => 'fields',
                    'fields' => [
                        '_source' => [
                            'label' => 'Dynamic Condition',
                            'type' => 'source-select',
                            'description' => 'Select a source to make its fields available for mapping in the condition configuration',
                        ],
                        '_sourceArgs' => [
                            'type' => 'source-query-arg',
                        ],
                        '_sourceFieldArgs' => [
                            'type' => 'source-field-arg',
                        ],
                        '_sourceFieldDirectives' => [
                            'type' => 'source-field-directive',
                        ],
                    ],
                ],
            ],
        ],
        'instantsearch-configure-searchable-facets' => [
            'title' => 'Facets',
            'fields' => [
                'title' => [
                    'label' => 'Field',
                ],
            ],
        ],
        'instantsearch-configure-attributes-override' => [
            'title' => 'Facet Names Override',
            'fields' => [
                'title' => [
                    'label' => 'Field',
                ],
                'name' => [
                    'label' => 'Name',
                ],
            ],
        ],
    ],
    'transforms' => [
        'render' => function ($node, $root) {
            $facets = [];
            foreach ($node->props['facets'] ?? [] as $facet) {
                $facet = (array) $facet;
                $field = $facet['title'] ?? null;

                if ($field) {
                    $facets[] = $field;
                }
            }

            $staticFilters = [];
            foreach ($node->props['filters'] ?? [] as $filter) {
                if ($filter) {
                    $filter = (array) $filter;
                    $field = $filter['title'] ?? null;

                    app(SourceTransform::class)->__invoke($filter, $root);

                    if ($field && isset($filter['value']) && $filter['value'] !== '') {
                        $staticFilters[] = [
                            'field' => $field,
                            'value' => $filter['value'],
                        ];
                    }
                }
            }

            $attributes = [];
            foreach ($node->props['attributes_override'] ?? [] as $facet) {
                $facet = (array) $facet;
                $field = $facet['title'] ?? null;
                $name = $facet['name'] ?? null;

                if (!$field || !$name) {
                    continue;
                }

                $attributes[$field] = $name;
            }

            $node->facet_id = sprintf('searchable-facet-%s', $node->id);
            $node->searchable_facets = json_encode(array_values($facets));
            $node->static_filters = json_encode($staticFilters);
            $node->attribute_labels = json_encode($attributes);
        },
    ],
];
