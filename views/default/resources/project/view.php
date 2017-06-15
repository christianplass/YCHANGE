<?php

$page_type = elgg_extract('page_type', $vars);
$guid = elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid, 'object', 'project');
elgg_group_gatekeeper();

$project = get_entity($guid);

elgg_set_page_owner_guid($project->container_guid);

// no header or tabs for viewing an individual project
$params = [
	'filter' => '',
	'title' => $project->title
];

$container = $project->getContainerEntity();
$crumbs_title = $container->name;

if ( elgg_instanceof($container, 'group') )
{
	elgg_push_breadcrumb($crumbs_title, "projects/group/$container->guid/all");
}
else
{
	elgg_push_breadcrumb($crumbs_title, "projects/owner/$container->username");
}

elgg_push_breadcrumb($project->title);

$params['content'] = elgg_view_entity($project, ['full_view' => true]);

$params['sidebar'] = elgg_view('project/sidebar', ['page' => $page_type]);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($params['title'], $body);
