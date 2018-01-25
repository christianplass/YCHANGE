<?php
/**
 * Settings functions
 *
 * @package Ychange
 */

/**
 * Returns Ychange plugin setting value for current language if present.
 * Defaults to value without language suffix that should hold the English value.
 * @param  string $name Setting name
 * @return mixed        Setting value
 */
function ychange_get_translated_plugin_setting($name)
{
    $currentLanguage = get_current_language();

    if ( $currentLanguage === 'en' )
    {
        return elgg_get_plugin_setting($name, 'ychange');
    }

    $setting = elgg_get_plugin_setting("$name:$currentLanguage", 'ychange');

    if ( $setting )
    {
        return $setting;
    }

    return elgg_get_plugin_setting($name, 'ychange');
}
