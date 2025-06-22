<?php
session_start();
require 'config.php';

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("sss", $name, $email, $password);
        if ($stmt->execute()) {
            $_SESSION['register_success'] = 'Registration successful. Please log in.';
            $_SESSION['active_form'] = 'login';
        } else {
            $_SESSION['register_error'] = 'Registration failed: ' . $stmt->error;
            $_SESSION['active_form'] = 'register';
        }
        $stmt->close();
    } else {
        $_SESSION['register_error'] = 'Registration error.';
        $_SESSION['active_form'] = 'register';
    }

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
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['login_time'] = date("Y-m-d H:i:s");

                // Insert session log (only once!)
                $user_id = $user['id'];
                $login_time = $_SESSION['login_time'];
                $ip_address = $_SERVER['REMOTE_ADDR'];
                $user_agent = $_SERVER['HTTP_USER_AGENT'];

                $stmt2 = $conn->prepare("INSERT INTO user_sessions (user_id, login_time, ip_address, user_agent) VALUES (?, ?, ?, ?)");
                if ($stmt2) {
                    $stmt2->bind_param("isss", $user_id, $login_time, $ip_address, $user_agent);
                    $stmt2->execute();
                    $_SESSION['session_id'] = $conn->insert_id;
                    $stmt2->close();
                }

                header("Location: " . ($user['role'] === 'admin' ? "admin_page.php" : "user_page.php"));
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

