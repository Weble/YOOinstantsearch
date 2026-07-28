<?php
$templates = [
    'render' => __DIR__ . '/templates/template.php',
    'content' => __DIR__ . '/templates/content.php',
];

$titleFields = [
    'title' => [
        'label' => 'Facet Title',
        'type' => 'text',
    ],
    'title_element' => [
        'label' => 'HTML Element',
        'description' => 'Choose one of the HTML elements to fit the semantic structure.',
        'type' => 'select',
        'options' => [
            'h1' => 'h1',
            'h2' => 'h2',
            'h3' => 'h3',
            'h4' => 'h4',
            'h5' => 'h5',
            'h6' => 'h6',
            'div' => 'div',
        ],
    ],
    'title_style' => [
        'label' => 'Style',
        'description' => 'Headline styles differ in font size and font family.',
        'type' => 'select',
        'options' => [
            'None' => '',
            '2X-Large' => 'heading-2xlarge',
            'X-Large' => 'heading-xlarge',
            'Large' => 'heading-large',
            'Medium' => 'heading-medium',
            'Small' => 'heading-small',
            'H1' => 'h1',
            'H2' => 'h2',
            'H3' => 'h3',
            'H4' => 'h4',
            'H5' => 'h5',
            'H6' => 'h6',
        ],
    ],
    'title_decoration' => [
        'label' => 'Decoration',
        'description' => 'Decorate the headline with a divider, bullet or a line that is vertically centered to the heading.',
        'type' => 'select',
        'options' => [
            'None' => '',
            'Divider' => 'divider',
            'Bullet' => 'bullet',
            'Line' => 'line',
        ],
    ],
    'title_color' => [
        'label' => 'Color',
        'description' => 'Select the text color. If the Background option is selected, styles that do not apply a background image use the primary color instead.',
        'type' => 'select',
        'options' => [
            'None' => '',
            'Muted' => 'muted',
            'Emphasis' => 'emphasis',
            'Primary' => 'primary',
            'Secondary' => 'secondary',
            'Success' => 'success',
            'Warning' => 'warning',
            'Danger' => 'danger',
            'Background' => 'background',
        ],
    ],
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

$fields = array_merge($titleFields, [
    'facet' => [
        'label' => 'Facet',
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
]);

return [
    'name' => 'instantsearch_range',
    'title' => 'Range Filter',
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
                            'label' => 'Range',
                            'type' => 'group',
                            'divider' => true,
                            'fields' => ['facet'],
                        ],
                        [
                            'label' => 'General',
                            'type' => 'group',
                            'divider' => false,
                            'fields' => $generalFields,
                        ],
                    ],
                ],
                [
                    'title' => 'Title',
                    'fields' => ['title', 'title_style', 'title_decoration', 'title_color', 'title_element'],
                ],
                '${builder.advanced}',
            ],
        ],
    ],
    'transforms' => [
        'render' => function ($node) {
        },
    ],
];
