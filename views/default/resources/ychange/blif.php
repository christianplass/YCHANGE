<?php
/**
 * Ychange BLIF page
 */

$title = elgg_echo('ychange:site:menu:blif');

$content = elgg_view('output/longtext', ['value' => ychange_get_translated_plugin_setting('blif'), 'class' => 'ychange-blif']);

$body = elgg_view_layout('one_column', array(
        'content' => $content,
        'title' => $title,
));

echo elgg_view_page($title, $body);
