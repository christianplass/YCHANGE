<?php
/**
* Makes project publicly visible.
*
* @package Ychange
*/

$guid = get_input('guid');
$project = get_entity($guid);

if ( ( $project instanceof \ElggYchangeProject ) && $project->canPublishAndUnpublish() )
{
    if ( $project->makePublic() )
    {
        system_message(elgg_echo('ychange:project:publish:yes'));
    }
    else
    {
        register_error(elgg_echo('ychange:project:publish:no'));
    }
}
else
{
    register_error(elgg_echo('ychange:project:publish:no'));
}

forward(REFERER);
