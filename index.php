<?php
session_start();

if(isset($_POST['submit_pass']) && $_POST['pass']) {
    $pass = $_POST['pass'];
    if($pass == "280724") {
        $_SESSION['password'] = $pass;
    } else {
        $error = "Incorrect Password!";
    }
}

if(isset($_POST['page_logout'])) {
    unset($_SESSION['password']);
}
?>

<html>
<head>
    <title>Our Space</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link rel="icon" href="logo.jpg">
    <link rel="stylesheet" type="text/css" href="password_style.css">
</head>
<body>
<div id="wrapper-1">

<?php
if($_SESSION['password'] == "280724") {
?>
<?php
session_start();

if(isset($_GET['logout'])) {    
    //Simple exit message
    $logout_message = "<div class='msgln'><span class='left-info'><b class='user-name-left'>". $_SESSION['name'] ."</b> has left the chat session.</span><br><br></div>";
    file_put_contents("log.html", $logout_message, FILE_APPEND | LOCK_EX);
    
    session_destroy();
    header("Location: index.php"); //Redirect the user
}

if(isset($_POST['enter'])) {
    if($_POST['name'] != "") {
        $_SESSION['name'] = stripslashes(htmlspecialchars($_POST['name']));
    } else {
        echo '<span class="error">Please type in a name</span>';
    }

    $logout_message = "<div class='msgln'><span class='enter-info'><b class='user-name-left'>". $_SESSION['name'] ."</b> has joined the chat session.</span><br><br></div>";
    file_put_contents("log.html", $logout_message, FILE_APPEND | LOCK_EX);
}

function loginForm() {
    echo
    '<div id="loginform" align="center">
    <p>
    Enter your name here!
    </p>
    <form action="index.php" method="post">
      <input type="text" name="name" id="name" placeholder="Name Here" />
      <input type="submit" name="enter" id="enter" value="ENTER" />
    </form> 
  </div>';
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />

        <title>Our Space</title>
        <meta name="description" content="" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
        <link rel="icon" href="logo.jpg">
        <link rel="stylesheet" href="style.css" />
    </head>
    <body>
    <?php
    if(!isset($_SESSION['name'])) {
        loginForm();
    } else {
    ?>
        <div id="wrapper-2">
            <div id="menu">
                <p class="welcome">Welcome, <b><?php echo $_SESSION['name']; ?></b></p>
                <p class="logout"><a id="exit" href="#">LOGOUT</a></p>
            </div>
            
            <div id="button" align="center">
                <button>Scroll Down &darr;</button>                
            </div>
            <br><br>

            <div id="chatbox">
            <?php
            if(file_exists("log.html") && filesize("log.html") > 0) {
                $contents = file_get_contents("log.html");          
                echo $contents;
            }
            ?>
            </div>

            <div>
            <form id="message_form" name="message" action="">
                <textarea name="usermsg" type="text" id="usermsg" placeholder="Type your message here..."></textarea>
                <input name="submitmsg" type="submit" id="submitmsg" value="Send" />
            </form></div>
        </div>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script type="text/javascript">
            // jQuery Document
            $(document).ready(function () {
                $("#submitmsg").click(function () {
                    var clientmsg = $("#usermsg").val();
                    $.post("post.php", { text: clientmsg });
                    $("#usermsg").val("");
                    return false;
                });

                // Handle message editing
                $(document).on('click', '.edit-btn', function () {
                    var messageId = $(this).data('message-id');
                    var messageText = $(this).siblings('.user-name').text();
                    $('#usermsg').val(messageText);
                    $('#submitmsg').val('Edit');
                    $('#submitmsg').data('edit-id', messageId);
                });

                function loadLog() {
                    var oldscrollHeight = $("#chatbox")[0].scrollHeight - 20; //Scroll height before the request

                    $.ajax({
                        url: "log.html",
                        cache: false,
                        success: function (html) {
                            $("#chatbox").html(html); //Insert chat log into the #chatbox div

                            //Auto-scroll           
                            var newscrollHeight = $("#chatbox")[0].scrollHeight - 20; //Scroll height after the request
                            if(newscrollHeight > oldscrollHeight) {
                                $("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal'); //Autoscroll to bottom of div
                            }   
                        }
                    });
                }

                setInterval (loadLog, 2500);

                $("#exit").click(function () {
                    var exit = confirm("Are you sure you want to end the session?");
                    if (exit == true) {
                    window.location = "index.php?logout=true";
                    }
                });
            });

            // Scroll Down
            $(document).ready(function() { 
                $("button").click(function() { 
                    $("html, body").animate({ 
                        scrollTop: $('html, body').get(0).scrollHeight 
                    }, 500); 
                }); 
            });
        </script>
    </body>
</html>
<?php
}
?>
<?php
} else {
?>
<div align="center">
    <form method="post" action="" id="login_form" align="center">
        <input type="password" name="pass" placeholder="Password Here">
        <input id="submit_pass" type="submit" name="submit_pass" value="CONTINUE">
        <p><font style="color:red;"><?php echo $error;?></font></p>
    </form>
</div>
<?php    
}
?>
</div>
</body>
</html>