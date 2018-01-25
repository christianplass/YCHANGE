<?php
/**
* Grants teacher privileges to a user.
*
* @package Ychange
*/

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
}
else
{
    // TODO Show some meaningful message
}

forward(REFERER);
