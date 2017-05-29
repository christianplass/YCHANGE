<?php
/**
 * Ychange tutorials page
 */

$title = elgg_echo('ychange:site:menu:video_tutorials');

$content = elgg_view('output/longtext', ['value' => elgg_get_plugin_setting('tutorials', 'ychange')]);

$body = elgg_view_layout('one_column', array(
        'content' => $content,
        'title' => $title,
));

echo elgg_view_page($title, $body);
