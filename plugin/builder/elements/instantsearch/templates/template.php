<?php
$container = $this->el('div', [
    'data-search-root' => 'true',
    'data-search-config' => htmlspecialchars(json_encode($node->search ?? []), ENT_QUOTES, 'UTF-8'),
    'v-cloak' => true
]);

$attrs['id'] = $attrs['id'] ?? 'search-' . $node->id;
?>

<?= $container($props, $attrs) ?>
    <ais-instant-search
            :search-client="searchClient"
            :index-name="indexName"
            :routing="routing">
        <ais-configure v-bind="rootConfigureState"></ais-configure>
        <div>
            <?= $builder->render($children) ?>
        </div>
    </ais-instant-search>
<?= $container->end(); ?>
