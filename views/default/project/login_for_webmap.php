<?php

if ( !elgg_is_logged_in() ) {
    echo elgg_view('output/longtext', [
        'value' => '<span class="elgg-icon fa fa-sign-in"></span>&nbsp;' . elgg_echo('ychange:project:list:webmap:text'),
        'class' => 'ychange-login-for-webmap',
        'parse_urls' => false,
        'parse_emails' => false,
        'sanitize' => false,
        'autop' => false,
    ]);
}
