<?php
/**
* Delete project entity
*
* @package Ychange
*/

$project_guid = get_input('guid');
$project = get_entity($project_guid);

if ( elgg_instanceof($project, 'object', 'project') && $project->canDelete() )
{
    $container = get_entity($project->container_guid);
    if ( $project->delete() )
    {
        system_message(elgg_echo('ychange:message:deleted_project'));
        if ( elgg_instanceof($container, 'group') )
        {
            forward("projects/group/$container->guid/all");
        }
        else
        {
            forward("projects/owner/$container->username");
        }
    }
    else
    {
        register_error(elgg_echo('ychange:error:cannot_delete_project'));
    }
}
else
{
    register_error(elgg_echo('ychange:error:project_not_found'));
}

forward(REFERER);
