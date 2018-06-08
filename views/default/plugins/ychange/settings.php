<?php
/**
 * Ychange settings page
 */
?>
<div class="mtm">
    <label>
        <?php echo elgg_echo('ychange:setting:google:analytics:key'); ?>
    </label>
    <?php echo elgg_view('input/text', ['name' => 'params[google_analytics_key]', 'value' => $vars['entity']->google_analytics_key]); ?>
</div>

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
        <?php echo elgg_echo('ychange:setting:project:samples:url'); ?>
    </label>
    <?php echo elgg_view('input/url', ['name' => 'params[project_samples_url]', 'value' => $vars['entity']->project_samples_url]); ?>
</div>

<div class="mtm">
    <label>
        <?php echo elgg_echo('ychange:setting:teacher:questionnaire:url'); ?>
    </label>
    <?php echo elgg_view('input/url', ['name' => 'params[teacher_questionnaire_url]', 'value' => $vars['entity']->teacher_questionnaire_url]); ?>
</div>

<div class="mtm">
    <label>
        <?php echo elgg_echo('ychange:setting:student:questionnaire:url'); ?>
    </label>
    <?php echo elgg_view('input/url', ['name' => 'params[student_questionnaire_url]', 'value' => $vars['entity']->student_questionnaire_url]); ?>
</div>
