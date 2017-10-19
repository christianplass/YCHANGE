<?php
/**
* Grants teacher privileges to a user.
*
* @package Ychange
*/

$guid = get_input('guid');
$user = get_entity($guid);

if ( ($user instanceof \ElggUser) && ($user->canEdit()) )
{
    if ( ychange_make_teacher($user) )
    {
        system_message(elgg_echo('ychange:admin:user:maketeacher:yes'));
    }
    else
    {
        register_error(elgg_echo('ychange:admin:user:maketeacher:no'));
    }
}
else
{
    register_error(elgg_echo('ychange:admin:user:maketeacher:no'));
}

forward(REFERER);
