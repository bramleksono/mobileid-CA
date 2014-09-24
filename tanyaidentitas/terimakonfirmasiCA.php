<?php

function tulispid($IDNumber,$PID,$data) {
    $filename = $IDNumber.".".$PID;
    $text = json_encode($data);
    if (file_exists("../data/pid/".$filename) == 1) {
        if (!file_put_contents("../data/pid/".$filename, $text)) {
            exit("kesalahan menyimpan process id");
        }
    }
    else exit("process id tidak ditemukan");
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
        tulispid($IDNumber,$PID,$postdata);
        $data=array('From' => 'Certificate Authority', 'Success' => TRUE, 'NIK' => $IDNumber, 'PID' => $PID);
        header('Content-type: application/json');
        echo json_encode($data);
    }
    else echo "Status Gagal";
}
else echo "PID tidak ditemukan";

?>