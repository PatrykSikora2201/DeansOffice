<?php
session_start();
$host = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "queue";
$conn = @new mysqli($host, $db_user, $db_password, $db_name);

if ($conn->connect_errno != 0) {
    echo "Błąd połączenia numer: " . $conn->connect_errno ;
    exit();
}
$conn->query("SET NAMES 'utf8'");

    $login = $_POST['studentLogin'];
    $password = $_POST['studentPassword'];

    if ($result = mysqli_query($conn, "SELECT * FROM students WHERE loginStudent='".$login."' and passwordStudent='".$password."';")) {
        $user_num = $result->num_rows;
        if ($user_num > 0) {
            $_SESSION['loggedStudent'] = true;

            $row = $result->fetch_assoc();
            $_SESSION['idStudent'] = $row['studentid'];
            $_SESSION['nameStudent'] = $row['nameStudent'];
            $_SESSION['surnameStudent'] = $row['surnameStudent'];
            $_SESSION['mailStudent'] = $row['mailStudent'];
            $_SESSION['albumStudent'] = $row['albumStudent'];

            unset($_SESSION['err']);
            $result->free_result();
            header("Location: ../studentPage.php");
            echo $_SESSION['logged'];
        } else {

            $_SESSION['err'] = true;
            header("Location: ../index.php");

        }
    }

$conn->close();


?>