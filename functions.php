<?php
header('Content-Type: text/html; charset=UTF-8');
global $db;
$user = 'u52945';
$pass = '3219665';
    $db = new PDO('mysql:host=localhost;dbname=u52945', $user, $pass, [
        PDO::ATTR_PERSISTENT => true,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
?>
