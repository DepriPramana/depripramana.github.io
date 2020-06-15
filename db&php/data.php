<?php
$ch = curl_init();
$lat = $_GET["lat"];
$lon = $_GET["lon"];

curl_setopt($ch, CURLOPT_URL, 'https://api-gejala-v2.bersatulawancovid.id/dev/location/regional_rawan_intro?lat='.$lat.'&lon='.$lon);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie.txt");
curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
//echo $result;
$js = json_decode($result,true);
$test =$js['status']['data'];
$a=array();
function bacaURL($kode){
     
     include "conf.php";
     $kode = str_split($kode, 2);
     $kode = $kode[0].'.'.$kode[1].'.'.$kode['2'];
     $query = $db->prepare("SELECT * FROM {$tbl_wilayah} WHERE kode=:id");
     $query->execute(array(':id'=>$kode));
     $d = $query->fetchObject();
     return $d->nama;
}

foreach($test as $mydata)
{
    
    array_push( $a, array('kode_kecamatan'=> $mydata['kode_kecamatan'],
                          'daerah'=>bacaURL($mydata['kode_kecamatan']),
                          'levelKerawanan'=> $mydata['levelKerawanan'],
                          'jumlah_penduduk'=> $mydata['jumlah_penduduk'],
                          'total'=> $mydata['total'],
                          'density'=> $mydata['density'],
                          'loc'=>$mydata['loc']
                        ));
}



curl_close($ch);
echo json_encode($a);