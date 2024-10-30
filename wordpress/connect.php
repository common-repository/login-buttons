<?php
require_once(dirname(dirname(__FILE__)) . '/openid/openid.php');
require_once(dirname(dirname(__FILE__)) . '/utils.php' );
try {
	if(!isset($_GET['openid_mode'])) {
		$openid = new LightOpenID;
		$openid->identity = urldecode($_GET['wordpress_blog_url']);
		$openid->required = array('namePerson', 'namePerson/friendly', 'contact/email');
		header('Location: ' . $openid->authUrl());
	} elseif($_GET['openid_mode'] == 'cancel') {
			?>
			<html>
			<body>
				<p><?php _e( 'You have cancelled this login. Please close this window and try again.', 'login-buttons' ); ?></p>
			</body>
			</html>
			<?php
	} else {
		$openid = new LightOpenID;
		if($openid->validate()) {
			$wordpress_id = $openid->identity;
			$attributes = $openid->getAttributes();
			$email = isset($attributes['contact/email']) ? $attributes['contact/email'] : '';
			$name = isset($attributes['namePerson']) ? $attributes['namePerson'] : '';
			$signature = login_buttons_generate_signature($wordpress_id);
			if($email == '') {
				?>
				<html>
				<body>
					<p><?php _e( 'You need to share your email address when prompted at wordpress.com. Please close this window and try again.', 'login-buttons' ); ?></p>
				</body>
				</html>
				<?php
			die();
		}

		?>
		<html>
		<head>
		<script>
		function init() {
			window.opener.wp_login_buttons({'action' : 'login_buttons', 'login_buttons_provider' : 'wordpress',
			'login_buttons_signature' : '<?php echo $signature ?>',
			'login_buttons_openid_identity' : '<?php echo $wordpress_id ?>',
			'login_buttons_email' : '<?php echo $email ?>',
			'login_buttons_name' : '<?php echo $name ?>'
		});
		window.close();
	}
	</script>
</head>
<body onload="init();">
</body>
</html>      
<?php
}
}
} catch(ErrorException $e) {
	echo $e->getMessage();
}
?>