<?php
require_once('../lib/filemanipulation.php');

function tulispid($IDNumber,$PID,$postdata) {
    $fileoutput = $IDNumber.".".$PID;
    $filektp = $IDNumber.".json";
    $dataktp = json_decode(file_get_contents("../data/ktp/".$filektp), true);

    $fp = fopen("../data/pid/".$fileoutput, 'w');
    foreach ($dataktp["KTP"] as $key => $value) {
        # code...
        if(is_array($value)){
            fwrite($fp, $key.": ");
            foreach ($value as $k => $v) {
                # code...
                fwrite($fp, $v." ");
            }
            fwrite($fp, "\n");
        }
        else{
            fwrite($fp, $key.": ".$value."\n");
        }
    }
    fwrite($fp, "\n\n");
    fwrite($fp, "Signature: ".$postdata["signature"]."\n");
    fwrite($fp, "OTP: ".$postdata["OTP"]."\n");
    fwrite($fp, "HMAC: ".$postdata["hmac"]."\n");
    fclose($fp);
    // $text = json_encode($postdata);
    // if (file_exists("../data/pid/".$filename) == 1) {
    //     if (!file_put_contents("../data/pid/".$filename, $text)) {
    //         exit("kesalahan menyimpan process id");
    //     }
    // }
    // else exit("process id tidak ditemukan");
}

function response($Status,$IDNumber,$pid,$Message) {
    $response['STATUS'] = array(
      'Success' => $Status, 
      'NIK' => $IDNumber,
      'PID' => $pid,
      'Message' => $Message,
    );
    return json_encode($response);
}

//reveice post message
$postdata = json_decode(file_get_contents('php://input'), true);
//echo json_encode($postdata);

//identify SI?

//ambil properties
$PID =  $postdata["PID"];
$IDNumber =  $postdata["NIK"];
$filename = $IDNumber.".".$PID;

if (!file_exists("../data/pid/".$filename) == 0) {
    if ($postdata["Success"] == true) {
        tulispid($IDNumber,$PID,$postdata);
        $data=array('From' => 'Certificate Authority', 'Success' => true, 'NIK' => $IDNumber, 'PID' => $PID);
        header('Content-type: application/json');
        echo json_encode($data);
    }
    // else echo "Status Gagal";
    else echo response(false,$IDNumber,$PID,"Status gagal");
}
// else echo "PID tidak ditemukan";
else echo response(false,$IDNumber,$PID,"PID tidak ditemukan");

?>