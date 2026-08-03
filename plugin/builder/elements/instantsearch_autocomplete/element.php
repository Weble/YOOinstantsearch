<?php

use Joomla\Filesystem\Folder;
use YOOtheme\Path;

$templateFiles = Folder::files(__DIR__ . '/templates');
$childThemeDir = Path::resolve('~theme/builder/instantsearch_autocomplete/templates');

if (is_dir($childThemeDir)) {
    $templateFiles = array_merge($templateFiles, Folder::files($childThemeDir));
}

$templateFiles = array_filter(
    $templateFiles,
    fn($template) => str_starts_with($template, 'template-') && pathinfo($template, PATHINFO_EXTENSION) === 'php',
);
$templateOptions = [];

foreach ($templateFiles as $template) {
    $template = pathinfo($template, PATHINFO_FILENAME);
    $templateOptions[$template] = $template;
}

$templateMap = [
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

$cssHints = [
    '.el-element',
    '.el-input',
    '.el-dropdown',
    '.el-item',
    '.el-title',
    '.el-meta',
    '.el-content',
    '.el-link',
];

return [
    'name' => 'instantsearch_autocomplete',
    'title' => 'Search Autocomplete',
    'group' => 'Instant Search',
    'icon' => '${url:svg/icon.svg}',
    'iconSmall' => '${url:svg/iconSmall.svg}',
    'element' => true,
    'width' => 500,
    'templates' => $templateMap,
    'defaults' => [
        'template' => 'template-hit',
        'placeholder' => 'Search...',
        'input_label' => 'Search',
        'result_limit' => 5,
        'dropdown_width' => 720,
        'dropdown_max_height' => 640,
        'dropdown_align' => 'right',
        'input_size' => 'small',
        'autofocus' => false,
        'escape_html' => false,
    ],
    'fields' => [
        'template' => [
            'type' => 'select',
            'label' => 'Template',
            'description' => 'Add template-*.php files in the child theme under builder/instantsearch_autocomplete/templates.',
            'options' => $templateOptions,
        ],
        'placeholder' => [
            'label' => 'Placeholder',
        ],
        'input_label' => [
            'label' => 'Accessible Label',
            'description' => 'Describe the search field for screen readers.',
        ],
        'result_limit' => [
            'label' => 'Maximum Results',
            'type' => 'range',
            'attrs' => [
                'min' => 1,
                'max' => 10,
                'step' => 1,
            ],
        ],
        'autofocus' => [
            'type' => 'checkbox',
            'text' => 'Autofocus the search input',
        ],
        'escape_html' => [
            'type' => 'checkbox',
            'text' => 'Escape HTML in hit values',
        ],
        'input_size' => [
            'label' => 'Input Size',
            'type' => 'select',
            'options' => [
                'Small' => 'small',
                'Default' => '',
                'Large' => 'large',
            ],
        ],
        'dropdown_width' => [
            'label' => 'Dropdown Width',
            'description' => 'Maximum dropdown width in pixels.',
            'type' => 'number',
            'attrs' => [
                'min' => 280,
                'max' => 1200,
                'step' => 10,
            ],
        ],
        'dropdown_max_height' => [
            'label' => 'Maximum Height',
            'description' => 'Maximum dropdown height in pixels before scrolling.',
            'type' => 'number',
            'attrs' => [
                'min' => 120,
                'max' => 1200,
                'step' => 10,
            ],
        ],
        'dropdown_align' => [
            'label' => 'Dropdown Alignment',
            'type' => 'select',
            'options' => [
                'Right' => 'right',
                'Left' => 'left',
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
            'description' => 'Enter your own custom CSS. The following selectors will be prefixed automatically for this element: ' . implode(', ', array_map(fn($hint) => "<code>{$hint}</code>", $cssHints)),
            'type' => 'editor',
            'editor' => 'code',
            'mode' => 'css',
            'attrs' => [
                'debounce' => 500,
                'hints' => $cssHints,
            ],
            'source' => true,
        ],
    ],
    'fieldset' => [
        'default' => [
            'type' => 'tabs',
            'fields' => [
                [
                    'title' => 'Content',
                    'fields' => [
                        'template',
                        'placeholder',
                        'input_label',
                        'result_limit',
                        'escape_html',
                    ],
                ],
                [
                    'title' => 'Settings',
                    'fields' => [
                        [
                            'label' => 'Dropdown',
                            'type' => 'group',
                            'divider' => true,
                            'fields' => ['dropdown_width', 'dropdown_max_height', 'dropdown_align'],
                        ],
                        [
                            'label' => 'Input',
                            'type' => 'group',
                            'divider' => true,
                            'fields' => ['input_size', 'autofocus'],
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
];
