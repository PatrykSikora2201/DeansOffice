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
$conn->query("SET NAMES 'utf8'");

$stringToTime = $_POST['stringToTime'];
$studentId = $_SESSION['idStudent'];

$StringToTime = date('Y-m-d H:i:s', $stringToTime);

mysqli_query($conn, "DELETE FROM reservation WHERE ResTime = '".$StringToTime."' AND studentId=".$_SESSION['idStudent'].";");
$conn->close();
$_SESSION['delSuccesful'] = true;
header("Location: ../studentPage.php");

?>