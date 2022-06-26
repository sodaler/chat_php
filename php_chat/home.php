<?php
    session_start();

    if (isset($_SESSION['username'])) {
        # database connection file
        include 'app/db.conn.php';

        include 'app/helpers/user.php';
        include 'app/helpers/conversations.php';
        include 'app/helpers/timeAgo.php';
        include 'app/helpers/last_chat.php';

        # Getting User data
        $user = getUser($_SESSION['username'], $conn);

        # Getting User conversations
        $conversations = getConversation($user['user_id'], $conn);


?>
<!doctype html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Chat app - home</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
        <link rel="icon" href="img/logo.svg">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" crossorigin="anonymous">
    </head>
    <body class = "d-flex
                   justify-content-center
                   align-items-center
                   vh-100">
        <div class="p-2 w-400 rounded shadow">
            <div class="d-flex
                       mb-3 p-3 bg-light
                       justify-content-between
                       align-items-center">
                <div class="d-flex align-items-center">
                    <img src="uploads/<?=$user['p_p'];?>" alt="user_picture"
                         class="w-25 rounded-circle">
                    <h3 class="fs-5 m-2"><?=$user['name'];?></h3>
                </div>
                <a href="logout.php" class="btn btn-dark">Logout</a>
            </div>
            <div class="input-group mb-3">
                <input type="text" placeholder="Search..." class="form-control" id="searchText">
                <button class="btn btn-primary" id="searchButton">
                    <i class="fa fa-search"></i>
                </button>
            </div>
            <ul class="list-group mvh-50 overflow-auto" id="chatList">
                <?php if (!empty($conversations)) { ?>
                    <?php foreach ($conversations as $conversation) {?>
                        <li class="list-group-item">
                            <a href="chat.php?user=<?=$conversation['username']?>" class="justify-content-between
                               align-items-center p-2">
                                <div class="d-flex align-items-center">
                                    <img src="uploads/<?=$conversation['p_p']?>"
                                         class="w-10 rounded-circle">
                                    <h3 class="fs-xs m-2"><?=$conversation['name'];?><br>
                                        <small>
                                            <?php
                                                echo lastChat($_SESSION['user_id'], $conversation['user_id'], $conn);
                                            ?>
                                        </small>
                                    </h3>
                                </div>
                                <?php if (last_seen($conversation['last_seen']) == 'Active') { ?>
                                <div title="online">
                                    <div class="online"></div>
                                </div>
                                <?php } ?>
                            </a>
                        </li>
                    <?php }?>
                <?php } else {?>
                    <div class="alert alert-info text-center">
                        <i class="fa fa-comments d-block fs-big"></i>
                        <span>No messages yet, Start the conversation</span>
                    </div>
                <?php }?>
            </ul>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script>
            $(document).ready(function(){
            // Search
            $("#searchText").on("input", function() {
                let searchText = $(this).val();
                if (searchText === "") return;
                $.post('app/ajax/search.php',{key: searchText}, function(data, status) {
                    $("#chatList").html(data);
                })
            })

            // Search using the button
            $("#searchButton").on("click", function() {
                let searchText = $("#searchText").val();
                if (searchText === "") return;
                $.post('app/ajax/search.php',{key: searchText}, function(data, status) {
                    $("#chatList").html(data);
                })


            })
            // auto update last seen for logged in user
            let lastSeenUpdate = function() {
                $.get("app/ajax/update_last_seen.php");
            }
            lastSeenUpdate();
            // auto update last seen every 10sec
            setInterval(lastSeenUpdate, 10000);
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
