<?php
/**
 * Displays list of teachers
 *
 * @package Ychange
 */

$teachers = elgg_list_entities_from_metadata([
    'type' => 'user',
    'subtype' => null,
    'full_view' => false,
    'metadata_names' => ['teacher'],
    'metadata_values' => ['yes'],
    'list-class' => 'elgg-user-teacher',
]);

echo elgg_view_module('inline', elgg_echo('ychange:admin:statistics:label:teachers'), $teachers);
