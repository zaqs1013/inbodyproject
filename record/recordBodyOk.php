<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>recoredBodyOk</title>
    <link rel="stylesheet" href="https://unpkg.com/mvp.css" />
    <link rel="stylesheet" href="./css/censter.css" />
</head>

<body>
    <?php
    session_start();
    if (!isset($_SESSION['ID'])) {
        echo '잘못된 접근';
        echo '<div id="register"><a href="./register.php"> 회원 가입</a>';
        echo '<div id="login"><a href="./login.php"> 로그인</a></div>';
        exit;
    }
    //세션에 있는 id값값
    $ID = $_SESSION['ID'] ?? null;
    //전달받은 값값
    $height = $_POST['height'] ?? null;
    $weight = $_POST['weight'] ?? null;
    $fatpercentage = $_POST['fatpercentage'] ?? null;

    //계산되는 변수들 입력날짜, 체지방량, 제지방량량
    $date = date("Y-m-d") ?? null;
    $fatmass = round(($weight * $fatpercentage * 0.01), 1) ?? null;
    $leanbodymass = round($weight * (1 - $fatpercentage * 0.01), 1) ?? null;


    //디비 열기기
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
        echo '잘못된 접근';
        exit;
    }

    //유효성검사
    if (empty($ID) || empty($height) || empty($weight) || empty($fatpercentage) || empty($date) || empty($fatmass) || empty($leanbodymass)) {
        echo "<div class='error'>오류가 발생했습니다. </div>";
        exit;
    }

    try {
        // 데이터 삽입 쿼리 작성
        $insertInfoQuery = "INSERT INTO userBodyInfo VALUES (NULL, '$ID', '$sex', '$date', '$height', '$weight', '$leanbodymass', '$fatmass', '$fatpercentage', '$score')";
        
        // 쿼리 실행
        $result = mysqli_query($dbcon, $insertInfoQuery);
    
        if ($result) {
            // 성공 메시지 출력
            echo $ID . '키: ' . $height . '몸무게: ' . $weight . ' 체지방률: ' . $fatpercentage . '제지방량: ' . $leanbodymass . '성별: ' . $sex . '입력 날짜: ' . $date . ' 점수: ' . $score;
            echo '<a href="./main.php"> <button>메인</button></a>';
        } else {
            // 예외 발생시키기
            throw new Exception('쿼리 실행 실패: ' . mysqli_error($dbcon));
        }
    } catch (Exception $e) {
        // 예외 발생 시 오류 메시지 출력
        echo '오류가 발생했습니다: ';
    }

    mysqli_close($dbcon);
    ?>




</body>

</html>