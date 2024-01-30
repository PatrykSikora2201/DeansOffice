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
$reservationToRemove = $_POST['reservationToRemove'];
$studentid = $_POST['studentid'];

    mysqli_query($conn, "DELETE FROM reservation WHERE studentid=" . $studentid . " AND ResTime = '".$reservationToRemove."';");
    header('Location: ../officePanel.php');

?>