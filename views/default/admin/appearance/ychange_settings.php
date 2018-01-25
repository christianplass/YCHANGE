<?php
/**
 * Admin section for editing settings
 */

$type = get_input('type', 'about');

echo elgg_view('ychange/settings/menu', array('type' => $type));

echo elgg_view_form('ychange/settings/edit', array('class' => 'elgg-form-settings'), array('type' => $type));
