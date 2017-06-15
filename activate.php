<?php
/**
 * Register the ElggYchangeProject class for the object/project subtype
 */

if ( get_subtype_id('object', 'project') )
{
    update_subtype('object', 'project', 'ElggYchangeProject');
}
else
{
    add_subtype('object', 'project', 'ElggYchangeProject');
}

if ( get_subtype_id('object', 'satellite_image') )
{
    update_subtype('object', 'satellite_image', 'ElggFile');
}
else
{
    add_subtype('object', 'satellite_image', 'ElggFile');
}
