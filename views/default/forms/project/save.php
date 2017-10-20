<?php
/**
 * Edit project form
 *
 * @package Ychange
 */

$project = get_entity($vars['guid']);
$vars['entity'] = $project;

$action_buttons = '';
$delete_link = '';

if ( $vars['guid'] )
{
	// add a delete button if editing
	$delete_url = "action/project/delete?guid={$vars['guid']}";
	$delete_link = elgg_view('output/url', [
		'href' => $delete_url,
		'text' => elgg_echo('delete'),
		'class' => 'elgg-button elgg-button-delete float-alt',
		'confirm' => true,
	]);
}

$save_button = elgg_view('input/submit', [
	'value' => elgg_echo('save'),
	'name' => 'save',
]);
$action_buttons = $save_button . $delete_link;

// Get post_max_size and upload_max_filesize
$post_max_size = elgg_get_ini_setting_in_bytes('post_max_size');
$upload_max_filesize = elgg_get_ini_setting_in_bytes('upload_max_filesize');

// Determine the correct value
$max_upload = $upload_max_filesize > $post_max_size ? $post_max_size : $upload_max_filesize;

$upload_limit = elgg_view('elements/forms/help', [
	'help' => elgg_echo('ychange:upload_limit', [elgg_format_bytes($max_upload)]),
]);

$post_limit = '';
if ( $post_max_size > $upload_max_filesize )
{
	$post_limit = elgg_view('elements/forms/help', [
		'help' => elgg_echo('ychange:post_limit', [elgg_format_bytes($post_max_size)]),
	]);
}

$icon_label = elgg_echo('ychange:project:icon');
$icon_input = elgg_view('input/file', [
	'name' => 'icon',
	'id' => 'project_icon',
	'accept' => 'image/*',
]);

$satellite_images_label = elgg_echo('ychange:project:satellite_images');
$satellite_images_input = elgg_view('input/file', [
	'name' => 'satellite_images[]',
	'id' => 'project_satellite_images',
	'accept' => 'image/*',
	'multiple' => true,
]);
$uploaded_satellite_images = '';
if ( elgg_instanceof($project, 'object') && $project->hasSatelliteImages() )
{
	foreach ( $project->getSatelliteImages() as $image )
	{
		$uploaded_satellite_images .= '<div class="ychange-satellite-image-edit">';
		$uploaded_satellite_images .= elgg_view_entity_icon($image, 'medium', [
			'href' => elgg_get_download_url($image),
			'width' => '',
			'height' => '',
			'img_class' => 'ychange-satellite-image',
			'link_class' => 'ychange-satellite-link',
		]);
		$uploaded_satellite_images .= elgg_view('input/checkbox', [
			'name' => 'removed_satellite_images[]',
			'value' => $image->guid,
			'default' => false,
			'label' => elgg_echo('delete'),
		]);
		$uploaded_satellite_images .= '</div>';
	}
}

$title_label = elgg_echo('ychange:project:name');
$title_input = elgg_view('input/text', [
	'name' => 'title',
	'id' => 'project_title',
	'value' => $vars['title'],
]);

$topic_label = elgg_echo('ychange:project:topic');
$topic_input = elgg_view('input/text', [
	'name' => 'topic',
	'id' => 'project_topic',
	'value' => $vars['topic'],
]);

$interesting_label = elgg_echo('ychange:project:interesting');
$interesting_input = elgg_view('input/longtext', [
	'name' => 'interesting',
	'id' => 'project_interesting',
	'value' => $vars['interesting'],
]);

$useful_label = elgg_echo('ychange:project:useful');
$useful_input = elgg_view('input/longtext', [
	'name' => 'useful',
	'id' => 'project_useful',
	'value' => $vars['useful'],
]);

$results_label = elgg_echo('ychange:project:results');
$results_input = elgg_view('input/longtext', [
	'name' => 'results',
	'id' => 'project_results',
	'value' => $vars['results'],
]);

$sources_label = elgg_echo('ychange:project:sources');
$sources_input = elgg_view('input/longtext', [
	'name' => 'sources',
	'id' => 'project_sources',
	'value' => $vars['sources'],
]);

$location_label = elgg_echo('ychange:project:location');
$location_input = elgg_view('input/text', [
	'name' => 'location',
	'id' => 'project_location',
	'value' => $vars['location'],
	'class' => 'ychange-geolocation',
]);

$category_label = elgg_echo('ychange:project:category');
$category_input = elgg_view('input/select', [
	'name' => 'category',
	'id' => 'project_category',
	'value' => $vars['category'],
	'options_values' => ychange_project_categories_options(),
]);

$access_style = ychange_is_teacher_or_admin_logged_in() ? '' : 'display:none;';
$access_label = elgg_echo('access');
$access_input = elgg_view('input/access', [
	'name' => 'access_id',
	'id' => 'access_id',
	'value' => elgg_extract('access_id', $vars, get_user_access_collections(elgg_get_page_owner_guid())[0]->id),
]);

// hidden inputs
$container_guid_input = elgg_view('input/hidden', ['name' => 'container_guid', 'value' => elgg_get_page_owner_guid()]);
$guid_input = elgg_view('input/hidden', ['name' => 'guid', 'value' => $vars['guid']]);


echo <<<___HTML

$draft_warning

<div>
	<label for="project_icon">$icon_label</label>
	$icon_input
	$upload_limit
</div>

<div>
	<label for="project_satellite_images">$satellite_images_label</label>
	$satellite_images_input
	$upload_limit
	$post_limit
	<div>
	    $uploaded_satellite_images
	</div>
</div>

<div class="ychange-required">
	<label for="project_name">$title_label</label>
	$title_input
</div>

<div class="ychange-required">
	<label for="project_topic">$topic_label</label>
	$topic_input
</div>

<div>
	<label for="project_interesting">$interesting_label</label>
	$interesting_input
</div>

<div>
	<label for="project_useful">$useful_label</label>
	$useful_input
</div>

<div>
	<label for="project_results">$results_label</label>
	$results_input
</div>

<div>
	<label for="project_sources">$sources_label</label>
	$sources_input
</div>

<div>
	<label for="project_location">$location_label</label>
	$location_input
</div>

<div>
    <label for="project_category">$category_label</label>
    $category_input
</div>

<div style="$access_style">
    <label for="access_id">$access_label</label>
	$access_input
</div>

$guid_input
$container_guid_input

___HTML;

$footer = <<<___HTML
$action_buttons
___HTML;

elgg_set_form_footer($footer);
