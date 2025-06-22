<?php
$host = 'localhost';
$db = 'users_db';
$user = 'root';
$pass = '';


$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$filename = "C:/xampp/htdocs/login_register/data/user_sessions.csv";

if (!file_exists(dirname($filename))) {
    mkdir(dirname($filename), 0777, true);
}

$output = fopen($filename, 'w');

fputcsv($output, [
    'id', 'user_id', 'login_time', 'logout_time', 'session_duration',
    'ip_address', 'user_agent', 'login_count'
]);

$query = "
SELECT 
    us.id, us.user_id, us.login_time, us.logout_time,
    us.session_duration, us.ip_address, us.user_agent,
    u.login_count
FROM user_sessions us
JOIN users u ON us.user_id = u.id
";

$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
}

fclose($output);
$conn->close();

echo " Export completed: $filename";
?>
