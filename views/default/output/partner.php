<?php
/**
 * Partner location output
 *
 * @package Ychange
 *
 * @uses $vars['value'] Partner location key
 */

 elgg_load_library('elgg:ychange:options');

 $options = ychange_get_partner_options();

 echo elgg_view('output/text', [
     'value' => array_key_exists($vars['value'], $options) ? $options[$vars['value']] : $vars['value'],
 ]);
