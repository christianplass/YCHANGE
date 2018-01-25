<?php
/**
 * Ychange front page override
 */

elgg_push_context('front');

$vars = [
    'pages' => [
        'about' => ychange_get_translated_plugin_setting('about'),
        'goal' => ychange_get_translated_plugin_setting('goal'),
        'participate' => ychange_get_translated_plugin_setting('participate'),
    ],
    'partners' => elgg_view('ychange/front_page/partners'),
    'sponsors' => elgg_view('ychange/front_page/sponsors'),
    'contact' => elgg_view('ychange/front_page/contact'),
];

$body = elgg_view('ychange/front_page/index', $vars);

elgg_unregister_rss_link();

echo elgg_view_page('', $body);
