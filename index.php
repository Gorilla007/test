<?php
require_once __DIR__ . '/fb/src/Facebook/autoload.php';

// App ID и App Secret из настроек приложения
$app_id = "1906904016262094";
$app_secret = "0dd5fa6b651acac12159514dcafbd75e"; 
// ID страницы и токен
$page_id = "100012282824788";
$secretClient = '890a07907ea193ed88ea0e73c66cf5c5';
$page_id = "329042160848542"; //page id
$group_id = "482208485311252";   // group id
$token = 'EAAbGUbCCk84BAIpVHDTDdF100qocQVXuzZCV2UHltIdJxPZAVDZAuO51feVPGYbXZAmD1PyfUGgFtIG7l58bkCIZAvYXtqmbKFGVHzJHPRZBaJYMoK8D5XqmyoUbwgG0356AeM07v4fZAAkMOst3JRwhji0fjqtoL4ZD';

 
$fb = new Facebook\Facebook(array(
'app_id'  => $app_id,
'app_secret' => $app_secret,
'default_graph_version' => 'v2.9',
));

//---------
$dir = '/var/www/velbix.com/public_html/video';
$files = glob($dir . '/*.mp4');
$file = $files[array_rand($files, 1)];

$filePath = $file;

$fileName = str_replace(".mp4", "", $filePath);
$fileName = str_replace($dir. '/', "", $fileName);
$newFilePath = $dir . '/' . time() . '.mp4';
rename($file, $newFilePath);
$newFilePath2 = $dir . '/' . microtime() . '.mp4';
copy($newFilePath, $newFilePath2);

$f = file_put_contents("/var/www/velbix.com/public_html/file.txt", $fileName . ' ' . $newFilePath . PHP_EOL, FILE_APPEND);
//---------

$data = [  
  'title' => $fileName,
  'description' => $fileName,
  'source' => $fb->videoToUpload($newFilePath),
];

$data2 = [  
  'title' => $fileName,
  'description' => $fileName,
  'source' => $fb->videoToUpload($newFilePath2),
];

try {
  //$response = $fb->post('/me/feed', $data, $token);
  
  $response = $fb->post("/{$group_id}/videos", $data, $token);
  $response = $fb->post("/{$page_id}/videos", $data2, $token);
  unlink($newFilePath);
  unlink($newFilePath2);
} catch(Facebook\Exceptions\FacebookResponseException $e) {
	rename($newFilePath, '/var/www/velbix.com/public_html/videoerr/' . $fileName . '.mp4');
	file_put_contents("error.txt", $newFilePath . ' ' . $fileName . PHP_EOL, FILE_APPEND);
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
	rename($newFilePath, '/var/www/velbix.com/public_html/videoerr/' . $fileName . '.mp4');
	file_put_contents("error.txt", $newFilePath . ' ' . $fileName . PHP_EOL, FILE_APPEND);
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

$graphNode = $response->getGraphNode();
var_dump($response);

echo 'ID: ' . $graphNode['id'] . ' ' . $fileName;