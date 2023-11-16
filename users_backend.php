<?php
session_start();

if (!isset($_SESSION['unique_id'])) {
    header('Location: index.php');
    exit();
}

require_once 'connect.php';

$outgoing_id = $_SESSION['unique_id'];

try {
    $mysqli = new mysqli($host, $db_user, $db_password, $db_name);

    if ($mysqli->errno > 0) throw new Exception();

    $result = $mysqli->query("SELECT * FROM users WHERE NOT unique_id={$outgoing_id}");
    $output = '';

    if ($result->num_rows == 0) {
        $output .= 'Nie ma dostępnych użytkowników';
    } else if ($result->num_rows > 0) {
        include 'data.php';
    }

    echo $output;
} catch (Exception $e) {
    echo 'Kod błędu: ' . $e->getCode();
}
