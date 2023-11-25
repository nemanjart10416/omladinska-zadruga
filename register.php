<?php
include_once("assets/php/funkcije.php");

if(isset($_SESSION["user"])){
    echo Message::danger("you are logged in already.");
    die();
}

$msg = "";

if (isset($_POST["register"])) {
    $username = UserInput::sanitize($_POST["username"]);
    $email = UserInput::sanitize($_POST["email"]);
    $password = UserInput::sanitize($_POST["password"]);
    $firstName = UserInput::sanitize($_POST["first_name"]);
    $lastName = UserInput::sanitize($_POST["last_name"]);
    $birthday = DateTimeImmutable::createFromFormat('Y-m-d', UserInput::sanitize($_POST["birthday"]));
    $address = UserInput::sanitize($_POST["address"]);
    $phone = UserInput::sanitize($_POST["phone"]);
    $role = UserInput::sanitize($_POST["role"]);

    // Remove spaces from the phone number
    $phone = str_replace(' ', '', $phone);

    // Validate inputs (you can add more validation logic here)
    if (empty($username) || empty($email) || empty($password) || empty($firstName) || empty($lastName) || empty($birthday) || empty($address) || empty($phone) || empty($role)) {
        $msg = Message::danger("All fields are required.");
    } else {
        // Check if username and email are already taken
        if (User::isUsernameTaken($username)) {
            $msg = Message::danger("Username is already taken.");
        } elseif (User::isEmailTaken($email)) {
            $msg = Message::danger("Email is already taken.");
        } else {
            //validate fields
            $data = [
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'name' => $firstName,
                'last name' => $lastName,
                'birthday' => $birthday->format('Y-m-d'),
                'address' => $address,
                'phone' => $phone,
                'role' => $role
            ];

            $rules = [
                'username' => 'required|min:5|max:20|alpha_numeric', // username only numbers and letters, min 5 max 20, unique
                'email' => 'required|min:5|max:90|email', // email min 5, max 90, email
                'password' => 'required|min:8|max:50', // password min 8 max 50
                'name' => 'required|min:3|max:50|alpha', // name min 3, max 50, only letters
                'last name' => 'required|min:3|max:50|alpha', // last name min 3, max 50, only letters
                'birthday' => 'required|date|birthday:18', // birthday, must be date, in the past, at least 18 years old
                'address' => 'required|min:3|max:50', // address min 3, max 50
                'phone' => 'required|phone_number|min:6|max:15', // phone number, min 6, max 15, phone number format
                'role' => 'required|in:employer,user' // role, employer or user
            ];

            $validator = new Validator($data, $rules);

            if ($validator->validate()) {
                // Hash the password before storing it in the database
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                // Create a new user in the database
                $user = new User(
                    null, $username, $email, $hashed_password, $firstName, $lastName, $birthday, $address, $phone, $role, null, null, null, null
                );

                try {
                    if(isset($_POST["test"])){
                        $msg = Message::success("Registration would be success if not testing.");
                    }else{
                        $new_user = $user->createUser();

                        $msg = Message::success("Registration is success, please confirm email address.");
                        // Redirect to login page after successful registration
                        // header("Location: login.php");
                        // exit();
                    }
                } catch (Exception $e) {
                    $msg = Message::danger("Registration failed. Please try again later. ".$e);
                }

            } else {
                $errors = $validator->getErrors();

                foreach ($errors as $field => $fieldErrors) {
                    foreach ($fieldErrors as $error) {
                        $msg .= Message::danger("Error in $field: $error");
                    }
                }
            }


        }
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

    <div class="row">
        <div class="col-12 col-md-6 offset-md-3">
            <h3>register to work</h3>
            <form class="loginForm" method="POST" action="register">
                <div class="mb-3">
                    <label for="username" class="form-label">Username <span class="required">*</span></label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address <span class="required">*</span></label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password <span class="required">*</span></label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="first_name" class="form-label">First Name <span class="required">*</span></label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                </div>
                <div class="mb-3">
                    <label for="last_name" class="form-label">Last Name <span class="required">*</span></label>
                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                </div>
                <div class="mb-3">
                    <label for="birthday" class="form-label">Birthday <span class="required">*</span></label>
                    <input type="date" class="form-control" id="birthday" name="birthday" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address <span class="required">*</span></label>
                    <input type="text" class="form-control" id="address" name="address" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number <span class="required">*</span></label>
                    <input type="tel" class="form-control" id="phone" name="phone" required>
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">register as: <span class="required">*</span></label>
                    <select class="form-control form-select" name="role" id="role">
                        <option value="user">employee - I want to work</option>
                        <option value="employer">employer - I want to post jobs</option>
                    </select>
                </div>
                <div class="mb-3">
                    <input type="checkbox" name="test"> Do not create account, this is testing
                </div>

                <button type="submit" class="btn btn-primary" name="register">Register</button>
                <br>
                Already have account? log in <a href="login">here</a>
                <br>
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