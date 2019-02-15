<?php
require_once('./DatabaseConnection.php');
require_once('./User.php');
require_once('./Auth.php');
session_start([
    'cookie_lifetime' => 5 * 60,
]);
//$userEmail = 'ktos@wp.pl';
//$userPassword = 'haslo';
//
//$user = new User();
//$user->setEmail('ktos@wp.pl')->setHashPass('haslo')->setUserName('auth_user_test')->saveToDB($conn);
//
//$loggedUser = Auth::user($conn, $userEmail, $userPassword);
if (!array_key_exists('userName', $_SESSION) && isset($loggedUser)) {
    $_SESSION['userName'] = $loggedUser->getUserName();
}
var_dump($_SESSION)