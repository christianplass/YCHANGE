<?php
/**
 * Ychange front page logos module
 */
?>
<div class="elgg-grid clearfix">
    <div class="elgg-col elgg-col-1of2 ychange-index-logo">
        <?php
            echo elgg_view('output/url', [
                'text' => elgg_view('output/img', ['src' => elgg_get_simplecache_url('logos/heidelberg.png'), 'alt' => 'logo']),
                'href' => 'https://www.rgeo.de/en/start/',
                'is_trusted' => true,
                'target' => '_blank',
            ]);
        ?>
    </div>

    <div class="elgg-col elgg-col-1of2 ychange-index-logo">
        <?php
            echo elgg_view('output/url', [
                'text' => elgg_view('output/img', ['src' => elgg_get_simplecache_url('logos/cuni.png'), 'alt' => 'logo']),
                'href' => 'https://www.natur.cuni.cz/geography/department-of-applied-geoinformatics-and-cartography',
                'is_trusted' => true,
                'target' => '_blank',
            ]);
        ?>
    </div>
</div>

<div class="elgg-grid clearfix">
    <div class="elgg-col elgg-col-1of2 ychange-index-logo">
        <?php
            echo elgg_view('output/url', [
                'text' => elgg_view('output/img', ['src' => elgg_get_simplecache_url('logos/fhnw.png'), 'alt' => 'logo']),
                'href' => 'http://www.gesellschaftswissenschaften-phfhnw.ch/ueber-uns/team/viehrig-kathrin-dr/',
                'is_trusted' => true,
                'target' => '_blank',
            ]);
        ?>
    </div>

    <div class="elgg-col elgg-col-1of2 ychange-index-logo">
        <?php
            echo elgg_view('output/url', [
                'text' => elgg_view('output/img', ['src' => elgg_get_simplecache_url('logos/tlu.png'), 'alt' => 'logo']),
                'href' => 'http://htk.tlu.ee/htk/',
                'is_trusted' => true,
                'target' => '_blank',
            ]);
        ?>
    </div>
</div>
