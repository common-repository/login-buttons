<?php
require_once(dirname(dirname(__FILE__)) . '/openid/openid.php');
require_once(dirname(dirname(__FILE__)) . '/utils.php' );

try {
  if(!isset($_GET['openid_mode']) || $_GET['openid_mode'] == 'cancel') {
    $openid = new LightOpenID;
    $openid->identity = 'me.yahoo.com';
    $openid->required = array('namePerson', 'namePerson/friendly', 'contact/email');
    header('Location: ' . $openid->authUrl());
  } else {
    $openid = new LightOpenID;
    if($openid->validate()) {
      $yahoo_id = $openid->identity;
      $attributes = $openid->getAttributes();
      $email = $attributes['contact/email'];
      $name = $attributes['namePerson'];
      $username = $attributes['namePerson/friendly'];
      $signature = login_buttons_generate_signature($yahoo_id);
      ?>
<html>
<head>
<script>
function init() {
  window.opener.wp_login_buttons({'action' : 'login_buttons', 'login_buttons_provider' : 'yahoo', 
    'login_buttons_openid_identity' : '<?php echo $yahoo_id ?>',
    'login_buttons_signature' : '<?php echo $signature ?>',
    'login_buttons_email' : '<?php echo $email ?>',
    'login_buttons_name' : '<?php echo $name ?>',
    'login_buttons_username' : '<?php echo $username ?>'});
    
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
