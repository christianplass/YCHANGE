<?php
/**
 * Ychange WEB MAP page
 */

$title = elgg_echo('ychange:site:menu:webmap');

$content = '<iframe width="100%" height="600px" src="https://www.arcgis.com/apps/MapTour/index.html?appid=e84dd45b8b2c44609e8f74117add8388"></iframe>';

$body = elgg_view_layout('one_column', array(
        'content' => $content,
        'title' => '',
));

echo elgg_view_page($title, $body);
