<?php
session_start();

if (!isset($_SESSION['unique_id'])) {
    header('location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - logowanie</title>
    <link rel="stylesheet" href="css/index.min.css">
    <script src="https://kit.fontawesome.com/83b6ac67d9.js" crossorigin="anonymous"></script>
</head>

<body>
    <section class="container users">
        <?php
        require_once 'connect.php';

        try {
            $mysqli = new mysqli($host, $db_user, $db_password, $db_name);

            if ($mysqli->connect_errno > 0) throw new Exception();

            $sql = "SELECT * FROM users WHERE unique_id={$_SESSION['unique_id']}";
            $result = $mysqli->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
            }
        } catch (Exception $e) {
            echo 'Kod błędu: ' . $e->getCode();
        }
        ?>
        <div class="top-panel">
            <div class="user">
                <img src="img/<?php echo $row['img'] ?>" alt="zdjęcie użytkownika">

                <div class="user-info">
                    <p class="name">
                        <?php echo $row['first_name'] . ' ' . $row['last_name'] ?>
                    </p>
                    <p class="is-active"><?php echo $row['status'] ?></p>
                </div>
            </div>

            <a href="logout.php?logout_id=<?php echo $row['unique_id'] ?>" class="logout">Wyloguj się</a>
            <a class="account-delete">Usuń konto</a>
        </div>

        <div class="search">
            <input type="text" placeholder="Wyszukaj rozmówcy">
        </div>

        <div class="users-list"></div>
    </section>

    <script src="js/users.js"></script>
</body>

</html>