<?php
    $dsn = 'path';
    $user = 'user';
    $password = 'pass';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
?>
