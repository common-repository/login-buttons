<?php
require_once(dirname(dirname(__FILE__)) . '/constants.php' );
require_once(dirname(__FILE__) . '/facebook.php' );
require_once(dirname(dirname(__FILE__)) . '/utils.php' );

$client_id = get_option('login_buttons_facebook_api_key');
$secret_key = get_option('login_buttons_facebook_secret_key');

if(isset($_GET['code'])) {
  $code = $_GET['code'];
  $client_id = get_option('login_buttons_facebook_api_key');
  $secret_key = get_option('login_buttons_facebook_secret_key');
  parse_str(sc_curl_get_contents("https://graph.facebook.com/oauth/access_token?" .
    'client_id=' . $client_id . '&redirect_uri=' . urlencode(login_buttons_PLUGIN_URL . '/facebook/callback.php') .
    '&client_secret=' .  $secret_key .
    '&code=' . urlencode($code)));
    
  $signature = login_buttons_generate_signature($access_token);  
?>
<html>
<head>
<script>
function init() {
  window.opener.wp_login_buttons({'action' : 'login_buttons', 'login_buttons_provider' : 'facebook',
    'login_buttons_signature' : '<?php echo $signature ?>',
    'login_buttons_access_token' : '<?php echo $access_token ?>'});
    
  window.close();
}
</script>
</head>
<body onload="init();">
</body>
</html>
<?php

} else {
  $redirect_uri = urlencode(login_buttons_PLUGIN_URL . '/facebook/callback.php');
  wp_redirect('https://graph.facebook.com/oauth/authorize?client_id=' . $client_id . '&redirect_uri=' . $redirect_uri . '&scope=email');
}
?>
