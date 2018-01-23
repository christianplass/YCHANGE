<?php
/**
 * Determine if any of the Student Project entities are missing language metadata
 * If there are any, make sure to set that to a default value.
 */

/**
 * Add language to a project if missing
 * @param  ElggObject $project Student Project
 * @return boolean
 */
function ychange_2018012300($project)
{
    if ( $project->language )
    {
        return true;
    }

    $project->language = 'en';

    error_log('Student Project language set');
    return $project->save();
}

$previous_access = elgg_set_ignore_access(true);

$dbprefix = elgg_get_config('dbprefix');
$name_metastring_id = elgg_get_metastring_id('language');

$projectCount = elgg_get_entities([
    'type' => 'object',
    'subtype' => 'project',
    'wheres' => [
        "NOT EXISTS (SELECT 1 FROM {$dbprefix}metadata md WHERE md.entity_guid = e.guid AND md.name_id = {$name_metastring_id})",
    ],
    'count' => true,
]);

// Do not upgrade if there are no upgradable projects
if ( !( $projectCount > 0 ) )
{
    return;
}

$options = [
    'type' => 'object',
    'subtype' => 'project',
    'wheres' => [
        "NOT EXISTS (SELECT 1 FROM {$dbprefix}metadata md WHERE md.entity_guid = e.guid AND md.name_id = {$name_metastring_id})",
    ],
];

$batch = new ElggBatch('elgg_get_entities', $options, 'ychange_2018012300', 50);

elgg_set_ignore_access($previous_access);

if ( $batch->callbackResult )
{
    error_log("Ychange upgrade (2018012300) succeeded");

}
else
{
    error_log("Ychange upgrade (2018012300) failed");
}
