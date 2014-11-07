<?php
// $CAaddr = "http://localhost/ca/mobileid-CA/";
$CAmainaddr = "https://mobileid-ca-c9-bramleksono.c9.io/";

// $SIaddr = "http://192.168.2.101/SI/mobileid-SI/";
$SImainaddr = "https://mobileid-si-c9-bramleksono.c9.io/";

$CAcallbackaddr = $CAmainaddr."tanyaidentitas/terimakonfirmasiCA.php";
$CAwebsigncallback = $CAmainaddr."tanyaidentitas/terimawebsignCA.php";
$CAdocsigncallback = $CAmainaddr."tanyaidentitas/terimadocsignCA.php";

$SIaddr = $SImainaddr."daftarlogin.php";
$SIaddrsignweb = $SImainaddr."signweb-si.php";
$SIaddrdocsign = $SImainaddr."daftardocsign.php";
$SIaddrmessage = $SImainaddr."kirimmessage.php";

?>