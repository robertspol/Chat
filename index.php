<?php
session_start();

if (isset($_SESSION['unique_id'])) {
    header('Location: users.php');
    exit();
}

require_once 'connect.php';

if (isset($_POST['last-name'])) {
    $all_correct = true;

    $first_name = $_POST['first-name'];
    $last_name = $_POST['last-name'];

    if (!ctype_alnum($first_name) && !ctype_alnum($last_name)) {
        $all_correct = false;
        $_SESSION['err_name'] = 'Imię i nazwisko mogą zawierać tylko litery i cyfry';
    }

    $email = $_POST['email'];

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            $mysqli = new mysqli($host, $db_user, $db_password, $db_name);

            if ($mysqli->connect_errno > 0) {
                throw new Exception();
            } else {
                $sql = "SELECT email FROM users WHERE email='$email'";

                $result = $mysqli->query($sql);

                if (!$result) throw new Exception();

                $users_amount = $result->num_rows;

                if ($users_amount > 0) {
                    $all_correct = false;
                    $_SESSION['err_email'] = 'Użytkownik z takim adresem e-mail już istnieje';
                }

                $result->close();
            }
        } catch (Exception) {
            echo 'Nie połączono z bazą danych.';
        }
    } else {
        $_SESSION['err_email'] = 'Nieprawidłowy adres e-mail';
    }

    $password = $_POST['password'];

    if (strlen($password) < 8 || strlen($password) > 20) {
        $all_correct = false;
        $_SESSION['err_password'] = 'Hasło musi posiadać od 8 do 20 znaków';
    }

    $pass_hash = password_hash($password, PASSWORD_DEFAULT);

    if (($_FILES['image']['error'] == UPLOAD_ERR_OK)) {
        $img_name = $_FILES['image']['name'];
        $tmp_name = $_FILES['image']['tmp_name'];

        $img_explode = explode('.', $img_name);
        $img_ext = end($img_explode);

        $extensions = ['jpg', 'jpeg', 'png'];

        if (in_array($img_ext, $extensions)) {
            $time = time();
            $new_name = $time . $img_name;

            move_uploaded_file($tmp_name, 'img/' . $new_name);

            $status = 'Aktywny';
            $random_id = rand(time(), 10000000);

            if ($all_correct) {
                $mysqli->query("INSERT INTO users (unique_id, first_name, last_name, email, password, img, status) VALUES ('$random_id', '$first_name', '$last_name', '$email', '$pass_hash', '$new_name', '$status')");

                $result = $mysqli->query("SELECT * FROM users WHERE email='$email'");

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();

                    $_SESSION['unique_id'] = $row['unique_id'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['file_name'] = $new_name;
                }

                $result->close();
                $mysqli->close();
                header('Location: users.php');
            }
        } else {
            $_SESSION['err_image'] = 'Plik musi posiadać rozszerzenie jpg, jpeg lub png';
        }
    } else {
        $_SESSION['err_image'] = 'Dodaj zdjęcie';
    }

    $_SESSION['entered_first_name'] = $first_name;
    $_SESSION['entered_last_name'] = $last_name;
    $_SESSION['entered_email'] = $email;
    $_SESSION['entered_password'] = $password;
}
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - rejestracja</title>
    <link rel="stylesheet" href="css/index.min.css">
</head>

<body>
    <section class="container">
        <h1>Chat</h1>

        <form class="registration" method="POST" enctype="multipart/form-data">
            <div class="input-wrapper">
                <label for="first-name">Imię</label>
                <input type="text" id="first-name" name="first-name" placeholder="Wpisz imię" value="<?php if (isset($_SESSION['entered_first_name'])) {
                                                                                                            echo $_SESSION['entered_first_name'];
                                                                                                            unset($_SESSION['entered_first_name']);
                                                                                                        };
                                                                                                        ?>">
            </div>

            <div class="input-wrapper">
                <label for="last-name">Nazwisko</label>
                <input type="text" id="last-name" name="last-name" placeholder="Wpisz nazwisko" value="<?php if (isset($_SESSION['entered_last_name'])) {
                                                                                                            echo $_SESSION['entered_last_name'];
                                                                                                            unset($_SESSION['entered_last_name']);
                                                                                                        };
                                                                                                        ?>">
            </div>

            <?php
            if (isset($_SESSION['err_name'])) {
                echo '<p class="err_name">' . $_SESSION['err_name'] . '</p>';
                unset($_SESSION['err_name']);
            }
            ?>

            <div class="input-wrapper">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" placeholder="Wpisz adres e-mail" value="<?php if (isset($_SESSION['entered_email'])) {
                                                                                                        echo $_SESSION['entered_email'];
                                                                                                        unset($_SESSION['entered_email']);
                                                                                                    };
                                                                                                    ?>">
            </div>

            <?php
            if (isset($_SESSION['err_email'])) {
                echo '<p class="err_email">' . $_SESSION['err_email'] . '</p>';
                unset($_SESSION['err_email']);
            }
            ?>

            <div class="input-wrapper">
                <label for="password">Hasło</label>
                <input type="password" id="password" name="password" placeholder="Wpisz hasło" value="<?php if (isset($_SESSION['entered_password'])) {
                                                                                                            echo $_SESSION['entered_password'];
                                                                                                            unset($_SESSION['entered_password']);
                                                                                                        };
                                                                                                        ?>">
            </div>

            <?php
            if (isset($_SESSION['err_password'])) {
                echo '<p class="err_password">' . $_SESSION['err_password'] . '</p>';
                unset($_SESSION['err_password']);
            }
            ?>

            <div class="input-wrapper file">
                <label for="file">Zdjęcie</label>
                <input type="file" id="file" name="image">
            </div>

            <?php
            if (isset($_SESSION['err_image'])) {
                echo '<p class="err_image">' . $_SESSION['err_image'] . '</p>';
                unset($_SESSION['err_image']);
            }
            ?>

            <input class="submit registration" type="submit" value="Załóż konto">
        </form>

        <div class="link">
            <span>Masz już konto?</span>
            <a href="logging.php">Zaloguj się!</a>
        </div>
    </section>
</body>

</html>