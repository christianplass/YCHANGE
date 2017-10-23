<?php
/**
* Revokes teacher privileges from a user.
*
* @package Ychange
*/

$guid = get_input('guid');
$project = get_entity($guid);

if ( ( $project instanceof \ElggYchangeProject ) && $project->canPublishAndUnpublish() )
{
    if ( $project->removeFromPublic() )
    {
        system_message(elgg_echo('ychange:project:unpublish:yes'));
    }
    else
    {
        register_error(elgg_echo('ychange:project:unpublish:no'));
    }
}
else
{
    register_error(elgg_echo('ychange:project:unpublish:no'));
}

forward(REFERER);
