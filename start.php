<?php

define('RECAPTCHA_JS_URL', 'https://www.google.com/recaptcha/api.js');
define('RECAPTCHA_VERIFY_URL', 'https://www.google.com/recaptcha/api/siteverify');
define('GOOGLE_MAPS_JS_URL', 'https://maps.googleapis.com/maps/api/js?key=');
define('GOOGLE_MAPS_STATIC_URL', 'https://maps.googleapis.com/maps/api/staticmap?center=');
define('GOOGLE_ANALYTICS_JS_URL', 'https://www.googletagmanager.com/gtag/js?id=');

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

/**
 *  Video tutorials page handler
 * @param  array $page Page path parts
 * @return bool
 */
function ychange_tutorials_page_handler($page)
{
    if ( elgg_is_admin_logged_in() ) {
        elgg_register_menu_item('title', array(
            'name' => 'edit',
            'text' => elgg_echo('edit'),
            'href' => "admin/appearance/ychange_settings?type=tutorials",
            'link_class' => 'elgg-button elgg-button-action',
        ));
    }

    elgg_require_js("ychange/video_tutorials");
    echo elgg_view_resource('ychange/tutorials');

    return true;
}

/**
 * Projects page handler
 * @param  array $page Page path parts
 * @return bool
 */
function ychange_project_page_handler($page)
{
    elgg_load_library('elgg:ychange:project');
    elgg_load_library('elgg:ychange:options');

    // push all projects breadcrumb
    elgg_push_breadcrumb(elgg_echo('ychange:projects'), 'projects/explore');

    $page_type = elgg_extract(0, $page, 'explore');
    $resource_vars = [
        'page_type' => $page_type,
    ];

    switch ($page_type)
    {
        case 'owner':
        $resource_vars['username'] = elgg_extract(1, $page);

        echo elgg_view_resource('project/owner', $resource_vars);
        break;
        case 'archive':
        $resource_vars['username'] = elgg_extract(1, $page);
        $resource_vars['lower'] = elgg_extract(2, $page);
        $resource_vars['upper'] = elgg_extract(3, $page);

        echo elgg_view_resource('project/archive', $resource_vars);
        break;
        case 'view':
        $resource_vars['guid'] = elgg_extract(1, $page);

        echo elgg_view_resource('project/view', $resource_vars);
        break;
        case 'add':
        elgg_load_js('googleMaps');
        elgg_require_js("ychange/google_maps");
        $resource_vars['guid'] = elgg_extract(1, $page);

        echo elgg_view_resource('project/add', $resource_vars);
        break;
        case 'edit':
        elgg_load_js('googleMaps');
        elgg_require_js("ychange/google_maps");
        $resource_vars['guid'] = elgg_extract(1, $page);
        $resource_vars['revision'] = elgg_extract(2, $page);

        echo elgg_view_resource('project/edit', $resource_vars);
        break;
        case 'group':
        $resource_vars['group_guid'] = elgg_extract(1, $page);
        $resource_vars['subpage'] = elgg_extract(2, $page);
        $resource_vars['lower'] = elgg_extract(3, $page);
        $resource_vars['upper'] = elgg_extract(4, $page);

        echo elgg_view_resource('project/group', $resource_vars);
        break;
        case 'all':
        echo elgg_view_resource('project/all', $resource_vars);
        break;
        case 'explore':
        echo elgg_view_resource('project/explore', $resource_vars);
        break;
        default:
        return false;
    }

    return true;
}

/**
 * Hadle Project entity URL
 * @param string $hook  Hook name
 * @param string $type  Entity type
 * @param string $url   URL
 * @param array $params Parameters
 */
function ychange_project_set_url($hook, $type, $url, $params)
{
    $entity = $params['entity'];
    if ( elgg_instanceof($entity, 'object', 'project') )
    {
        $friendly_title = elgg_get_friendly_title($entity->title);
        return "projects/view/{$entity->guid}/$friendly_title";
    }
}

/**
* Add a menu item to an ownerblock
*/
function ychange_project_owner_block_menu($hook, $type, $return, $params)
{
    $entity = elgg_extract('entity', $params);
    if ( $entity instanceof \ElggUser )
    {
        $url = "projects/owner/{$entity->username}";
        $return[] = new \ElggMenuItem('project', elgg_echo('ychange:projects'), $url);

    }
    elseif ( $entity instanceof \ElggGroup )
    {
        if ( $entity->project_enable != "no" )
        {
            $url = "projects/group/{$entity->guid}/all";
            $return[] = new \ElggMenuItem('project', elgg_echo('ychange:project:group'), $url);
        }
    }

    return $return;
}

/**
 * Setup project entity menu
 */
function ychange_project_entity_menu_setup($hook, $type, $return, $params)
{
    if ( elgg_in_context('widgets') )
    {
        return $return;
    }

    $entity = $params['entity'];
    $handler = elgg_extract('handler', $params, false);
    if ( $handler != 'projects' )
    {
        return $return;
    }

    return $return;
}

/**
 * reCaptcha action hook
 * @param  string $hook         Hook name
 * @param  string  $entity_type Type
 * @param  mixed $returnvalue   Value
 * @param  mixed $params        Params
 * @return bool
 */
function ychange_captcha_verify_action_hook($hook, $entity_type, $returnvalue, $params)
{
  $gRecaptchaResponse = get_input('g-recaptcha-response');
  $remoteIp = _elgg_services()->request->getClientIp();

  if ( $gRecaptchaResponse )
  {
      // TODO Would have included a module for that, need to check if plugins
      // can define vendor dependencies
      $postData = [
          'secret' => elgg_get_plugin_setting('recaptcha_secret', 'ychange'),
          'response' => $gRecaptchaResponse,
          'remoteip' => $remoteIp,
      ];
      $options = array(
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($postData, '', '&'),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
            CURLINFO_HEADER_OUT => false,
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true
        );

      $ch = curl_init(RECAPTCHA_VERIFY_URL);
      curl_setopt_array($ch, $options);
      $response = curl_exec($ch);
      curl_close($ch);

      $parsedResponse = json_decode($response, true);
        if ( $parsedResponse['success'] )
        {
            return true;
        }
        else
        {
            _elgg_services()->logger->error( print_r( $parsedResponse['error-codes'], true ) );
        }
  }

  register_error(elgg_echo('ychange:captcha:captchafail'));

  return false;
}

/**
 * Add elements to page head
 * @param  string $hook Hook name
 * @param  string $type Hook type
 * @param  array $data Data array
 * @return array       Array with additional heade elements
 */
function ychange_head($hook, $type, $data)
{
    $removables = array('apple-touch-icon', 'icon-vector', 'icon-16', 'icon-32', 'icon-64', 'icon-128');

    foreach($removables as $removable)
    {
        if ( isset($data['links'][$removable]) )
        {
            unset($data['links'][$removable]);
        }
    }

    $data['links']['icon-ico'] = array(
        'rel' => 'icon',
        'href' => elgg_get_simplecache_url('favicons/favicon.ico'),
	  );

    return $data;
}

/**
 * Adds teacher role administration actions to user menu for administrators
 * @param  string $hook  Hook name
 * @param  string $type  Hook type
 * @param  array $return An array of menu items
 * @param  array $params An array of parameters
 * @return array         An array of menu items
 */
function ychange_menu_user_hover($hook, $type, $return, $params)
{
    if ( elgg_is_admin_logged_in() )
    {
        $user = elgg_extract('entity', $params);
        $isTeacher = ychange_is_teacher($user);

        $actionName = $isTeacher ? 'removeteacher' : 'maketeacher';
        $actionText = elgg_echo( $isTeacher ? 'ychange:user:action:remove:teacher' : 'ychange:user:action:make:teacher' );
        $actionUrl = elgg_add_action_tokens_to_url('action/admin/user/' . $actionName . '?guid=' . $user->guid);

        $menuItem = new \ElggMenuItem($actionName, $actionText, $actionUrl);
        $menuItem->setSection('admin');
        $menuItem->setConfirmText(true);

        $return[] = $menuItem;

        if ( $user->request_teacher === 'yes' )
        {
            $rejectMenuItem = new \ElggMenuItem('rejectteacher', elgg_echo('ychange:user:action:reject:teacher'), elgg_add_action_tokens_to_url('action/admin/user/rejectteacher?guid=' . $user->guid));
            $rejectMenuItem->setSection('admin');
            $rejectMenuItem->setConfirmText(true);

            $return[] = $rejectMenuItem;
        }
    }

    return $return;
}

/**
 * Removed group add button from title menu for normal users
 * @param  string $hook   Hook name
 * @param  string $type   Hook type
 * @param  array  $return An array of menu items
 * @param  array  $params An array of parameters
 * @return array          An array of menu items
 */
function ychange_title_menu_handler($hook, $type, $return, $params)
{
    if ( elgg_is_logged_in() && elgg_in_context('groups') && !ychange_can_create_groups(elgg_get_logged_in_user_entity()) )
    {
        if ( is_array($return) && count($return) > 0 )
        {
            foreach ($return as $index => $item)
            {
                if ( $item->getName() === 'add' )
                {
                    unset($return[$index]);
                }
            }
        }
    }

    return $return;
}

/**
 * Prevent normal user from seeing group ceate page
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param string $return Returned HTML
 * @param array $params  An array of parameters
 * @return string        Returned HTML
 */
function ychange_alter_group_add_view($hook, $type, $return, $params)
{
    if ( elgg_is_logged_in() && !ychange_can_create_groups(elgg_get_logged_in_user_entity()) )
    {
        elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());
        $title = elgg_echo('groups:add');
        elgg_push_breadcrumb($title);
        $params = [
            'content' => elgg_echo('groups:cantcreate'),
            'title' => $title,
            'filter' => '',
        ];
        $body = elgg_view_layout('content', $params);

        return elgg_view_page($title, $body);
    }

    return $return;
}

/**
 * Prevent normal user from being able to run groups/edit action
 * @param  string $hook    Hook name
 * @param  string $type    Hook type
 * @param  boolean $return Allowed or not
 * @param  array $params   An aeeay of parameters
 * @return boolean
 */
function ychange_group_edit_action_hook($hook, $type, $return, $params)
{
    if ( elgg_is_logged_in() && !ychange_can_create_groups(elgg_get_logged_in_user_entity()) )
    {
        return false;
    }

    return $return;
}

/**
 * Handle project entity menu
 * @param  string $hook   Hook name
 * @param  string $type   Hook type
 * @param  array $return  An array of menu items
 * @param  array $params  An array of parameters
 * @return array          An array of menu items
 */
function ychnage_project_menu_handler($hook, $type, $return, $params)
{
    if ( elgg_in_context('widgets') )
    {
        return $return;
    }

    $entity = elgg_extract('entity', $params);

    if ( ( $entity->getSubtype() === 'project' ) && $entity->canPublishAndUnpublish() )
    {
        $isPublic = $entity->getAccessID() === ACCESS_PUBLIC;

        $actionName = $isPublic ? 'unpublish' : 'publish';
        $tooptipText = elgg_echo( $isPublic ? 'ychange:project:action:unpublish' : 'ychange:project:action:publish' );
        $actionText = '<span class="elgg-icon fa ' . ( $isPublic ? 'fa-eye-slash' : 'fa-eye' ) . '"></span>';

        $actionUrl = elgg_add_action_tokens_to_url('action/project/' . $actionName . '?guid=' . $entity->guid);

        $menuItem = new \ElggMenuItem($actionName, $actionText, $actionUrl);
        $menuItem->setConfirmText(true);
        $menuItem->setTooltip($tooptipText);

        $return[] = $menuItem;
    }

    return $return;
}

/**
 * Handle user registration
 * @param  string  $hook   Hook name
 * @param  string  $type   Hook type
 * @param  boolean $return Allow or prevent registration
 * @param  array $params   An array of parameters
 * @return boolean         The same value as before, we do not prevent registration
 */
function ychange_register_user($hook, $type, $return, $params)
{
    elgg_load_library('elgg:ychange:options');

    $user = elgg_extract('user', $params);
    $values = elgg_get_sticky_values('register');

    $user->gender = elgg_extract('gender', $values);
    $user->location = elgg_extract('location', $values);
    $user->class_grade = elgg_extract('class_grade', $values);

    if ( elgg_extract('request_teacher', $values) === 'yes' )
    {
        $user->request_teacher = 'yes';
    }

    $language = elgg_extract('language', $values);
    if ( $language && array_key_exists( $language, ychange_get_language_options() ) )
    {
        $user->language = $language;
        // This seems to be the only way to change the language, writing directly into the database
        // The normal ways seem to be ignored
        // Might be related to the fact that entity has just been crated and is overwritten later,
        // with all the function arguments being passed by value
        global $CONFIG;

        $sanitizedLanguage = sanitize_string($language);
        $query = "UPDATE {$CONFIG->dbprefix}users_entity SET language='$sanitizedLanguage' WHERE guid = {$user->guid}";
        _elgg_services()->db->updateData($query);
    }

    $user->save();

    return $return;
}

/**
 * Override Project delete permission
 * Permission is given to admins, owner and any teacher that is part of the group
 * @param  string  $hook   Hook name
 * @param  string  $type   Hook type
 * @param  boolean $return Allow or not
 * @param  array  $params  Array of parameters
 * @return boolean
 */
function ychnage_project_delete_hook($hook, $type, $return, $params)
{
    $entity = elgg_extract('entity', $params);
    $user = elgg_extract('user', $params);
    $container = $entity->getContainerEntity();

    if ( elgg_instanceof($entity, 'object', 'project') )
    {
        if ( elgg_instanceof($user, 'user') && !$user->isBanned()
            && ( ( $user->isAdmin() || ( $entity->getOwnerGUID() === $user->getGUID() ) )
            || ( elgg_instanceof($container, 'group') && $container->isMember($user) && ychange_is_teacher($user) ) )
        )
        {
            return true;
        }
        return false;
    }

    return $return;
}

/**
 * Pverride user pofile fields
 * @param  string $hook   Hook name
 * @param  string $type   Hook type
 * @param  array  $return An array of fields
 * @return array          An array of fields
 */
function ychange_user_profile_fields_handler($hook, $type, $return)
{
    return [
        'description' => 'longtext',
        'gender' => 'gender',
        'location' => 'partner',
        'class_grade' => 'class_grade',
    ];
}

/**
 * User entity menu override
 * @param  string $hook   Hook name
 * @param  string $type   Hook type
 * @param  array  $return An array of menu items
 * @param  array  $params An array of parameters
 * @return array          An array of menu items
 */
function ychange_user_entity_menu_handler($hook, $type, $return, $params)
{
    if ( elgg_in_context('widgets') )
    {
        return $return;
    }

    $entity = elgg_extract('entity', $params);
    if ( !elgg_instanceof($entity, 'user') )
    {
        return $return;
    }

    if ( !$entity->isBanned() )
    {
        if ( $return && is_array($return) && count($return) > 0)
        {
            array_walk($return, function(&$item) use ($entity)
            {
                if ( $item->getName() === 'location' )
                {
                    $item->setText(elgg_view('output/partner', [
                        'value' => $entity->location,
                    ]));
                }
            });
        }
    }

    return $return;
}

/**
 * Configure JS with specific data
 * @param  string $hook   Hook name
 * @param  string $type   Hook type
 * @param  array  $value  An array of data settings
 * @param  array  $params An array of parameters
 * @return array          Extended array of values
 */
function ychange_config_site($hook, $type, $value, $params)
{
    $googleAnalyticsKey = elgg_get_plugin_setting('google_analytics_key', 'ychange');

    if ( $googleAnalyticsKey )
    {
        $value['ychange']['analytics']['key'] = $googleAnalyticsKey;
    }

    return $value;
}

/**
 * Runs upgrade scripts
 * @return void
 */
function ychange_run_upgrades()
{
    $path = __DIR__ . '/upgrades/';
    $files = elgg_get_upgrade_files($path);

    foreach ( $files as $file )
    {
        include $path . $file;
    }
}

/**
 * Adds menu items to the settings edit form
 *
 * @param string $hook   'register'
 * @param string $type   'menu:ychange_settings'
 * @param array  $return current menu items
 * @param array  $params parameters
 *
 * @return array
 */
function ychange_settings_menu_register_hook($hook, $type, $return, $params)
{
        $type = elgg_extract('type', $params);

        $settings = ['about', 'goal', 'participate', 'tutorials', 'blif'];
        foreach ( $settings as $setting ) {
                $return[] = ElggMenuItem::factory(array(
                        'name' => $setting,
                        'text' => elgg_echo("ychange:settings:$setting"),
                        'href' => "admin/appearance/ychange_settings?type=$setting",
                        'selected' => $setting === $type,
                ));
        }
        return $return;
}

/**
 *  BLIF page handler
 * @param  array $page Page path parts
 * @return bool
 */
function ychange_blif_page_handler($page)
{
    if ( elgg_is_admin_logged_in() ) {
        elgg_register_menu_item('title', array(
            'name' => 'edit',
            'text' => elgg_echo('edit'),
            'href' => "admin/appearance/ychange_settings?type=blif",
            'link_class' => 'elgg-button elgg-button-action',
        ));
    }

    echo elgg_view_resource('ychange/blif');

    return true;
}

/**
 * Initializes plugin, registering any logics or overrides needed
 * @return void
 */
function ychange_init()
{
    elgg_register_library('elgg:ychange:project', __DIR__ . '/lib/project.php');
    elgg_register_library('elgg:ychange:teacher', __DIR__ . '/lib/teacher.php');
    elgg_register_library('elgg:ychange:options', __DIR__ . '/lib/options.php');
    elgg_register_library('elgg:ychange:settings', __DIR__ . '/lib/settings.php');

    elgg_load_library('elgg:ychange:teacher');
    elgg_load_library('elgg:ychange:settings');

    elgg_register_event_handler('upgrade', 'system', 'ychange_run_upgrades');

    elgg_extend_view('elgg.css', 'ychange/css');
    elgg_extend_view('elgg.css', 'ychange/front_page/index.css');

    elgg_register_plugin_hook_handler('config', 'htmlawed', 'ychange_htmlawed_config');

    $tutorialsItem = new \ElggMenuItem('tutorials', elgg_echo('ychange:site:menu:video_tutorials'), 'tutorials');
    elgg_register_menu_item('site', $tutorialsItem);

    elgg_register_page_handler('tutorials', 'ychange_tutorials_page_handler');

    $projectItem = new \ElggMenuItem('projects', elgg_echo('ychange:projects'), 'projects/explore');
    elgg_register_menu_item('site', $projectItem);

    $nasaCredits = new \ElggMenuItem('credits', elgg_echo('ychange:nasa:credits'), 'https://earthobservatory.nasa.gov');
    $nasaCredits->setSection('meta');
    elgg_register_menu_item('footer', $nasaCredits);

    elgg_register_page_handler('projects', 'ychange_project_page_handler');

    elgg_register_plugin_hook_handler('entity:url', 'object', 'ychange_project_set_url');

     elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'ychange_project_owner_block_menu');

    elgg_register_entity_type('object', 'project');

    add_group_tool_option('project', elgg_echo('ychange:enableproject'), true);
    elgg_extend_view('groups/tool_latest', 'project/group_module');

    elgg_register_plugin_hook_handler('register', 'menu:entity', 'ychange_project_entity_menu_setup');

    $action_path = __DIR__ . '/actions/project';
    elgg_register_action('project/save', "$action_path/save.php");
    elgg_register_action('project/delete', "$action_path/delete.php");
    elgg_register_action('project/publish', "$action_path/publish.php");
    elgg_register_action('project/unpublish', "$action_path/unpublish.php");

    elgg_register_plugin_hook_handler('likes:is_likable', 'object:project', 'Elgg\Values::getTrue');
    elgg_register_plugin_hook_handler('permissions_check:delete', 'object', 'ychnage_project_delete_hook');

    elgg_register_js('recaptcha', RECAPTCHA_JS_URL, 'head');
    elgg_register_plugin_hook_handler("action", "register", "ychange_captcha_verify_action_hook");
    elgg_register_plugin_hook_handler("action", "user/requestnewpassword", "ychange_captcha_verify_action_hook");

    $recaptchaKey = elgg_get_plugin_setting('recaptcha_key', 'ychange');
    $recaptchaSecret = elgg_get_plugin_setting('recaptcha_secret', 'ychange');
    $googleMapsKey = elgg_get_plugin_setting('google_maps_key', 'ychange');
    if ( elgg_is_admin_logged_in() && elgg_get_context() === 'admin' ) {
        // Manage admin message if required configurations are missing
        if ( empty($recaptchaKey) || empty($recaptchaSecret) )
        {
            elgg_add_admin_notice('recaptcha_settings_missing', elgg_echo('ychange:recaptcha:settings:missing'));
        }
        else
        {
            if ( elgg_admin_notice_exists('recaptcha_settings_missing') )
            {
                elgg_delete_admin_notice('recaptcha_settings_missing');
            }
        }

        if ( empty($googleMapsKey) )
        {
            elgg_add_admin_notice('google_maps_settings_missing', elgg_echo('ychange:google:maps:settings:missing'));
        }
        else
        {
            if ( elgg_admin_notice_exists('google_maps_settings_missing') )
            {
                elgg_delete_admin_notice('google_maps_settings_missing');
            }
        }

        if ( ychnage_has_teacher_requests() )
        {
            elgg_add_admin_notice('unhandled_teacher_requests', elgg_echo('ychange:unhandled:teacher:requests:present', [elgg_normalize_url('admin/users/teacher_requests')]));
        }
        else
        {
            if ( elgg_admin_notice_exists('unhandled_teacher_requests') )
            {
                elgg_delete_admin_notice('unhandled_teacher_requests');
            }
        }
    }
    elgg_register_js('googleMaps', GOOGLE_MAPS_JS_URL . $googleMapsKey, 'head');

    $googleAnalyticsKey = elgg_get_plugin_setting('google_analytics_key', 'ychange');
    if ( $googleAnalyticsKey )
    {
        elgg_register_plugin_hook_handler('elgg.data', 'site', 'ychange_config_site');
        elgg_register_js('googleAnalytics', GOOGLE_ANALYTICS_JS_URL . $googleAnalyticsKey, 'head');
        elgg_load_js('googleAnalytics');
        elgg_require_js('ychange/google_analytics');
    }

    elgg_register_plugin_hook_handler('head', 'page', 'ychange_head');

    elgg_register_plugin_hook_handler('register', 'menu:user_hover', 'ychange_menu_user_hover');

    $action_path = __DIR__ . '/actions/admin/user';
    elgg_register_action('admin/user/maketeacher', "$action_path/maketeacher.php", 'admin');
    elgg_register_action('admin/user/removeteacher', "$action_path/removeteacher.php", 'admin');
    elgg_register_action('admin/user/rejectteacher', "$action_path/rejectteacher.php", 'admin');

    elgg_register_plugin_hook_handler('register', 'menu:title', 'ychange_title_menu_handler', 1000);
    elgg_register_plugin_hook_handler('view', 'resources/groups/add', 'ychange_alter_group_add_view', 1000);
    elgg_register_plugin_hook_handler('action', 'groups/edit', 'ychange_group_edit_action_hook', 1000);
    elgg_register_plugin_hook_handler('register', 'menu:entity', 'ychnage_project_menu_handler');

    elgg_register_admin_menu_item('administer', 'teachers', 'users', 25);
    elgg_register_admin_menu_item('administer', 'teacher_requests', 'users', 26);

    elgg_extend_view('register/extend', 'ychange/register');
    elgg_register_plugin_hook_handler('register', 'user', 'ychange_register_user');

    elgg_extend_view('profile/status', 'ychange/profile/status');

    elgg_register_plugin_hook_handler('profile:fields', 'profile', 'ychange_user_profile_fields_handler');
    elgg_register_plugin_hook_handler('register', 'menu:entity', 'ychange_user_entity_menu_handler', 1000);

    elgg_extend_view('page/elements/footer', 'ychange/cookie_consent');

    elgg_register_plugin_hook_handler('register', 'menu:ychange_settings', 'ychange_settings_menu_register_hook');
    elgg_register_admin_menu_item('configure', 'ychange_settings', 'appearance');

    // register action
    $actions_base = __DIR__ . '/actions';
    elgg_register_action("ychange/settings/edit", __DIR__ . "/actions/admin/setting/edit.php", 'admin');

    $blifItem = new \ElggMenuItem('blif', elgg_echo('ychange:site:menu:blif'), 'blif');
    elgg_register_menu_item('site', $blifItem);

    elgg_register_page_handler('blif', 'ychange_blif_page_handler');
}
