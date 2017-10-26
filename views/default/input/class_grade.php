<?php
/**
 * Class grade input
 *
 * @package Ychange
 */

$defaults = [
    'min' => 0,
    'max' => 12,
    'required' => true,
];
$vars = array_merge($defaults, $vars);

echo elgg_view('input/number', $vars);
