<?php
/**
 * Ychange WEB MAP page
 */

$title = elgg_echo('ychange:site:menu:webmap');

$content = '<iframe width="100%" height="700px" src="https://cuni.maps.arcgis.com/apps/MapTour/index.html?appid=56279b2055a048b9808b2ece27907a63"></iframe>';

$body = elgg_view_layout('one_column', array(
        'content' => $content,
        'title' => '',
));
$vars = [
  'body_attrs' => [
    'class' => 'ychange-webmap',
  ],
];

echo elgg_view_page($title, $body, 'default', $vars);
