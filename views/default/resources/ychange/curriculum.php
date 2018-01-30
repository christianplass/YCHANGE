<?php
/**
 * Ychange curriculum page
 */

$title = elgg_echo('ychange:settings:curriculum');

$content = elgg_view('output/longtext', ['value' => ychange_get_translated_plugin_setting('curriculum'), 'class' => 'ychange-curriculum']);

$body = elgg_view_layout('one_column', array(
        'content' => $content,
        'title' => $title,
));

echo elgg_view_page($title, $body);
