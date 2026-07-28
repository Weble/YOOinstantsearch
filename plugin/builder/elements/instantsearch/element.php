<?php

use Weble\Plugin\System\YOOinstantsearch\SearchService;
use YOOtheme\Metadata;
use YOOtheme\Path;
use function YOOtheme\app;

$templates = [
    'render' => __DIR__ . '/templates/template.php',
    'content' => __DIR__ . '/templates/content.php',
];

return [
    'name' => 'instantsearch',
    'title' => 'instantsearch',
    'width' => 500,
    'container' => true,
    'templates' => $templates,
    'transforms' => [
        'render' => function ($node) {
            $search = new SearchService($node->props);

            $metadata = app(Metadata::class);
            $metadata->set('script:yooinstantsearch', ['src' => Path::get('~yooinstantsearch_url/assets/yooinstantsearch.min.js'), 'defer' => true]);

            $node->search = $search->config();
        },
    ],
];
