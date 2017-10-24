<?php
/**
 * Displays list of users with teacher requests
 *
 * @package Ychange
 */

$users = elgg_list_entities_from_metadata([
    'type' => 'user',
    'subtype' => null,
    'full_view' => false,
    'metadata_names' => ['request_teacher'],
    'metadata_values' => ['yes'],
    'list-class' => 'elgg-user-teacher',
]);

echo elgg_view_module('inline', elgg_echo('ychange:admin:statistics:label:teacher_requests'), $users);
