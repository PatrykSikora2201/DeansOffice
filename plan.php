<?php
session_start();
$host = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "queue";
$conn = @new mysqli($host, $db_user, $db_password, $db_name);

if ($conn->connect_errno != 0) {
    echo "Błąd połączenia numer: " . $conn->connect_errno;
    exit();
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta chraset="utf-8"/>
    <meta http-equiv="refresh" content="30">
    <link rel="icon" href="assets/img/logo.png">
    <title>Kolejka</title>
    <link rel="stylesheet" href="assets/css/planStyle.css"/>
    <link rel="stylesheet" href="assets/css/popup.css">
    <link rel="stylesheet" href="assets/css/mystyle.css">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="container">

    <div class="center">
        <h1>Kolejka <?php echo date('d') . "." . date('m') . "." . date('Y') ?></h1>

        <?php

        $hours = [9, 10, 11, 12];
        $minutes = [0, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55];
        $today = date('Y-m-d');
//        $today = "2023-09-13"
        $Reservation = mysqli_query($conn, "SELECT * FROM reservation WHERE ResTime BETWEEN '" . $today . " 8:00:00'AND '" . $today . " 13:00:00' ORDER BY ResTime;")->fetch_all();
        $Students = mysqli_query($conn, "SELECT * FROM students;")->fetch_all();

        echo '';
        ?>
        <table class="table">
            <?php

            $currentTime = $_SERVER['REQUEST_TIME'];

            if (!empty($Reservation)) {
                if (strtotime($Reservation[0][2]) < $currentTime) {
                    mysqli_query($conn, "DELETE FROM reservation WHERE ResTime = '" . $Reservation[0][2] . "' AND studentId = " . $_SESSION['idStudent'] . ";");
                }
            }

            if (sizeof($Reservation) > 1) {
                $currentStudentTime = strtotime($Reservation[0][2]);
                $nextStudentTime = strtotime($Reservation[1][2]);
                $currentStudentTimeEnd = strtotime($Reservation[0][3]);
                $nextStudentTimeEnd = strtotime($Reservation[1][3]);
                $surrentStudentAlbum = '';
                $nextStudentAlbum = '';

                for ($i = 0; $i < sizeof($Students); $i++) {
                    if ($Students[$i][0] == $Reservation[0][1]) {
                        $surrentStudentAlbum = $Students[$i][4];
                        break;
                    }
                }
                for ($i = 0; $i < sizeof($Students); $i++) {
                    if ($Students[$i][0] == $Reservation[1][1]) {
                        $nextStudentAlbum = $Students[$i][4];
                        break;
                    }
                }
                echo '<tr><td><h2>Najbliższy obsługiwany student:</h2></td></tr>';
                echo '<tr><td>' . date('H:i', $currentStudentTime) . ' - ' . date('H:i', $currentStudentTimeEnd) . '</td>
                            <td>Numer albumu: <b>' . $surrentStudentAlbum . '</b></td></tr> <hr>';

                echo '<tr><td><h2>Następnie</h2></td></tr>';
                echo '<tr><td>' . date('H:i', $nextStudentTime) . ' - ' . date('H:i', $nextStudentTimeEnd) . '</td>
                            <td>Numer albumu: ' . $nextStudentAlbum . '</td></tr> <br>';

            } elseif (sizeof($Reservation) == 1) {
                $currentStudentTime = strtotime($Reservation[0][2]);
                $currentStudentTimeEnd = strtotime($Reservation[0][3]);
                $surrentStudentAlbum = '';
                for ($i = 0; $i < sizeof($Students); $i++) {
                    if ($Students[$i][0] == $Reservation[0][1]) {
                        $surrentStudentAlbum = $Students[$i][4];
                        break;
                    }
                }

                    echo '<tr><td><h2>Najbliższy obsługiwany student: </h2></td></tr>';
                    echo '<tr><td>' . date('H:i', $currentStudentTime) . ' - ' . date('H:i', $currentStudentTimeEnd) . '</td>
                            <td>Numer albumu: ' . $surrentStudentAlbum . '</td></tr> <hr>';

            } else {
                echo '<tr><td><h2>Brak rezerwacji</h2></td></tr><hr>';
            }
            ?>

        </table>
    </div>
</body>
</html>
