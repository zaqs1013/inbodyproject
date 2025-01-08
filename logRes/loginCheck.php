<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>로그인 확인</title>
    <link rel="stylesheet" href="https://unpkg.com/mvp.css" />
    <link rel="stylesheet" href="../static/css/center.css" />
    <link rel="stylesheet" href="../static/css/erros.css" />
</head>
<body>
<?php
    include '../static/dbconfig.php';//디비 접속정보 파일

    $ID = $_POST['ID'];
    $PW = $_POST['PW'];

    $dbcon = mysqli_connect($host, $user, $password, $database);

    $query = "SELECT * FROM register WHERE userId = '$ID' AND password = '$PW'";
    $result = mysqli_query($dbcon, $query);

    $row = mysqli_fetch_array($result);

    if ($result && mysqli_num_rows($result) > 0) {
        session_start();
        $_SESSION['time'] = time();
        $_SESSION['ID'] = $row['userId'];

        echo "<div class='container'>";
        echo "<div class='card'>";
        echo "<h1>✅ 로그인 성공</h1>";
        echo "<p>로그인이 완료되었습니다. 메인 페이지로 이동합니다.</p>";
        echo "<meta http-equiv='refresh' content='2; url=../mainpage/main.php' />";
        echo "</div>";
        echo "</div>";
    } else {
        echo "<div class='container'>";
        echo "<div class='card'>";
        echo "<h1>⚠️ 로그인 실패</h1>";
        echo "<p>아이디 또는 비밀번호가 잘못되었습니다.</p>";
        echo "<a href='./register.php'>회원 가입</a>";
        echo "<a href='./login.php' class='error'>다시 로그인</a>";
        echo "</div>";
        echo "</div>";
    }
    mysqli_close($dbcon);
?>
</body>
</html>
