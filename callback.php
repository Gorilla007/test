<?php
session_start(); 

require_once __DIR__ . '/fb/src/Facebook/autoload.php';

$hash = $_GET['hash'];
$hashMd5 = md5('dgfkK3453hksdhk345k' . $hash);

if ($hashMd5 != 'de07f372315b16d6ba95808c1796b0ed') 
{
	echo 'error hash';
	exit;
}

$params = file_get_contents('http://gorillatv.16mb.com/key.php/?hash=' . $hash);
$params = json_decode($params, true); 

// App ID и App Secret из настроек приложения
$app_id = $params['app_id'];
$app_secret = $params['app_secret']; 
// ID страницы и токен
$page_id = $params['page_id']; //page id
$group_id = $params['group_id'];   // group id
$token = $params['token'];

$fb = new Facebook\Facebook([
'app_id'  => $app_id,
'app_secret' => $app_secret,
'default_graph_version' => 'v2.9',
]); 
 
$helper = $fb->getRedirectLoginHelper();

$f = file_get_contents('http://gorillatv.16mb.com/savefb-token.php/?token=' . $helper->getAccessToken());
header("Location: http://gorillatv.16mb.com/publish.php");
exit;
 
try {
$accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
// When Graph returns an error
echo 'Graph returned an error: ' . $e->getMessage();
exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
// When validation fails or other local issues
echo 'Facebook SDK returned an error: ' . $e->getMessage();
exit;
}
 
if (! isset($accessToken)) {
	if ($helper->getError()) {
		header('HTTP/1.0 401 Unauthorized');
		echo "Error: " . $helper->getError() . "\n";
		echo "Error Code: " . $helper->getErrorCode() . "\n";
		echo "Error Reason: " . $helper->getErrorReason() . "\n";
		echo "Error Description: " . $helper->getErrorDescription() . "\n";
	} else {
		header('HTTP/1.0 400 Bad Request');
		echo 'Bad request';
	}
	exit;
}
 
// Logged in
echo '<h3>Access Token</h3>';
var_dump($accessToken->getValue());
 
// The OAuth 2.0 client handler helps us manage access tokens
$oAuth2Client = $fb->getOAuth2Client();
 
// Get the access token metadata from /debug_token
$tokenMetadata = $oAuth2Client->debugToken($accessToken);
echo '<h3>Metadata</h3>';
var_dump($tokenMetadata);

// Validation (these will throw FacebookSDKException's when they fail)
$tokenMetadata->validateAppId($app_id);
// If you know the user ID this access token belongs to, you can validate it here
//$tokenMetadata->validateUserId('123');
$tokenMetadata->validateExpiration();
 
if (! $accessToken->isLongLived()) {
// Exchanges a short-lived access token for a long-lived one
try {
	$accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
} 
catch (Facebook\Exceptions\FacebookSDKException $e) {
	echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
	exit;
}
 
echo '<h3>Long-lived</h3>';
var_dump($accessToken->getValue());
}