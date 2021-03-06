<?php
require_once('../lib/filemanipulation.php');
include ('./addr-path.php');

date_default_timezone_set('Asia/Jakarta');

//konfigurasi
$CAid = "123123123asfgfjhhtweasdvbgjtyrewras";
// $CAcallbackaddr = "http://localhost/ca/mobileid-CA/tanyaidentitas/terimakonfirmasiCA.php";
// $CAcallbackaddr = "http://red-trigger-44-141737.apse1.nitrousbox.com/CA/tanyaidentitas/terimakonfirmasiCA.php";
// $SIaddr = "http://localhost/si/mobileid-SI/daftarlogin.php";
// $SIaddr = "http://red-trigger-44-141737.apse1.nitrousbox.com/SI/daftarlogin.php";
//

function cariapp($appid) {
    return findline($appid,'../data/app.txt');
}

function catatpid($IDNumber,$pid) {
    $filename = $IDNumber.".".$pid;
    if (file_exists("../data/pid/".$filename) == 0) {
        //echo "Catat sebagai proses baru. PID = $pid".PHP_EOL;
        $text = "Menunggu konfirmasi..";
        //tulis ke file
        if (!file_put_contents("../data/pid/".$filename, $text)) {
            exit("kesalahan menyimpan process id");
        }
    }
}

function constructrequest($appid,$message,$callbackurl,$data) {
    $data["META"]["AppID"]=$appid;
    $data["META"]["Message"]=$message;
    $data["META"]["CallbackURL"]=$callbackurl;
    return $data;
}

function sendpost($url,$data) {
    $options = array(
        'http' => array(
            'method'  => 'POST',
            'content' => json_encode( $data ),
            'header'=>  "Content-Type: application/json\r\n" .
                        "Accept: application/json\r\n"
          )
    );

    $context     = stream_context_create($options);
    $result      = file_get_contents($url, false, $context);
    $response    = json_decode($result, true);
    return $response;
}

//reveice post message
//var_dump($_POST);
$postdata = json_decode(file_get_contents('php://input'), true);
$AppID =  $postdata["META"]["AppID"];
$postmessage =  $postdata["META"]["Message"];
$IDNumber =  $postdata["ASK"]["NIK"];

$filename = $IDNumber.".json";

if (cariapp($AppID) >= 0) {
    if (file_exists("../data/ktp/".$filename) == 1) {
        //ambil data ktp
        $data = json_decode(file_get_contents("../data/ktp/".$filename), true);

        //data ktp ada, catat permintaan dan lanjutkan query ke SI
        $message = $postmessage;
        $request = constructrequest($CAid,$message,$CAcallbackaddr,$data);
        
        $sendquery  = sendpost($SIaddr,$request);

        if ($sendquery["STATUS"]["Success"] == true) {
            //catat query di file pid
            $pid = $sendquery["STATUS"]["PID"];
            $daftar = catatpid($IDNumber,$pid);
            //kirim data ke SI
            // echo "Permintaan berhasil";
            echo json_encode($sendquery);
        }
         else echo "Tidak dapat mengirim query";
    }
     else echo "Nomor identitas tidak terdaftar";
}
 else echo "App ID tidak terdaftar";
?>