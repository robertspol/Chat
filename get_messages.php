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
        $output = '';

        $sql = "SELECT * FROM messages 
                LEFT JOIN users ON users.unique_id = messages.incoming_id
                WHERE (outgoing_id='$outgoing_id' AND incoming_id='$incoming_id') OR (outgoing_id='$incoming_id' AND incoming_id='$outgoing_id') ORDER BY msg_id";

        $result = $mysqli->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if ($row['outgoing_id'] == $outgoing_id) {
                    $output .= '
                    <div class="outgoing">
                        <div class="message">
                            <p>' . $row['message'] . '</p>
                        </div>
                    </div>';
                } else {
                    $output .= '
                    <div class="incoming">
                        <img src="img/' . $row['img'] . '" alt="zdjęcie użytkownika">
                        <div class="message">
                            <p>' . $row['message'] . '</p>
                        </div>
                    </div>';
                }
            }

            echo $output;
        }

        $result->close();
        $mysqli->close();
    } catch (Exception $e) {
        echo 'Kod błędu: ' . $e->getCode();
    }
} else {
    header('Location: index.php');
}
