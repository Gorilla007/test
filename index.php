<?php
require_once __DIR__ . '/fb/src/Facebook/autoload.php';

$params = file_get_contents('http://gorillatv.16mb.com/key.php');
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

$file = file_get_contents('http://gorillatv.16mb.com/file.php');
$file = json_decode($file, true);
$fileName = $file['name'];
$filePath = $file['g_name'];
$filePath2 = $file['p_name'];

$data = [  
  'title' => $fileName,
  'description' => $fileName,
  'source' => $fb->videoToUpload($filePath),
];

$data2 = [  
  'title' => $fileName,
  'description' => $fileName,
  'source' => $fb->videoToUpload($filePath2),
];

try {
  //$response = $fb->post('/me/feed', $data, $token);
  
  $response = $fb->post("/{$group_id}/videos", $data, $token);
  $response = $fb->post("/{$page_id}/videos", $data2, $token); 
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