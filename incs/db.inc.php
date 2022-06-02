<?php
$username = "juri";
$password = "juripassword";
$server = "localhost";
$database = "juri_grading";

try {
    $conn = new PDO("mysql:host=$server;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $pe) {
    die("Could not connect to the database $database :" . $pe->getMessage());
}