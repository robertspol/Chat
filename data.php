<?php
while ($row = $result->fetch_assoc()) {
    $sql2 = "SELECT * FROM messages WHERE (incoming_id={$row['unique_id']}
    OR outgoing_id={$row['unique_id']}) AND (outgoing_id={$outgoing_id}
    OR incoming_id={$outgoing_id}) ORDER BY msg_id DESC LIMIT 1";

    $result2 = $mysqli->query($sql2);

    if ($result2->num_rows > 0) {
        $row2 = $result2->fetch_assoc();
        $res = $row2['message'];
    } else {
        $res = 'Nie ma żadnych wiadomości';
    }

    (strlen($res) > 28) ? $msg = substr($res, 0, 23) . '...' : $msg = $res;

    $you = '';

    if (isset($row2)) {
        ($outgoing_id == $row2['outgoing_id']) ? $you = 'Ty: ' : $you = '';
    }

    ($row['status'] == 'Nieaktywny') ? $offline = ' offline' : $offline = '';

    $output .= '
    <a href="chat_window.php?user_id=' . $row['unique_id'] . ' ">
        <div class="user">
            <img src="img/' . $row['img'] . '" alt="zdjęcie użytkownika">

            <div class="user-info list">
                <div class="name-dot"/>
                    <p class="name">' . $row['first_name'] . ' ' . $row['last_name'] . '</p>      
                    <span class="dot' . $offline . '"></span>
                </div>
                <div class="short-message-wrapper">
                    <p class="short-message">' . $you . $msg . '</p>
                </div>
            </div>
        </div>
    </a>';
}
