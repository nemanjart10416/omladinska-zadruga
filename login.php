<?php
include_once("assets/php/funkcije.php");

if(isset($_SESSION["user"])){
    echo Message::danger("you are logged in already.");
    die();
}

session_regenerate_id(true);

$msg = "";

if (isset($_POST["login"])) {
    // Check if the form is submitted
    $username = UserInput::sanitize($_POST["username"]);
    $password = UserInput::sanitize($_POST["password"]);

    // Authenticate user
    try {
        if (!User::authenticateUser($username, $password)) {
            $msg = Message::danger("Invalid credentials");
        }
    } catch (Exception $e) {
        $msg = Message::danger("Error has occurred, please try later.");
    }
}
?>

<!doctype html>
<html lang="sr">
<head>
    <!-- meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="Index, Follow">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="copyright" content="">
    <meta name="Audience" content="all">
    <meta name="distribution" content="global">
    <meta name="theme-color" content="#EBEBEB" >
    <meta name="language" content="sr">

    <link rel="preload" as="image" href="">

    <link href="" rel="canonical">

    <link href="" rel="icon">
    <link href="" rel="apple-touch-icon">

    <title>Hello, world!</title>

    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="loginPage">
<div class="container-fluid">

    <div class="row">
        <?php include_once("assets/components/navigacija.php"); ?>
    </div>

    <div class="row">
        <div class="col-12">
            <?php echo $msg; ?>
        </div>
    </div>

    <!-- Login Form -->
    <div class="row mt-5">
        <div class="col-12 col-md-6 offset-md-3">
            <form method="post" action="login" class="loginForm">
                <h1>Login</h1>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary" name="login">Login</button>
                <div class="text-start">
                    <br>
                    Don't have account? register <a href="register">here</a>
                    <br>
                    <a href="forgot-password">forgot password</a>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <?php include_once("assets/components/footer.php"); ?>
    </div>

</div>


<script src="assets/js/script.js" defer></script>
</body>
</html>