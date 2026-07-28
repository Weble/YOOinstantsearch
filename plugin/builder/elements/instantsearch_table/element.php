<?php

use Joomla\Filesystem\Folder;
use YOOtheme\Path;

$templateFiles = Folder::files(__DIR__ . '/templates');
$childThemeDir = Path::resolve('~theme/builder/instantsearch_table/templates');

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
    'name' => 'instantsearch_table',
    'title' => 'Table',
    'group' => 'Instant Search',
    'icon' => '${url:svg/icon.svg}',
    'iconSmall' => '${url:svg/iconSmall.svg}',
    'element' => true,
    'width' => 500,
    'container' => true,
    'templates' => $templateMap,
    'defaults' => [
        'margin_top' => 'default',
        'margin_bottom' => 'default',
        'link_text' => 'Dettagli',
    ],
    'fields' => [
        'content' => [
            'label' => 'Columns',
            'type' => 'content-items',
            'item' => 'instantsearch_table_item',
        ],
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
                ['title' => 'Content', 'fields' => ['content', 'template', 'link_text']],
                ['title' => 'Settings', 'fields' => [['label' => 'General', 'type' => 'group', 'divider' => false, 'fields' => $generalFields]]],
                '${builder.advanced}',
            ],
        ],
    ],
    'transforms' => [
        'render' => function ($node) {
        },
    ],
];
