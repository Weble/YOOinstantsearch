<?php

namespace Weble\Plugin\System\YOOinstantsearch;

use Joomla\CMS\Factory;

class SearchService
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function config(): array
    {
        return [
            'provider' => $this->provider(),
            'indexName' => $this->indexName(),
            'hitsPerPage' => $this->hitsPerPage(),
            'localeField' => $this->string('locale_field'),
            'stateField' => $this->string('state_field', 'state'),
            'stateValue' => $this->string('state_value', '1'),
            'defaultFilters' => $this->defaultFilters(),
            'typesense' => [
                'host' => $this->string('typesense_host'),
                'port' => $this->string('typesense_port'),
                'protocol' => $this->string('typesense_protocol', 'http'),
                'path' => $this->string('typesense_path'),
                'apiKey' => $this->string('typesense_search_api_key'),
                'queryBy' => $this->string('typesense_query_by', 'title'),
            ],
            'algolia' => [
                'appId' => $this->string('algolia_app_id'),
                'apiKey' => $this->string('algolia_search_api_key'),
            ],
        ];
    }

    private function provider(): string
    {
        return $this->string('provider', 'typesense');
    }

    private function indexName(): string
    {
        if ($this->provider() === 'algolia') {
            return $this->string('algolia_index_name');
        }

        return $this->string('typesense_collection_name');
    }

    private function hitsPerPage(): int
    {
        $hitsPerPage = (int) $this->string('item_per_page', '20');

        return $hitsPerPage > 0 ? $hitsPerPage : 20;
    }

    private function defaultFilters(): array
    {
        $filters = [];
        $dynamicFilter = $this->dynamicFilter();

        if ($dynamicFilter) {
            $filters[] = $dynamicFilter;
        }

        return $filters;
    }

    private function dynamicFilter(): ?array
    {
        $mode = $this->string('context_filter_mode');

        if ($mode === 'category') {
            $categoryTitle = $this->currentCategoryTitle();

            if ($categoryTitle) {
                return [
                    'field' => 'category',
                    'value' => $categoryTitle,
                ];
            }
        }

        if ($mode === 'article-title') {
            $field = $this->string('context_filter_field');
            $articleTitle = $this->currentArticleTitle();

            if ($field && $articleTitle) {
                return [
                    'field' => $field,
                    'value' => $articleTitle,
                ];
            }
        }

        return null;
    }

    private function currentCategoryTitle(): ?string
    {
        $categoryId = Factory::getApplication()->input->getInt('id');

        if (!$categoryId) {
            return null;
        }

        $category = Factory::getApplication()->bootComponent('com_categories')
            ->getMVCFactory()
            ->createTable('Category', 'Administrator');

        if (!$category->load(['id' => $categoryId])) {
            return null;
        }

        return $category->title ?: null;
    }

    private function currentArticleTitle(): ?string
    {
        $articleId = Factory::getApplication()->input->getInt('id');

        if (!$articleId) {
            return null;
        }

        $article = Factory::getApplication()->bootComponent('com_content')
            ->getMVCFactory()
            ->createTable('Article', 'Site');

        if (!$article->load(['id' => $articleId])) {
            return null;
        }

        return $article->title ?: null;
    }

    private function string(string $key, string $default = ''): string
    {
        $value = $this->config[$key] ?? $default;

        if (is_string($value) || is_numeric($value)) {
            return trim((string) $value);
        }

        return $default;
    }
}
