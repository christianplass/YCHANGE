<?php
/**
 * Ychange settings page
 */
?>
<div class="mtm">
    <label>
        <?php echo elgg_echo('ychange:setting:recaptcha:key'); ?>
    </label>
    <?php echo elgg_view('input/text', ['name' => 'params[recaptcha_key]', 'value' => $vars['entity']->recaptcha_key]); ?>
</div>

<div class="mtm">
    <label>
        <?php echo elgg_echo('ychange:setting:recaptcha:secret'); ?>
    </label>
    <?php echo elgg_view('input/text', ['name' => 'params[recaptcha_secret]', 'value' => $vars['entity']->recaptcha_secret]); ?>
</div>

<div class="mtm">
    <label>
        <?php echo elgg_echo('ychange:setting:google:maps:key'); ?>
    </label>
    <?php echo elgg_view('input/text', ['name' => 'params[google_maps_key]', 'value' => $vars['entity']->google_maps_key]); ?>
</div>

<div class="mtm">
    <label>
        <?php echo elgg_echo('ychange:index:about'); ?>
    </label>
    <?php echo elgg_view('input/longtext', ['name' => 'params[about]', 'value' => $vars['entity']->about]); ?>
</div>

<div class="mtm">
    <label>
        <?php echo elgg_echo('ychange:index:goal'); ?>
    </label>
    <?php echo elgg_view('input/longtext', ['name' => 'params[goal]', 'value' => $vars['entity']->goal]); ?>
</div>

<div class="mtm">
    <label>
        <?php echo elgg_echo('ychange:index:participate'); ?>
    </label>
    <?php echo elgg_view('input/longtext', ['name' => 'params[participate]', 'value' => $vars['entity']->participate]); ?>
</div>

<div class="mtm">
    <label>
        <?php echo elgg_echo('ychange:site:menu:video_tutorials'); ?>
    </label>
    <?php echo elgg_view('input/longtext', ['name' => 'params[tutorials]', 'value' => $vars['entity']->tutorials]); ?>
</div>
