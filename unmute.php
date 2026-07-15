<?php

include "koneksi.php";

mysqli_query($conn, "UPDATE kontrol SET mute='0' WHERE id=1");

header("Location:index.php");

?>