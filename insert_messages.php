<?php
session_start();

if (isset($_SESSION['unique_id'])) {
    require_once 'connect.php';

    try {
        $mysqli = new mysqli($host, $db_user, $db_password, $db_name);

        if ($mysqli->connect_errno > 0) throw new Exception();

        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data);

        $incoming_id = $mysqli->real_escape_string($data->incoming_id);
        $outgoing_id = $mysqli->real_escape_string($data->outgoing_id);
        $message = $mysqli->real_escape_string($data->message_field);

        if (!empty($message)) {
            $sql = "INSERT INTO messages (incoming_id, outgoing_id, message) VALUES ('$incoming_id', '$outgoing_id', '$message')" or die;

            $mysqli->query($sql);
            $mysqli->close();
        }
    } catch (Exception $e) {
        echo 'Kod błędu: ' . $e->getCode();
    }
} else {
    header('Location: index.php');
}
