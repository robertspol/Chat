<?php
session_start();
require_once 'connect.php';

if (isset($_SESSION['unique_id'])) {
    try {
        $mysqli = new mysqli($host, $db_user, $db_password, $db_name);

        if ($mysqli->errno > 0) throw new Exception();

        $logout_id = $mysqli->real_escape_string($_GET['logout_id']);

        if (isset($logout_id)) {
            $status = 'Nieaktywny';
        }

        $result =  $mysqli->query("UPDATE users SET status='{$status}' WHERE unique_id='{$logout_id}'");

        if ($result) {
            session_unset();
            session_destroy();

            header('Location: logging.php');
        } else {
            header('Location: users.php');
        }
    } catch (Exception $e) {
        echo 'Kod błędu: ' . $e->getCode();
    }
} else {
    header('Location: index.php');
}
