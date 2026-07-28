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
    'blend',
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
    'pagination_type' => [
        'label' => 'Pagination',
        'description' => 'Choose between the previous/next or numeric pagination.',
        'type' => 'select',
        'options' => [
            'Previous/Next' => 'previous/next',
            'Numeric' => 'numeric',
        ],
    ],
    'pagination_space_between' => [
        'type' => 'checkbox',
        'text' => 'Show space between links',
        'enable' => "pagination_type == 'previous/next'",
    ],
    'scroll_to' => [
        'label' => 'Scroll To',
        'description' => 'Element selector to scroll to when paginating.',
    ],
    'position' => '${builder.position}',
    'position_left' => '${builder.position_left}',
    'position_right' => '${builder.position_right}',
    'position_top' => '${builder.position_top}',
    'position_bottom' => '${builder.position_bottom}',
    'position_z_index' => '${builder.position_z_index}',
    'blend' => '${builder.blend}',
    'margin_top' => '${builder.margin_top}',
    'margin_bottom' => '${builder.margin_bottom}',
    'maxwidth' => '${builder.maxwidth}',
    'maxwidth_breakpoint' => '${builder.maxwidth_breakpoint}',
    'block_align' => '${builder.block_align}',
    'block_align_breakpoint' => '${builder.block_align_breakpoint}',
    'block_align_fallback' => '${builder.block_align_fallback}',
    'text_align' => '${builder.text_align}',
    'text_align_breakpoint' => '${builder.text_align_breakpoint}',
    'text_align_fallback' => '${builder.text_align_fallback}',
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
    'name' => 'instantsearch_pagination',
    'title' => 'Pagination',
    'group' => 'Instant Search',
    'icon' => '${url:svg/icon.svg}',
    'iconSmall' => '${url:svg/iconSmall.svg}',
    'element' => true,
    'width' => 500,
    'templates' => $templates,
    'defaults' => [
        'pagination_type' => 'previous/next',
        'text_align' => 'center',
        'margin_top' => 'default',
        'margin_bottom' => 'default',
        'scroll_to' => '.ais-InstantSearch',
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
                            'label' => 'Pagination',
                            'type' => 'group',
                            'divider' => true,
                            'fields' => ['pagination_type', 'pagination_space_between', 'scroll_to'],
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
    'transforms' => [
        'render' => function ($node) {
        },
    ],
];
