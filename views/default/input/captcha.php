<?php
/**
 * reCaptcha integration
 */

elgg_load_js('recaptcha');
elgg_require_js("ychange/recaptcha");
?>
<div
    class="g-recaptcha"
    data-sitekey="<?php echo elgg_get_plugin_setting('recaptcha_key', 'ychange'); ?>"
    data-callback="reCaptchaSolvedCb"
    data-size="invisible">
</div>
