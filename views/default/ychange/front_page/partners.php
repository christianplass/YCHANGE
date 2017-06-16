<?php
/**
 * Ychange front pahge logos module
 */
?>
<div class="elgg-grid clearfix">
    <div class="elgg-col elgg-col-1of3 ychange-index-logo">
        <?php echo elgg_view('output/img', ['src' => elgg_get_simplecache_url('logos/heidelberg.png'), 'alt' => 'logo']); ?>
    </div>

    <div class="elgg-col elgg-col-1of3 ychange-index-logo">
        <?php echo elgg_view('output/img', ['src' => elgg_get_simplecache_url('logos/cuni.png'), 'alt' => 'logo']); ?>
    </div>

    <div class="elgg-col elgg-col-1of3 ychange-index-logo">
        <?php echo elgg_view('output/img', ['src' => elgg_get_simplecache_url('logos/tlu.png'), 'alt' => 'logo']); ?>
    </div>

    <div class="elgg-col elgg-col-1of1 ychange-index-logo">
        <?php echo elgg_view('output/img', ['src' => elgg_get_simplecache_url('logos/fhnw.png'), 'alt' => 'logo']); ?>
    </div>

    <div class="clearfloat"></div>

    <div class="elgg-col elgg-col-1of1 ychange-index-logo">
        <?php echo elgg_view('output/img', ['src' => elgg_get_simplecache_url('logos/program.png'), 'alt' => 'logo']); ?>
    </div>
</div>
