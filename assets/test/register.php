<?php
include_once("../../assets/php/funkcije.php");

if(isset($_SESSION["user"])){
    echo Message::danger("you are logged in already.");
    die();
}

$msg = "";

//if (isset($_POST["register"]) && isset($_POST["username"])) {
if (Functions::issetValues(["register","username","email","password","first_name","last_name","birthday","address","phone","role"], $_POST)) {
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
                'email' => 'required|min:6|max:90|email', // email min 5, max 90, email
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

echo $msg;
?>