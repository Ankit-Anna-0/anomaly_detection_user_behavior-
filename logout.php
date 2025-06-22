<?php
session_start();
require 'config.php';

if (isset($_SESSION['user_id']) && isset($_SESSION['login_time'])) {
    $user_id = $_SESSION['user_id'];
    $login_time = $_SESSION['login_time'];
    $logout_time = date("Y-m-d H:i:s");
    $duration = strtotime($logout_time) - strtotime($login_time);

    // Get the most recent session for this user and login time
    $stmt = $conn->prepare("SELECT id FROM user_sessions WHERE user_id = ? AND login_time = ? ORDER BY id DESC LIMIT 1");
    $stmt->bind_param("is", $user_id, $login_time);
    $stmt->execute();
    $result = $stmt->get_result();
    $session = $result->fetch_assoc();
    $stmt->close();

    if ($session) {
        $session_id = $session['id'];
        $stmt2 = $conn->prepare("UPDATE user_sessions SET logout_time = ?, session_duration = ? WHERE id = ?");
        $stmt2->bind_param("sii", $logout_time, $duration, $session_id);
        $stmt2->execute();
        $stmt2->close();
    }
}

session_unset();
session_destroy();
header("Location: index.php");
exit();
?>


