<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://github.com/jarlah/Author-Bind
 * @since             1.0.0
 * @package           Author_Bind
 *
 * @wordpress-plugin
 * Plugin Name:       Author Bind
 * Plugin URI:        http://github.com/jarlah/Author-Bind
 * Description:       Author Bind is a plugin that lets you configure a User ID to override all future added or edited posts/pages.
 * Version:           1.0.0
 * Author:            Jarl André Hübenthal
 * Author URI:        http://github.com/jarlah
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       author-bind
 * Domain Path:       /languages
 */

defined('ABSPATH') or die('No script kiddies please!');

function assign_new_post_to_specific_author($data, $postarr)
{
    
    $user_id = get_option('author_bind_user_id');
    
    $user = get_userdata($user_id);
    
    if ($user != false) {
        $data['post_author'] = $user_id;
    }
    
    return $data;
}

add_filter('wp_insert_post_data', 'assign_new_post_to_specific_author', '99', 2);

function give_meaningless_message_on_login_failure($error)
{
    global $errors;
    $err_codes = $errors->get_error_codes();
    
    if (in_array('invalid_username', $err_codes)) {
        $error = '<strong>ERROR</strong>: The username/password you entered is incorrect.';
    }
    
    if (in_array('incorrect_password', $err_codes)) {
        $error = '<strong>ERROR</strong>: The username/password you entered is incorrect.';
    }
    
    return $error;
}

add_filter('login_errors', 'give_meaningless_message_on_login_failure');

function redirect_away_from_author_page()
{
    if (is_author()) {
        wp_redirect(home_url());
    }
}

add_action('template_redirect', 'redirect_away_from_author_page');

add_action('admin_menu', 'author_bind_menu');

function author_bind_menu()
{
    add_options_page('Author Bind Options', 'Author Bind', 'manage_options', 'author-bind-settings', 'author_bind_options');
}

function author_bind_options()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    
    include(dirname(__FILE__) . '/author-bind-settings.php');
}