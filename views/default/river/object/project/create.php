<?php
/**
 * Project river view.
 *
 * @package Ychange
 */

$item = $vars['item'];
/* @var ElggRiverItem $item */

$object = $item->getObjectEntity();

$excerpt = $object->topic;
$excerpt = strip_tags($excerpt);
$excerpt = elgg_get_excerpt($excerpt);

echo elgg_view('river/elements/layout', [
	'item' => $vars['item'],
	'message' => $excerpt,
]);
