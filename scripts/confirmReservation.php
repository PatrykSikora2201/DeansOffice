<!DOCTYPE html>
<html>
<head>
    <meta chraset="utf-8"/>
    <link rel="icon" href="../assets/img/logo.png">
    <title>Potwierdź rezerwacje</title>
    <link rel="stylesheet" href="../assets/css/planStyle.css"/>
    <link rel="stylesheet" href="../assets/css/popup.css">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
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
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$conn->query("SET NAMES 'utf8'");

$Tokens = mysqli_query($conn, "SELECT * FROM tokens WHERE isUsed = 1")->fetch_all();

//$addres = $_POST['mailAdress'];
$addres = "patryksikora@protonmail.com";
$studentid = $_POST['studentid'];
$stringToTime = $_POST['stringToTime'];
$timeOfReservation = $_POST['timeOfReservation'];
$timeToString = date('Y', $stringToTime)."-".date('m', $stringToTime)."-".date('d', $stringToTime)." ".date('H', $stringToTime).":".date('i', $stringToTime);

function generateCode($length = 4)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}

$confirmCode = generateCode(4);

function mailSender($confirmCode, $addres)
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
    $mail->Body = "Kod potwierdzający rezerwację: ".$confirmCode;
    if (!$mail->Send()) {
        echo "";
    } else {
        echo "";
    }
}

if(date('H', $stringToTime) == '10' && date('i', $stringToTime) <= '30'){

    echo '
            <div class="overlay" id="divSave">
                <div class="wrapper">
                    <div class="content">
                        <div class="container1">
                    
                            <h2>Potwierdź rezerwację terminu</h2>
                            <form method = "post" action = "saveReservation.php">
                                 <input type="text" name="confirmCodeTextfield" placeholder="Kod potiwerdzający" required>
  
                                 <input type="text" name="tokenTextfield" placeholder="Token" required>
                                 <input type="hidden" name="addres" value="' . $addres . '">
                                 <input type="hidden" name="timeOfReservation" value="' . $timeOfReservation . '">
                                 <input type="hidden" name="confirmCode" value="' . $confirmCode . '">
                                 <input type="hidden" name="studentid" value="' . $studentid . '">
                                 <input type="hidden" name="stringToTime" value="' . $stringToTime . '">
                                <input type="submit" class="button" value="Potwierdź">
                            </form>
                        </div>
                        <br>
                        <br>
                        <a>Wybrałeś/aś termin priorytetowy. Do zapisania wymagany jest token dostępny w dziekanacie.</a>
                        <br>
                        <a>Na podany adres E-mail został wysłany kod do potwierdzenia rezerwacji</a>
                    </div>
                    
                </div>
            </div>
            ';

}else {

    echo '
            <div class="overlay" id="divSave">
                <div class="wrapper">
                    <div class="content">
                        <div class="container1">
                    
                            <h2>Potwierdź rezerwację terminu</h2>
                            <form method = "post" action = "saveReservation.php">
                                 <input type="text" name="confirmCodeTextfield" placeholder="Kod potiwerdzający" required>
                                 <input type="hidden" name="addres" value="' . $addres . '">
                                 <input type="hidden" name="timeOfReservation" value="' . $timeOfReservation . '">
                                 <input type="hidden" name="confirmCode" value="' . $confirmCode . '">
                                 <input type="hidden" name="studentid" value="' . $studentid . '">
                                 <input type="hidden" name="stringToTime" value="' . $stringToTime . '">
                                <input type="submit" class="button" value="Potwierdź">
                            </form>
                        </div>
                        <br>
                        <br>
                        <a>Na podany adres E-mail został wysłany kod do potwierdzenia rezerwacji</a>
                    </div>
                    
                </div>
            </div>
            ';
}
?>
<div class="hidden"><?php mailSender($confirmCode, $addres); ?></div>
</body>
</html>
