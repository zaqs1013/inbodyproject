<?php
$servername = "localhost";
$username = "gongryak"; // 기본 사용자
$password = "trainingfito4!"; // 기본 비밀번호
$dbname = "gongryak";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}
?>
