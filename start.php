<?php

define('RECAPTCHA_JS_URL', 'https://www.google.com/recaptcha/api.js');
define('RECAPTCHA_VERIFY_URL', 'https://www.google.com/recaptcha/api/siteverify');
define('GOOGLE_MAPS_JS_URL', 'https://maps.googleapis.com/maps/api/js?key=');
define('GOOGLE_MAPS_STATIC_URL', 'https://maps.googleapis.com/maps/api/staticmap?center=');

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
            'href' => "admin/plugin_settings/ychange",
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

    // push all projects breadcrumb
    elgg_push_breadcrumb(elgg_echo('ychange:projects'), 'projects/all');

    $page_type = elgg_extract(0, $page, 'all');
    $resource_vars = [
        'page_type' => $page_type,
    ];

    switch ($page_type) {
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
    if ( $entity instanceof ElggUser )
    {
        $url = "projects/owner/{$entity->username}";
        $return[] = new ElggMenuItem('project', elgg_echo('ychange:projects'), $url);

    }
    elseif ( $entity instanceof ElggGroup )
    {
        if ( $entity->project_enable != "no" )
        {
            $url = "projects/group/{$entity->guid}/all";
            $return[] = new ElggMenuItem('project', elgg_echo('ychange:project:group'), $url);
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
        else {
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
function ychange_head($hook, $type, $data) {
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
 * Initializes plugin, registering any logics or overrides needed
 * @return void
 */
function ychange_init()
{
    elgg_register_library('elgg:ychange:project', __DIR__ . '/lib/project.php');

    elgg_extend_view('elgg.css', 'ychange/css');
    elgg_extend_view('elgg.css', 'ychange/front_page/index.css');

    elgg_register_plugin_hook_handler('config', 'htmlawed', 'ychange_htmlawed_config');

    $tutorialsItem = new ElggMenuItem('tutorials', elgg_echo('ychange:site:menu:video_tutorials'), 'tutorials');
    elgg_register_menu_item('site', $tutorialsItem);

    elgg_register_page_handler('tutorials', 'ychange_tutorials_page_handler');

    $projectItem = new ElggMenuItem('projects', elgg_echo('ychange:projects'), 'projects/all');
    elgg_register_menu_item('site', $projectItem);

    $nasaCredits = new ElggMenuItem('credits', elgg_echo('ychange:nasa:credits'), 'https://earthobservatory.nasa.gov');
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

    elgg_register_plugin_hook_handler('likes:is_likable', 'object:project', 'Elgg\Values::getTrue');

    elgg_register_js('recaptcha', RECAPTCHA_JS_URL, 'head');
    elgg_register_plugin_hook_handler("action", "register", "ychange_captcha_verify_action_hook");
    elgg_register_plugin_hook_handler("action", "user/requestnewpassword", "ychange_captcha_verify_action_hook");

    // Manage admin message if required configurations are missing
    $recaptchaKey = elgg_get_plugin_setting('recaptcha_key', 'ychange');
    $recaptchaSecret = elgg_get_plugin_setting('recaptcha_secret', 'ychange');
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

    $googleMapsKey = elgg_get_plugin_setting('google_maps_key', 'ychange');
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
    elgg_register_js('googleMaps', GOOGLE_MAPS_JS_URL . $googleMapsKey, 'head');

    elgg_register_plugin_hook_handler('head', 'page', 'ychange_head');
}
