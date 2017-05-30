<?php
require_once __DIR__ . '/fb/src/Facebook/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$hash = $_GET['hash'];
$hashMd5 = md5('dgfkK3453hksdhk345k' . $hash);

if ($hashMd5 != 'de07f372315b16d6ba95808c1796b0ed') 
{
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
 
$fb = new Facebook\Facebook(array(
'app_id'  => $app_id,
'app_secret' => $app_secret,
'default_graph_version' => 'v2.9',
));

$file = file_get_contents('http://gorillatv.16mb.com/img.php/?hash=' . $hash);
$file = json_decode($file, true);

if (!$file) 
{
	exit;
}

$data = [  
  'url' => $file['url']
];

try {  
  $response = $fb->post("/{$group_id}/photos", $data, $token);  
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

echo 'ID: ' . $graphNode['id'] . ' ' . $fileName;