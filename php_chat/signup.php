<?php
    session_start();

    if (!isset($_SESSION['username'])) {
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Chat - Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="img/logo.svg">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
<div class="w-400 p-5 shadow rounded">
    <form method="post" action="app/http/signup.php" enctype="multipart/form-data">
        <div class="d-flex justify-content-center align-items-center flex-column">
            <img src="img/logo.svg" class="w-25">
            <h3 class="display-4 fs-1 text-center">Sign Up</h3>
        </div>
        <?php if (isset($_GET['error'])) { ?>
        <div class="alert alert-warning" role="alert">
            <?= htmlspecialchars($_GET['error']);?>
        </div>
        <?php }
            if (isset($_GET['name'])) {
                $name = $_GET['name'];
            } else {
                $name = '';
            }

            if (isset($_GET['username'])) {
                $username = $_GET['username'];
            } else {
                $username = '';
        }
        ?>
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text"
                   class="form-control"
                   name="name"
                   value="<?=$name;?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text"
                   class="form-control"
                   name="username"
                   value="<?=$username?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="password">
        </div>

        <div class="mb-3">
            <label class="form-label">Profile Picture</label>
            <input type="file" class="form-control" name="pp">
        </div>

        <button type="submit" class="btn btn-primary">Sign Up</button>
        <a href="index.php">Login</a>
    </form>
</div>

</body>
</html>
<?php
    } else {
        header("Location: home.php");
        exit;
    }
?>