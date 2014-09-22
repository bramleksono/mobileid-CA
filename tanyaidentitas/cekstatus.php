<?php
$postdata = json_decode(file_get_contents('php://input'), true);
$AppID =  $postdata["META"]["AppID"];
$IDNumber =  $postdata["ASK"]["NIK"];

$filename = $AppID.".".$IDNumber;

if (file_exists("../data/access/".$filename) == 1) {
    $data = json_decode(file_get_contents("../data/access/".$filename), true);
    $status = $data["STATUS"];
    switch ($status) {
        case 0 :
        echo "Menunggu konfirmasi";
        break;
        
        case 1 :
        echo "Boleh login";
        break;
        
        case 2 :
        echo "Tidak boleh login";
        break;
        
        default:
        echo "Status tidak diketahui";
    }
}
else echo "Tidak ada permintaan";

?>