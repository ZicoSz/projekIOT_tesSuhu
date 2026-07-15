<?php
include 'koneksi.php';

$data = mysqli_query($conn, "SELECT * FROM data_suhu ORDER BY id DESC LIMIT 1");
$row = mysqli_fetch_assoc($data);

$suhu = $row['suhu'] ?? 0;
?>

<!DOCTYPE html>
<html>

<head>

    <title>Monitoring Suhu IoT</title>

    <meta http-equiv="refresh" content="5">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Poppins', sans-serif;
        }

        body{

            background:
            linear-gradient(135deg,#0f172a,#1e3a8a,#0f172a);

            min-height:100vh;

            color:white;

            padding:40px 20px;
        }

        .container{
            max-width:1200px;
            margin:auto;
        }

        .top{
            display:flex;
            justify-content:center;
            margin-bottom:40px;
        }

        .card{

            width:450px;

            background:rgba(255,255,255,0.08);

            backdrop-filter: blur(10px);

            border:1px solid rgba(255,255,255,0.1);

            border-radius:30px;

            padding:40px;

            text-align:center;

            box-shadow:
            0 8px 32px rgba(0,0,0,0.3);
        }

        .title{
            font-size:32px;
            font-weight:600;
            margin-bottom:20px;
        }

        .temp{

            font-size:90px;

            font-weight:700;

            color:#38bdf8;

            text-shadow:
            0 0 20px rgba(56,189,248,0.5);
        }

        .subtitle{

            margin-top:10px;

            color:#cbd5e1;

            font-size:18px;
        }

        .clock{

            margin-top:15px;

            font-size:22px;

            color:#94a3b8;
        }

        .buttons{

            margin-top:30px;

            display:flex;

            flex-wrap:wrap;

            justify-content:center;

            gap:12px;
        }

        button{

            padding:12px 20px;

            border:none;

            border-radius:14px;

            font-size:16px;

            font-weight:600;

            cursor:pointer;

            transition:0.3s;
        }

        .on{
            background:#22c55e;
            color:white;
        }

        .on:hover{

            transform:translateY(-3px);

            box-shadow:
            0 10px 20px rgba(34,197,94,0.4);
        }

        .off{
            background:#ef4444;
            color:white;
        }

        .off:hover{

            transform:translateY(-3px);

            box-shadow:
            0 10px 20px rgba(239,68,68,0.4);
        }

        .delete{
            background:#f59e0b;
            color:white;
        }

        .delete:hover{

            transform:translateY(-3px);

            box-shadow:
            0 10px 20px rgba(245,158,11,0.4);
        }

        .download{
            background:#8b5cf6;
            color:white;
        }

        .download:hover{

            transform:translateY(-3px);

            box-shadow:
            0 10px 20px rgba(139,92,246,0.4);
        }

        h2{

            text-align:center;

            margin-bottom:20px;

            font-size:40px;
        }

        .table-container{

            background:rgba(255,255,255,0.08);

            backdrop-filter:blur(10px);

            border-radius:25px;

            padding:20px;

            overflow:hidden;

            box-shadow:
            0 8px 32px rgba(0,0,0,0.3);
        }

        table{

            width:100%;

            border-collapse:collapse;
        }

        th{

            background:#38bdf8;

            color:white;

            padding:18px;

            font-size:18px;
        }

        td{

            padding:16px;

            text-align:center;

            border-bottom:
            1px solid rgba(255,255,255,0.1);

            color:white;
        }

        tr:hover{
            background:rgba(255,255,255,0.05);
        }

        @media(max-width:768px){

            .temp{
                font-size:70px;
            }

            h2{
                font-size:30px;
            }

            .card{
                width:100%;
            }

        }

    </style>

</head>

<body>

<div class="container">

    <div class="top">

        <div class="card">

            <div class="title">
                🌡 Monitoring Suhu IoT
            </div>

            <div class="temp">
                <?php echo $suhu; ?>°C
            </div>

            <div class="subtitle">
                Data realtime dari ESP8266
            </div>

            <div class="clock" id="jam">
            </div>

            <div class="buttons">

                <a href="kontrol.php?status=1">
                    <button class="on">
                        START
                    </button>
                </a>

                <a href="kontrol.php?status=0">
                    <button class="off">
                        STOP
                    </button>
                </a>

                <a href="mute.php">
                    <button class="mute">
                        STOP ALARM
                    </button>
                </a>

                <a href="hapus.php">
                    <button class="delete">
                        HAPUS
                    </button>
                </a>

                <a href="download.php">
                    <button class="download">
                        DOWNLOAD
                    </button>
                </a>

            </div>

        </div>

    </div>

    <h2>
        📊 Riwayat Data
    </h2>

    <div class="table-container">

        <table>

            <tr>
                <th>ID</th>
                <th>Jam</th>
                <th>Suhu</th>
            </tr>

            <?php

            $semua = mysqli_query($conn,
            "SELECT * FROM data_suhu ORDER BY id DESC");

            while($d = mysqli_fetch_array($semua)){

            ?>

            <tr>

                <td>
                    <?php echo $d['id']; ?>
                </td>

                <td>
                    <?php
                    echo date('H:i:s',
                    strtotime($d['waktu']));
                    ?>
                </td>

                <td>
                    🌡 <?php echo $d['suhu']; ?> °C
                </td>

            </tr>

            <?php } ?>

        </table>

    </div>

</div>

<script>

function updateClock(){

    const now = new Date();

    const jam =
    now.toLocaleTimeString();

    document.getElementById("jam")
    .innerHTML = jam;
}

setInterval(updateClock,1000);

updateClock();

</script>

</body>

</html> 