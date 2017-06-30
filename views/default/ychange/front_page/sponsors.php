<?php
/**
 * Ychange front page logos module
 */
?>
<div class="elgg-grid clearfix">
    <div class="elgg-col elgg-col-1of2 ychange-index-logo">
        <?php echo elgg_view('output/img', ['src' => elgg_get_simplecache_url('logos/erasmus_plus.jpg'), 'alt' => 'logo']); ?>
    </div>

    <div class="elgg-col elgg-col-1of2 ychange-index-logo">
        <?php echo elgg_view('output/img', ['src' => elgg_get_simplecache_url('logos/movetia.jpg'), 'alt' => 'logo']); ?>
    </div>
</div>
