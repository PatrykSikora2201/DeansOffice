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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="icon" href="../assets/img/logo.png">
    <title>Dziekanat - rezerwuj termin</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <link rel="stylesheet" href="style.css">

    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="../assets/css/mystyle.css" rel="stylesheet">
    <link href="../assets/css/style_calendar.css" rel="stylesheet">
    <link href="../assets/css/popup2.css" rel="stylesheet">
    <script src="../assets/js/calendar.js" defer></script>

</head>

<div class="overlay" id="ReservationPopup">
    <div class="wrapper">
        <div class="content">
            <div class="container1">
                <h2>Dostępne terminy</h2>
                <br>
                <div class="column">
                    <table class="tablica">
                        <?php

                        function isZero($minutes){
                            if($minutes == 0 || $minutes == 5) {
                                return '0';
                            }
                        }

                        $day = $_POST['day'];
                        $year = $_POST['year'];
                        if (isset($_POST["month"])) {
                            $month = $_POST["month"];
                        }
                        if (isset($_POST["minutes"])) {
                            $minutes = $_POST["minutes"];
                        }
                        if (isset($_POST["hours"])) {
                            $hours = $_POST["hours"];
                        }
                        if (isset($_POST["typeOfReservation"])) {
                            $timeOfReservation = $_POST["typeOfReservation"];
                        }

                        $hours = [9, 10, 11, 12];
                        $minutes = [0, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55];

                        $Reservation = mysqli_query($conn, 'SELECT ResTime, ToResTime FROM reservation;')->fetch_all();

                        echo '';
                        for ($i = 0; $i < 1; $i++) {
                            for ($j = 0; $j < sizeof($minutes); $j++) {
                                $time = $year . "-" . $month . "-" . $day . " " . $hours[$i] . ":" . $minutes[$j];
                                $selectedTime = strtotime($time);
                                $hasReseravtion = false;

                                if (empty($Reservation)) {
                                    if ($timeOfReservation == 5) {
                                        echo '<form method="post" action="confirmReservation.php">
                                                <input type="hidden" name="stringToTime" value="' . $selectedTime . '">
                                                <input type="hidden" name="timeOfReservation" value="' . $timeOfReservation . '">
                                                <input type="hidden" name="mailAdress" value="' . $_SESSION['mailStudent'] . '">
                                                <input type="hidden" name="studentid" value="' . $_SESSION['idStudent'] . '">
                                                <tr><td>' . $hours[$i] . ':' . isZero($minutes[$j]) . ''.$minutes[$j].'</td><td></td><td>
                                                <input type="submit" class="button5" value="Rezerwuj termin"/></td></tr></form>';

                                    } elseif ($timeOfReservation == 10) {
                                        echo '<form method="post" action="confirmReservation.php">                      
                                                <input type="hidden" name="stringToTime" value="' . $selectedTime . '">
                                                <input type="hidden" name="timeOfReservation" value="' . $timeOfReservation . '">
                                                <input type="hidden" name="mailAdress" value="' . $_SESSION['mailStudent'] . '">
                                                <input type="hidden" name="studentid" value="' . $_SESSION['idStudent'] . '"> <tr><td>' . $hours[$i] . ':' . isZero($minutes[$j]) . ''.$minutes[$j].'</td><td></td><td>
                                                <input type="submit" class="button5" value="Rezerwuj termin"/></td></tr></form>';

                                    } elseif ($timeOfReservation == 15) {
                                        echo '<form method="post" action="confirmReservation.php">                       
                                                <input type="hidden" name="stringToTime" value="' . $selectedTime . '">
                                                <input type="hidden" name="timeOfReservation" value="' . $timeOfReservation . '">
                                                <input type="hidden" name="mailAdress" value="' . $_SESSION['mailStudent'] . '">
                                                <input type="hidden" name="studentid" value="' . $_SESSION['idStudent'] . '"> <tr><td>' . $hours[$i] . ':' . isZero($minutes[$j]) . ''.$minutes[$j].'</td><td></td><td>
                                                <input type="submit" class="button5" value="Rezerwuj termin"/></td></tr></form>';

                                    }
                                } else {

                                    if ($timeOfReservation == 5) {
                                    for ($l = 0; $l < sizeof($Reservation); $l++) {
                                        $ReservationToTime = strtotime($Reservation[$l][0]);
                                        $ReservationToTime2 = strtotime($Reservation[$l][1]);
                                        if ($selectedTime == $ReservationToTime || $selectedTime >= $ReservationToTime && $selectedTime < $ReservationToTime2) {
                                            $hasReseravtion = true;
                                        }
                                    }
                                    }
                                    if ($timeOfReservation == 10) {
                                        for ($l = 0; $l < sizeof($Reservation); $l++) {
                                            $ReservationToTime = strtotime($Reservation[$l][0]);
                                            $ReservationToTime2 = strtotime($Reservation[$l][1]);
                                            if ($selectedTime == $ReservationToTime || ($selectedTime+5*60) >= $ReservationToTime && ($selectedTime+5*60) <= $ReservationToTime2) {
                                                $hasReseravtion = true;
                                            }
                                        }
                                    }


                                    if ($timeOfReservation == 15) {
                                        for ($l = 0; $l < sizeof($Reservation); $l++) {
                                            $ReservationToTime = strtotime($Reservation[$l][0]);
                                            $ReservationToTime2 = strtotime($Reservation[$l][1]);
                                            if ($selectedTime == $ReservationToTime || ($selectedTime+5*60) >= $ReservationToTime && ($selectedTime+5*60) <= $ReservationToTime2 || ($selectedTime+10*60) >= $ReservationToTime && ($selectedTime+10*60) <= $ReservationToTime2) {
                                                $hasReseravtion = true;
                                            }
                                        }
                                    }
                                    if (!$hasReseravtion) {
                                        echo '<form method="post" action="confirmReservation.php">                       
                                                <input type="hidden" name="stringToTime" value="' . $selectedTime . '">
                                                <input type="hidden" name="timeOfReservation" value="' . $timeOfReservation . '">
                                                <input type="hidden" name="mailAdress" value="' . $_SESSION['mailStudent'] . '">
                                                <input type="hidden" name="studentid" value="' . $_SESSION['idStudent'] . '"> <tr><td>' . $hours[$i] . ':' . isZero($minutes[$j]) . ''.$minutes[$j].'</td><td></td><td>
                                                <input type="submit" class="button5" value="Rezerwuj termin"/></td></tr></form>';
                                    }
                                }
                            }
                        }
                        ?>
                    </table>
                </div>
                <div class="column">
                    <table class="tablica">
                        <?php



                        for ($i = 1; $i < 2; $i++) {
                            for ($j = 0; $j < sizeof($minutes); $j++) {
                                $time = date('Y') . "-" . $month . "-" . $day . " " . $hours[$i] . ":" . $minutes[$j];
                                $selectedTime = strtotime($time);
                                $hasReseravtion = false;

                                if (empty($Reservation)) {
                                    if ($timeOfReservation == 5) {
                                        echo '<form method="post" action="confirmReservation.php">                       
                                                <input type="hidden" name="stringToTime" value="' . $selectedTime . '">
                                                <input type="hidden" name="timeOfReservation" value="' . $timeOfReservation . '">
                                                <input type="hidden" name="mailAdress" value="' . $_SESSION['mailStudent'] . '">
                                                <input type="hidden" name="studentid" value="' . $_SESSION['idStudent'] . '"> <tr><td>' . $hours[$i] . ':' . isZero($minutes[$j]) . ''.$minutes[$j].'</td><td></td><td>
                                                <input type="submit" class="button5" value="Rezerwuj termin"/></td></tr></form>';
                                    } elseif ($timeOfReservation == 10) {
                                        echo '<form method="post" action="confirmReservation.php">                       
                                                <input type="hidden" name="stringToTime" value="' . $selectedTime . '">
                                                <input type="hidden" name="timeOfReservation" value="' . $timeOfReservation . '">
                                                <input type="hidden" name="mailAdress" value="' . $_SESSION['mailStudent'] . '">
                                                <input type="hidden" name="studentid" value="' . $_SESSION['idStudent'] . '"> <tr><td>' . $hours[$i] . ':' . isZero($minutes[$j]) . ''.$minutes[$j].'</td><td></td><td>
                                                <input type="submit" class="button5" value="Rezerwuj termin"/></td></tr></form>';
                                    } elseif ($timeOfReservation == 15) {
                                        echo '<form method="post" action="confirmReservation.php">                       
                                                <input type="hidden" name="stringToTime" value="' . $selectedTime . '">
                                                <input type="hidden" name="timeOfReservation" value="' . $timeOfReservation . '">
                                                <input type="hidden" name="mailAdress" value="' . $_SESSION['mailStudent'] . '">
                                                <input type="hidden" name="studentid" value="' . $_SESSION['idStudent'] . '"> <tr><td>' . $hours[$i] . ':' . isZero($minutes[$j]) . ''.$minutes[$j].'</td><td></td><td>
                                                <input type="submit" class="button5" value="Rezerwuj termin"/></td></tr></form>';
                                    }
                                } else {

                                    if ($timeOfReservation == 5) {
                                        for ($l = 0; $l < sizeof($Reservation); $l++) {
                                            $ReservationToTime = strtotime($Reservation[$l][0]);
                                            $ReservationToTime2 = strtotime($Reservation[$l][1]);
                                            if ($selectedTime == $ReservationToTime || $selectedTime >= $ReservationToTime && $selectedTime < $ReservationToTime2) {
                                                $hasReseravtion = true;
                                            }
                                        }
                                    }
                                    if ($timeOfReservation == 10) {
                                        for ($l = 0; $l < sizeof($Reservation); $l++) {
                                            $ReservationToTime = strtotime($Reservation[$l][0]);
                                            $ReservationToTime2 = strtotime($Reservation[$l][1]);
                                            if ($selectedTime == $ReservationToTime || ($selectedTime+5*60) >= $ReservationToTime && ($selectedTime+5*60) <= $ReservationToTime2) {
                                                $hasReseravtion = true;
                                            }
                                        }
                                    }


                                    if ($timeOfReservation == 15) {
                                        for ($l = 0; $l < sizeof($Reservation); $l++) {
                                            $ReservationToTime = strtotime($Reservation[$l][0]);
                                            $ReservationToTime2 = strtotime($Reservation[$l][1]);
                                            if ($selectedTime == $ReservationToTime || ($selectedTime+5*60) >= $ReservationToTime && ($selectedTime+5*60) <= $ReservationToTime2 || ($selectedTime+10*60) >= $ReservationToTime && ($selectedTime+10*60) <= $ReservationToTime2) {
                                                $hasReseravtion = true;
                                            }
                                        }
                                    }
                                    if (!$hasReseravtion) {
                                        echo '<form method="post" action="confirmReservation.php">                       
                                                <input type="hidden" name="stringToTime" value="' . $selectedTime . '">
                                                <input type="hidden" name="timeOfReservation" value="' . $timeOfReservation . '">
                                                <input type="hidden" name="mailAdress" value="' . $_SESSION['mailStudent'] . '">
                                                <input type="hidden" name="studentid" value="' . $_SESSION['idStudent'] . '"> <tr><td>' . $hours[$i] . ':' . isZero($minutes[$j]) . ''.$minutes[$j].'</td><td></td><td>
                                                <input type="submit" class="button5" value="Rezerwuj termin"/></td></tr></form>'; }
                                }
                            }
                        }
                        ?>
                    </table>
                </div>
                <div class="column">
                    <table class="tablica">
                        <?php
                        for ($i = 2; $i < 3; $i++) {
                            for ($j = 0; $j < sizeof($minutes); $j++) {
                                $time = date('Y') . "-" . $month . "-" . $day . " " . $hours[$i] . ":" . $minutes[$j];
                                $selectedTime = strtotime($time);
                                $hasReseravtion = false;

                                if (empty($Reservation)) {
                                    if ($timeOfReservation == 5) {
                                        echo '<form method="post" action="confirmReservation.php">                       
                                                <input type="hidden" name="stringToTime" value="' . $selectedTime . '">
                                                <input type="hidden" name="timeOfReservation" value="' . $timeOfReservation . '">
                                                <input type="hidden" name="mailAdress" value="' . $_SESSION['mailStudent'] . '">
                                                <input type="hidden" name="studentid" value="' . $_SESSION['idStudent'] . '"> <tr><td>' . $hours[$i] . ':' . isZero($minutes[$j]) . ''.$minutes[$j].'</td><td></td><td>
                                                <input type="submit" class="button5" value="Rezerwuj termin"/></td></tr></form>';
                                    } elseif ($timeOfReservation == 10) {
                                        echo '<form method="post" action="confirmReservation.php">                       
                                                <input type="hidden" name="stringToTime" value="' . $selectedTime . '">
                                                <input type="hidden" name="timeOfReservation" value="' . $timeOfReservation . '">
                                                <input type="hidden" name="mailAdress" value="' . $_SESSION['mailStudent'] . '">
                                                <input type="hidden" name="studentid" value="' . $_SESSION['idStudent'] . '"> <tr><td>' . $hours[$i] . ':' . isZero($minutes[$j]) . ''.$minutes[$j].'</td><td></td><td>
                                                <input type="submit" class="button5" value="Rezerwuj termin"/></td></tr></form>';
                                    } elseif ($timeOfReservation == 15) {
                                        echo '<form method="post" action="confirmReservation.php">                       
                                                <input type="hidden" name="stringToTime" value="' . $selectedTime . '">
                                                <input type="hidden" name="timeOfReservation" value="' . $timeOfReservation . '">
                                                <input type="hidden" name="mailAdress" value="' . $_SESSION['mailStudent'] . '">
                                                <input type="hidden" name="studentid" value="' . $_SESSION['idStudent'] . '"> <tr><td>' . $hours[$i] . ':' . isZero($minutes[$j]) . ''.$minutes[$j].'</td><td></td><td>
                                                <input type="submit" class="button5" value="Rezerwuj termin"/></td></tr></form>';
                                    }
                                } else {

                                    if ($timeOfReservation == 5) {
                                        for ($l = 0; $l < sizeof($Reservation); $l++) {
                                            $ReservationToTime = strtotime($Reservation[$l][0]);
                                            $ReservationToTime2 = strtotime($Reservation[$l][1]);
                                            if ($selectedTime == $ReservationToTime || $selectedTime >= $ReservationToTime && $selectedTime < $ReservationToTime2) {
                                                $hasReseravtion = true;
                                            }
                                        }
                                    }
                                    if ($timeOfReservation == 10) {
                                        for ($l = 0; $l < sizeof($Reservation); $l++) {
                                            $ReservationToTime = strtotime($Reservation[$l][0]);
                                            $ReservationToTime2 = strtotime($Reservation[$l][1]);
                                            if ($selectedTime == $ReservationToTime || ($selectedTime+5*60) >= $ReservationToTime && ($selectedTime+5*60) <= $ReservationToTime2) {
                                                $hasReseravtion = true;
                                            }
                                        }
                                    }


                                    if ($timeOfReservation == 15) {
                                        for ($l = 0; $l < sizeof($Reservation); $l++) {
                                            $ReservationToTime = strtotime($Reservation[$l][0]);
                                            $ReservationToTime2 = strtotime($Reservation[$l][1]);
                                            if ($selectedTime == $ReservationToTime || ($selectedTime+5*60) >= $ReservationToTime && ($selectedTime+5*60) <= $ReservationToTime2 || ($selectedTime+10*60) >= $ReservationToTime && ($selectedTime+10*60) <= $ReservationToTime2) {
                                                $hasReseravtion = true;
                                            }
                                        }
                                    }
                                    if (!$hasReseravtion) {
                                        echo '<form method="post" action="confirmReservation.php">                       
                                                <input type="hidden" name="stringToTime" value="' . $selectedTime . '">
                                                <input type="hidden" name="timeOfReservation" value="' . $timeOfReservation . '">
                                                <input type="hidden" name="mailAdress" value="' . $_SESSION['mailStudent'] . '">
                                                <input type="hidden" name="studentid" value="' . $_SESSION['idStudent'] . '"> <tr><td>' . $hours[$i] . ':' . isZero($minutes[$j]) . ''.$minutes[$j].'</td><td></td><td>
                                                <input type="submit" class="button5" value="Rezerwuj termin"/></td></tr></form>'; }
                                }
                            }
                        }
                        ?>

                    </table>
                </div>
                <div class="column">
                    <table class="tablica">
                        <?php

                        for ($i = 3; $i < 4; $i++) {
                        for ($j = 0; $j < sizeof($minutes); $j++) {
                            $time = date('Y') . "-" . $month . "-" . $day . " " . $hours[$i] . ":" . $minutes[$j];
                            $selectedTime = strtotime($time);
                            $hasReseravtion = false;

                            if (empty($Reservation)) {
                                if ($timeOfReservation == 5) {
                                    echo '<form method="post" action="confirmReservation.php">                       
                                                <input type="hidden" name="stringToTime" value="' . $selectedTime . '">
                                                <input type="hidden" name="timeOfReservation" value="' . $timeOfReservation . '">
                                                <input type="hidden" name="mailAdress" value="' . $_SESSION['mailStudent'] . '">
                                                <input type="hidden" name="studentid" value="' . $_SESSION['idStudent'] . '"> <tr><td>' . $hours[$i] . ':' . isZero($minutes[$j]) . ''.$minutes[$j].'</td><td></td><td>
                                                <input type="submit" class="button5" value="Rezerwuj termin"/></td></tr></form>';
                                } elseif ($timeOfReservation == 10) {
                                    echo '<form method="post" action="confirmReservation.php">                       
                                                <input type="hidden" name="stringToTime" value="' . $selectedTime . '">
                                                <input type="hidden" name="timeOfReservation" value="' . $timeOfReservation . '">
                                                <input type="hidden" name="mailAdress" value="' . $_SESSION['mailStudent'] . '">
                                                <input type="hidden" name="studentid" value="' . $_SESSION['idStudent'] . '"> <tr><td>' . $hours[$i] . ':' . isZero($minutes[$j]) . ''.$minutes[$j].'</td><td></td><td>
                                                <input type="submit" class="button5" value="Rezerwuj termin"/></td></tr></form>';
                                } elseif ($timeOfReservation == 15) {
                                    echo '<form method="post" action="confirmReservation.php">                       
                                                <input type="hidden" name="stringToTime" value="' . $selectedTime . '">
                                                <input type="hidden" name="timeOfReservation" value="' . $timeOfReservation . '">
                                                <input type="hidden" name="mailAdress" value="' . $_SESSION['mailStudent'] . '">
                                                <input type="hidden" name="studentid" value="' . $_SESSION['idStudent'] . '"> <tr><td>' . $hours[$i] . ':' . isZero($minutes[$j]) . ''.$minutes[$j].'</td><td></td><td>
                                                <input type="submit" class="button5" value="Rezerwuj termin"/></td></tr></form>';
                                }
                            } else {

                                if ($timeOfReservation == 5) {
                                    for ($l = 0; $l < sizeof($Reservation); $l++) {
                                        $ReservationToTime = strtotime($Reservation[$l][0]);
                                        $ReservationToTime2 = strtotime($Reservation[$l][1]);
                                        if ($selectedTime == $ReservationToTime || $selectedTime >= $ReservationToTime && $selectedTime < $ReservationToTime2) {
                                            $hasReseravtion = true;
                                        }
                                    }
                                }
                                if ($timeOfReservation == 10) {
                                    for ($l = 0; $l < sizeof($Reservation); $l++) {
                                        $ReservationToTime = strtotime($Reservation[$l][0]);
                                        $ReservationToTime2 = strtotime($Reservation[$l][1]);
                                        if ($selectedTime == $ReservationToTime || ($selectedTime+5*60) >= $ReservationToTime && ($selectedTime+5*60) <= $ReservationToTime2) {
                                            $hasReseravtion = true;
                                        }
                                    }
                                }


                                if ($timeOfReservation == 15) {
                                    for ($l = 0; $l < sizeof($Reservation); $l++) {
                                        $ReservationToTime = strtotime($Reservation[$l][0]);
                                        $ReservationToTime2 = strtotime($Reservation[$l][1]);
                                        if ($selectedTime == $ReservationToTime || ($selectedTime+5*60) >= $ReservationToTime && ($selectedTime+5*60) <= $ReservationToTime2 || ($selectedTime+10*60) >= $ReservationToTime && ($selectedTime+10*60) <= $ReservationToTime2) {
                                            $hasReseravtion = true;
                                        }
                                    }
                                }
                                if (!$hasReseravtion) {
                                    echo '<form method="post" action="confirmReservation.php">                       
                                                <input type="hidden" name="stringToTime" value="' . $selectedTime . '">
                                                <input type="hidden" name="timeOfReservation" value="' . $timeOfReservation . '">
                                                <input type="hidden" name="mailAdress" value="' . $_SESSION['mailStudent'] . '">
                                                <input type="hidden" name="studentid" value="' . $_SESSION['idStudent'] . '"> <tr><td>' . $hours[$i] . ':' . isZero($minutes[$j]) . ''.$minutes[$j].'</td><td></td><td>
                                                <input type="submit" class="button5" value="Rezerwuj termin"/></td></tr></form>'; }
                            }
                        }
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</html>