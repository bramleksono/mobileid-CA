<?php

function kirimcallback($url,$IDNumber) {
    //$url="http://postcatcher.in/catchers/5417ac22dc35d6020000077f";
    $data=array('From' => 'Certificate Authority', 'Success' => TRUE, 'NIK' => $IDNumber);
    sendpost($url,$data);
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
$postdata = json_decode(file_get_contents('php://input'), true);
//echo json_encode($postdata);

//identify SI?

//ambil properties
$PID =  $postdata["PID"];
$IDNumber =  $postdata["NIK"];
$filename = $IDNumber.".".$PID;

if (!file_exists("../data/pid/".$filename) == 0) {
    if ($postdata["STATUS"]["Success"] = TRUE) {
        $data = json_decode(file_get_contents("../data/pid/".$filename), true);
        $CallbackURL =  $data["META"]["CallbackURL"];
        kirimcallback($CallbackURL,$IDNumber);
    }
    else echo "Status Gagal";
}
else echo "PID tidak ditemukan";

?>