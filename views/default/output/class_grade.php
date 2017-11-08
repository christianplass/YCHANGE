<?php
/**
 * Class grade output
 *
 * @package Ychange
 *
 * @uses $vars['value'] Class grade value
 */

elgg_load_library('elgg:ychange:options');

$options = ychange_get_class_grade_options();

echo elgg_view('output/text', [
    'value' => array_key_exists($vars['value'], $options) ? $options[$vars['value']] : $vars['value'],
]);
