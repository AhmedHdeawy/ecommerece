<?php

$dns = "mysql:host=localhost;dbname=shop";
$user = "ahmed";
$pass = "ahmed12";
$option = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',
);

try{
    $conn = new PDO($dns, $user, $pass, $option);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e)
{
    echo "Failed to Connect to DB " . $e->getMessage();
}