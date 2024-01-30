<?php

session_start();

if (!isset($_SESSION['logged'])) {
    header("Location: ./index.php");
}


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
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="refresh" content="60">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="icon" href="assets/img/logo.png">
    <title>Panel obsługi dziekanatu</title>
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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link href="assets/css/style_office.css" rel="stylesheet">
    <link href="assets/css/style_calendar.css" rel="stylesheet">
    <link href="assets/css/mystyle.css" rel="stylesheet">
    <link href="assets/css/popup1.css" rel="stylesheet">

    <script src="assets/js/watch.js"></script>

    <script>
        $(document).ready(function () {
            $("#genereToken").click(function () {
                $.ajax({
                    url: "tokenGenerator.php", success: function (result) {
                        $("#divShowCode").css('visibility', 'visible').css('opacity', 1);
                        $("#tokenField").text(result);
                    }
                });
            });
        });
    </script>

</head>

<body>

<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
        <a class="logo d-flex align-items-center">
            <span class="d-none d-lg-block">Dziekanat</span>
        </a>
        <span class="d-none d-lg-block"></span>
    </div>

</header>

<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link collapsed" href="scripts/logout.php">
                <i class="bi bi-box-arrow-in-right"></i>
                <span>Wyloguj</span>
            </a>
        </li>

    </ul>
</aside>


<main id="main" class="main">
    <div class="contianer">
        <div class="calendar">
            <div class="calendar-header">
                <span class="month-picker"> Dane studenta</span>

            </div>
            <div class='editor'>
                <?php

                $today = date('Y-m-d');
//                                $today = "2023-09-13";

                $Reservations = mysqli_query($conn, "SELECT * FROM `reservation` WHERE ResTime BETWEEN '" . $today . " 8:00:00'AND '" . $today . " 13:00:00'; ")->fetch_all();
                $Tokens = mysqli_query($conn, 'SELECT * FROM tokens WHERE isUsed=0;')->fetch_all();

                $_SESSION['firstStudent'] = 0;

                if (empty($Reservations)) {

                    echo "<div>Imie</div>";
                    echo "<a>BRAK DANYCH</a>";
                    echo "<div>Nazwisko</div>";
                    echo "<a>BRAK DANYCH</a>";
                    echo "<div>Numer albumu</div>";
                    echo "<a>BRAK DANYCH</a>";
                    echo '</div><div class="editor">';


                    echo "<div>E-Mail</div>";
                    echo "<a>BRAK DANYCH</a>";
                    echo "<div>Data</div>";
                    echo "<a>BRAK DANYCH</a>";
                    echo "<div>Godzina</div>";
                    echo "<a>BRAK DANYCH</a>";

                } else {
                    $Students = mysqli_query($conn, 'SELECT * FROM students WHERE studentid = ' . $Reservations[0][1] . ';')->fetch_all();
                    $stringToTime = strtotime($Reservations[$_SESSION['firstStudent']][2]);
                    echo "<div>Imie</div>";
                    echo "<a>" . $Students[$_SESSION['firstStudent']][1] . "</a>";
                    echo "<div>Nazwisko</div>";
                    echo "<a>" . $Students[$_SESSION['firstStudent']][2] . "</a>";
                    echo "<div>Numer albumu</div>";
                    echo "<a>" . $Students[$_SESSION['firstStudent']][4] . "</a>";
                    echo '</div><div class="editor">';
                    echo "<div>E-Mail</div>";
                    echo "<a>" . $Students[$_SESSION['firstStudent']][3] . "</a>";
                    echo "<div>Data</div>";
                    echo "<a>" . date('d.m.Y', $stringToTime) . "</a>";

                    echo "<div>Godzina</div>";
                    echo "<a>" . date('H:i', $stringToTime) . "</a>";

                }
                ?>

            </div>
        </div>
        <div class="calendar-formate">
            <form action="scripts/endReservation.php" method="post" class="input-group">
                <div>
                    <?php

                    if (empty($Reservations)) {
                        echo "<br><h3 style='color:#9796f0;'><b>BRAK TREMINÓW W DNIU: <br>" . date('d') . "." . date('m') . ".20" . date('y') . "</h3></b><br>";
                    } else {

                        $stringToTimeEnd = strtotime($Reservations[$_SESSION['firstStudent']][2]);
                        $currentTime = $_SERVER['REQUEST_TIME'];
                        $expectedTime = $stringToTimeEnd;

                        if($expectedTime <= $currentTime){
                            echo '<input type="hidden" name="reservationToRemove" value="'.date('Y-m-d H:i:s', $stringToTime).'">';
                            echo '<input type="hidden" name="studentid" value="'.$Students[$_SESSION['firstStudent']][0].'">';
                            mysqli_query($conn,"DELETE FROM reservation WHERE studentid=" . $Students[$_SESSION['firstStudent']][0] . " AND ResTime = '".date('Y-m-d H:i:s', $stringToTime)."';");
                        }

                        echo '<input type="hidden" name="reservationToRemove" value="'.date('Y-m-d H:i:s', $stringToTime).'">';
                        echo '<input type="hidden" name="studentid" value="'.$Students[$_SESSION['firstStudent']][0].'">';

                        echo "<h3 ><b></b></h3><br>";
                        echo '<button type="submit" class="submit-btn2" >Zakończ termin</button>';
//
                    }

                    ?>

                </div>
                <button type="button" name="genereToken" id="genereToken" class="box">Wygeneruj token</button>
            </form>
        </div>
    </div>
    <div class="overlay" id="divShowCode">
        <div class="wrapper">
            <div class="content">
                <div class="container1">
                    <form>
                        <h2>Token</h2>
                        <h2 id="tokenField"></h2>
                        <button class="box1" href="officePanel.php">Zamknij</button>
                    </form>
                </div>
            </div>
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

    <script src="assets/js/main.js"></script>

</body>

</html>