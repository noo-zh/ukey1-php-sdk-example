<?php

	namespace Example;
	
	include_once __DIR__ . '/autoload.php';
	include_once __DIR__ . '/example-logic.php';
	include_once __DIR__ . '/ukey1-credentials.php'; // There is your credentials here
	include_once __DIR__ . '/ukey1-sdk-logic.php'; // There is an example code here

?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>ukey1-php-sdk example</title>
		<link rel="stylesheet" type="text/css" href="https://gitcdn.xyz/repo/asaritech/ukey1-signin-button/master/css/ukey1-button.min.css" media="screen">
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
		
		
		
<?php } elseif ($action == ACTION_REFRESH_TOKEN) { ?>
		
		
		
<?php } elseif ($action == ACTION_GET_USER_DETAILS) { ?>
		
		<p>
			<strong>DEBUG INFO:</strong><br/>
			Access token: <input value="<?= getSession("access_token"); ?>" readonly="readonly"><br/>
			Expiration: <input value="<?= getSession("expiration"); ?>" readonly="readonly"><br/>
			Refresh token: <input value="<?= (getSession("refresh_token") ? getSession("refresh_token") : "- no refresh token -"); ?>" readonly="readonly"><br/>
			Scope: <input value="<?= getSession("scope"); ?>" readonly="readonly">
		</p>
		
		<p>
			<strong>USER DATA:</strong><br/>
			JSON string: <pre><?= $resultData["json"]; ?></pre><br/>
			Encoded array: <pre><?= $resultData["array"]; ?></pre>
		</p>
		
		<?php if (getSession("refresh_token")) { ?>
		<p><a href="<?= getUrl(ACTION_REFRESH_TOKEN); ?>">Refresh token now</a> | <a href="<?= getUrl(); ?>">Try it again</a></p>
		<?php } ?>
		
<?php } elseif ($action == ACTION_CONNECT) { ?>
		
		
		
<?php } else { ?>
		
		<p><a href="<?= getUrl(ACTION_CONNECT); ?>" class="ukey1-button">Sign in to this example</a></p>
		<p>Get this sign-in button <a href="https://github.com/asaritech/ukey1-signin-button">on GitHub</a>.</p>
		
<?php } ?>

<?php

	if ($exception) {
		echo '<pre class="red">' . $exception . '</pre>';
	}

?>
    </body>
</html>
