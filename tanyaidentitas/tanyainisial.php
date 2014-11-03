<?php
function get_starred($str){
    $len = strlen($str);
    return substr($str, 0,1). str_repeat('*',$len - 2) . substr($str, $len - 1 ,1);
}

function get_initial($str) {
    $words = explode(" ",$str);
    $inisial = "";
    foreach ($words as $w) {
          $inisial .= $w[0];
    }
    return $inisial;
}

$CAaskphrase1 = "tanyainisial";
$CAaskphrase2 = "tanyaidentitas";

header('Access-Control-Allow-Origin: *');

if (($_POST["askphrase"] == $CAaskphrase1) && (isset($_POST["userid"]))) {
    //cari data ktp di file json
    $userid = $_POST["userid"];
    $filepath = "../data/ktp/".$userid.".json";
    if (file_exists($filepath)) {
        $data = json_decode(file_get_contents($filepath), true);
        $words = get_starred($data["KTP"]["Nama"]);
        
        echo (strtoupper($words));
    }
    else echo "id ".$userid." tidak ditemukan";
} else if (($_POST["askphrase"] == $CAaskphrase2) && (isset($_POST["userid"]))) {
     //cari data ktp di file pid
     $userid = $_POST["userid"];
     $pid =  $_POST["pid"];
     $filepath = "../data/pid/".$userid.".".$pid;
     if (file_exists($filepath)) {
         echo $data = file_get_contents($filepath);
     }
     else echo "id ".$userid." dan pid ".$pid." tidak ditemukan";
}


else {
    echo "kata kunci salah";
}

?>