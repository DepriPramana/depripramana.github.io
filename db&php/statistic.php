<?php

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://api-gejala-v2.bersatulawancovid.id/dev/statistics/overview');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
$js = json_decode($result,true);
echo json_encode($js['statsOverview']);
curl_close($ch);
//echo $js['status']['status'];
//var_dump($js);