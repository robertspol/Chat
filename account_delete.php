<?php
session_start();

if (!isset($_SESSION['unique_id'])) {
    header('Location: index.php');
    exit();
}

require_once 'connect.php';

try {
    $mysqli = new mysqli($host, $db_user, $db_password, $db_name);

    if ($mysqli->connect_errno > 0) throw new Exception();

    $email = $_SESSION['email'];
    $outgoing_id = $_SESSION['unique_id'];

    $mysqli->query("DELETE FROM users WHERE email='$email'");
    $mysqli->query("DELETE FROM messages WHERE outgoing_id='$outgoing_id' OR incoming_id='$outgoing_id'");

    $mysqli->close();

    $file_name = $_SESSION['file_name'];

    if (file_exists('img/' . $file_name)) {
        unlink('img/' . $file_name);
    }

    session_unset();
    session_destroy();
} catch (Exception $e) {
    echo 'Kod błędu: ' . $e->getCode();
}
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - usunięcie konta</title>
    <link rel="stylesheet" href="./css/index.min.css">
</head>

<body>
    <div class="delete-wrapper">
        <h1>Konto zostało usunięte. Kliknij przycisk poniżej, aby powrócić do strony głównej.</h1>
        <a href="index.php">Strona główna</a>
    </div>
</body>

</html>