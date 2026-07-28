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
    'text' => [
        'label' => 'Text',
        'type' => 'editor',
        'description' => 'Insert <code>{value}</code> to use dynamic search values.<br><code>{page}</code> - Current page.<br><code>{nbPages}</code> - Number of pages.<br><code>{hitsPerPage}</code> - Number of results per page.<br><code>{nbHits}</code> - Number of results.<br><code>{processingTimeMS}</code> - Processing time in ms.<br><code>{query}</code> - The query.',
    ],
    'showing' => [
        'text' => 'Remove if no results or no query',
        'type' => 'checkbox',
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
    'name' => 'instantsearch_stats',
    'title' => 'Stats',
    'group' => 'Instant Search',
    'icon' => '${url:svg/icon.svg}',
    'iconSmall' => '${url:svg/iconSmall.svg}',
    'element' => true,
    'width' => 500,
    'templates' => $templates,
    'defaults' => [
        'margin_top' => 'default',
        'margin_bottom' => 'default',
        'text' => 'Page {page} of {nbPages} with {hitsPerPage} hits per page  -<br> {nbHits} hits retrieved in {processingTimeMS}ms for <q>{query}</q>',
    ],
    'fields' => $fields,
    'fieldset' => [
        'default' => [
            'type' => 'tabs',
            'fields' => [
                [
                    'title' => 'Content',
                    'fields' => ['text', 'showing'],
                ],
                [
                    'title' => 'Settings',
                    'fields' => [
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
