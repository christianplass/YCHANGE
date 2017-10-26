<?php
/**
 * Partner input
 *
 * @package Ychange
 */

elgg_load_library('elgg:ychange:options');

$defaults = [
    'options_values' => ychange_get_partner_options(),
    'required' => true,
];
$vars = array_merge($defaults, $vars);

echo elgg_view('input/dropdown', $vars);
