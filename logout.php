<?php
session_start();
require 'config.php';

if (isset($_SESSION['session_id']) && isset($_SESSION['login_time'])) {
    $logout_time = date("Y-m-d H:i:s");
    $login_time = $_SESSION['login_time'];
    $duration = strtotime($logout_time) - strtotime($login_time); // seconds
    $session_id = $_SESSION['session_id'];

    $stmt = $conn->prepare("UPDATE user_sessions SET logout_time = ?, session_duration = ? WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("sii", $logout_time, $duration, $session_id);
        $stmt->execute();
        $stmt->close();
    }
}

$log_message = date("Y-m-d H:i:s") . " - User " . $_SESSION['name'] . " logged out.\n";
file_put_contents('session_logs.txt', $log_message, FILE_APPEND);

session_unset();
session_destroy();
header("Location: index.php");
exit();
?>
