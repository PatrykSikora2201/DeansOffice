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

if (isset($_SESSION['logged'])) {
    header("Location: ../officePanel.php");
} else {

    $login = $_POST['officeLogin'];
    $password = $_POST['officePassword'];

    $login = htmlentities($login, ENT_QUOTES, "UTF-8");
    $password = htmlentities($password, ENT_QUOTES, "UTF-8");
    $password = sha1($password);

    if ($result = mysqli_query($conn, "SELECT * FROM office WHERE loginOffice='$login' and passwordOffice='$password'")) {
        $user_num = $result->num_rows;
        if ($user_num > 0) {
            $_SESSION['logged'] = true;

            $row = $result->fetch_assoc();
            $_SESSION['id'] = $row['officeId'];

            unset($_SESSION['err']);
            $result->free_result();
            header("Location: ../officePanel.php");
            echo $_SESSION['logged'];
        } else {

            $_SESSION['err'] = true;
            header("Location: ../loginView.php");

        }
    }
}
$conn->close();


?>