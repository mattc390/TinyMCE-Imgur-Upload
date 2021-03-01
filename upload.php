<?php
$origins = array("http://localhost", "http://192.168.1.1", "http://something.com");

  if (isset($_SERVER['HTTP_ORIGIN'])) {
    // Check orgin
    if (in_array($_SERVER['HTTP_ORIGIN'], $origins)) {
      header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
    } else {
      header("HTTP/1.1 403 Origin Denied");
      return;
    }
  }
  
$temp = current($_FILES);

    // Sanitize input
    if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
        header("HTTP/1.1 400 Invalid file name.");
        return;
    }

    // Verify extension
    if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png"))) {
        header("HTTP/1.1 400 Invalid extension.");
        return;
    }
	
    $client_id = 'a5de0b7915fa108';

        $file = file_get_contents($temp["tmp_name"]);

        $url = 'https://api.imgur.com/3/image.json';
        $headers = array("Authorization: Client-ID $client_id");
        $pvars = array('image' => base64_encode($file));

        $curl = curl_init();

        curl_setopt_array($curl, array(
           CURLOPT_URL=> $url,
           CURLOPT_TIMEOUT => 30,
           CURLOPT_POST => 1,
           CURLOPT_RETURNTRANSFER => 1,
           CURLOPT_HTTPHEADER => $headers,
           CURLOPT_POSTFIELDS => $pvars
        ));

        $json_returned = curl_exec($curl); 
		$array = json_decode($json_returned, true);
		$img_url = $array['data']['link'];
		
		if (strpos($img_url, 'imgur') !== false) {
			echo json_encode(array('location' => $img_url));
		}else{
			header("HTTP/1.1 500 Server Error");
            return;
		}



?>
