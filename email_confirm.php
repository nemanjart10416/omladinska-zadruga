<?php
include_once("assets/php/funkcije.php");

$msg = "";

// Check if the user is already logged in, redirect to dashboard if logged in
if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// Check if the token provided in the URL
if (isset($_GET['token'])) {
    $token = UserInput::sanitize($_GET['token']);

    // Validate the token
    if (TokenManager::validateToken($token)) {
        // Token is valid, activate the user's account
        $userId = TokenManager::getUserIdByToken($token);

        try {
            $user = User::getUserById($userId);

            $user->activateAccount();

            // Delete the confirmation token from the database
            TokenManager::deleteToken($userId);

            // Display a success message to the user
            $msg = Message::success("Account activated successfully. You can now <a href='login'>log in</a>.");
        } catch (Exception $e) {
            $msg = Message::danger("Error (ec1) has occurred, please try later");
        }
    } else {
        // Invalid token, display an error message
        $msg = Message::danger("Invalid or expired token.");
    }
} else {
    // Token or user ID not provided, display an error message
    $msg = Message::danger("Invalid request.");
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

    <link rel="preload" as="image" href="important.png">

    <link href="" rel="canonical">

    <link href="" rel="icon">
    <link href="" rel="apple-touch-icon">

    <title>Hello, world!</title>

    <link rel="stylesheet" href="assets/css/style.min.css">
</head>
<body>
<div class="container-fluid">

    <div class="row">
        <?php include_once("assets/components/navigacija.php"); ?>
    </div>

    <div class="row">
        <div class="col-12">
            <?php echo $msg; ?>
        </div>
    </div>

    <div class="row">
        <?php include_once("assets/components/footer.php"); ?>
    </div>

</div>


<script src="assets/js/script.js" defer></script>
</body>
</html>
