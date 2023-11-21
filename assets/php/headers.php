<?php
// Set a custom server signature
#header("Server: IIS/6.0", true); // this will fool observers
#header("X-Powered-By: Feces-Throwing-Monkey 3.14", true);

// Cross-Origin Resource Sharing(CORS)
//Ne diraj ako ne znas sta radis !!!
/*
header('Access-Control-Allow-Origin: https://domain.com');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept');
*/

/*
//prevent host header attack
$domains = ['abc.example.com', 'foo.bar.baz'];
if (!in_array($_SERVER['SERVER_NAME'], $domains)) {
    die();
}
*/

/*
 * TODO
 *      CSP: Wildcard Directive (1 occurence)
 * */

?>