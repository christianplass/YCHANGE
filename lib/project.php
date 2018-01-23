<?php
/**
* Project helper functions
*
* @package Ychange
*/

/**
* Get page components to list a user's or all projects.
*
* @param int $container_guid The GUID of the page owner or NULL for all projects
* @return array
*/
function ychange_project_get_page_content_list($container_guid = NULL)
{
	$return = array();

	$return['filter_context'] = $container_guid ? 'mine' : 'all';
	$options = [
		'type' => 'object',
		'subtype' => 'project',
		'full_view' => false,
		'no_results' => elgg_echo('ychange:project:none'),
		'preload_owners' => true,
	];

	$current_user = elgg_get_logged_in_user_entity();

	if ( $container_guid )
	{
		// access check for closed groups
		elgg_group_gatekeeper();

		$container = get_entity($container_guid);
		if ( $container instanceof ElggGroup )
		{
			$options['container_guid'] = $container_guid;
		}
		else
		{
			$options['owner_guid'] = $container_guid;
		}
		$return['title'] = elgg_echo('ychange:title:user_projects', [$container->name]);

		$crumbs_title = $container->name;
		elgg_push_breadcrumb($crumbs_title);

		if ( $current_user && ( $container_guid == $current_user->guid ) )
		{
			$return['filter_context'] = 'mine';
		}
		else if ( elgg_instanceof($container, 'group') )
		{
			$return['filter'] = false;
		}
		else
		{
			// do not show button or select a tab when viewing someone else's projects
			$return['filter_context'] = 'none';
		}
	}
	else
	{
		$options['preload_containers'] = true;
		$return['filter_context'] = 'all';
		$return['title'] = elgg_echo('ychange:title:all_projects');
		elgg_pop_breadcrumb();
		elgg_push_breadcrumb(elgg_echo('ychange:projects'));
	}

	if ( $container instanceof ElggGroup )
	{
		elgg_register_title_button('projects', 'add', 'object', 'project');
	}

	$return['content'] = elgg_list_entities($options);

	return $return;
}

/**
* Get explore page contents with list of projects according to criteria.
*
* @return array
*/
function ychange_project_get_explore_page_content_list()
{
	$language = get_input('language');
	$category = get_input('category');

	$return = array();

	$return['filter_context'] = 'explore';
	$options = [
		'type' => 'object',
		'subtype' => 'project',
		'full_view' => false,
		'no_results' => elgg_echo('ychange:project:none'),
		'preload_owners' => true,
		'metadata_name_value_pairs' => [
			[
				'name' => 'language',
				'value' => $language,
			],
			[
				'name' => 'category',
				'value' => $category,
			],
		],
	];

	$options['preload_containers'] = true;
	$return['title'] = elgg_echo('ychange:title:explore_projects');
	elgg_pop_breadcrumb();
	elgg_push_breadcrumb(elgg_echo('ychange:projects'));

	$return['content'] = elgg_view('project/explore', [
		'languages' => ychange_get_language_options(),
		'categories' => ychange_project_categories_options(),
		'language' => $language,
		'category' => $category,
	]);

	if ( $language && $category )
	{
		$return['content'] .= elgg_list_entities_from_metadata($options);
	}

	return $return;
}

/**
* Get page components to show projects with publish dates between $lower and $upper
*
* @param int $owner_guid The GUID of the owner of this page
* @param int $lower      Unix timestamp
* @param int $upper      Unix timestamp
* @return array
*/
function ychange_project_get_page_content_archive($owner_guid, $lower = 0, $upper = 0) {

	$owner = get_entity($owner_guid);
	elgg_set_page_owner_guid($owner_guid);

	$crumbs_title = $owner->name;
	if ( elgg_instanceof($owner, 'user') )
	{
		$url = "projects/owner/{$owner->username}";
	}
	else
	{
		$url = "projects/group/$owner->guid/all";
	}
	elgg_push_breadcrumb($crumbs_title, $url);
	elgg_push_breadcrumb(elgg_echo('ychange:project:archives'));

	if ( $lower )
	{
		$lower = (int)$lower;
	}

	if ( $upper )
	{
		$upper = (int)$upper;
	}

	$options = [
		'type' => 'object',
		'subtype' => 'project',
		'full_view' => false,
		'no_results' => elgg_echo('ychange:project:none'),
		'preload_owners' => true,
	];

	if ( $owner instanceof ElggGroup )
	{
		$options['container_guid'] = $owner_guid;
	}
	elseif ( $owner instanceof ElggUser )
	{
		$options['owner_guid'] = $owner_guid;
	}

	if ( $lower )
	{
		$options['created_time_lower'] = $lower;
	}

	if ( $upper )
	{
		$options['created_time_upper'] = $upper;
	}

	$content = elgg_list_entities($options);

	$title = elgg_echo('date:month:' . date('m', $lower), array(date('Y', $lower)));

	return [
		'content' => $content,
		'title' => $title,
		'filter' => '',
	];
}

/**
* Get page components to edit/create a project.
*
* @param string  $page     'edit' or 'add'
* @param int     $guid     GUID of project or container
* @return array
*/
function ychange_project_get_page_content_edit($page, $guid = 0) {
	$return = [
		'filter' => '',
	];

	$vars = [];
	$vars['id'] = 'project-edit';
	$vars['class'] = 'elgg-form-alt';
	$vars['enctype'] = 'multipart/form-data';

	$sidebar = '';
	if ( $page == 'edit' )
	{
		$project = get_entity((int)$guid);

		$title = elgg_echo('ychange:project:edit');

		if ( elgg_instanceof($project, 'object', 'project') && $project->canEdit() )
		{
			$vars['entity'] = $project;

			$title .= ": \"$project->title\"";

			$body_vars = ychange_project_prepare_form_vars($project);

			elgg_push_breadcrumb($project->title, $project->getURL());
			elgg_push_breadcrumb(elgg_echo('edit'));

			$content = elgg_view_form('project/save', $vars, $body_vars);
		}
		else
		{
			$content = elgg_echo('ychange:project:error:cannot_edit_project');
		}
	}
	else
	{
		elgg_push_breadcrumb(elgg_echo('ychange:project:add'));
		$body_vars = ychange_project_prepare_form_vars(null);

		$title = elgg_echo('ychange:project:add');
		$content = elgg_view_form('project/save', $vars, $body_vars);
	}

	$return['title'] = $title;
	$return['content'] = $content;
	$return['sidebar'] = $sidebar;
	return $return;
}

/**
* Pull together project variables for the save form
*
* @param ElggYchangeProject $project
* @return array
*/
function ychange_project_prepare_form_vars($project = NULL) {

	// input names => defaults
	$values = array(
		'title' => NULL,
		'description' => NULL,
		'topic' => NULL,
		'interesting' => NULL,
		'useful' => NULL,
		'results' => NULL,
		'sources' => NULL,
		'location' => NULL,
		'language' => NULL,
		'category' => NULL,
		'container_guid' => NULL,
		'guid' => NULL,
	);

	if ( $project )
	{
		foreach ( array_keys($values) as $field )
		{
			if ( isset($project->$field) )
			{
				$values[$field] = $project->$field;
			}
		}
	}

	if ( elgg_is_sticky_form('project') )
	{
		$sticky_values = elgg_get_sticky_values('project');
		foreach ( $sticky_values as $key => $value )
		{
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('project');

	if ( !$project )
	{
		return $values;
	}

	return $values;
}

/**
 * Returns an array of Project category options
 * @return array
 */
function ychange_project_categories_options()
{
	return [
		'biodiversity_and_sustainability' => elgg_echo('ychange:project:category:biodiversity_and_sustainability'),
		'disaster_resilience' => elgg_echo('ychange:project:category:disaster_resilience'),
		'energy_and_resources_management' => elgg_echo('ychange:project:category:energy_and_resources_management'),
		'food_security_and_sustainable_agriculture' => elgg_echo('ychange:project:category:food_security_and_sustainable_agriculture'),
		'infrastructure_and_transport_management' => elgg_echo('ychange:project:category:infrastructure_and_transport_management'),
		'public_health_surveillance' => elgg_echo('ychange:project:category:public_health_surveillance'),
		'sustainable_urban_development' => elgg_echo('ychange:project:category:sustainable_urban_development'),
		'water_resources_management' => elgg_echo('ychange:project:category:water_resources_management'),
		'other' => elgg_echo('ychange:project:category:other'),
	];
}

/**
 * Returns category option title or passed key
 * @param  string $key Opton key
 * @return string      Translated title or passed key
 */
function ychange_project_category_by_key(string $key)
{
	$options = ychange_project_categories_options();

	if ( array_key_exists($key, $options) )
	{
		return $options[$key];
	}

	return $key;
}
