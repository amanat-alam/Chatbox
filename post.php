<?php
date_default_timezone_set("Asia/Dhaka");
session_start();

if(isset($_SESSION['name'])) {
    $text = $_POST['text'];
    $edit_id = $_POST['edit_id'] ?? null;

    if ($edit_id) {
        // Code to edit the message in log.html
        $log_contents = file_get_contents("log.html");
        $log_contents = str_replace($old_message, $new_message, $log_contents); // Here you should define $old_message and $new_message
        file_put_contents("log.html", $log_contents);
    } else {
        $text_message = "<div class='msgln'><span class='chat-time'>".date("h:i A D d M Y")."</span> <br><br> <b class='user-name'>".$_SESSION['name']."</b> ".stripslashes(htmlspecialchars($text))."<br><br><br></div>";
        file_put_contents("log.html", $text_message, FILE_APPEND | LOCK_EX);
    }
}
?>