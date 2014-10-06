<?php
require_once('../lib/filemanipulation.php');


function response($Status,$IDNumber,$pid,$Message) {
    $response = array(
      'Success' => $Status, 
      'NIK' => $IDNumber,
      'PID' => $pid,
      'Message' => $Message,
    );
    return json_encode($response);
}

function tulispid($filename,$postdata) {
	$IDNumber = $postdata["userid"];
	$PID = $postdata["PID"];
    if (!file_exists("./../data/pid/".$filename) == 0) {
        //echo "Catat sebagai proses baru. PID = $pid".PHP_EOL;
        //catat OTP
        $encode = json_encode($postdata);
        //tulis ke file
        if (!file_put_contents("./../data/pid/".$filename, $encode)) {
            echo response(false,$IDNumber,$PID,"Kesalahan menyimpan PID");
        }
        // echo response(true,$IDNumber,$PID,"OK");
    }
    else echo response(false,$IDNumber,$PID,"PID tidak ditemukan");
}

function sendpost($url,$data) {
    $options = array(
        'http' => array(
            'method'  => 'POST',
            'content' => http_build_query($data),
            'header'=>  "Content-type: application/x-www-form-urlencoded"
          )
    );
    // echo json_encode($data);
    $context     = stream_context_create($options);
    $result      = file_get_contents($url, false, $context);
    $response    = json_decode($result, true);
    return $response;
    // return $result;
}

//reveice post message
// $postdata = json_decode(file_get_contents('php://input'), true);
$postdata = $_POST;
$IDNumber = $postdata["userid"];
$tableID = $postdata["id"];
$PID = $postdata["PID"];
$filename = $IDNumber.".".$tableID.".".$PID;

if (!file_exists("./../data/pid/".$filename) == 0) {
	$datapid = json_decode(file_get_contents("./../data/pid/".$filename), true);
	$callbackpath = $datapid["callbackpath"];

	if ($postdata["Success"] == true) {
		$postdata["callbackpath"] = $callbackpath;
	    tulispid($filename,$postdata);
	    $response = sendpost($postdata["callbackpath"], $postdata);
	    // echo $response["Success"];
	    if($response["Success"] == true){
	    	echo response(true,$IDNumber,$PID,"CA - OK");
	    } else {
	    	echo response(false,$IDNumber,$PID,"Webservice response error");
	    }
	    // $data=array('From' => 'Certificate Authority', 'Success' => true, 'NIK' => $IDNumber, 'PID' => $PID);
	    // header('Content-type: application/json');
	    // echo json_encode($data);
	}
	// else echo "Status Gagal";
	else echo response(false,$IDNumber,$PID,"Status gagal");
}

?>