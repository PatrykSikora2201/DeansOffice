<!DOCTYPE html>
<?php
session_start();

if(isset($_SESSION['logged'])){
    header('Location: officePanel.php');
}
?>
<html>
  <head>
    <meta chraset="utf-8" />
      <link rel="icon" href="assets/img/logo.png">
    <title>Zaloguj pracownika</title>
    <link rel="stylesheet" href="assets/css/style_login.css" />
  </head>
  <body>
    <div class="container">
      <div class="center">
        <h1>Zaloguj się</h1>

        <form method="POST" action="scripts/loginOffice.php" class="input-group">
          <input
            id = "loginid"
            type="text"
            name="login"
            placeholder="Nr. pracownika"
            class="input-field"
            required
          />
          <input
            id = "password"
            type="password"
            name="password"
            placeholder="Hasło"
            class="input-field"
            required
          />

            <?php

            if(isset($_SESSION['err'])){

                echo "<p class='incorret'>Niepoprawne dane*</p>";
                unset($_SESSION['err']);

            }

            ?>

          <button type="submit" name="submit" class="submit-btn">Zaloguj</button>
        </form>
      </div>
    </div>
  </body>
</html>
