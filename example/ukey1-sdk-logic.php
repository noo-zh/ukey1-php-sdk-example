<?php

	namespace Example;
	
	include_once __DIR__ . '/autoload.php';
	include_once __DIR__ . '/example-logic.php';
	include_once __DIR__ . '/ukey1-credentials.php';
	
	use Ukey1\App;
	use Ukey1\Endpoints\Authentication\Connect;
	use Ukey1\Endpoints\Authentication\AccessToken;
	use Ukey1\Endpoints\Authentication\User;
  use Ukey1\Endpoints\Authentication\ExtranetUsers;
	use Ukey1\Generators\RandomString;
	
	try {
		if ($action == ACTION_CONNECT) {
			// User clicked to "sign-in button"
			// Inits of connection with Ukey1 and redirects user to Ukey1 gateway
			
			$app = new App();
			$app->appId(APP_ID)
				->secretKey(SECRET_KEY);

			$requestId = RandomString::generate(64); // returns string with the length of 128 chars
			$returnUrl = getUrl(ACTION_GET_TOKEN);

			$module = new Connect($app);
			$module->setRequestId($requestId)
				->setReturnUrl($returnUrl)
				->setScope([
					"country",
          "language!",
          "firstname!",
          "surname",
          "email",
          "image"
				]);

			$connectId = $module->getId();

			// Note that you need to store these values (at least temporarily)...
			
			saveSession("request_id", $requestId);
			saveSession("connect_id", $connectId);
			
			// Now you can redirect to gateway URL (yourself or via redirect() method)
			
			//$gatewayUrl = $module->getGatewayUrl();
			$module->redirect();
		}
		
		elseif ($action == ACTION_GET_TOKEN) {
			// Checks returned status and gets access token
			
			$app = new App();
			$app->appId(APP_ID)
				->secretKey(SECRET_KEY);
			
			$module = new AccessToken($app);
			$module->setRequestId(getSession("request_id"))
				->setConnectId(getSession("connect_id"));
			
			$check = $module->check();
			
			if ($check) {
				// Now you can get your access token
				
				$accessToken = $module->getAccessToken(); // you can store the access token in your database
				$accessTokenExpiration = $module->getAccessTokenExpiration();
				$grantedScope = $module->getScope();
				
				// This is only for this example...
				
				saveSession("access_token", $accessToken);
				saveSession("expiration", $accessTokenExpiration);
				saveSession("scope", $grantedScope);
				
				header("Location: " . getUrl(ACTION_GET_USER_DETAILS));
			}
			else {
				$action = null;
				$exception = "The request was canceled (by user).";
			}
		}
		
		elseif ($action == ACTION_GET_USER_DETAILS) {
			// Gets user's data
			
			$app = new App();
			$app->appId(APP_ID)
				->secretKey(SECRET_KEY);
			
			$module = new User($app);
			$module->setAccessToken(getSession("access_token"));
      
      $resultData["user_id"] = $module->id(); // ID is parsed from access token (i.e. you can get user's ID before you make a call for details)
      $resultData["token_state"] = ($module->valid() ? "valid": "expired");
      $resultData["json"] = $resultData["array"] = "- no data -";
      
      // now you can make a call for details (or not if you need only user's ID)
			
      if ($module->valid()) {
        $rawJSON = $module->raw(); // returns raw JSON string

        $resultData["json"] = $rawJSON;
        $resultData["array"] = print_r(json_decode($rawJSON, true), true);
      }
			
			// You can also get indiviual fields using the following methods
			
			/*$user = $module->user(); // an entity of the user
        $user->scope();
        $user->id();
				$user->firstname();
				$user->surname();
				$user->language();
				$user->country();
				$user->email();
				$user->image();*/
		}
    
    elseif ($action == ACTION_EXTRANET_ADD_USER && get("your_email") && get("your_locale")) {
      // Create extranet user
      
      $app = new App();
			$app->appId(APP_ID)
				->secretKey(SECRET_KEY);
      
      $module = new ExtranetUsers($app);
      $module->setEmail(get("your_email"));
      $module->setLocale(get("your_locale"));
      $referenceId = $module->getReferenceId(); // you should store reference ID in your database together with user ID that you will get later
      $successStatus = $module->getSuccessStatus();
      
      // This is only for this example...
				
      saveSession("extranet_reference_id", $referenceId);
      saveSession("extranet_success_status", $successStatus);
      
      header("Location: " . getUrl(ACTION_EXTRANET_ADD_USER));
    }
    
    elseif ($action == ACTION_EXTRANET_AUTOLOGIN) {
      // Auto-login for extranet purposes (it's actually the same like ACTION_CONNECT)
			// Inits of connection with Ukey1 and redirects user to Ukey1 gateway
			
			$app = new App();
			$app->appId(APP_ID)
				->secretKey(SECRET_KEY);

			$requestId = RandomString::generate(64); // returns string with the length of 128 chars
			$returnUrl = getUrl(ACTION_GET_TOKEN);

			$module = new Connect($app);
			$module->setRequestId($requestId)
				->setReturnUrl($returnUrl)
				->setScope([
          "firstname!",
          "surname!",
          "email!",
				]);

			$connectId = $module->getId();

			// Note that you need to store these values (at least temporarily)...
			
			saveSession("request_id", $requestId);
			saveSession("connect_id", $connectId);
			
			// Now you can redirect to gateway URL (yourself or via redirect() method)
			
			//$gatewayUrl = $module->getGatewayUrl();
			$module->redirect();
    }
	}
	catch (\Exception $e) {
		$exception = print_r($e, true);
	}