<?php
/**
* Rejects teacher privileges to a user.
*
* @package Ychange
*/

$guid = get_input('guid');
$user = get_entity($guid);

if ( ($user instanceof \ElggUser) && ($user->canEdit()) )
{
    if ( ychange_reject_teacher_request($user) )
    {
        system_message(elgg_echo('ychange:admin:user:rejectteacher:yes'));
    }
    else
    {
        register_error(elgg_echo('ychange:admin:user:rejectteacher:no'));
    }
}
else
{
    register_error(elgg_echo('ychange:admin:user:rejectteacher:no'));
}

forward(REFERER);
