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

$callback = "http://gorilla-gorilla.1d35.starter-us-east-1.openshiftapps.com/callback.php/?hash=" . $hash; 

$fb = new Facebook\Facebook([
'app_id'  => $app_id,
'app_secret' => $app_secret,
'default_graph_version' => 'v2.9',
]); 

$helper = $fb->getRedirectLoginHelper();
 

// для публикации в группах достаточно разрешения publish_actions
// для публикации на страницах нужны все 3 элемента

$permissions = ['publish_actions','manage_pages','publish_pages'];

$loginUrl = $helper->getLoginUrl($callback, $permissions);

 

echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';