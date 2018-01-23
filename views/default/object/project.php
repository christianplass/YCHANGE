<?php
/**
 * View for project objects
 *
 * @package Ychange
 */

$full = elgg_extract('full_view', $vars, FALSE);
$project = elgg_extract('entity', $vars, FALSE);

if ( !$project )
{
	return TRUE;
}

$owner = $project->getOwnerEntity();
$owner_icon = elgg_view_entity_icon($owner, 'tiny');

$vars['owner_url'] = "projects/owner/$owner->username";
$by_line = elgg_view('page/elements/by_line', $vars);

$subtitle = "$by_line";

$metadata = '';
if ( !elgg_in_context('widgets') )
{
	// only show entity menu outside of widgets
	$metadata = elgg_view_menu('entity', [
		'entity' => $vars['entity'],
		'handler' => 'projects',
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	]);
}

if ( $full )
{
	$icon = '';
	if ( $project->hasIcon('large') )
	{
		$icon = elgg_view_entity_icon($project, 'large', [
			'href' => '',
			'width' => '',
			'height' => '',
		]);
		$icon = "<div class=\"ychange-project-icon\">$icon</div>";
	}

	$images = '';
	if ( $project->hasSatelliteImages() )
	{
		$images .= '<div>';
		$images .= '<label class="ychnage-project-label">' . elgg_echo('ychange:project:satellite_images') . '</label>';
		foreach ( $project->getSatelliteImages() as $image )
		{
			$imageIcon = elgg_view_entity_icon($image, 'medium', [
				'href' => elgg_get_download_url($image),
				'width' => '',
				'height' => '',
				'img_class' => 'ychange-satellite-image',
				'link_class' => 'ychange-satellite-link',
			]);

			$images .= $imageIcon;
		}
		$images .= '</div>';
	}

	$topic = '';
	if ( $project->topic )
	{
		$topic_output .= elgg_view('output/text', [
			'value' => $project->topic,
		]);
		$topic = '<label class="ychnage-project-label">' . elgg_echo('ychange:project:topic') . '</label>';
		$topic .= "<div class=\"elgg-output\">$topic_output</div>";
	}

	$interesting = '';
	if ( $project->interesting )
	{
		$interesting = '<label class="ychnage-project-label">' . elgg_echo('ychange:project:interesting') . '</label>';
		$interesting .= elgg_view('output/longtext', [
			'value' => $project->interesting,
			'class' => 'project',
		]);
	}

	$useful = '';
	if ( $project->useful )
	{
		$useful = '<label class="ychnage-project-label">' . elgg_echo('ychange:project:useful') . '</label>';
		$useful .= elgg_view('output/longtext', [
			'value' => $project->useful,
			'class' => 'project',
		]);
	}

	$sources = '';
	if ( $project->sources )
	{
		$sources = '<label class="ychnage-project-label">' . elgg_echo('ychange:project:sources') . '</label>';
		$sources .= elgg_view('output/longtext', [
			'value' => $project->sources,
			'class' => 'project',
		]);
	}

	$location = '';
	if ( $project->location )
	{
		$key = elgg_get_plugin_setting('google_maps_key', 'ychange');
		$location_output = elgg_view('output/text', [
			'value' => $project->location,
		]);
		$location = '<label class="ychnage-project-label">' . elgg_echo('ychange:project:location') . '</label>';
		$location .= "<div class=\"elgg-output\">$location_output</div>";
		if ( $project->hasCorrectGeolocation() )
		{
			$locationBaseUrl = GOOGLE_MAPS_STATIC_URL;
			$location .= "<img src=\"{$locationBaseUrl}{$project->location}&amp;zoom=10&amp;size=320x240&amp;maptype=hybrid&amp;markers=color:red%7C{$project->location}&amp;key={$key}\" alt=\"map\">";

		}

	}

	$language = '';
	if ( $project->language )
	{
		$language_output = elgg_view('output/text', [
			'value' => ychage_get_option_value_by_key($project->language, ychange_get_language_options()),
		]);
		$language = '<label class="ychnage-project-label">' . elgg_echo('ychange:project:language') . '</label>';
		$language .= "<div class=\"elgg-output\">$language_output</div>";
	}

	$category = '';
	if ( $project->category )
	{
		$category_output = elgg_view('output/text', [
			'value' => ychange_project_category_by_key($project->category),
		]);
		$category = '<label class="ychnage-project-label">' . elgg_echo('ychange:project:category') . '</label>';
		$category .= "<div class=\"elgg-output\">$category_output</div>";
	}

	$body = "$icon $images $topic $interesting $useful $results $sources $location $language $category";

	$params = [
		'entity' => $project,
		'title' => false,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
	];
	$params = $params + $vars;
	$summary = elgg_view('object/elements/summary', $params);

	echo elgg_view('object/elements/full', [
		'entity' => $project,
		'summary' => $summary,
		'icon' => $owner_icon,
		'body' => $body,
	]);
}
else
{
	// brief view
	$icon = elgg_view_entity_icon($project, 'tiny', $vars);

	$params = [
		'entity' => $project,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'content' => elgg_view('output/text', [
			'value' => $project->topic,
		]),
		'icon' => $project->hasIcon('tiny') ? $icon : $owner_icon,
	];
	$params = $params + $vars;
	echo elgg_view('object/elements/summary', $params);

}
