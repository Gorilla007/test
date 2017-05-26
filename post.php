<?php
require_once __DIR__ . '/fb/src/Facebook/autoload.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// App ID и App Secret из настроек приложения
$app_id = "1906904016262094";
$app_secret = "0dd5fa6b651acac12159514dcafbd75e"; 
// ID страницы и токен
$page_id = "100012282824788";
$secretClient = '890a07907ea193ed88ea0e73c66cf5c5';
$page_id = "329042160848542"; //page id
$group_id = "482208485311252";   // group id
$object_id = "330078190744939";   // object id
$token = 'EAAbGUbCCk84BAIpVHDTDdF100qocQVXuzZCV2UHltIdJxPZAVDZAuO51feVPGYbXZAmD1PyfUGgFtIG7l58bkCIZAvYXtqmbKFGVHzJHPRZBaJYMoK8D5XqmyoUbwgG0356AeM07v4fZAAkMOst3JRwhji0fjqtoL4ZD';

 
$fb = new Facebook\Facebook(array(
'app_id'  => $app_id,
'app_secret' => $app_secret,
'default_graph_version' => 'v2.9',
));

// описание параметров есть в документации
$linkData = array(
'link' => 'https://www.facebook.com/groups/gorillatv/',
'message' => 'Смотрим!',
);

try {  
  $response = $fb->post("/{$page_id}/feed", $linkData, $token); // post to page  
} catch(Facebook\Exceptions\FacebookResponseException $e) {	
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {	
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

$graphNode = $response->getGraphNode();
var_dump($response);

echo 'ID: ' . $graphNode['id'];