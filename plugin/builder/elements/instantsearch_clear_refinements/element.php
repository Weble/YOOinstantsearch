<?php
$templates = [
    'render' => __DIR__ . '/templates/template.php',
    'content' => __DIR__ . '/templates/content.php',
];

$generalFields = [
    'position',
    'position_left',
    'position_right',
    'position_top',
    'position_bottom',
    'position_z_index',
    'margin_top',
    'margin_bottom',
    'maxwidth',
    'maxwidth_breakpoint',
    'block_align',
    'block_align_breakpoint',
    'block_align_fallback',
    'text_align',
    'text_align_breakpoint',
    'text_align_fallback',
    'animation',
    '_parallax_button',
    'visibility',
];

$fields = [
    'excluded-attributes' => [
        'label' => 'Excluded Facets',
        'type' => 'yooessentials-settings-panel',
        'panel' => 'instantsearch-active-filters-excluded-facets',
        'emptyMsg' => 'No Exclusions Yet',
        'button' => 'Add Exclusion',
    ],
    'button_text' => [
        'label' => 'Button Text',
    ],
    'button_style' => [
        'label' => 'Style',
        'description' => 'Set the button style.',
        'type' => 'select',
        'options' => [
            'Default' => 'default',
            'Primary' => 'primary',
            'Secondary' => 'secondary',
            'Danger' => 'danger',
            'Text' => 'text',
            'Link' => '',
            'Link Muted' => 'link-muted',
            'Link Text' => 'link-text',
        ],
    ],
    'fullwidth' => [
        'type' => 'checkbox',
        'text' => 'Full width button',
    ],
    'button_size' => [
        'label' => 'Size',
        'type' => 'select',
        'options' => [
            'Small' => 'small',
            'Default' => '',
            'Large' => 'large',
        ],
    ],
    'icon' => [
        'label' => 'Icon',
        'description' => 'Pick an optional icon.',
        'type' => 'icon',
        'source' => true,
    ],
    'icon_align' => [
        'label' => 'Alignment',
        'description' => 'Choose the icon position.',
        'type' => 'select',
        'options' => [
            'Left' => 'left',
            'Right' => 'right',
        ],
    ],
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
    'css' => [
        'label' => 'CSS',
        'description' => 'Enter your own custom CSS. The following selectors will be prefixed automatically for this element: <code>.el-element</code>',
        'type' => 'editor',
        'editor' => 'code',
        'mode' => 'css',
        'attrs' => [
            'debounce' => 500,
            'hints' => ['.el-element'],
        ],
        'source' => true,
    ],
];

return [
    'name' => 'instantsearch_clear_refinements',
    'title' => 'Clear Refinements',
    'group' => 'Instant Search',
    'icon' => '${url:svg/icon.svg}',
    'iconSmall' => '${url:svg/iconSmall.svg}',
    'element' => true,
    'width' => 500,
    'templates' => $templates,
    'defaults' => [
        'margin_top' => 'default',
        'margin_bottom' => 'default',
        'button_style' => 'default',
    ],
    'fields' => $fields,
    'fieldset' => [
        'default' => [
            'type' => 'tabs',
            'fields' => [
                [
                    'title' => 'Settings',
                    'fields' => [
                        [
                            'label' => 'Button',
                            'type' => 'group',
                            'divider' => true,
                            'fields' => [
                                'excluded-attributes',
                                'button_text',
                                'button_style',
                                'button_size',
                                'fullwidth',
                                'icon',
                                'icon_align',
                            ],
                        ],
                        [
                            'label' => 'General',
                            'type' => 'group',
                            'divider' => false,
                            'fields' => $generalFields,
                        ],
                    ],
                ],
                '${builder.advanced}',
            ],
        ],
    ],
    'panels' => [
        'instantsearch-active-filters-excluded-facets' => [
            'title' => 'Excluded Facets',
            'fields' => [
                'title' => ['label' => 'Field'],
            ],
        ],
    ],
    'transforms' => [
        'render' => function ($node) {
            $excluded_facets = [];

            foreach ($node->props['excluded-attributes'] ?? [] as $facet) {
                $facet = (array) $facet;
                $facet['field'] = $facet['title'] ?? null;

                if (!isset($facet['field']) || !$facet['field']) {
                    continue;
                }

                $excluded_facets[] = $facet['field'];
            }

            $node->excluded_facets = json_encode($excluded_facets);
        },
    ],
];
