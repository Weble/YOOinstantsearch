<?php

namespace Weble\Plugin\System\YOOinstantsearch;

use YOOtheme\Arr;
use YOOtheme\Config;
use YOOtheme\Path;

class CustomizerListener
{
    public static function initCustomizer(Config $config): void
    {
        $config->addFile('customizer', Path::get('../builder/config/customizer.json'));
    }

    public static function builderType(Config $config, array $type): array
    {
        if (!Arr::has($type, 'fieldset.default')) {
            return $type;
        }

        if (!in_array($type['name'], ['section', 'row'])) {
            return $type;
        }

        foreach (Arr::get($type, 'fieldset.default.fields', []) as $key => $fieldset) {
            if (($fieldset['title'] ?? null) !== "Advanced") {
                continue;
            }

            $statusField = [
                'type' => 'checkbox',
                'name' => 'instantsearch.state',
                'label' => 'Instant Search',
                'text' => 'Enable as Instant Search area'
            ];

            $configButton = [
                'name' => 'instantsearch',
                'text' => 'Configuration',
                'type' => 'button-panel',
                'panel' => 'instantsearch',
                'enable' => 'instantsearch.state',
                'description' => 'Enable this element as a search area and set the provider configuration.'
            ];

            // set button right after status field
            Arr::splice($fieldset['fields'], 2, 0, [
                $statusField,
                $configButton
            ]);

            Arr::set($type, "fieldset.default.fields.$key", $fieldset);
        }

        return $type;
    }
}
