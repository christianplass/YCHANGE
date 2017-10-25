<?php
/**
 * Cookie consent
 *
 * @package Ychange
 */

elgg_require_js('ychange/cookie_consent');

$button = elgg_view('input/button', [
    'value' => elgg_echo('ychnage:cookie_consent:agree'),
    'name' => 'agree',
    'class' => 'elgg-button-action float-alt',
]);
?>
<div id="ychange-cookie-consent">
    <?php echo $button; ?>
    <?php echo elgg_echo('ychange:cookie_consent', [elgg_normalize_url('privacy')]); ?>
</div>
