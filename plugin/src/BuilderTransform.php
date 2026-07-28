<?php

namespace Weble\Plugin\System\YOOinstantsearch;

use YOOtheme\Builder;
use function YOOtheme\app;

readonly class BuilderTransform
{
    public const string INSTANT_SEARCH_ELEMENT_TYPE = 'instantsearch';

    public function __construct(protected Builder $builder)
    {
    }

    public function __invoke($node, array $params): void
    {
        if ($this->isInstantSearchNode($node)) {
            $this->appendInstantSearchNode($node, $params);
        }
    }

    protected function isInstantSearchNode($node): bool
    {
        return (bool) ($node->props[static::INSTANT_SEARCH_ELEMENT_TYPE]?->state ?? false);
    }

    protected function appendInstantSearchNode($node, array $params): void
    {
        $config = (array) $node->props[static::INSTANT_SEARCH_ELEMENT_TYPE];

        // Create unique id
        $instantSearchElementId = hash('crc32b', json_encode([
            static::INSTANT_SEARCH_ELEMENT_TYPE,
            app()->config->get('req.url'),
            $params['parent']->id ?? 0,
            $params['index'] ?? 0
        ]));

        $instantSearchNode = $this->builder->load(json_encode([
            'id' => $instantSearchElementId,
            'type' => static::INSTANT_SEARCH_ELEMENT_TYPE,
            'props' => $config
        ]), ['context' => 'render']);

        $instantSearchNode->config = $config;
        $instantSearchNode->children = $node->children;

        // Append the new InstantSearch node in the parent node
        $node->children = [$instantSearchNode];
    }
}
