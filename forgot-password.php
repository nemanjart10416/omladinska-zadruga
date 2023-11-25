<?php
include_once("assets/php/funkcije.php");

$msg = "";

if (isset($_SESSION["user"])) {
    echo Message::danger("you are logged in already.");
    die();
}

// Step 1 - just send email
if (isset($_POST["send"])) {
    $email = UserInput::sanitize($_POST['email']);

    try {
        $user = User::getActiveUserByEmail($email);

        if ($user !== null) {
            $token = PasswordResetTokenManager::generateToken();
            $expirationDate = new DateTimeImmutable('+1 hour');

            if (PasswordResetTokenManager::storeToken($email, $token, $expirationDate)) {
                PasswordResetTokenManager::sendPasswordResetEmail($email, $token);
                $msg = Message::success("Reset link has been set to your email address.");
            } else {
                $msg = Message::danger("There was an error, please try again later");
            }
        } else {
            $msg = Message::danger("Email address not found. <a href='forgot-password'>back.</a>");
        }
    } catch (Exception $e) {
        $msg = Message::danger("There was an error, please try again later");
    }
}

// Step 2 - show form if token is adequate
if (isset($_GET['token']) && !isset($_POST["reset"])) {
    $token = UserInput::sanitize($_GET['token']);

    if(!PasswordResetTokenManager::validateToken($token)){
        $msg = Message::danger("Invalid or expired token");
    }
}

// Step 3 - change password
if (isset($_GET['token']) && isset($_POST["reset"])) {
    // Handle form submission
    $password = UserInput::sanitize($_POST['password']);
    $confirm = UserInput::sanitize($_POST['confirm']);
    $token = UserInput::sanitize($_GET['token']);

    $ans = PasswordResetTokenManager::validateToken($token);

    if ($ans) {
        if ($password === $confirm) {

            //validate fields
            $data = ['password' => $password];
            $rules = ['password' => 'required|min:8|max:50']; // password min 8 max 50];
            $validator = new Validator($data, $rules);

            if ($validator->validate()) {
                $user = User::getActiveUserByEmail($ans);

                // Reset the password
                if ($user->resetPassword($password)) {
                    PasswordResetTokenManager::deleteToken($token);
                    // Password reset successful, redirect to login page
                    header('Location: login.php');
                    exit();
                } else {
                    // Password reset failed, display an error message
                    $msg = Message::danger("Password reset failed");
                }
            }else{
                $errors = $validator->getErrors();

                foreach ($errors as $field => $fieldErrors) {
                    foreach ($fieldErrors as $error) {
                        $msg .= Message::danger("Error in $field: $error");
                    }
                }
            }
        } else {
            // Passwords do not match, display an error message
            $msg = Message::danger("Password and confirmation does not match");
        }
    } else {
        $msg = Message::danger("Invalid or expired token");
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
    <meta name="theme-color" content="#EBEBEB">
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

    <div class="row mt-1">
        <div class="col-12 breadcrumb-wrapper">
            <ul id="breadcrumb">
                <li><a href="#"><span class="icon icon-home"> </span></a></li>
                <li><a href="#"><span class="icon icon-beaker"> </span> Home</a></li>
                <li><a href="#"><span class="icon icon-double-angle-right"></span>page</a></li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <?php echo $msg; ?>
        </div>
    </div>

    <?php
    if (!isset($_POST["send"]) && !isset($_GET["token"])) {
        ?>
        <div class="row mt-5">
            <div class="col-12 col-md-6 offset-md-3">
                <form method="post" action="forgot-password" class="loginForm">
                    <h1>Login</h1>
                    <div class="mb-3">
                        <label for="email" class="form-label">email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="send">send</button>
                </form>
            </div>
        </div>
        <?php
    }

    if (isset($_GET["token"]) && !isset($_POST["reset"]) && PasswordResetTokenManager::validateToken(UserInput::sanitize($_GET['token']))) {
        ?>
        <div class="row mt-5">
            <div class="col-12 col-md-6 offset-md-3">
                <form method="post" action="forgot-password?token=<?php echo htmlentities(UserInput::sanitize($_GET['token'])); ?>" class="loginForm">
                    <h1>Login</h1>
                    <div class="mb-3">
                        <label for="password" class="form-label">password</label>
                        <input type="text" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm" class="form-label">confirm password</label>
                        <input type="text" class="form-control" id="confirm" name="confirm" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="reset">reset</button>
                </form>
            </div>
        </div>
        <?php
    }
    ?>

    <div class="row">
        <?php include_once("assets/components/footer.php"); ?>
    </div>

</div>


<script src="assets/js/script.js" defer></script>
</body>
</html>