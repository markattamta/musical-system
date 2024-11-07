<?php
session_start();
include('config.php');

if (isset($_SESSION['id'])) {
    $userID = $_SESSION['id'];
    $result = mysqli_query($conn, "SELECT last_activity FROM users WHERE id = $userID");

    if ($row = mysqli_fetch_assoc($result)) {
        $lastActivity = $row['last_activity'];
        $currentTime = time();

        if ($currentTime - $lastActivity <= 10) {
            echo "online";
        } else {
            echo "offline";
        }
    } else {
        echo "offline";
    }
} else {
    echo "offline";
}
?>
