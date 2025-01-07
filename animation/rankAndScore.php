<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <?php
    database:
    session_start();
    $ID = $_SESSION['ID'] ?? null;


    // 데이터베이스 연결
    $dbcon = mysqli_connect("localhost", "root", "", "fito");

    // 최신 userBodyInfo 가져오기
    $searchUserBodyInfoQuery = "SELECT * FROM userBodyInfo WHERE userId = '$ID' ORDER BY date DESC LIMIT 1";
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
            echo "생성 성공";
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
            echo "수정 성공";
        }
    }
    ?>

    <?php
    // 순위보여주는 쿼리
    $selectrnakQuery = "SELECT userId, score FROM $scoreRank WHERE age=$age ORDER BY age ASC, score DESC";
    $selectrnak = mysqli_query($dbcon, $selectrnakQuery);

    // 테이블 시작 태그 출력
    echo '<table border="1" style="border-collapse: collapse; width: 100%;">';
    echo '<tr>';
    echo '<th>순위</th>';
    echo '<th>아이디</th>';
    echo '<th>점수</th>';
    echo '</tr>';
    $rankCounter = 1;
    $userRank = null;

    while ($rank = mysqli_fetch_assoc($selectrnak)) {
        echo '<tr>';
        echo '<td>' . $rankCounter . '</td>'; // 순위 출력
        echo '<td>' . htmlspecialchars($rank['userId']) . '</td>'; // 사용자 ID
        echo '<td>' . htmlspecialchars($rank['score']) . '</td>'; // 점수
        echo '</tr>';

        if ($rank['userId'] === $ID) {
            $userRank = $rankCounter; // 현재 사용자의 순위 저장
        }
        $rankCounter++; // 순위 증가
    
    }
    echo '</table>';


    // 결과 출력
    if ($userRank !== null) {
        echo "사용자 '$ID'의 순위는 $userRank 위 입니다.";
    } else {
        echo "해당 나이에 대한 순위를 찾을 수 없습니다.";
    }
    ?>


</body>

</html>