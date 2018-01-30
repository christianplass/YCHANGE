<?php

echo elgg_view('output/longtext', [
    'value' => elgg_echo('ychange:project:list:questionnaire:text', [
        '<span class="elgg-icon fa fa-eye"></span>',
        ychange_project_get_teacher_questionnaire_url(),
        ychange_project_get_student_questionnaire_url(),
    ]),
    'class' => 'ychange-questionnaire-info-box',
    'parse_urls' => false,
    'parse_emails' => false,
    'sanitize' => false,
    'autop' => false,

]);
