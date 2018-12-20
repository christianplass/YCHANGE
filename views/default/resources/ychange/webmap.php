<?php
/**
 * Ychange WEB MAP page
 */

$title = elgg_echo('ychange:site:menu:webmap');

$mapUrl = elgg_get_plugin_setting('webmap_url', 'ychange');

if ( $mapUrl ) {
  $content = '<strong class="elgg-output ychange-open-map-in-new-tab">';
  $content .= elgg_view('output/url', [
    'text' => '<span class="elgg-icon fa fa-map-o"></span>&nbsp;' . elgg_echo('ychange:webmap:open:in:new:tab'),
    'href' => $mapUrl,
    'is_action' => false,
    'is_trusted' => true,
    'target' => '_blank',
  ]);
  $content .= '</strong>';
  $content .= '<iframe width="100%" height="700px" src="' . $mapUrl . '"></iframe>';
}

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
