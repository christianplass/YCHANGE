<?php
/**
* Teacher role helper functions
*
* @package Ychange
*/

/**
 * Determines if logged in user is a teacher or admin
 * @return boolean
 */
function ychange_is_teacher_or_admin_logged_in()
{
    return elgg_is_admin_logged_in() || ( elgg_is_logged_in() && ychange_is_teacher(elgg_get_logged_in_user_entity()) );
}

/**
 * Determines if user has a teacher role
 * @param  \ElggUser $user User object
 * @return boolean
 */
function ychange_is_teacher(\ElggUser &$user)
{
    return $user->teacher && $user->teacher === 'yes';
}

/**
 * Tries to set teacher role to a user
 * @param  \ElggUser $user User object
 * @return boolean
 */
function ychange_make_teacher(\ElggUser &$user)
{
    if ( !elgg_is_admin_logged_in() )
    {
        return false;
    }

    $user->teacher = 'yes';

    if ( $user->save() )
    {
        elgg_trigger_event('add:role:teacher', 'user', $user);

        return true;
    }

    return false;
}

/**
 * Tries to remove teacher role from a user
 * @param  \ElggUser $user User object
 * @return boolean
 */
function ychange_remove_teacher(\ElggUser &$user)
{
    if ( !elgg_is_admin_logged_in() )
    {
        return false;
    }

    $user->teacher = 'no';

    if ( $user->save() )
    {
        elgg_trigger_event('remove:role:teacher', 'user', $user);

        return true;
    }

    return false;
}

/**
 * Determines if user is allowed to create groups
 * @param  \ElggUser $user User object
 * @return boolean
 */
function ychange_can_create_groups(\ElggUser $user)
{
    return elgg_is_admin_logged_in() || ychange_is_teacher($user);
}

/**
 * Determines if current user has teacher request associated
 * @param  \ElggUser $user User object
 * @return boolean
 */
function ychange_has_teacher_request(\ElggUser &$user)
{
    return $user->request_teacher === 'yes';
}

/**
 * Tries to remove teacher request flag
 * @param  \ElggUser $user User object
 * @return boolean
 */
function ychange_reject_teacher_request(\ElggUser &$user)
{
    if ( !elgg_is_admin_logged_in() )
    {
        return false;
    }

    if ( !ychange_has_teacher_request($user) )
    {
        return false;
    }

    $user->deleteMetadata('request_teacher');

    elgg_trigger_event('reject:teacher:role:request', 'user', $user);

    return true;
}
