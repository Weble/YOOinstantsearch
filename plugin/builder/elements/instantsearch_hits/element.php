<?php

use Joomla\Filesystem\Folder;
use YOOtheme\Path;

$templateFiles = Folder::files(__DIR__ . '/templates');
$childThemeDir = Path::resolve('~theme/builder/instantsearch_hits/templates');

if (is_dir($childThemeDir)) {
    $templateFiles = array_merge($templateFiles, Folder::files($childThemeDir));
}

$templateFiles = array_filter($templateFiles, fn($template) => stripos($template, 'template-') === 0 && pathinfo($template, PATHINFO_EXTENSION) === 'php');
$templates = [];

foreach ($templateFiles as $template) {
    $template = str_replace('.php', '', $template);
    $templates[$template] = $template;
}

$generalFields = ['position', 'position_left', 'position_right', 'position_top', 'position_bottom', 'position_z_index', 'margin_top', 'margin_bottom', 'maxwidth', 'maxwidth_breakpoint', 'block_align', 'block_align_breakpoint', 'block_align_fallback', 'text_align', 'text_align_breakpoint', 'text_align_fallback', 'animation', '_parallax_button', 'visibility'];
$cssHints = ['.el-element', '.el-item', '.el-title', '.el-meta', '.el-content', '.el-image', '.el-link'];
$templateMap = ['render' => __DIR__ . '/templates/template.php', 'content' => __DIR__ . '/templates/content.php'];

return [
    'name' => 'instantsearch_hits',
    'title' => 'Hits',
    'group' => 'Instant Search',
    'icon' => '${url:svg/icon.svg}',
    'iconSmall' => '${url:svg/iconSmall.svg}',
    'element' => true,
    'width' => 500,
    'templates' => $templateMap,
    'defaults' => [
        'margin_top' => 'default',
        'margin_bottom' => 'default',
        'grid_default' => '1',
        'grid_medium' => '3',
        'link_text' => 'Dettagli',
    ],
    'fields' => [
        'template' => [
            'type' => 'select',
            'label' => 'Template',
            'options' => $templates,
        ],
        'link_text' => [
            'label' => 'Link Text',
            'description' => 'Set a different link text for this item.',
            'source' => true,
        ],
        'grid_masonry' => [
            'label' => 'Masonry',
            'description' => 'Create a gap-free layout if grid items have different heights.',
            'type' => 'checkbox',
            'text' => 'Enable masonry layout',
        ],
        'grid_parallax' => [
            'label' => 'Parallax',
            'description' => 'Define the vertical parallax offset in pixels.',
            'type' => 'range',
            'attrs' => [
                'min' => 0,
                'max' => 600,
                'step' => 10,
            ],
        ],
        'grid_column_gap' => [
            'label' => 'Column Gap',
            'description' => 'Set the size of the gap between the grid columns.',
            'type' => 'select',
            'options' => [
                'Small' => 'small',
                'Medium' => 'medium',
                'Default' => '',
                'Large' => 'large',
                'None' => 'collapse',
            ],
        ],
        'grid_row_gap' => [
            'label' => 'Row Gap',
            'description' => 'Set the size of the gap between the grid rows.',
            'type' => 'select',
            'options' => [
                'Small' => 'small',
                'Medium' => 'medium',
                'Default' => '',
                'Large' => 'large',
                'None' => 'collapse',
            ],
        ],
        'grid_divider' => [
            'label' => 'Divider',
            'description' => 'Show a divider between grid columns.',
            'type' => 'checkbox',
            'text' => 'Show dividers',
            'enable' => "grid_column_gap != 'collapse' && grid_row_gap != 'collapse'",
        ],
        'grid_column_align' => [
            'label' => 'Alignment',
            'type' => 'checkbox',
            'text' => 'Center columns',
        ],
        'grid_row_align' => [
            'description' => 'Center rows vertically.',
            'type' => 'checkbox',
            'text' => 'Center rows',
        ],
        'grid_default' => [
            'label' => 'Phone Portrait',
            'description' => 'Set the number of grid columns for each breakpoint.',
            'type' => 'select',
            'options' => [
                '1 Column' => '1',
                '2 Columns' => '2',
                '3 Columns' => '3',
                '4 Columns' => '4',
                '5 Columns' => '5',
                '6 Columns' => '6',
                'Auto' => 'auto',
            ],
        ],
        'grid_small' => [
            'label' => 'Phone Landscape',
            'description' => 'Set the number of grid columns for each breakpoint.',
            'type' => 'select',
            'options' => [
                'Inherit' => '',
                '1 Column' => '1',
                '2 Columns' => '2',
                '3 Columns' => '3',
                '4 Columns' => '4',
                '5 Columns' => '5',
                '6 Columns' => '6',
                'Auto' => 'auto',
            ],
        ],
        'grid_medium' => [
            'label' => 'Tablet Landscape',
            'description' => 'Set the number of grid columns for each breakpoint.',
            'type' => 'select',
            'options' => [
                'Inherit' => '',
                '1 Column' => '1',
                '2 Columns' => '2',
                '3 Columns' => '3',
                '4 Columns' => '4',
                '5 Columns' => '5',
                '6 Columns' => '6',
                'Auto' => 'auto',
            ],
        ],
        'grid_large' => [
            'label' => 'Desktop',
            'description' => 'Set the number of grid columns for each breakpoint.',
            'type' => 'select',
            'options' => [
                'Inherit' => '',
                '1 Column' => '1',
                '2 Columns' => '2',
                '3 Columns' => '3',
                '4 Columns' => '4',
                '5 Columns' => '5',
                '6 Columns' => '6',
                'Auto' => 'auto',
            ],
        ],
        'grid_xlarge' => [
            'label' => 'Large Screens',
            'description' => 'Set the number of grid columns for each breakpoint.',
            'type' => 'select',
            'options' => [
                'Inherit' => '',
                '1 Column' => '1',
                '2 Columns' => '2',
                '3 Columns' => '3',
                '4 Columns' => '4',
                '5 Columns' => '5',
                '6 Columns' => '6',
                'Auto' => 'auto',
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
        'css' => ['label' => 'CSS', 'description' => 'Enter your own custom CSS. The following selectors will be prefixed automatically for this element: ' . implode(', ', array_map(fn($hint) => "<code>{$hint}</code>", $cssHints)), 'type' => 'editor', 'editor' => 'code', 'mode' => 'css', 'attrs' => ['debounce' => 500, 'hints' => $cssHints], 'source' => true],
    ],
    'fieldset' => [
        'default' => [
            'type' => 'tabs',
            'fields' => [
                ['title' => 'Content', 'fields' => ['template', 'link_text']],
                [
                    'title' => 'Settings',
                    'fields' => [
                        ['label' => 'Grid', 'type' => 'group', 'divider' => true, 'fields' => ['grid_masonry', 'grid_parallax', 'grid_column_gap', 'grid_row_gap', 'grid_divider', 'grid_column_align', 'grid_row_align']],
                        ['label' => 'Columns', 'type' => 'group', 'divider' => true, 'fields' => ['grid_default', 'grid_small', 'grid_medium', 'grid_large', 'grid_xlarge']],
                        ['label' => 'General', 'type' => 'group', 'divider' => false, 'fields' => $generalFields],
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
