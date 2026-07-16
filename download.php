<?php

include 'koneksi.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="data_suhu.csv"');

$output = fopen("php://output", "w");

fputcsv(
    $output,
    array('ID','Waktu','Suhu'),
    ';'
);

$data = mysqli_query($conn,
"SELECT * FROM data_suhu ORDER BY id DESC");

while($d = mysqli_fetch_assoc($data)){

    fputcsv(
        $output,
        array(
            $d['id'],
            $d['waktu'],
            $d['suhu']
        ),
        ';'
    );
}

fclose($output);

?>  
