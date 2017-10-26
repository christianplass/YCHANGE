<?php
/**
 * Gender input
 *
 * @package Ychange
 */

elgg_load_library('elgg:ychange:options');

$defaults = [
    'options' => ychange_get_gender_options(true),
    'align' => 'horizontal',
    'required' => true,
];
$vars = array_merge($defaults, $vars);

echo elgg_view('input/radio', $vars);
