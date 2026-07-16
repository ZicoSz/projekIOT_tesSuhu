<?php

include 'koneksi.php';

$status = $_GET['status'];

mysqli_query($conn,
"UPDATE kontrol SET status='$status' WHERE id=1");

header("Location: index.php");

?>
