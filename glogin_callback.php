<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
ini_set('display_errors', 0);


require __DIR__ . '/vendor/autoload.php';
session_start();

$client = new \Google\Client();
$client->setClientId('YOUR_CLIENT_ID');
$client->setClientSecret('YOUR_CLIENT_SECRET');
$client->setRedirectUri('http://localhost/inquiryX/glogin_callback.php');

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (!isset($token['error'])) {
        $client->setAccessToken($token['access_token']);
        $oauth = new \Google\Service\Oauth2($client);
        $user = $oauth->userinfo->get();

        $_SESSION['loggedin'] = true;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->name;

        header('Location: dashboard.php');
        exit();
    } else {
        echo "Google OAuth error: " . htmlspecialchars($token['error']);
    }
} else {
    echo "No OAuth code returned – something went wrong";
}
