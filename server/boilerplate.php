<?php
$username = "root";
$servername = "localhost";
$dbname = "mydb";

$con = mysqli_connect($servername, $username, "", $dbname); // setting up database connection

if (!$con)
    die("Connection failed" + mysqli_connect_error());

function test_input($data)  // prevention against sql injection
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
function completePayment(){  // mock function
    return true;
}
