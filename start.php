<?php

elgg_register_event_handler('init', 'system', 'ychange_init');

function ychange_init()
{
    elgg_extend_view('elgg.css', 'ychange/front_page/index.css');
}
