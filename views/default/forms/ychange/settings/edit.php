<?php
/**
 * Edit form body for ychange setting
 *
 * @uses $vars['type']
 *
 */

elgg_load_library('elgg:ychange:options');

$languages = ychange_get_language_options();

$type = $vars['type'];

$content = elgg_get_plugin_setting($type, 'ychange');

// set the required form variables
$input_area = elgg_view('input/longtext', [
        'name' => 'content',
        'value' => $content,
]);

$translations_input_area = '';

foreach ( $languages as $key => $language )
{
    if ( $key != 'en' )
    {
        $translations_input_area .= elgg_view('input/longtext', [
            'name' => "content:$key",
            'value' => elgg_get_plugin_setting("$type:$key", 'ychange'),
        ]);
        $translations_input_area .= elgg_view('elements/forms/help', [
            'help' => $language,
        ]);
    }
}

$submit_input = elgg_view('input/submit', [
        'name' => 'submit',
        'value' => elgg_echo('save'),
]);
$hidden_type = elgg_view('input/hidden', [
        'name' => 'type',
        'value' => $type,
]);

$title = elgg_echo("ychange:settings:$type");

//construct the form
echo <<<EOT
<div class="mtm">
        <label>$title</label>
        $input_area
        $translations_input_area
</div>
<div class="elgg-foot">
$hidden_type
$submit_input
</div>
EOT;
