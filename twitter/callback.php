<?php
require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/wp-load.php');
require_once(dirname(__FILE__) . '/EpiCurl.php' );
require_once(dirname(__FILE__) . '/EpiOAuth.php' );
require_once(dirname(__FILE__) . '/EpiTwitter.php' );
require_once(dirname(dirname(__FILE__)) . '/utils.php' );

$consumer_key = get_option('login_buttons_twitter_consumer_key');
$consumer_secret = get_option('login_buttons_twitter_consumer_secret');
$twitter_api = new EpiTwitter($consumer_key, $consumer_secret);

$twitter_api->setToken($_GET['oauth_token']);
$token = $twitter_api->getAccessToken();
$twitter_api->setToken($token->oauth_token, $token->oauth_token_secret);

$user = $twitter_api->get_accountVerify_credentials();
$name = $user->name;
$screen_name = $user->screen_name;
$twitter_id = $user->id;
$signature = login_buttons_generate_signature($twitter_id);
?>

<html>
<head>
<script>
function init() {
  window.opener.wp_login_buttons({'action' : 'login_buttons', 'login_buttons_provider' : 'twitter', 
    'login_buttons_signature' : '<?php echo $signature ?>',
    'login_buttons_twitter_identity' : '<?php echo $twitter_id ?>',
    'login_buttons_screen_name' : '<?php echo $screen_name ?>',
    'login_buttons_name' : '<?php echo $name ?>'});
    
  window.close();
}
</script>
</head>
<body onload="init();">
</body>
</html>
