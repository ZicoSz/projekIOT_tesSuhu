<?php

include 'koneksi.php';

$suhu = $_GET['suhu'];

$query = "INSERT INTO data_suhu(suhu)
VALUES('$suhu')";

mysqli_query($conn, $query);

echo "Data tersimpan";

?>