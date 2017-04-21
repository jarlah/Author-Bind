<?php
/*
Plugin Name: Author Binder
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

function assign_new_post_to_specific_author( $data , $postarr ) {

  $user_id = get_option('author_bind_user_id');
  
  $user = get_userdata( $user_id ); 
  
  if ( $user != false ) {
  	$data['post_author'] = $user_id; 
  }

  return $data;
}

add_filter( 'wp_insert_post_data', 'assign_new_post_to_specific_author', '99', 2 );

add_filter( 'login_errors', function( $error ) {
	global $errors;
	$err_codes = $errors->get_error_codes();

	if ( in_array( 'invalid_username', $err_codes ) ) {
		$error = '<strong>ERROR</strong>: The username/password you entered is incorrect.';
	}

	if ( in_array( 'incorrect_password', $err_codes ) ) {
		$error = '<strong>ERROR</strong>: The username/password you entered is incorrect.';
	}

	return $error;
} );

function author_page_redirect() {
    if ( is_author() ) {
        wp_redirect( home_url() );
    }
}

add_action( 'template_redirect', 'author_page_redirect' );

/** Step 2 (from text above). */
add_action( 'admin_menu', 'author_bind_menu' );

/** Step 1. */
function author_bind_menu() {
	add_options_page( 'Author Bind Options', 'Author Bind', 'manage_options', 'author-bind-settings', 'my_plugin_options' );
}

/** Step 3. */
function my_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	include( dirname(__FILE__) . '/settings.php');
}