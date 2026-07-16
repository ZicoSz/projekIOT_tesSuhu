<?php

include "koneksi.php";

$data = mysqli_query($conn, "SELECT * FROM kontrol WHERE id=1");

$row = mysqli_fetch_assoc($data);

echo $row['mute'];

?>
