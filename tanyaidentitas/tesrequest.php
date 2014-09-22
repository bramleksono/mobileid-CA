<?php
//working, with incorrect response

$data = '{"KTP":{"BerlakuHingga":"10/08/2018","Kewarganegaraan":"WNI","Pekerjaan":"PELAJAR/MAHASISWA","StatusPerkawinan":"BELUM KAWIN","Agama":"ISLAM","Alamat":{"Kecamatan":"CIBEUNYING","KelDesa":"DAGO","RW":"003","RT":"005","Jalan":"JL GANESHA 12"},"GolDarah":"O","JenisKelamin":"PEREMPUAN","TempatTglLahir":{"TanggalLahir":"10/08/1995","TempatLahir":"JAKARTA"},"Nama ":"NAMA USER3","NIK ":"3271231008950005"},"META":{"CallbackURL":"http://red-trigger-44-141737.apse1.nitrousbox.com/CA/tanyaidentitas/terimakonfirmasiCA.php","Message":"Permintaan Login : Tes tanya ktp","AppID":"123123123asfgfjhhtweasdvbgjtyrewras","DeviceID":"APA91bGG1zYbmWCl7kuFFwlcjmaIj0L-74IAXS5s-gYyOfAp5YNuTCyvbYsyf6hJJcodTkugM_uJwaLU0ibBgyCsDDPBDmH8z5oF3L4HOoujgf4x3RLdu9kE3oyPQEGfocUGIr0JiRSzOMNd5nfSONETRk38rQwnBg"}}';
$SIaddr = "http://red-trigger-44-141737.apse1.nitrousbox.com/SI/daftarlogin.php";

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
    $response    = json_decode($result);
    var_dump($response);
}
$data = json_decode($data, true);
sendpost($SIaddr,$data);

?>