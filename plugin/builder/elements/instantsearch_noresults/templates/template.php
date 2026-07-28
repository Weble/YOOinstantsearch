<?php
$el = $this->el('div', [
    'class' => 'el-element'
]);

$dictionary = [
    '{page}' => '{{ page + 1 }}',
    '{nbPages}' => '{{ nbPages }}',
    '{hitsPerPage}' => '{{ hitsPerPage }}',
    '{nbHits}' => '{{ nbHits }}',
    '{processingTimeMS}' => '{{ processingTimeMS }}',
    '{query}' => '{{ query }}'
];

foreach ($dictionary as $search => $replace) {
    $props['text'] = str_replace($search, $replace, $props['text']);
}


?>

<?= $el($props, $attrs); ?>
<ais-stats>
    <template v-slot:default="{ hitsPerPage, nbPages, nbHits, page, processingTimeMS, query }">
        <span :class="nbHits === 0 ? 'uk-visible' : 'uk-hidden'">
                <?= $props['text'] ?>
           </span>
    </template>
</ais-stats>
<?= $el->end(); ?>
