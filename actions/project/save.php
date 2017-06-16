<?php
/**
* Save project entity
*
* @package Ychange
*/

// Get variables
$title = htmlspecialchars(get_input('title', '', false), ENT_QUOTES, 'UTF-8');
$topic = htmlspecialchars(get_input('topic', '', false), ENT_QUOTES, 'UTF-8');
$interesting = get_input('interesting');
$useful = get_input('useful');
$results = get_input('results');
$sources = get_input('sources');
$location = htmlspecialchars(get_input('location', '', false), ENT_QUOTES, 'UTF-8');
$category = get_input('category');
$container_guid = (int) get_input('container_guid', 0);
$guid = (int) get_input('guid');

elgg_make_sticky_form('project');

// check whether this is a new file or an edit
$new_project = true;
if ( $guid > 0 )
{
    $new_project = false;
}

if ( $new_project )
{
    if ( $container_guid == 0 )
    {
        register_error(elgg_echo('ychange:project:no_container'));
        forward(REFERER);
    }
    else
    {
        $container = get_entity($container_guid);
        if ( !elgg_instanceof($container, 'group') )
        {
            register_error(elgg_echo('ychange:project:wrong_container'));
            forward(REFERER);
        }
    }

    $project = new ElggYchangeProject();
}
else
{
    // load original file object
    $project = get_entity($guid);
    if ( !$project instanceof ElggYchangeProject )
    {
        register_error(elgg_echo('ychange:project:cannotload'));
        forward(REFERER);
    }

    // user must be able to edit a project
    if ( !$project->canEdit() )
    {
        register_error(elgg_echo('ychange:project:noaccess'));
        forward(REFERER);
    }
}

$project->title = $title;
$project->topic = $topic;
$project->interesting = $interesting;
$project->useful = $useful;
$project->results = $results;
$project->sources = $sources;
$project->location = $location;
$project->category = $category;
$project->access_id = get_user_access_collections($container_guid)[0]->id;
$project->container_guid = $container_guid;

if ( $new_project )
{
    $guid = $project->save();
}
else
{
    $project->save();
}

$uploaded_icons = elgg_get_uploaded_files('icon');
$uploaded_icon = array_shift($uploaded_icons);
if ( $uploaded_icon && $uploaded_icon->isValid() && substr_count($uploaded_icon->getClientMimeType(), 'image/') )
{
    $filehandler = new ElggFile();
    $filehandler->owner_guid = $project->owner_guid;
    $filehandler->setFilename("projects/$project->guid.jpg");
    $filehandler->open("write");
    $filehandler->write($uploaded_icon);
    $filehandler->close();

    if ( $filehandler->exists() )
    {
        // Non existent file throws exception
        $project->saveIconFromElggFile($filehandler);
    }
}

$uploaded_satellite_images = elgg_get_uploaded_files('satellite_images');
if ( $uploaded_satellite_images && is_array($uploaded_satellite_images) && count($uploaded_satellite_images) > 0 )
{
    foreach( $uploaded_satellite_images as $image )
    {
        if ( $image && $image->isValid() )
        {
            if ( substr_count($image->getClientMimeType(), 'image/') )
            {
                $imageFile = new ElggFile();
                $imageFile->subtype = 'satellite_image';
                $imageFile->title = $image->getClientOriginalName();
                $imageFile->access_id = $project->access_id;
                $imageFile->container_guid = $project->guid;

                if ( $imageFile->acceptUploadedFile($image) )
                {
                    $imageFileGuid = $imageFile->save();
                }

                if ( $imageFileGuid && $imageFile->saveIconFromElggFile($imageFile) )
                {
                    $imageFile->thumbnail = $imageFile->getIcon('small')->getFilename();
                    $imageFile->smallthumb = $imageFile->getIcon('medium')->getFilename();
                    $imageFile->largethumb = $imageFile->getIcon('large')->getFilename();
                }
                else
                {
                    $imageFile->deleteIcon();
                    unset($imageFile->thumbnail);
                    unset($imageFile->smallthumb);
                    unset($imageFile->largethumb);
                }
            }
        }
        else if ( $image )
        {
            $error = elgg_get_friendly_upload_error($image->getError());
            register_error($error);
        }
    }
}

$removed_satellite_images = get_input('removed_satellite_images');
if ( $removed_satellite_images && is_array($removed_satellite_images) && count($removed_satellite_images) > 0 )
{
    foreach( $removed_satellite_images as $imageGuid )
    {
        $image = get_entity($imageGuid);
        if ( elgg_instanceof($image, 'object', 'satellite_image') && $image->container_guid = $project->guid )
        {
            $image->delete();
        }
    }
}

// project saved so clear sticky form
elgg_clear_sticky_form('project');


// handle results differently for new project and project updates
if ( $new_project )
{
    if ( $guid )
    {
        $message = elgg_echo("ychange:project:saved");
        system_message($message);
        elgg_create_river_item(array(
            'view' => 'river/object/project/create',
            'action_type' => 'create',
            'subject_guid' => elgg_get_logged_in_user_guid(),
            'object_guid' => $project->guid,
        ));
    }
    else
    {
        // failed to save project object - nothing we can do about this
        $error = elgg_echo("ychange:project:notsaved");
        register_error($error);
    }

    $container = get_entity($container_guid);
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
    if ( $guid )
    {
        system_message(elgg_echo("ychange:project:saved"));
    }
    else
    {
        register_error(elgg_echo("ychange:project:notsaved"));
    }

    forward($project->getURL());
}
