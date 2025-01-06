<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// 데이터베이스 연결 정보
$host = 'localhost';
$user = 'your_username';
$password = 'your_password';
$database = 'fito';

// 데이터베이스 연결
$conn = mysqli_connect('localhost', 'root', '', 'fito');



// 연결 오류 확인
if ($conn->connect_error) {
    die(json_encode(['error' => 'Failed to connect to database']));
}

// 데이터 조회 쿼리
$query = "
    SELECT 
        id AS rank,
        userId AS name,
        sex As sex,
        (weight / (height * height)) AS bmi,
        score 
    FROM userbodyinfo
    ORDER BY score DESC
";

$result = $conn->query($query);

// 결과 변환
if ($result->num_rows > 0) {
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $row['bmi'] = round($row['bmi'], 1); // BMI를 소수점 한 자리로
        $users[] = $row;
    }
    echo json_encode($users);
} else {
    echo json_encode([]);
}

$conn->close();
?>
