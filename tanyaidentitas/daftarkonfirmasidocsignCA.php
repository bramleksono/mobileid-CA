<?php
include ('./addr-path.php');

function sendpost($url,$data) {
    $options = array(
        'http' => array(
            'method'  => 'POST',
            'content' => http_build_query($data),
            'header'=>  "Content-type: application/x-www-form-urlencoded"
          )
    );

    $context     = stream_context_create($options);
    $result      = file_get_contents($url, false, $context);
    $response    = json_decode($result, true);
    return $response;
    // return $result;
}

if(isset($_POST["id"])){
	// echo '{"status":"request sent"}';
	$filename = $_POST['id'].".json";
	if (file_exists("../data/ktp/".$filename) == 1) {
        //ambil data ktp
        $data = json_decode(file_get_contents("../data/ktp/".$filename), true);
        $sendpost["content"] = $_POST["content"];
        $sendpost["id"] =$_POST["id"];
		$sendpost["regid"] = $data["META"]["DeviceID"];

		$returndata = sendpost($SIaddrmessage, $sendpost);
        
        if($returndata["success"]) {
            $result["success"] = $returndata["success"];
            echo json_encode($result);
        } else {
            echo "Request failed";
        }
    } else echo "id not found";
} else echo "Request unknown";
?>