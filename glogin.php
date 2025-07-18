<?php
require __DIR__ . '/vendor/autoload.php'; // Correct path to autoload.php
session_start();

$client = new \Google\Client();
$client->setClientId('581552662608-s7l0in65dovkoec9ovbor8ln5a70085d.apps.googleusercontent.com');
$client->setClientSecret('YOUR_SECRET');
$client->setRedirectUri('http://localhost/InquiryX/glogin_callback.php');
$client->addScope(['email', 'profile']);
$client->setAccessType('offline');
$client->setPrompt('select_account consent'); // Ensures code is returned every time

header('Location: ' . $client->createAuthUrl());
exit();
