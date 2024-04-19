<?php

$host = "localhost";
$database = "computo";
$user = "d52024";
$password = "12345";

try {
  $conn = new PDO("mysql:host=$host;dbname=$database",$user,$password);
} catch (PDOException $e) {
  die("¡Error!:" . $e->getMessage());
}
