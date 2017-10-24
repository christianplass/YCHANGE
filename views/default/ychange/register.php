<?php
/**
 * Register form extension
 *
 * @package Ychange
 */

$fields = [
    [
        '#type' => 'radio',
        '#label' => elgg_echo('ychange:gender'),
        'name' => 'gender',
        'options' => [
            elgg_echo('ychange:gender:male') => 'male',
            elgg_echo('ychange:gender:female') => 'female',
            elgg_echo('ychange:gender:other') => 'other',
        ],
        'align' => 'horizontal',
        'required' => true,
        'value' => elgg_extract('gender', $vars, 'male'),
    ],
    [
        '#type' => 'dropdown',
        '#label' => elgg_echo('ychange:location'),
        'name' => 'location',
        'options_values' => [
            'germany' => elgg_echo('ychange:location:germany'),
            'czech' => elgg_echo('ychange:location:czech'),
            'estonia' => elgg_echo('ychange:location:estonia'),
            'switzerland' => elgg_echo('ychange:location:switzerland'),
        ],
        'required' => true,
        'value' => elgg_extract('location', $vars),
    ],
    [
        '#type' => 'number',
        '#label' => elgg_echo('ychange:class_grade'),
        'name' => 'class_grade',
        'min' => 0,
        'max' => 12,
        'required' => true,
        'value' => elgg_extract('class_grade', $vars, ''),
    ],
    [
        '#type' => 'checkbox', // Teacher, student
        'name' => 'request_teacher',
        'value' => 'yes',
        'default' => 'no',
        'checked' => (elgg_extract('request_teacher', $vars) === 'yes') ? true : false,
        'label' => elgg_echo('ychange:request:teacher:role'),
    ],
];

foreach ($fields as $field)
{
    echo elgg_view_field($field);
}
