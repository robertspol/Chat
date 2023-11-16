<?php
require_once('connect.php');
session_start();

if (isset($_SESSION['unique_id'])) {
    header('Location: users.php');
    exit();
}

if (isset($_SESSION['entered_first_name'])) unset($_SESSION['entered_first_name']);
if (isset($_SESSION['entered_last_name'])) unset($_SESSION['entered_last_name']);
if (isset($_SESSION['entered_email'])) unset($_SESSION['entered_email']);
if (isset($_SESSION['entered_password'])) unset($_SESSION['entered_password']);

if (isset($_SESSION['err_name'])) unset($_SESSION['err_name']);
if (isset($_SESSION['err_email'])) unset($_SESSION['err_email']);
if (isset($_SESSION['err_password'])) unset($_SESSION['err_password']);
if (isset($_SESSION['err_image'])) unset($_SESSION['err_image']);

mysqli_report(MYSQLI_REPORT_STRICT);

try {
    $mysqli = new mysqli($host, $db_user, $db_password, $db_name);

    if ($mysqli->connect_errno > 0) throw new Exception();

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $mysqli->prepare("SELECT * FROM users WHERE email=?");

    $stmt->bind_param('s', $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if (!$result) throw new Exception();

    $users = $result->num_rows;

    if ($users > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            $status = 'Aktywny';

            $result2 =  $mysqli->query("UPDATE users SET status='{$status}' WHERE unique_id='{$row['unique_id']}'");

            if ($result2) {
                $_SESSION['unique_id'] = $row['unique_id'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['file_name'] = $row['img'];
            }

            header('Location: users.php');
        } else {
            $_SESSION['err_logging'] = 'Nieprawidłowy adres e-mail lub hasło';
            header('Location: logging.php');
        }
    } else {
        $_SESSION['err_logging'] = 'Nieprawidłowy adres e-mail lub hasło';
        header('Location: logging.php');
    }

    $result->close();
    $mysqli->close();
} catch (Exception $e) {
    echo 'Kod błędu: ' . $e->getCode();
}
