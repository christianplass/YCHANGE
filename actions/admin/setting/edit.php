<?php
/**
* Grants teacher privileges to a user.
*
* @package Ychange
*/

elgg_load_library('elgg:ychange:options');

$languages = ychange_get_language_options();

$type = get_input('type');
$content = get_input('content', '', false);

if ( $type )
{
    if ( elgg_set_plugin_setting($type, $content, 'ychange') )
    {
        // TODO Show some meaningful message
    }
    else
    {
        // TODO Show some meaningful message
    }

    foreach ( array_keys($languages) as $language )
    {
        if ( $language === 'en' )
        {
            continue;
        }

        elgg_set_plugin_setting("$type:$language", get_input("content:$language", '', false), 'ychange');
    }
}
else
{
    // TODO Show some meaningful message
}

forward(REFERER);
