<?php
require_once( dirname( dirname( dirname( dirname( __FILE__ )))) . '/wp-load.php' );

if( !defined( 'login_buttons_PLUGIN_URL' )) {
  define( 'login_buttons_PLUGIN_URL', plugins_url() . '/' . basename( dirname( __FILE__ )));
}

?>