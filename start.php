<?php

define('RECAPTCHA_JS_URL', 'https://www.google.com/recaptcha/api.js');
define('RECAPTCHA_VERIFY_URL', 'https://www.google.com/recaptcha/api/siteverify');

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

    echo elgg_view_resource('ychange/tutorials');

    return true;
}

/**
 * reCaptcha action hook
 * @param  string $hook         Hook name
 * @param  string  $entity_type Type
 * @param  mixed $returnvalue   Value
 * @param  mixed $params        Params
 * @return bool
 */
function ychange_captcha_verify_action_hook($hook, $entity_type, $returnvalue, $params) {
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
}
