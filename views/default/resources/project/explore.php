<?php

$page_type = elgg_extract('page_type', $vars);

$params = ychange_project_get_explore_page_content_list();

$params['sidebar'] = elgg_view('project/sidebar', ['page' => $page_type]);
$params['filter'] = elgg_view('project/project_sort_menu', ['filter_context' => 'explore']);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($params['title'], $body);
