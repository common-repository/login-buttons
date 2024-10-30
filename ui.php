<?php

function sc_render_login_form_login_buttons( $args = NULL ) {

	if( $args == NULL )
		$display_label = true;
	elseif ( is_array( $args ) )
		extract( $args );

	if( !isset( $images_url ) )
		$images_url = login_buttons_PLUGIN_URL . '/media/img/';

	$twitter_enabled = get_option( 'login_buttons_twitter_enabled' ) && get_option( 'login_buttons_twitter_consumer_key' ) && get_option( 'login_buttons_twitter_consumer_secret' );
	$facebook_enabled = get_option( 'login_buttons_facebook_enabled', 1 ) && get_option( 'login_buttons_facebook_api_key' ) && get_option( 'login_buttons_facebook_secret_key' );
	$google_enabled = get_option( 'login_buttons_google_enabled', 1 );
	$yahoo_enabled = get_option( 'login_buttons_yahoo_enabled', 1 );
	$wordpress_enabled = get_option( 'login_buttons_wordpress_enabled', 1 );
	?>
	<div class="login_buttons_ui <?php if( strpos( $_SERVER['REQUEST_URI'], 'wp-signup.php' ) ) echo 'mu_signup'; ?>">
		<?php if( $display_label !== false ) : ?>
			<div style="margin-bottom: 3px;"><label><?php _e( 'Connect with', 'login_buttons' ); ?>:</label></div>
		<?php endif; ?>
		<div class="login_buttons_form" title="Login Buttons">
			<?php if( $facebook_enabled ) : ?>
				<a href="javascript:void(0);" title="Facebook" class="login_buttons_login_facebook"><img alt="Facebook" src="<?php echo $images_url . 'facebook_32.png' ?>" /></a>
			<?php endif; ?>
			<?php if( $twitter_enabled ) : ?>
				<a href="javascript:void(0);" title="Twitter" class="login_buttons_login_twitter"><img alt="Twitter" src="<?php echo $images_url . 'twitter_32.png' ?>" /></a>
			<?php endif; ?>
			<?php if( $google_enabled ) : ?>
				<a href="javascript:void(0);" title="Google" class="login_buttons_login_google"><img alt="Google" src="<?php echo $images_url . 'google_32.png' ?>" /></a>
			<?php endif; ?>
			<?php if( $yahoo_enabled ) : ?>
				<a href="javascript:void(0);" title="Yahoo" class="login_buttons_login_yahoo"><img alt="Yahoo" src="<?php echo $images_url . 'yahoo_32.png' ?>" /></a>
			<?php endif; ?>
			<?php if( $wordpress_enabled ) : ?>
				<a href="javascript:void(0);" title="WordPress.com" class="login_buttons_login_wordpress"><img alt="Wordpress.com" src="<?php echo $images_url . 'wordpress_32.png' ?>" /></a>
			<?php endif; ?>
		</div>

		<?php
	$login_buttons_provider = isset( $_COOKIE['login_buttons_current_provider']) ? $_COOKIE['login_buttons_current_provider'] : '';

?>
	<div id="login_buttons_facebook_auth">
		<input type="hidden" name="client_id" value="<?php echo get_option( 'login_buttons_facebook_api_key' ); ?>" />
		<input type="hidden" name="redirect_uri" value="<?php echo urlencode( login_buttons_PLUGIN_URL . '/facebook/callback.php' ); ?>" />
	</div>
	<div id="login_buttons_twitter_auth"><input type="hidden" name="redirect_uri" value="<?php echo( login_buttons_PLUGIN_URL . '/twitter/connect.php' ); ?>" /></div>
	<div id="login_buttons_google_auth"><input type="hidden" name="redirect_uri" value="<?php echo( login_buttons_PLUGIN_URL . '/google/connect.php' ); ?>" /></div>
	<div id="login_buttons_yahoo_auth"><input type="hidden" name="redirect_uri" value="<?php echo( login_buttons_PLUGIN_URL . '/yahoo/connect.php' ); ?>" /></div>
	<div id="login_buttons_wordpress_auth"><input type="hidden" name="redirect_uri" value="<?php echo( login_buttons_PLUGIN_URL . '/wordpress/connect.php' ); ?>" /></div>

	<div class="login_buttons_wordpress_form" title="WordPress">
		<p><?php _e( 'Enter your WordPress.com blog URL', 'login_buttons' ); ?></p><br/>
		<p>
			<span>http://</span><input class="wordpress_blog_url" size="15" value=""/><span>.wordpress.com</span> <br/><br/>
			<a href="javascript:void(0);" class="login_buttons_wordpress_proceed"><?php _e( 'Proceed', 'login_buttons' ); ?></a>
		</p>
	</div>
</div> <!-- End of login_buttons_ui div -->
<?php
}
add_action( 'login_form',          'sc_render_login_form_login_buttons' );
add_action( 'register_form',       'sc_render_login_form_login_buttons' );
add_action( 'after_signup_form',   'sc_render_login_form_login_buttons' );
add_action( 'login_buttons_form', 'sc_render_login_form_login_buttons' );


function sc_login_buttons_add_comment_meta( $comment_id ) {
	$login_buttons_comment_via_provider = isset( $_POST['login_buttons_comment_via_provider']) ? $_POST['login_buttons_comment_via_provider'] : '';
	if( $login_buttons_comment_via_provider != '' ) {
		update_comment_meta( $comment_id, 'login_buttons_comment_via_provider', $login_buttons_comment_via_provider );
	}
}
add_action( 'comment_post', 'sc_login_buttons_add_comment_meta' );


function sc_login_buttons_render_comment_meta( $link ) {
	global $comment;
	$images_url = login_buttons_PLUGIN_URL . '/media/img/';
	$login_buttons_comment_via_provider = get_comment_meta( $comment->comment_ID, 'login_buttons_comment_via_provider', true );
	if( $login_buttons_comment_via_provider && current_user_can( 'manage_options' )) {
		return $link . '&nbsp;<img class="login_buttons_comment_via_provider" alt="'.$login_buttons_comment_via_provider.'" src="' . $images_url . $login_buttons_comment_via_provider . '_16.png"  />';
	} else {
		return $link;
	}
}
add_action( 'get_comment_author_link', 'sc_login_buttons_render_comment_meta' );


function sc_render_comment_form_login_buttons() {
	if( comments_open() && !is_user_logged_in()) {
		sc_render_login_form_login_buttons();
	}
}
add_action( 'comment_form_top', 'sc_render_comment_form_login_buttons' );


function sc_render_login_page_uri(){
	?>
	<input type="hidden" id="login_buttons_login_form_uri" value="<?php echo site_url( 'wp-login.php', 'login_post' ); ?>" />
	<?php
}
add_action( 'wp_footer', 'sc_render_login_page_uri' );


/**
 * LoginButtonsWidget Class
 */
class LoginButtonsWidget extends WP_Widget {
	/** constructor */
	function LoginButtonsWidget() {
		parent::WP_Widget(
			'login_buttons', //unique id
			'Login Buttons', //title displayed at admin panel
			//Additional parameters
			array( 
				'description' => __( 'Login or register with Facebook, Twitter, Yahoo, Google or a Wordpress.com account', 'login_buttons' ))
			);
	}

	/** This is rendered widget content */
	function widget( $args, $instance ) {
		extract( $args );
		
		if($instance['hide_for_logged_in']==1 && is_user_logged_in()) return;
		
		echo $before_widget;

		if( !empty( $instance['title'] ) ){
			$title = apply_filters( 'widget_title', $instance[ 'title' ] );
			echo $before_title . $title . $after_title;
		}

		if( !empty( $instance['before_widget_content'] ) ){
			echo $instance['before_widget_content'];
		}

		sc_render_login_form_login_buttons( array( 'display_label' => false ) );

		if( !empty( $instance['after_widget_content'] ) ){
			echo $instance['after_widget_content'];
		}

		echo $after_widget;
	}

	/** Everything which should happen when user edit widget at admin panel */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['before_widget_content'] = $new_instance['before_widget_content'];
		$instance['after_widget_content'] = $new_instance['after_widget_content'];
		$instance['hide_for_logged_in'] = $new_instance['hide_for_logged_in'];

		return $instance;
	}

	/** Widget edit form at admin panel */
	function form( $instance ) {
		/* Set up default widget settings. */
		$defaults = array( 'title' => '', 'before_widget_content' => '', 'after_widget_content' => '' );

		foreach( $instance as $key => $value ) 
			$instance[ $key ] = esc_attr( $value );

		$instance = wp_parse_args( (array)$instance, $defaults );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'login_buttons' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>" />
			<label for="<?php echo $this->get_field_id( 'before_widget_content' ); ?>"><?php _e( 'Before widget content:', 'login_buttons' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'before_widget_content' ); ?>" name="<?php echo $this->get_field_name( 'before_widget_content' ); ?>" type="text" value="<?php echo $instance['before_widget_content']; ?>" />
			<label for="<?php echo $this->get_field_id( 'after_widget_content' ); ?>"><?php _e( 'After widget content:', 'login_buttons' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'after_widget_content' ); ?>" name="<?php echo $this->get_field_name( 'after_widget_content' ); ?>" type="text" value="<?php echo $instance['after_widget_content']; ?>" />
			<br /><br /><label for="<?php echo $this->get_field_id( 'hide_for_logged_in' ); ?>"><?php _e( 'Hide for logged in users:', 'login_buttons' ); ?></label>
			<input type="checkbox" id="<?php echo $this->get_field_id( 'hide_for_logged_in' ); ?>" name="<?php echo $this->get_field_name( 'hide_for_logged_in' ); ?>" type="text" value="1" <?php if($instance['hide_for_logged_in']==1) echo 'checked="checked"'; ?> />
		</p>
		<?php
}

}
add_action( 'widgets_init', create_function( '', 'return register_widget( "LoginButtonsWidget" );' ));


function sc_login_buttons_shortcode_handler( $args ) {
	if( !is_user_logged_in()) {
		sc_render_login_form_login_buttons();
	}
}
add_shortcode( 'login_buttons', 'sc_login_buttons_shortcode_handler' );
