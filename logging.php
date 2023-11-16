<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - logowanie</title>
    <link rel="stylesheet" href="css/index.min.css">
</head>

<body>
    <section class="container">
        <h1>Chat</h1>

        <form action="logging_backend.php" method="POST">
            <div class="input-wrapper">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" placeholder="Wpisz adres e-mail">
            </div>

            <div class="input-wrapper">
                <label for="password">Hasło</label>
                <input type="password" id="password" name="password" placeholder="Wpisz hasło">
            </div>

            <?php
            if (isset($_SESSION['err_logging'])) {
                echo '<p class="err_logging">' . $_SESSION['err_logging'] . '</p>';
                unset($_SESSION['err_logging']);
            }
            ?>

            <input class="submit" type="submit" value="Zaloguj się">
        </form>

        <div class="link">
            <span>Nie masz konta?</span>
            <a href="index.php">Załóż je!</a>
        </div>
    </section>
</body>

</html>