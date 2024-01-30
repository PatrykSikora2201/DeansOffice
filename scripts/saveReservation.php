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

$Tokens = mysqli_query($conn, "SELECT * FROM tokens WHERE isUsed = 1")->fetch_all();

$studentid = $_POST['studentid'];
$addres = $_POST['addres'];
$addres = "patryksikora@protonmail.com";
$stringToTime = $_POST['stringToTime'];
$timeOfReservation = $_POST['timeOfReservation'];
$confirmCode = $_POST['confirmCode'];
$confirmCodeTextfield = $_POST['confirmCodeTextfield'];
$tokenTextfield = $_POST['tokenTextfield'];

$stringToTime = intval($stringToTime);

$ResTime = date('Y-m-d H:i:s',$stringToTime);
$stringToTime = $stringToTime + $timeOfReservation * 60;
$ToResTime = date('Y-m-d H:i:s',$stringToTime);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
function mailSender($mailBody, $addres)
{

    require '../Mailer/src/Exception.php';
    require '../Mailer/src/PHPMailer.php';
    require '../Mailer/src/SMTP.php';

    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->CharSet = "UTF-8";
    $mail->Host = "pop.gmail.com";
    $mail->SMTPDebug = 1;
    $mail->Port = 465;
    $mail->SMTPSecure = 'ssl';
    $mail->SMTPAuth = true;
    $mail->IsHTML(true);
    $mail->Username = 'uczelniadziekanat@gmail.com';
    $mail->Password = 'mhwludeczbdjbehz';
    $mail->setFrom('uczelniadziekanat@gmail.com', 'Dziekanat Uczelnia');
    $mail->AddAddress($addres);
    $mail->Subject = "Dziekanat";
    $mail->Body = $mailBody;
    if (!$mail->Send()) {
        echo "" . $mail->ErrorInfo;
    } else {
        echo "";
    }
}
function generateToken($length = 6)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}

$mailBody = "Termin ".date('Y-m-d H:i',$stringToTime)." został zarezerwowany";

if(isset($tokenTextfield)) {
    $correctToken = false;
    $confirmedCode = false;
    for($i = 0; sizeof($Tokens); $i++){
        if($tokenTextfield == $Tokens[$i][1]){
            $correctToken = true;
            break;
        }
    }
    if ($confirmCodeTextfield == $confirmCode) {
        $confirmedCode = true;
    }
    if($confirmedCode && $correctToken){
        mysqli_query($conn, "INSERT INTO `reservation` (`reservationId`, `studentId`, `ResTime`, `ToResTime`) VALUES (NULL, " . $studentid . ", '" . $ResTime . "', '" . $ToResTime . "');");
        mysqli_query($conn, "DELETE FROM tokens WHERE token = '".$Tokens[$i][1]."'");
        mysqli_query($conn, "INSERT INTO `tokens` (`tokenId`, `token`, `isUsed`) VALUES (NULL, '".generateToken(6)."', '0');");
            mailSender($mailBody, $addres);
        header("Location: ../studentPage.php");
    }else{
        header("Location: ../studentPage.php");
    }
}else{
    $confirmedCode = false;
    if ($confirmCodeTextfield == $confirmCode) {
        $confirmedCode = true;
    }
    if($confirmedCode){
        mysqli_query($conn, "INSERT INTO `reservation` (`reservationId`, `studentId`, `ResTime`, `ToResTime`) VALUES (NULL, " . $studentid . ", '" . $ResTime . "', '" . $ToResTime . "');");
        mailSender($mailBody, $addres);
        header("Location: ../studentPage.php");
    }else{
        header("Location: ../studentPage.php");
    }
}


?>