<?php
/**
 * Ychange front page override
 */

elgg_push_context('front');

$vars = [
    'pages' => [
        'about' => elgg_get_plugin_setting('about', 'ychange'),
        'goal' => elgg_get_plugin_setting('goal', 'ychange'),
        'participate' => elgg_get_plugin_setting('participate', 'ychange'),
    ],
    'partners' => elgg_view('ychange/front_page/partners'),
    'contact' => elgg_view('ychange/front_page/contact'),
];

$body = elgg_view('ychange/front_page/index', $vars);

elgg_unregister_rss_link();

echo elgg_view_page('', $body);
