<?php
    session_start();

    if (isset($_SESSION['username'])) {
        # database connection file
        include 'app/db.conn.php';
        include 'app/helpers/user.php';
        include 'app/helpers/timeAgo.php';
        include 'app/helpers/chat.php';
        include 'app/helpers/opened.php';

        if (!isset($_GET['user'])) {
            header("Location: home.php");
            exit;
        }

        # Getting User data
        $chatWith = getUser($_GET['user'], $conn);

        if (empty($chatWith)) {
            header("Location: home.php");
            exit;
        }

        $chats = getChats($_SESSION['user_id'], $chatWith['user_id'], $conn);

        opened($chatWith['user_id'], $conn, $chats);


?>
<!doctype html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Chat app - Chat</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
        <link rel="icon" href="img/logo.svg">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" crossorigin="anonymous">
    </head>
    <body class = "d-flex
               justify-content-center
               align-items-center
               vh-100">
        <div class="w-400 shadow p-4 rounded">
            <a href="home.php" class="fs-4 link-dark">&#8592;</a>
            <div class="d-flex align-items-center">
                <img src="uploads/<?=$chatWith['p_p']?>" alt="default image" class="w-15 rounded-circle">
                <h3 class="display-4 fs-sm m-2"><?=$chatWith['name']?> <br>
                    <div class="d-flex align-items-center" title="online">
                        <?php
                            if (last_seen($chatWith['last_seen']) === "Active") {
                        ?>
                            <div class="online"></div>
                            <small class="ms-1">Online</small>
                        <?php }else { ?>
                            <small class="ms-1">Last seen: <?=$chatWith['last_seen']?></small>
                        <?php } ?>
                    </div>
                </h3>
            </div>
            <div class="shadow p-4 rounded d-flex flex-column mt-2 chat-box" id="chatBox">
                <?php
                    if (!empty($chats)) {
                        foreach ($chats as $chat) {
                            if ($chat['from_id'] == $_SESSION['user_id'])
                            { ?>
                                <p class="rtext align-self-end border rounded p-2 mb-1">
                                    <?=$chat['message']?>
                                    <small class="d-block"><?=$chat['created_at']?></small>
                                </p>
                <?php }else { ?>
                                <p class="ltext border rounded p-2 mb-1">
                                    <?=$chat['message']?>
                                    <small class="d-block"><?=$chat['created_at']?></small>
                                </p>
                            <?php }
                        }
                    }else {?>
                        <div class="alert alert-info text-center">
                            <i class="fa fa-comments d-block fs-big"></i>
                            <span>No messages yet, Start the conversation</span>
                        </div>
                <?php } ?>
            </div>
            <div class="input-group mb-3">
                <textarea cols="3" class="form-control" id="message">

                </textarea>
                <button class="btn btn-primary" id="sendButton">
                    <i class="fa fa-paper-plane"></i>
                </button>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script>
            let scrollDown = function() {
                let chatBox = document.getElementById('chatBox');
                chatBox.scrollTop = chatBox.scrollHeight;
            }

            scrollDown();

            $(document).ready(function(){
                $("#sendButton").on('click', function() {
                    let message = $("#message").val();
                    if (message == "") {
                        return;
                    }
                    $.post("app/ajax/insert.php",
                            {
                                message: message,
                                to_id: <?=$chatWith['user_id']?>
                            },
                            function(data, status) {
                                $("#message").val("");
                                $("#chatBox").append(data);
                                scrollDown();
                            });
                })
                // auto update last seen for logged in user
                let lastSeenUpdate = function() {
                    $.get("app/ajax/update_last_seen.php");
                }
                lastSeenUpdate();
                // auto update last seen every 10sec
                setInterval(lastSeenUpdate, 10000);


                // auto refresh / reload
                let fetchData = function () {
                    $.post("app/ajax/getMessage.php",
                        {
                            id_2: <?=$chatWith['user_id']?>
                        },
                        function (data, status) {
                            $("#chatBox").append(data);
                            if (data != "") {
                                scrollDown();
                            }
                        });
                }
                fetchData();
                // auto update last seen every 0.5 sec
                setInterval(fetchData, 500);
            })
        </script>
    </body>
    </html>

<?php
    } else {
        header("Location: index.php");
        exit;
    }
?>