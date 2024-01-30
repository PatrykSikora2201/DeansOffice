<!DOCTYPE html>
<html>
<head>
    <meta chraset="utf-8"/>
    <link rel="icon" href="../assets/img/logo.png">
    <title>Zaloguj się</title>
    <link rel="stylesheet" href="./assets/css/planStyle.css"/>
    <link rel="stylesheet" href="./assets/css/popup.css">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

</head>
<body>
<?php
session_start();

if (isset($_SESSION['loggedStudent'])) {
    header("Location: studentPage.php");
}
if (isset($_SESSION['logged'])) {
    header("Location: officePanel.php");
}

echo '
            <div class="overlay">
                <div class="wrapper">
                    <div class="content">
                    <h2>System rezerwacji wizyty w dziekanacie</h2>
                        <div class="container1">
                        <h4>Logowanie studenta</h4>
                            <form method="post" action="scripts/loginStudent.php">
                                <input type="text" name="studentLogin" placeholder="Login" required>
                                <input type="password" name="studentPassword" placeholder="Hasło" required>
                                <input type="submit" class="button" value="Zaloguj się">
                            </form>
                        </div>
                        <br>
                        <div class="container1">
                        <h4>Logowanie pracownika</h4>
                            <form method="post" action="scripts/loginOffice.php">
                                <input type="text" name="officeLogin" placeholder="Login" required>
                                <input type="password" name="officePassword" placeholder="Hasło" required>
                                <input type="submit" class="button" value="Zaloguj się">
                            </form>
                            <form action="plan.php">
                            <input type="submit" class="button" value="Plan dnia">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            ';
?>
</body>
</html>