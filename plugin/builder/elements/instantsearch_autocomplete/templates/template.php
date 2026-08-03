<?php

use YOOtheme\Config;
use YOOtheme\File;
use function YOOtheme\app;

$config = app(Config::class);
$path = $__dir . '/' . $props['template'];

if ($childDir = $config('theme.childDir')) {
    $childPath = $childDir . '/builder/instantsearch_autocomplete/templates/' . $props['template'];

    if (File::exists($childPath . '.php')) {
        $path = $childPath;
    }
}

?>

<?= $this->render($path, compact('props')) ?>
