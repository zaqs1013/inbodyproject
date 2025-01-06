<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>recoredBodyOk</title>
    <link rel="stylesheet" href="https://unpkg.com/mvp.css" />
    <link rel="stylesheet" href="./css/censter.css" />
    <link rel="stylesheet" href="./css/errorsRecord.css" />

</head>

<body>
<?php
session_start();

if (!isset($_SESSION['ID'])) {
    echo "<div class='container'>";
    echo "<div class='card'>";
    echo "<h1>⚠️ 잘못된 접근</h1>";
    echo "<p>로그인 후 이용하실 수 있습니다.</p>";
    echo "<a href='../logRes/register.php'>회원가입</a>";
    echo "<a href='../logRes/login.php'>로그인</a>";
    echo "</div>";
    echo "</div>";
    exit;
}

//세션에 있는 id값값
$ID = $_SESSION['ID'] ?? null;

//전달받은 값값
$height = $_POST['height'] ?? null;
$weight = $_POST['weight'] ?? null;
$fatpercentage = $_POST['fatpercentage'] ?? null;

//계산되는 변수들 입력날짜, 체지방량, 제지방량량
$date = date("Y-m-d");
$fatmass = round(($weight * $fatpercentage * 0.01), 1);
$leanbodymass = round($weight * (1 - $fatpercentage * 0.01), 1);

//디비 열기
$dbcon = mysqli_connect('localhost', 'root', '');
mysqli_select_db($dbcon, 'FiTo');

//성별찾기
$searchUserSexQuery = "SELECT sex FROM register WHERE userId = '$ID'";
$searchUserSex = mysqli_query($dbcon, $searchUserSexQuery);
$sex = mysqli_fetch_array($searchUserSex)['sex'];

//성별별 점수 계산
if ($sex == 1) {
    $score = round($leanbodymass - ((($height * 0.01) ** 2) * 22 * 0.85) + 80);
} else if ($sex == 2) {
    $score = round($leanbodymass - ((($height * 0.01) ** 2) * 21.5 * 0.77) + 80);
} else {
    echo "<div class='container'>";
    echo "<div class='card'>";
    echo "<h1>⚠️ 오류 발생</h1>";
    echo "<p>잘못된 접근입니다.</p>";
    echo "<a href='../logRes/login.php'>로그인</a>";
    echo "</div>";
    echo "</div>";
    exit;
}

//유효성검사
if (empty($ID) || empty($height) || empty($weight) || empty($fatpercentage)) {
    echo "<div class='container'>";
    echo "<div class='card'>";
    echo "<h1>⚠️ 입력 오류</h1>";
    echo "<p>모든 필드를 올바르게 입력해주세요.</p>";
    echo "<a href='./recordBody.php'>돌아가기</a>";
    echo "</div>";
    echo "</div>";
    exit;
}

try {
    // 데이터 삽입 쿼리 작성
    $insertInfoQuery = "INSERT INTO userBodyInfo VALUES (NULL, '$ID', '$sex', '$date', '$height', '$weight', '$leanbodymass', '$fatmass', '$fatpercentage', '$score')";
    
    // 쿼리 실행
    $result = mysqli_query($dbcon, $insertInfoQuery);

    if ($result) {
        //성공메세지 출력
        echo "<div class='container'>";
        echo "<div class='card'>";
        echo "<h1>✅ 입력 성공</h1>";
        echo "<p>체성분 데이터가 성공적으로 저장되었습니다.</p>";
        echo "<a href='../mainpage/main.php'>메인으로 돌아가기</a>";
        echo "</div>";
        echo "</div>";
    } else {
        // 예외 발생시키기
        throw new Exception('쿼리 실행 실패: ' . mysqli_error($dbcon));
    }
} catch (Exception $e) {
    // 예외 발생 시 오류 메시지 출력
    echo "<div class='container'>";
    echo "<div class='card'>";
    echo "<h1>⚠️ 시스템 오류</h1>";
    echo "<p>오류가 발생했습니다. 관리자에게 문의하세요.</p>";
    echo "</div>";
    echo "</div>";
}

mysqli_close($dbcon);
?>
</body>
</html>  
