<?php
header('Access-Control-Allow-Origin: *');

require_once('../lib/filemanipulation.php');
include ('./addr-path.php');

function savepid($data) {
    $pidtrylimit = 100;
    $i=0;
    $j=0;
    while ($i<1 && $j<$pidtrylimit) {
        $filename = $data["STATUS"]["NIK"].".".$data["STATUS"]["TableID"].".".$data["STATUS"]["PID"];
        if (file_exists("./../data/pid/".$filename) == 0) {
            //echo "Catat sebagai proses baru. PID = $pid".PHP_EOL;
            //catat OTP
            $encode = json_encode($data["STATUS"]);
            //tulis ke file
            if (!file_put_contents("./../data/pid/".$filename, $encode)) {
                echo "kesalahan menyimpan process id";
            }
            $i++;
            $result=1;
        }
        if ($j>$pidtrylimit-2) {
            echo "tidak mendapat PID unik";
            $result=0;
        }
        $j++;
    }
    return $result==1? true:false;
}

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
	$sendpost = $_POST;
	$filename = $sendpost['userid'].".json";
	if (file_exists("../data/ktp/".$filename) == 1) {
        //ambil data ktp
        $data = json_decode(file_get_contents("../data/ktp/".$filename), true);

		$sendpost["regid"] = $data["META"]["DeviceID"];
        $sendpost["CAwebsigncallback"] = $CAwebsigncallback;
        $webservice_callback = $sendpost["callbackpath"];
        unset($sendpost["callbackpath"]);
		$returndata = sendpost($SIaddrsignweb, $sendpost);
        $returndata["STATUS"]["callbackpath"] = $webservice_callback;
		//echo $returndata["STATUS"]["NIK"]." ".$returndata["STATUS"]["PID"];
        if(savepid($returndata)){
            echo "Request sent, PID file created";
        } else {
            echo "Request sent, PID file not created";
        }
    }
} elseif (isset($_POST["websign"])) {
    # code...
    $signature = base64_decode($_POST["websign"]);
    $data = $_POST["hash"];
    $signer_id = $_POST["signer"];
    // $public_key_pem = $details['key'];
    $pub_key = file_get_contents('./../cert/'.$signer_id.'.cert.pem');
    $pub = openssl_pkey_get_public($pub_key);
    // echo $data;
    $r = openssl_verify($data, $signature, $pub, "sha256WithRSAEncryption");
    if($r == 1){
        echo "Verified. Data signed by: ".$signer_id;
    } else {
        echo "Data verification error!";
    }
} else {
	echo "error";
}

?>