<?php

$servername = "localhost";
$serveruser = "root";
$password = "";
$database = "companydatabase";

$connection = mysqli_connect($servername, $serveruser, $password, $database);

//when connection fails
if($connection === false) {
    die("Connection failed. " . mysqli_connect_error());
}

?>