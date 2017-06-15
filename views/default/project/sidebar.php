<?php
/**
 * Project sidebar
 *
 * @package Ychnage
 */

$page = elgg_extract('page', $vars);

if ( in_array($page, ['owner', 'group', 'archive']) )
{
        echo elgg_view('project/sidebar/archives', $vars);
}
