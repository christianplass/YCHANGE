<?php
$languages = $vars['languages'];
$language = $vars['language'];
$categories = $vars['categories'];
$category = $vars['category'];


$fields = [
    [
        '#type' => 'dropdown',
        '#label' => elgg_echo('ychange:choose:language'),
        'name' => 'language',
        'options_values' => $languages,
        'align' => 'horizontal',
        'value' => $language,
    ],
    [
        '#type' => 'dropdown',
        '#label' => elgg_echo('ychange:choose:category'),
        'name' => 'category',
        'options_values' => $categories,
        'align' => 'horizontal',
        'value' => $category,
    ],
];

$body = '';
foreach ( $fields as $field ) {
    $body .= elgg_view_field($field);
}

$body .= elgg_view('input/submit', [
	'value' => elgg_echo('ychange:explore'),
	'name' => 'explore',
]);

$form = elgg_view('input/form', [
    'body' => $body,
    'action' => 'projects/explore',
    'action_name' => 'explore',
    'method' => 'get',
    'disable_security' => true,
    'class' => 'ychange-projecrs-explore',
]);

echo elgg_view_module('main', '', $form, []);
