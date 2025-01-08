<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>랭킹</title>


</head>

<body>

    <?php
    database:
    session_start();
    $ID = $_SESSION['ID'] ?? null;

    include '../static/dbconfig.php';
    // 데이터베이스 연결
    $dbcon = mysqli_connect($host, $user, $password, $database);

    // 최신 userBodyInfo 가져오기
    $searchUserBodyInfoQuery = "SELECT * FROM userbodyinfo WHERE userId = '$ID' ORDER BY date DESC LIMIT 1";
    $searchUserBodyInfo = mysqli_query($dbcon, $searchUserBodyInfoQuery);
    $UserBodyInfo = mysqli_fetch_array($searchUserBodyInfo);

    // 생년월일 가져오기
    $searchBirthQuery = "SELECT birth FROM register WHERE userId = '$ID'";
    $searchBirth = mysqli_query($dbcon, $searchBirthQuery);
    $birth = mysqli_fetch_array($searchBirth);

    // 나이 계산
    $birthDate = new DateTime($birth['birth']);
    $currentDate = new DateTime();
    $age = $birthDate->diff($currentDate)->y;

    ?>

    <?php

    //성별에 따른 테이블 정하기
    if ($UserBodyInfo['sex'] == 1) {

        $scoreRank = 'scorerankmale';
    } else if ($UserBodyInfo['sex'] == 2) {
        $scoreRank = 'scorerankfemale';
    }

    //테이블에 중복된 ID있는지 확인
    $checkIDQuery = "SELECT userId FROM $scoreRank WHERE  userId = '$ID'";
    $checkIDQ = mysqli_query($dbcon, $checkIDQuery);

    // 테이블에 중복된 ID가 없을시 데이터 삽입
    if (!($checkIDQ && mysqli_num_rows($checkIDQ) > 0)) {

        $insertscorerankmaleQuery = "INSERT INTO $scoreRank (sex, age, height, weight, leanBodyMass, fatMass, fatPercentage, score, userId)
        VALUES ('$UserBodyInfo[sex]', '$age', '$UserBodyInfo[height]', '$UserBodyInfo[weight]', '$UserBodyInfo[leanBodyMass]', '$UserBodyInfo[fatMass]', '$UserBodyInfo[fatPercentage]', '$UserBodyInfo[score]', '$ID')";

        $insertscorerankmale = mysqli_query($dbcon, $insertscorerankmaleQuery);
        if ($insertscorerankmale) {
            // echo "생성 성공";
        }

    } else { // ID가 중복일시 데이터 수정하는 쿼리문 생성
        $updatescorerankmaleQuery = "
        UPDATE $scoreRank
        SET 
            age = '$age', 
            height = '$UserBodyInfo[height]', 
            weight = '$UserBodyInfo[weight]', 
            leanBodyMass = '$UserBodyInfo[leanBodyMass]', 
            fatMass = '$UserBodyInfo[fatMass]', 
            fatPercentage = '$UserBodyInfo[fatPercentage]', 
            score = '$UserBodyInfo[score]' 
        WHERE userId = '$ID'
    ";
        $updatescorerankmale = mysqli_query($dbcon, $updatescorerankmaleQuery);
        if ($updatescorerankmale) {
            // echo "수정 성공";
        }
    }
    ?>

    <?php
    // 순위 보여주는 쿼리
    $selectrnakQuery = "SELECT * FROM $scoreRank WHERE age=$age ORDER BY score DESC";
    $selectrnak = mysqli_query($dbcon, $selectrnakQuery);

    // 사용자 정보 저장 변수
    $userRank = null;
    $userScore = null;
    $userInfo = null; // 사용자 정보를 저장할 변수

    // 테이블 생성 준비
    $rankCounter = 1;

    while ($rank = mysqli_fetch_assoc($selectrnak)) {
        if ($rank['userId'] === $ID) {
            $userRank = $rankCounter; // 현재 사용자의 순위 저장
            $userScore = $rank['score']; // 현재 사용자의 점수 저장
            $userInfo = $rank; // 현재 사용자의 모든 정보 저장
            break; 
        }
        $rankCounter++;
    }

    // 사용자 정보를 테이블 위에 출력
    echo "
<div id='rankBanner' style='text-align: center; margin-bottom: 20px;'>
    <h2 style='color: #4CAF50; font-size: 24px;'>건강 순위</h2>
    <p style='font-size: 18px; margin: 5px;'>사용자 <strong>$ID</strong>님의 현재 순위는 <strong>$userRank 위</strong>입니다.</p>
    <p style='font-size: 18px; margin: 5px;'>점수: <strong>$userScore</strong></p>

</div>";
if ($userInfo) {
    echo "
    <div style='margin-bottom: 20px; text-align: center;'>
        <h3 style='color: #4CAF50;'>세부 정보</h3>
        <table border='1' style='margin: 0 auto; border-collapse: collapse; width: 80%; text-align: center;'>
            <tr>
                <th>성별</th>
                <th>나이</th>
                <th>키</th>
                <th>몸무게</th>
                <th>제지방량</th>
                <th>체지방량</th>
                <th>체지방률</th>
            </tr>
            <tr>
                <td>" . ($userInfo['sex'] == 1 ? '남성' : '여성') . "</td>
                <td>{$userInfo['age']}</td>
                <td>{$userInfo['height']} cm</td>
                <td>{$userInfo['weight']} kg</td>
                <td>{$userInfo['leanBodyMass']} kg</td>
                <td>{$userInfo['fatMass']} kg</td>
                <td>{$userInfo['fatPercentage']}%</td>
            </tr>
        </table>
    </div>";
} else {
    echo "<p style='text-align: center; color: red;'>사용자의 정보를 찾을 수 없습니다.</p>";
}


    // 순위 테이블 출력
    echo '<table border="1" style="border-collapse: collapse; width: 100%;">';
    echo '<tr>';
    echo '<th>순위</th>';
    echo '<th>아이디</th>';
    echo '<th>점수</th>';
    echo '</tr>';

    $selectrnak = mysqli_query($dbcon, $selectrnakQuery); // 테이블 재조회
    $rankCounter = 1;

    while ($rank = mysqli_fetch_assoc($selectrnak)) {
        echo '<tr>';
        echo '<td>' . $rankCounter . '</td>'; // 순위 출력
        echo '<td>' . htmlspecialchars($rank['userId']) . '</td>'; // 사용자 ID
        echo '<td>' . htmlspecialchars($rank['score']) . '</td>'; // 점수
        echo '</tr>';
        $rankCounter++; // 순위 증가
    }

    echo '</table>';
    ?>


</body>

</html>