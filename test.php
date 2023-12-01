<link rel="stylesheet" href="assets/css/style.css">

<?php
include_once("assets/php/funkcije.php");

function post(array $data, string $url): string {
    // use key 'http' even if you send the request to https://...
    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data),
        ],
    ];

    $context = stream_context_create($options);
    return file_get_contents($url, false, $context);
}

/*
 * Run database sql setup
 * */
$conn = Connection::connection();
if ($conn->multi_query(file_get_contents("other/database.sql"))) {
    echo "SQL file successfully executed.<hr>";
} else {
    echo "Error executing SQL file: " . $conn->error;
    die();
}

/*
 * User registration test
 * */
echo "<hr>User registration test</hr>";
echo "<br><b>creating legitimate users createUser()</b><br>";
$url = 'http://127.0.0.1/omladinska-zadruga/assets/test/register';
echo "<br><b>selena</b><br>";

echo Functions::httpRequest([
    "register"=>"","username"=>"selena","email"=>"selena@gmail.com","password"=>"testpass","first_name"=>"selena","last_name"=>"gomez",
    "birthday"=>"1990-01-01","address"=>"addr","phone"=>"03940349","role"=>"user"],$url,"POST"
);

echo "<br><b>emma</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"emma123","email"=>"emma@gmail.com","password"=>"testpass","first_name"=>"emma","last_name"=>"watson",
    "birthday"=>"1990-01-01","address"=>"addr","phone"=>"039401349","role"=>"user"],$url
);

echo "<br><b>emma</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"pera123","email"=>"pera@gmail.com","password"=>"testpass","first_name"=>"pera","last_name"=>"peric",
    "birthday"=>"1990-01-01","address"=>"addr","phone"=>"039404349","role"=>"user"],$url
);

/*
 * username check
 * */
echo "<br><b>creating user with <b>username already taken (selena)</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"selena","email"=>"marko@gmail.com","password"=>"testpass","first_name"=>"marko","last_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"addr","phone"=>"0339340349","role"=>"user"],$url
);

echo "<br><b>creating user with <b>username to small (ema)</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"ema","email"=>"marko@gmail.com","password"=>"testpass","first_name"=>"marko","last_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"addr","phone"=>"0339340349","role"=>"user"],$url
);

echo "<br><b>creating user with <b>username to big (asdkl9asik9asjdk9asjd9asjd9asjdasd)</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"asdkl9asik9asjdk9asjd9asjd9asjdasd","email"=>"marko@gmail.com","password"=>"testpass","first_name"=>"marko","last_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"addr","phone"=>"0339340349","role"=>"user"],$url
);

echo "<br><b>creating user with <b>username has special characters (te@st)</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"te@st","email"=>"marko@gmail.com","password"=>"testpass","first_name"=>"marko","last_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"addr","phone"=>"0339340349","role"=>"user"],$url
);

echo "<br><b>creating user with <b>username is missing</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","email"=>"marko@gmail.com","password"=>"testpass","first_name"=>"marko","last_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"addr","phone"=>"0339340349","role"=>"user"],$url
);

echo "<br><b>creating user with <b>username is empty</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"","email"=>"marko@gmail.com","password"=>"testpass","first_name"=>"marko","last_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"addr","phone"=>"0339340349","role"=>"user"],$url
);

/*
 * email check
 * */
echo "<br><b>creating user with <b>email already taken (emma@gmail.com)</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"testeee","email"=>"emma@gmail.com","password"=>"testpass","first_name"=>"marko","last_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"addr","phone"=>"0339340349","role"=>"user"],$url
);

echo "<br><b>creating user with <b>email to small (a@a.a)</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"asdsadasd","email"=>"a@a.a","password"=>"testpass","first_name"=>"marko","last_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"addr","phone"=>"0339340349","role"=>"user"],$url
);

echo "<br><b>creating user with <b>email to big (asdkl9asik9asjdk9asjdasdasd9asjd9asjdasd@gmail.com)</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"asdasd","email"=>"asdkl9asiasdasdasdasdasdasdasdasasdasdk9asjdk9asjdasdasd9asjd9asjdasd@gmail.com","password"=>"testpass","first_name"=>"marko","last_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"addr","phone"=>"0339340349","role"=>"user"],$url
);

echo "<br><b>creating user with <b>Email not valid format(test@test)</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"teast","email"=>"test@test","password"=>"testpass","first_name"=>"marko","last_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"addr","phone"=>"0339340349","role"=>"user"],$url
);
echo "<br><b>creating user with <b>Email not valid format(testtest.com)</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"teast","email"=>"testtest.com","password"=>"testpass","first_name"=>"marko","last_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"addr","phone"=>"0339340349","role"=>"user"],$url
);

echo "<br><b>creating user with <b>email is missing</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"teast","password"=>"testpass","first_name"=>"marko","last_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"addr","phone"=>"0339340349","role"=>"user"],$url
);

echo "<br><b>creating user with <b>email is empty</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"teast","email"=>"","password"=>"testpass","first_name"=>"marko","last_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"addr","phone"=>"0339340349","role"=>"user"],$url
);

/*
 * password check
 * */

echo "<br><b>creating user with <b>password to small (123)</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"asdsadasd","email"=>"aasdasd@a.a","password"=>"123","first_name"=>"marko","last_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"addr","phone"=>"0339340349","role"=>"user"],$url
);

echo "<br><b>creating user with <b>password to big (asdkl9asik9asjdk9asjdasdasd9asjd9asjdasd)</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"asdasd","email"=>"aasdasd@a.a","password"=>"testpassasdkl9asik9asjdk9asjdasdasd9asjd9asjdasdasdkl9asik9asjdk9asjdasdasd9asjd9asjdasd","first_name"=>"marko","last_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"addr","phone"=>"0339340349","role"=>"user"],$url
);

echo "<br><b>creating user with <b>password is missing</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"teast","email"=>"aasdasd@a.a","first_name"=>"marko","last_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"addr","phone"=>"0339340349","role"=>"user"],$url
);

echo "<br><b>creating user with <b>password is empty</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"teast","email"=>"aasdasd@a.a","password"=>"","first_name"=>"marko","last_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"addr","phone"=>"0339340349","role"=>"user"],$url
);

/*
 * first name check
 * */
echo "<br><b>creating user with <b>name to small (a)</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"asdsadasd","email"=>"aasdasd@a.a","password"=>"asdsadasd","first_name"=>"a","last_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"addr","phone"=>"0339340349","role"=>"user"],$url
);

echo "<br><b>creating user with <b>name has numbers (test123asd)</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"asdasd","email"=>"aasdasd@a.a","password"=>"asdasdasd","first_name"=>"test123asd","last_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"addr","phone"=>"0339340349","role"=>"user"],$url
);

echo "<br><b>creating user with <b>name to big (asdsadasdasdasdasdasasddasasdsadasdasdasdasdasasddas)</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"asdasd","email"=>"aasdasd@a.a","password"=>"asdasdasd","first_name"=>"asdsadasdasdasdasdasasddasasdsadasdasdasdasdasasddas","last_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"addr","phone"=>"0339340349","role"=>"user"],$url
);

echo "<br><b>creating user with <b>name is missing</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"teast","email"=>"aasdasd@a.a","password"=>"asdasdasd","last_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"addr","phone"=>"0339340349","role"=>"user"],$url
);

echo "<br><b>creating user with <b>name is empty</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"teast","email"=>"aasdasd@a.a","password"=>"asdasdasd","first_name"=>"","last_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"addr","phone"=>"0339340349","role"=>"user"],$url
);

/*
 * last name check
 * */
echo "<br><b>creating user with <b>last name to small (a)</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"asdsadasd","email"=>"aasdasd@a.a","password"=>"asdsadasd","last_name"=>"a","first_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"addr","phone"=>"0339340349","role"=>"user"],$url
);

echo "<br><b>creating user with <b>last name has numbers (test123asd)</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"asdasd","email"=>"aasdasd@a.a","password"=>"asdasdasd","last_name"=>"test123asd","first_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"addr","phone"=>"0339340349","role"=>"user"],$url
);

echo "<br><b>creating user with <b>last name to big (asdsadasdasdasdasdasasddasasdsadasdasdasdasdasasddas)</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"asdasd","email"=>"aasdasd@a.a","password"=>"asdasdasd","last_name"=>"asdsadasdasdasdasdasasddasasdsadasdasdasdasdasasddas","first_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"addr","phone"=>"0339340349","role"=>"user"],$url
);

echo "<br><b>creating user with <b>last name is missing</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"teast","email"=>"aasdasd@a.a","password"=>"asdasdasd","first_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"addr","phone"=>"0339340349","role"=>"user"],$url
);

echo "<br><b>creating user with <b>last name is empty</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"teast","email"=>"aasdasd@a.a","password"=>"asdasdasd","last_name"=>"","first_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"addr","phone"=>"0339340349","role"=>"user"],$url
);

/*
 * last name check
 * */

//must be a valid date
echo "<br><b>creating user with <b>birthday is not date(asdasdasd)</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"asdsadasd","email"=>"aasdasd@a.a","password"=>"asdsadasd","first_name"=>"masdasda","last_name"=>"markovic",
    "birthday"=>"asdasdasd","address"=>"addr","phone"=>"0339340349","role"=>"user"],$url
);
echo "<br><b>creating user with <b>birthday is not date(234234234)</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"asdsadasd","email"=>"aasdasd@a.a","password"=>"asdsadasd","first_name"=>"masdasda","last_name"=>"markovic",
    "birthday"=>"234234234","address"=>"addr","phone"=>"0339340349","role"=>"user"],$url
);
//must not be in future
echo "<br><b>creating user with <b>birthday is in future date(2024-01-01)</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"asdsadasd","email"=>"aasdasd@a.a","password"=>"asdsadasd","first_name"=>"masdasda","last_name"=>"markovic",
    "birthday"=>"2024-01-01","address"=>"addr","phone"=>"0339340349","role"=>"user"],$url
);
//must be at least 18y old
echo "<br><b>creating user with <b>birthday must be at least 18y old date(2015-01-01)</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"asdsadasd","email"=>"aasdasd@a.a","password"=>"asdsadasd","first_name"=>"masdasda","last_name"=>"markovic",
    "birthday"=>"2015-01-01","address"=>"addr","phone"=>"0339340349","role"=>"user"],$url
);

echo "<br><b>creating user with <b>birthday is missing</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"teast","email"=>"aasdasd@a.a","password"=>"asdasdasd","last_name"=>"asdasdasd","first_name"=>"markovic",
    "address"=>"addr","phone"=>"0339340349","role"=>"user"],$url
);

echo "<br><b>creating user with <b>birthday is empty</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"teast","email"=>"aasdasd@a.a","password"=>"asdasdasd","last_name"=>"asdasdasd","first_name"=>"markovic",
    "birthday"=>"","address"=>"addr","phone"=>"0339340349","role"=>"user"],$url
);

/*
 * address
 * */
echo "<br><b>creating user with <b>address name to small (a)</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"asdsadasd","email"=>"aasdasd@a.a","password"=>"asdsadasd","last_name"=>"asdasda","first_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"a","phone"=>"0339340349","role"=>"user"],$url
);

echo "<br><b>creating user with <b>address to big (asdsadasdasdasdasdasasddasasdsadasdasdasdasdasasddas)</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"asdsadasd","email"=>"aasdasd@a.a","password"=>"asdsadasd","last_name"=>"asdasda","first_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"asdsadasdasdasdasdasasddasasdsadasdasdasdasdasasddas","phone"=>"0339340349","role"=>"user"],$url
);

echo "<br><b>creating user with <b>address is missing</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"asdsadasd","email"=>"aasdasd@a.a","password"=>"asdsadasd","last_name"=>"asdasda","first_name"=>"markovic",
    "birthday"=>"1990-01-01","phone"=>"0339340349","role"=>"user"],$url
);

echo "<br><b>creating user with <b>address is empty</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"asdsadasd","email"=>"aasdasd@a.a","password"=>"asdsadasd","last_name"=>"asdasda","first_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"","phone"=>"0339340349","role"=>"user"],$url
);

/*
 * phone 'required|phone_number|min:6|max:15'
 * */

echo "<br><b>creating user with <b>phone to small (a)</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"asdsaaaadasd","email"=>"aasdasd@a.a","password"=>"asdsadasd","last_name"=>"asdasdasda","first_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"addr","phone"=>"0345","role"=>"user"],$url
);

echo "<br><b>creating user with <b>phone to big (asdsadasdasdasdasdasasddasasdsadasdasdasdasdasasddas)</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"asdsaaaadasd","email"=>"aasdasd@a.a","password"=>"asdasdasd","last_name"=>"asdasdasd","first_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"addr","phone"=>"0345034503450345","role"=>"user"],$url
);

echo "<br><b>creating user with <b>phone is missing</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"asdsaaaadasd","email"=>"aasdasd@a.a","password"=>"asdsadasd","last_name"=>"asdasda","first_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"asdasdasd","role"=>"user"],$url
);

echo "<br><b>creating user with <b>phone is empty</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"asdsaaaadasd","email"=>"aasdasd@a.a","password"=>"asdsadasd","last_name"=>"asdasda","first_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"asdasdasd","phone"=>"","role"=>"user"],$url
);

echo "<br><b>creating user with <b>phone not in format (343jji3433)</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"asdsaaaadasd6","email"=>"aasd6asd@a.a","password"=>"asdsadasd","last_name"=>"asdasda","first_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"asdasdasd","phone"=>"343jji3433","role"=>"user"],$url
);

echo "<br><b>creating user with <b>phone not in format (12345)</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"asdsaaaadasd5","email"=>"aasda5sd@a.a","password"=>"asdsadasd","last_name"=>"asdasda","first_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"asdasdasd","phone"=>"12345","role"=>"user"],$url
);

echo "<br><b>creating user with <b>phone not in format (+12345678901234567890)</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"asdsaaaadasd4","email"=>"aasd4asd@a.a","password"=>"asdsadasd","last_name"=>"asdasda","first_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"asdasdasd","phone"=>"+12345678901234567890","role"=>"user"],$url
);

echo "<br><b>creating user with <b>phone not in format (abc1234567)</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"asdsaaaadasd3","email"=>"aasd3asd@a.a","password"=>"asdsadasd","last_name"=>"asdasda","first_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"asdasdasd","phone"=>"abc1234567","role"=>"user"],$url
);

echo "<br><b>creating user with <b>phone not in format (+1 23 45 67 89)</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"asdsaaaadasd2","email"=>"aasd2asd@a.a","password"=>"asdsadasd","last_name"=>"asdasda","first_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"asdasdasd","phone"=>"+1 23 45 67 89","role"=>"user"],$url
);

echo "<br><b>creating user with <b>phone not in format (123-456-7890)</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"asdsaaaadasd1","email"=>"aasd1asd@a.a","password"=>"asdsadasd","last_name"=>"asdasda","first_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"asdasdasd","phone"=>"123-456-7890","role"=>"user"],$url
);

/*
 * role test
 * */
echo "<br><b>creating user with <b>wrong role (test)</b> createUser()</b><br>";
echo Functions::httpRequest([
    "register"=>"","username"=>"hryjytjy","email"=>"tyjtyjrj@a.a","password"=>"asdsadasd","last_name"=>"asdasda","first_name"=>"markovic",
    "birthday"=>"1990-01-01","address"=>"asdasdasd","phone"=>"0543567777","role"=>"test"],$url
);

?>