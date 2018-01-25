<?php
/**
 * Edit form body for ychange setting
 *
 * @uses $vars['type']
 *
 */

$type = $vars['type'];

$content = elgg_get_plugin_setting($type, 'ychange');

// set the required form variables
$input_area = elgg_view('input/longtext', array(
        'name' => 'content',
        'value' => $content,
));
$submit_input = elgg_view('input/submit', array(
        'name' => 'submit',
        'value' => elgg_echo('save'),
));
$hidden_type = elgg_view('input/hidden', array(
        'name' => 'type',
        'value' => $type,
));

$title = elgg_echo("ychange:settings:$type");

//construct the form
echo <<<EOT
<div class="mtm">
        <label>$title</label>
        $input_area
</div>
<div class="elgg-foot">
$hidden_type
$submit_input
</div>
EOT;
