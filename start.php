<?php

// Register plugin initialization hook
elgg_register_event_handler('init', 'system', 'ychange_init');

/**
 * Overrides current htmLawed settings allowing embedding.
 * @param  string $hook  Hook name
 * @param  string $type  Hook type
 * @param  array $items  htmLawed config
 * @param  array $vars   Additional variables
 * @return array         Modified configuration
 */
function ychange_htmlawed_config($hook, $type, $items, $vars)
{
    $items['elements'] = '*+iframe';
    return $items;
}

function ychange_tutorials_page_handler($page)
{
    if ( elgg_is_admin_logged_in() ) {
        elgg_register_menu_item('title', array(
            'name' => 'edit',
            'text' => elgg_echo('edit'),
            'href' => "admin/plugin_settings/ychange",
            'link_class' => 'elgg-button elgg-button-action',
        ));
    }

    echo elgg_view_resource('ychange/tutorials');

    return true;
}

/**
 * Initializes plugin, registering any logics or overrides needed
 * @return void
 */
function ychange_init()
{
    elgg_extend_view('elgg.css', 'ychange/css');
    elgg_extend_view('elgg.css', 'ychange/front_page/index.css');

    elgg_register_plugin_hook_handler('config', 'htmlawed', 'ychange_htmlawed_config');

    $item = new ElggMenuItem('tutorials', elgg_echo('ychange:site:menu:video_tutorials'), 'tutorials');
    elgg_register_menu_item('site', $item);

    elgg_register_page_handler('tutorials', 'ychange_tutorials_page_handler');
}
