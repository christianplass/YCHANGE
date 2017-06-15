<?php
/**
 * Group project module
 *
 * @package Ychnage
 */

$group = elgg_get_page_owner_entity();

if ( $group->project_enable == "no" )
{
        return true;
}

$all_link = elgg_view('output/url', [
        'href' => "projects/group/$group->guid/all",
        'text' => elgg_echo('link:view:all'),
        'is_trusted' => true,
]);

elgg_push_context('widgets');
$options = [
        'type' => 'object',
        'subtype' => 'project',
        'container_guid' => elgg_get_page_owner_guid(),
        'limit' => 6,
        'full_view' => false,
        'pagination' => false,
        'no_results' => elgg_echo('ychange:project:none'),
        'distinct' => false,
];
$content = elgg_list_entities($options);
elgg_pop_context();

$new_link = elgg_view('output/url', [
        'href' => "projects/add/$group->guid",
        'text' => elgg_echo('ychange:project:add'),
        'is_trusted' => true,
]);

echo elgg_view('groups/profile/module', [
        'title' => elgg_echo('ychange:project:group'),
        'content' => $content,
        'all_link' => $all_link,
        'add_link' => $new_link,
]);
