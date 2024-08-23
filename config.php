<?php

ini_set('display_errors',0);
session_start();

$servername = "localhost";

if($_SERVER['SERVER_NAME'] == 'salesman.local'){
    $username = "root";
    $password = "root";
    $dbname = "sales_db";
}else{
    $username = "moochies_mystuff";
    $password = "fh#49YD&7qnjocPo";
    $dbname = "moochies_mystuff";
}

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function sendNotification($itemid,$type,$statuslabel){
    global $conn;

    $sql = "SELECT * FROM items LEFT JOIN images ON images.item_id = items.id 
    WHERE items.id = '$itemid'";
    $result = $conn->query($sql);
    while($row = $result->fetch_object()){

        switch($type){

            case "status":
                    
                switch($row->status){
                    case "Available":
                        $status = '<span style="background-color: green; color: #ffffff; padding: 5px 10px; border-radius: 5px; font-size: 20px;">'.$row->status.'</span>';
                        $message = "A new item: <b>$row->title</b> has been added to your MyStuff list.";
                        break;
                    case "Reserved":
                        $status = '<span style="background-color: black; color: #ffffff; padding: 5px 10px; border-radius: 5px; font-size: 20px;">'.$row->status.'</span>';
                        $message = "The status for <b>$row->title</b> that you have listed on MyStuff has been updated.";
                        break;
                    case "Taken":
                        $status = '<span style="background-color: green; color: #ffffff; padding: 5px 10px; border-radius: 5px; font-size: 20px;">'.$row->status.'</span>';
                        $message = "The status for <b>$row->title</b> that you have listed on MyStuff has been updated.";
                        break;
                }

                break;
        }
        
        $itemTitle = $row->title;

        $html = '
        <body style="color: #555; font-family: Helvetica; font-weight: 100; background-color: #efefef;">

        <table cellpadding="20" width="800px" align="center">
            <tr><td>
                <center><h1>MyStuff</h1></center>
                <table bgcolor="#ffffff" style="padding: 20px; border-radius: 10px; border: 1px solid #d5d5d5;"><tr><td>
                    <table width="100%" cellpadding="10" align="center">
                    <tr><td colspan="2"><p>{{message}}</p></td></tr>
                    <tr>
                        <td valign="top" width="35%" rowspan="2"><img src="https://mystuff.resonate.co.za/'.str_replace(" ","%20",$row->image_path).'" width="100%" style="border-radius: 10px;"></td>
                        <td valign="top">'.$status.'
                            <h2>'.$row->title.'</h2>
                            '.$row->description.'
                            <h3>AED '.number_format($row->price,2).'</h3><hr style="border: 1px solid #d5d5d5;"><br>
                            <a style="background-color: #efefef; color: #555; padding: 10px 20px; text-decoration:none; border-radius: 8px;" href="https://mystuff.resonate.co.za/edit/item/'.$row->id.'">View</a>
                        </td>
                    </tr>
                    </table>
                </td></tr>
            </table>                        
            </td></tr>
        </table>';

    }

    $html = str_replace("{{message}}", $message, $html);

    // $to = 'justin@resonate.co.za, megs@resonate.co.za';
    $to = 'justin@resonate.co.za';

    $subject = 'MyStuff | '.$itemTitle.' | '.strtoupper($statuslabel);

    $headers  = "From: info@resonate.co.za\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    if(mail($to, $subject, $html, $headers)){
        return true;
    }else{
        return false;
    };    

}

function getNumItems(){
    global $conn;
    global $uri;

    $userID = getUserInfo($uri[2],"id");

    $sql = "SELECT id FROM items WHERE user_id = '".$userID."'";
    $result = $conn->query($sql);
    return $result->num_rows;
}

function isLoggedIn() {
    if (isset($_SESSION['user_id'])) {
        return true;
    } elseif (isset($_COOKIE['remember_token'])) {
        global $conn;
        $token = $_COOKIE['remember_token'];
        $stmt = $conn->prepare("SELECT id FROM users WHERE remember_token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id);
            $stmt->fetch();
            $_SESSION['user_id'] = $id;
            return true;
        }
    }
    return false;
}

function isAdmin() {
    global $conn;
    if (isLoggedIn()) {
        $stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $stmt->bind_result($role);
        $stmt->fetch();
        return $role === 'Administrator';
    }
    return false;
}

function login($email, $password, $remember_me = false) {
    global $conn;

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");    
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {

            $_SESSION['user_id'] = $id;

            $sql = "SELECT * FROM users WHERE id = $id";
            $results = $conn->query($sql);
            while($row = $results->fetch_object()){
                $_SESSION['first_name'] = $row->first_name;
                $_SESSION['last_name'] = $row->last_name;
                $_SESSION['username'] = $row->username;
                if($row->role == 'Administrator'){
                    $_SESSION['role_type'] = $row->role;
                }
            }

            if ($remember_me) {
                $token = bin2hex(random_bytes(16));
                setcookie("remember_token", $token, time() + (86400 * 30), "/"); // 30 days
                $stmt = $conn->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
                $stmt->bind_param("si", $token, $id);
                $stmt->execute();
            }

            return true;
        }
    }

    return false;
}

function logout() {
    session_unset();
    session_destroy();
    setcookie("remember_token", "", time() - 3600, "/");
}

function register($password, $email, $role = 'User') {
    global $conn;

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (password, email, role) VALUES (?, ?, ?)");
    $stmt->bind_param("ssss", $hashed_password, $email, $role);
    return $stmt->execute();
}

function getAllUsers() {
    global $conn;
    $result = $conn->query("SELECT id, first_name, last_name, email, role FROM users");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getUserById($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT id, first_name, last_name, email, role FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function getUserInfo($username,$type) {
    global $conn;
    $sql = "SELECT $type FROM users WHERE username = '".$username."'";
    $result = $conn->query($sql);
    while($row = $result->fetch_object()){
        return $row->{$type};
    }
}

function updateUser($id, $first_name, $last_name, $email, $role) {
    global $conn;
    if($conn->query("UPDATE users SET first_name = '$first_name', last_name = '$last_name', email = '$email', role = '$role' WHERE id = $id")){
        return true;
    }

}

function deleteUser($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

function sendPasswordReset($email) {
    global $conn;

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id);
        $stmt->fetch();
        $token = bin2hex(random_bytes(16));
        $expires = date("Y-m-d H:i:s", time() + 3600); // 1 hour from now

        $stmt = $conn->prepare("UPDATE users SET password_reset_token = ?, password_reset_expires = ? WHERE id = ?");
        $stmt->bind_param("ssi", $token, $expires, $id);
        $stmt->execute();

        $reset_link = "http://salesman:8890/reset_password.php?token=$token";
        $subject = "Password Reset";
        $message = "Click on this link to reset your password: $reset_link";
        mail($email, $subject, $message);

        return true;
    }

    return false;
}

function resetPassword($token, $new_password) {
    global $conn;

    $stmt = $conn->prepare("SELECT id FROM users WHERE password_reset_token = ? AND password_reset_expires > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id);
        $stmt->fetch();

        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE users SET password = ?, password_reset_token = NULL, password_reset_expires = NULL WHERE id = ?");
        $stmt->bind_param("si", $hashed_password, $id);
        $stmt->execute();

        return true;
    }

    return false;
}
?>