<?php
/**
 * Profile statu view extension
 *
 * @package Ychange
 */

$user = elgg_extract('entity', $vars);

if ( ychange_is_teacher($user) )
{
    echo '<div class="p-role p-role-teacher elgg-subtext">';
        echo '<i class="fa fa-graduation-cap"></i>';
        echo '&nbsp;';
        echo elgg_echo('ychange:role:teacher');
    echo '</div>';
}
