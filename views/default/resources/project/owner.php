<?php

$page_type = elgg_extract('page_type', $vars);
$username = elgg_extract('username', $vars);

$user = get_user_by_username($username);
if ( !$user )
{
	forward('', '404');
}
$params = ychange_project_get_page_content_list($user->guid);

$params['sidebar'] = elgg_view('project/sidebar', ['page' => $page_type]);
$params['filter'] = elgg_view('project/project_sort_menu', ['filter_context' => 'mine']);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($params['title'], $body);
