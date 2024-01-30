<?php
session_start();
// require_once "..\scripts\connector.php";
$host = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "queue";
$conn = @new mysqli($host, $db_user, $db_password, $db_name);

if ($conn->connect_errno != 0) {
    echo "Błąd połączenia numer: " . $conn->connect_errno;
    exit();
}

if (!isset($_SESSION['loggedStudent'])) {
    header("Location: ./index.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="icon" href="assets/img/logo.png">
    <title>Dziekanat - rezerwuj termin</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <link rel="stylesheet" href="style.css">

    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/mystyle.css" rel="stylesheet">
    <link href="assets/css/style_calendar.css" rel="stylesheet">
    <link href="assets/css/popup2.css" rel="stylesheet">
    <!--    <script src="assets/js/calendar.js" defer></script>-->

</head>

<body>

<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
        <a href="index.php" class="logo d-flex align-items-center">
            <span class="d-none d-lg-block">Dziekanat</span>
    </div>


</header>

<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse"
               href="contactOffice.php">
                <i class="bi bi-envelope"></i><span>Kontakt</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="contactOffice.php">
                        <i class="bi bi-circle"></i>
                        <span>Dziekanat</span>
                    </a>
                </li>


            </ul>

        </li>


        <li class="nav-item">
            <a class="nav-link collapsed" href="scripts/logout.php">
                <i class="bi bi-box-arrow-in-right"></i>
                <span>Wyloguj</span>
            </a>
        </li>
    </ul>
</aside>

<main id="main" class="main">
    <div class="pagetitle">

    </div>

    <div class="contianer">
        <div class="calendar">
            <div class="calendar-header">
                <span class="month-picker" id="month-picker"><?php echo $_SESSION['nameStudent'];
                    echo " ";
                    echo $_SESSION['surnameStudent']; ?></span>
                <div class="year-picker" id="year-picker">

                </div>
            </div>

            <div class="calendar-body">
                <br>
                <h3>Zapisane rezerwacje</h3>

                <table class="table">
                    <tr>
                        <th>Data</th>
                        <th>Od</th>
                        <th>Do</th>
                    </tr>
                    <?php

                    function fullNameOfMonth($variable)
                    {

                        if ($variable == 1) {
                            return "Styczeń";
                        }
                        if ($variable == 2) {
                            return "Luty";
                        }
                        if ($variable == 3) {
                            return "Marzec";
                        }
                        if ($variable == 4) {
                            return "Kwiecień";
                        }
                        if ($variable == 5) {
                            return "Maj";
                        }
                        if ($variable == 6) {
                            return "Czerwiec";
                        }
                        if ($variable == 7) {
                            return "Lipiec";
                        }
                        if ($variable == 8) {
                            return "Sierpień";
                        }
                        if ($variable == 9) {
                            return "Wrzesień";
                        }
                        if ($variable == 10) {
                            return "Październik";
                        }
                        if ($variable == 11) {
                            return "Listopad";
                        }
                        if ($variable == 12) {
                            return "Grudzień";
                        }
                    }

                    $Reservations = mysqli_query($conn, "SELECT * FROM reservation WHERE studentId = " . $_SESSION['idStudent'] . " ORDER BY ResTime;")->fetch_all();

                    for ($i = 0; $i < sizeof($Reservations); $i++) {
                        $isTooLate = strtotime($Reservations[$i][2]);
                        $currentTime = $_SERVER['REQUEST_TIME'];
                        if ($isTooLate < $currentTime) {
                            mysqli_query($conn, "DELETE FROM reservation WHERE ResTime = '" . $Reservations[$i][2] . "' AND studentId = " . $_SESSION['idStudent'] . ";");
                        }
                    }

                    $Reservations = mysqli_query($conn, "SELECT ResTime, ToResTime FROM reservation WHERE studentId = " . $_SESSION['idStudent'] . " ORDER BY ResTime")->fetch_all();

                    if (sizeof($Reservations) == 0) {
                        echo "";
                    } elseif (sizeof($Reservations) == 1) {
                        $ReservationToString = strtotime($Reservations[0][0]);
                        $ReservationToString2 = strtotime($Reservations[0][1]);
                        echo "<tr><td>" . date('d', $ReservationToString) . " " . fullNameOfMonth(date('m', $ReservationToString)) . " ".date('Y', $ReservationToString)."</td><td>" . date('H', $ReservationToString) . ":" . date('i', $ReservationToString) . "</td><td>". date('H', $ReservationToString2) . ":" . date('i', $ReservationToString2) ."</td>   <td>
                                <form action='scripts/deleteReservation.php' method='post'>
                                <input type='hidden' name='stringToTime' value='". $ReservationToString ."'>
                                <input type='submit' class='button6' value='Anuluj'>
                                </form>
                                </tr>";
                    } else {
                        for ($i = 0; $i < sizeof($Reservations); $i++) {
                            $ReservationToString = strtotime($Reservations[$i][0]);
                            $ReservationToString2 = strtotime($Reservations[$i][1]);
                            echo "<tr><td>" . date('d', $ReservationToString) . " " . fullNameOfMonth(date('m', $ReservationToString)) . " ".date('Y', $ReservationToString)."</td><td>" . date('H', $ReservationToString) . ":" . date('i', $ReservationToString) . "</td><td>". date('H', $ReservationToString2) . ":" . date('i', $ReservationToString2) ."</td>     
                                <td><form action='scripts/deleteReservation.php' method='post'>
                                <input type='hidden' name='stringToTime' value='". $ReservationToString ."'>
                                <input type='submit' class='button6' value='Anuluj'>
                                </form>
                                </tr>";
                        }
                    }

                    function is5Reservation($Reservation){
                        if(sizeof($Reservation) == 5){
                            echo "disabled";
                        }else{
                            echo "";
                        }
                    }
                    ?>
                </table>

            </div>
            <div class="calendar-footer">
            </div>
            <div class="month-list"></div>
        </div>
        <div class="calendar-formate">

            <form method="POST" action="scripts/reservation.php" class="input-group1">
                <h3>Zarezerwuj</h3>
                <br>
                <input name="day" id="day" type="text" placeholder="Dzień" class="input-field2" required>

                <select name="month" id="month" class="input-field2" required>
                    <option selected disabled hidden="">Miesiąc</option>
                    <option value="1">Styczeń</option>
                    <option value="2">Luty</option>
                    <option value="3">Marzec</option>
                    <option value="4">Kwiecień</option>
                    <option value="5">Maj</option>
                    <option value="6">Czerwiec</option>
                    <option value="7">Lipiec</option>
                    <option value="8">Sierpień</option>
                    <option value="9">Wrzesień</option>
                    <option value="10">Październik</option>
                    <option value="11">Listopad</option>
                    <option value="12">Grudzień</option>
                </select>

                <input name="year" type="text" placeholder="Rok" class="input-field2" required>

                <select name="typeOfReservation" id="typeOfReservation" class="input-field2" required>
                    <option selected disabled hidden=""> Czas</option>
                    <option value="5" type="text">5 min</option>
                    <option value="10" type="text">10 min</option>
                    <option value="15" type="text">15 min</option>
                </select >
                <br>

                <input type="submit" class="submit-btn1" value="Zapisz termin" <?php is5Reservation($Reservations)?>>
                <?php
                if (isset($_SESSION['res'])) {

                    echo "<p class='incorret'>Termin jest już zajęty</p>";
                    unset($_SESSION['res']);

                }
                if (isset($_SESSION['toolate'])) {

                    echo "<p class='incorret'>Termin jest niedostępny</p>";
                    unset($_SESSION['toolate']);

                }
                if (isset($_SESSION['correct'])) {

                    echo "<p class='corret'>Termin zapisany</p>";
                    unset($_SESSION['correct']);

                }
                if (isset($_SESSION['delSuccesful'])) {
                    echo "<p class='corret'>Termin anulowany</p>";
                    unset($_SESSION['delSuccesful']);
                }

                ?>
            </form>
        </div>
    </div>


    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
                class="bi bi-arrow-up-short"></i></a>

    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/chart.js/chart.min.js"></script>
    <script src="assets/vendor/echarts/echarts.min.js"></script>
    <script src="assets/vendor/quill/quill.min.js"></script>
    <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>


</main>

</body>

</html>