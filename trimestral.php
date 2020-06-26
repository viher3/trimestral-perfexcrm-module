<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Trimestral
Description: Generate invoices and expenses by quarters
Version: 0.1
Requires at least: 2.3.*
*/

define('TRIMESTRAL_MODULE_NAME', 'trimestral');

hooks()->add_action('admin_init', 'trimestral_module_init_menu_items');

/**
 * Register language files, must be registered if the module is using languages
 */
register_language_files(TRIMESTRAL_MODULE_NAME, [TRIMESTRAL_MODULE_NAME]);

/**
 * Init module menu items in setup in admin_init hook
 * @return null
 */
function trimestral_module_init_menu_items()
{
    $CI = &get_instance();

    $CI->app->add_quick_actions_link([
        'name'       => _l('trimestral_name'),
        'url'        => 'trimestral',
    ]);

    $CI->app_menu->add_sidebar_children_item('utilities', [
        'slug'     => 'trimestral',
        'name'     => _l('trimestral_name'),
        'href'     => admin_url('trimestral'),
    ]);
}
