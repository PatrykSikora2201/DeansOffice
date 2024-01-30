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

$Tokens = mysqli_query($conn, 'SELECT * FROM tokens WHERE isUsed=0;')->fetch_all();
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

function randomToken($array, $conn)
{
    $newToken = generateToken();
    if (!empty($array)) {
        $randomToken = array_rand($array, 1);
        mysqli_query($conn, "UPDATE tokens SET isUsed = 1 WHERE token = '" . $array[$randomToken][1] . "';");
        return $array[$randomToken][1];
    } else {
        mysqli_query($conn, "INSERT INTO tokens (tokenId, token, isUsed) VALUES (NULL, '" . $newToken . "', 0);");
        return $newToken;

    }
}

echo randomToken($Tokens, $conn);

?>