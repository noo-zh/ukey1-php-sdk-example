<?php

	namespace Example;
	
	include_once __DIR__ . '/autoload.php';
	include_once __DIR__ . '/example-logic.php';
	include_once __DIR__ . '/ukey1-credentials.php'; // There are your credentials here
	include_once __DIR__ . '/ukey1-sdk-logic.php'; // There is an example code here

?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>ukey1-php-sdk example</title>
		<link rel="stylesheet" type="text/css" href="https://code.ukey1cdn.com/ukey1-signin-button/master/css/ukey1-button.min.css" media="screen">
		<style>
			body {
				padding: 50px;
			}
			h1 {
				font-size: 20px;
			}
			p {
				margin: 50px 0px;
			}
			.red {
				color: #ff0000;
			}
			input {
				width: 75%;
			}
		</style>
    </head>
    <body>
		<h1>ukey1-php-sdk example</h1>
		
<?php if ($action == ACTION_GET_TOKEN) { ?>
		
		
		
<?php 

} elseif ($action == ACTION_GET_USER_DETAILS && getSession("access_token")) { 
    $available = $rejected = "";
    
    if ($resultData["token_state"] == "valid") {
        $available = readAvailableScopes(getSession("access_token"), $rejected);
    }

?>
		
		<p>
			<strong>DEBUG INFO:</strong><br/>
			Access Token: <input value="<?= getSession("access_token"); ?>" readonly="readonly"><br/>
			Expiration: <input value="<?= date("Y-m-d H:i:s", getSession("expiration")); ?>" readonly="readonly"><br/>
      Available permissions: <input value="<?= $available; ?>" readonly="readonly"><br/>
      Rejected permissions: <input value="<?= $rejected; ?>" readonly="readonly"><br/>
			User ID: <input value="<?= $resultData["user_id"]; ?>" readonly="readonly"><br/>
			State: <input value="<?= $resultData["token_state"]; ?>" readonly="readonly">
		</p>
		
		<p>
			<strong>USER DATA:</strong><br/>
			JSON string: <pre><?= $resultData["json"]; ?></pre><br/>
			Encoded array: <pre><?= $resultData["array"]; ?></pre>
		</p>
		
		<p><a href="<?= getUrl(); ?>">Try it again</a></p>
		
<?php } elseif ($action == ACTION_CONNECT) { ?>
		
    
    
<?php } elseif ($action == ACTION_EXTRANET_UM) { ?>
		
    <p><strong>Add user to your <q>extranet</q></strong></p>
    
    <form action="<?= getUrl(); ?>" method="GET">
      <input type="email" name="your_email" required placeholder="Your valid email (we will send you the invitation link)">
      <select name="your_locale">
        <option selected="selected">en_GB</option>
        <option>cs_CZ</option>
      </select>
      <input type="hidden" name="<?= ACTION; ?>" value="<?= ACTION_EXTRANET_ADD_USER; ?>">
      <button type="submit">Create</button>
    </form>
    
		<p>This feature is for premium apps only.</p>
    
<?php } elseif ($action == ACTION_EXTRANET_ADD_USER) { ?>
		
    <?php if (getSession("extranet_reference_id")) { ?>
    <p>Success</p>
    
    <p>
			<strong>DEBUG INFO:</strong><br/>
			Extranet Reference ID: <input value="<?= getSession("extranet_reference_id"); ?>" readonly="readonly"><br/>
			Success Status: <input value="<?= getSession("extranet_success_status"); ?>" readonly="readonly">
		</p>
    
    <p>Check your inbox for the invitation...</p>
    <?php } ?>
    
    <p><a href="<?= getUrl(); ?>">Go to start</a></p>
    
<?php } elseif ($action == ACTION_EXTRANET_AUTOLOGIN) { ?>
		
		
		
<?php } else { ?>
		
    <p>Available scopes: <strong><?= readAvailableScopes(); ?></strong></p>
		<p><a href="<?= getUrl(ACTION_CONNECT); ?>" class="ukey1-button">Sign in to this example</a></p>
		<p>Get this sign-in button <a href="https://github.com/asaritech/ukey1-signin-button">on GitHub</a>.</p>
    <p>Try new feature <a href="<?= getUrl(ACTION_EXTRANET_UM); ?>">Private users</a> for premium apps.</p>
		
<?php } ?>

<?php

	if ($exception) {
		echo '<pre class="red">' . $exception . '</pre>';
	}

?>
    </body>
</html>
