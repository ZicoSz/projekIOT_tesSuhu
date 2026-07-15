<?php

include 'koneksi.php';

mysqli_query($conn,
"DELETE FROM data_suhu");

header("Location: index.php");

?>