<?php
session_start();

if (!isset($_SESSION['unique_id'])) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - okno rozmowy</title>
    <link rel="stylesheet" href="css/index.min.css">
    <script src="https://kit.fontawesome.com/83b6ac67d9.js" crossorigin="anonymous"></script>
</head>

<body>
    <section class="chat-container">
        <div class="user chat">
            <a href="users.php"><i class="fa-solid fa-arrow-left"></i></a>
            <?php
            require_once 'connect.php';
            $mysqli = new mysqli($host, $db_user, $db_password, $db_name);

            $user_id = $mysqli->real_escape_string($_GET['user_id']);

            $sql = "SELECT * FROM users WHERE unique_id={$user_id}";
            $result = $mysqli->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
            }
            ?>
            <img src="img/<?php echo $row['img'] ?>" alt="zdjęcie użytkownika">

            <div class="user-info">
                <p class="name">
                    <?php echo $row['first_name'] . ' ' . $row['last_name'] ?>
                </p>
                <p class="is-active"> <?php echo $row['status'] ?></p>
            </div>
        </div>

        <div class="chat-window"></div>

        <form action="insert_messages.php" class="message-field-wrapper" autocomplete="off">
            <input type="text" name="incoming_id" hidden value="<?php echo $user_id ?>">
            <input type="text" name="outgoing_id" hidden value="<?php echo $_SESSION['unique_id'] ?>">

            <input type="text" class="message-field" name="message_field" placeholder="Wpisz wiadomość...">

            <button type="submit">
                <i class="fa-solid fa-paper-plane"></i>
            </button>
        </form>
    </section>

    <script src="js/chat_window.js"></script>
</body>

</html>