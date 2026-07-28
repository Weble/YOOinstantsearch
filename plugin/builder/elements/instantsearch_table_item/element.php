<?php
return [
    'name' => 'instantsearch_table_item',
    'title' => 'Item',
    'width' => 500,
    'fields' => [
        'title' => [
            'label' => 'Column Heading',
            'source' => true,
        ],
        'value' => [
            'label' => 'Field Value',
            'source' => true,
        ],
        'status' => '${builder.statusItem}',
        'source' => '${builder.source}',
    ],
    'fieldset' => [
        'default' => [
            'type' => 'tabs',
            'fields' => [
                [
                    'title' => 'Content',
                    'fields' => ['title', 'value'],
                ],
                '${builder.advancedItem}',
            ],
        ],
    ],
];
