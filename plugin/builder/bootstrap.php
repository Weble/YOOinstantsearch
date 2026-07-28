<?php

namespace Weble\YOOAlgolia;

use Weble\Plugin\System\YOOinstantsearch\BuilderTransform;
use Weble\Plugin\System\YOOinstantsearch\CustomizerListener;
use YOOtheme\Builder;
use YOOtheme\Path;

return [

    'events' => [

        'customizer.init' => [
            CustomizerListener::class => 'initCustomizer'
        ],

        'builder.type' => [
            CustomizerListener::class => ['builderType', -10]
        ],

    ],

    'extend' => [
        Builder::class => function (Builder $builder) {
            $builder->addTypePath(Path::get('./elements/*/element.php'));
            $builder->addTransform('render', new BuilderTransform($builder));
        },

    ],

];
