<?php
session_start();
require 'config.php';

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssss", $name, $email, $password, $role);
        $stmt->execute();
        $stmt->close();
    }

    $_SESSION['success'] = 'Registration successful! You can now log in.';
    $_SESSION['active_form'] = 'login';
    header("Location: index.php");
    exit();
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['login_time'] = date("Y-m-d H:i:s");

                // Insert session into tracking table
                $user_id = $user['id'];
                $login_time = $_SESSION['login_time'];
                $stmt2 = $conn->prepare("INSERT INTO user_sessions (user_id, login_time) VALUES (?, ?)");
                if ($stmt2) {
                    $stmt2->bind_param("is", $user_id, $login_time);
                    $stmt2->execute();
                    $_SESSION['session_id'] = $conn->insert_id;
                    $stmt2->close();
                }

                if ($user['role'] === 'admin') {
                    header("Location: admin_page.php");
                } else {
                    header("Location: user_page.php");
                }
                exit();
            }
        }
        $stmt->close();
    }

    $_SESSION['login_error'] = 'Incorrect email or password';
    $_SESSION['active_form'] = 'login';
    header("Location: index.php");
    exit();
}
?>
