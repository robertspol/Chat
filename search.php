<?php
session_start();

if (!isset($_SESSION['unique_id'])) {
    header('Location: index.php');
    exit();
}

require_once 'connect.php';

$search_term = $_POST['search_term'];
$outgoing_id = $_SESSION['unique_id'];

try {
    $mysqli = new mysqli($host, $db_user, $db_password, $db_name);

    if ($mysqli->errno > 0) throw new Exception();

    $sql = "SELECT * FROM users WHERE NOT unique_id={$outgoing_id} AND (first_name LIKE '%{$search_term}%' OR last_name LIKE '%{$search_term}%')";

    $result = $mysqli->query($sql);
    $output = '';

    if ($result->num_rows > 0) {
        include 'data.php';
    } else {
        $output = "Żaden użytkownik nie pasuje";
    }

    echo $output;
} catch (Exception $e) {
    echo 'Kod błędu: ' . $e->getCode();
}
