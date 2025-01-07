<?php
include '../static/dbconfig.php';
$servername = $host;
$username = $user; // 기본 사용자
$password = $password; // 기본 비밀번호
$dbname = $database;

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}
?>
