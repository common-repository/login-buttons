<?php

function sc_add_stylesheets(){
	if( !wp_style_is( 'login_buttons', 'registered' ) ) {
		wp_register_style( "login_buttons", login_buttons_PLUGIN_URL . "/media/css/style.css" );
		wp_register_style( "jquery-ui", 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.5/themes/smoothness/jquery-ui.css' );
	}

	if ( did_action( 'wp_print_styles' ) ) {
		wp_print_styles( 'login_buttons' );
		wp_print_styles( 'jquery-ui' );
	} else {
		wp_enqueue_style( "login_buttons" );
		wp_enqueue_style( "jquery-ui" );
	}
}
add_action( 'login_head', 'sc_add_stylesheets' );
add_action( 'wp_head', 'sc_add_stylesheets' );


function sc_add_admin_stylesheets(){
	if( !wp_style_is( 'login_buttons', 'registered' ) ) {
		wp_register_style( "login_buttons", login_buttons_PLUGIN_URL . "/media/css/style.css" );
	}

	if ( did_action( 'wp_print_styles' )) {
		wp_print_styles( 'login_buttons' );
	} else {
		wp_enqueue_style( "login_buttons" );
	}
}
add_action( 'admin_print_styles', 'sc_add_admin_stylesheets' );


function sc_add_javascripts(){
	if( !wp_script_is( 'login_buttons', 'registered' ) ) {
		wp_register_script( "login_buttons", login_buttons_PLUGIN_URL . "/media/js/connect.js" );
	}
	wp_print_scripts( "jquery" );
	wp_print_scripts( 'jquery-ui-core' );
	wp_print_scripts( 'jquery-ui-dialog' );
	wp_print_scripts( "login_buttons" );
}
add_action( 'login_head', 'sc_add_javascripts' );
add_action( 'wp_head', 'sc_add_javascripts' );
