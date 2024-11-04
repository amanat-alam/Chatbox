<?php
date_default_timezone_set("Asia/Dhaka");
session_start();
if(isset($_SESSION['name'])){
    $text = $_POST['text'];
     
    $text_message = "<div class='msgln'><span class='chat-time'>".date("h:i A D d M Y")."</span> <br><br> <b class='user-name'>".$_SESSION['name']."</b> ".stripslashes(htmlspecialchars($text))."<br><br><br></div>";
    file_put_contents("log.html", $text_message, FILE_APPEND | LOCK_EX);
}
?>