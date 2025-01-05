<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>loginCheck</title>
    <link rel="stylesheet" href="https://unpkg.com/mvp.css" />
  <link rel="stylesheet" href="./css/censter.css" />
</head>
<body>
<?php
    $ID = $_POST['ID'];
    $PW = $_POST['PW'];

    $dbcon = mysqli_connect('localhost', 'root', '');
    mysqli_select_db($dbcon, 'FiTo');


    $query = "SELECT * FROM register WHERE userId = '$ID' AND password = '$PW'";
    $result = mysqli_query($dbcon, $query);

    $row = mysqli_fetch_array($result);

    if ($result && mysqli_num_rows($result) > 0) {
        echo "로딩중";

        session_start();
        $_SESSION['time'] = time();
        $_SESSION['ID'] = $row['userId'];
        echo '<meta http-equiv="refresh" content="0; url=./main.php" />';

    } else {
        echo "<h1>로그인 실패</h1>";
        echo '<div id="register"><a href="./register.php"> 회원 가입</a>';
        echo '<div id="register"><a href="./login.php"> 로그인</a></div>';
    }
    mysqli_close($dbcon);


    ?>


</body>
</html>