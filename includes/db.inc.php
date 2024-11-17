<?php
$serverName = "localhost";
$connUsername = "root";
$connPassword = "root";
$connName = "todowithme";

$conn = mysqli_connect($serverName, $connUsername, $connPassword, $connName);
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
