<?php
/**
* Revokes teacher privileges from a user.
*
* @package Ychange
*/

$guid = get_input('guid');
$user = get_entity($guid);

if ( ($user instanceof \ElggUser) && ($user->canEdit()) )
{
    if ( ychange_remove_teacher($user) )
    {
        system_message(elgg_echo('ychange:admin:user:removeteacher:yes'));
    }
    else
    {
        register_error(elgg_echo('ychange:admin:user:removeteacher:no'));
    }
}
else
{
    register_error(elgg_echo('ychange:admin:user:removeteacher:no'));
}

forward(REFERER);
