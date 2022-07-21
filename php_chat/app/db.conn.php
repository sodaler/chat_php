<?php

# server name
$sName = "localhost";
# username
$uName = "root";
# password
$pass = "";

# database name
$db_name = "chat_app_db";

# creating database connection
try {
    $conn = new PDO("mysql:host=$sName;dbname=$db_name", $uName, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $ex) {
    echo "Connection failed : " . $ex->getMessage();
}