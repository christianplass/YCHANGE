<?php
/**
 * Project filters override
 *
 * @package Ychange
 */
$context = elgg_extract('context', $vars, elgg_get_context());
// register the default content filters
if ( elgg_is_logged_in() && $context ) {
        $username = elgg_get_logged_in_user_entity()->username;
        $filter_context = elgg_extract('filter_context', $vars, 'all');

        // generate a list of default tabs
        $tabs = [
                'all' => [
                        'text' => elgg_echo('all'),
                        'href' => (isset($vars['all_link'])) ? $vars['all_link'] : "$context/all",
                        'selected' => ($filter_context == 'all'),
                        'priority' => 200,
                ],
                'mine' => [
                        'text' => elgg_echo('mine'),
                        'href' => (isset($vars['mine_link'])) ? $vars['mine_link'] : "$context/owner/$username",
                        'selected' => ($filter_context == 'mine'),
                        'priority' => 300,
                ],
        ];

        foreach ($tabs as $name => $tab) {
                $tab['name'] = $name;
                elgg_register_menu_item('filter', $tab);
        }
}

echo elgg_view('page/layouts/elements/filter', $vars);
