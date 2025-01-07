<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    session_start();

    $ID = $_SESSION['ID'] ?? null;
    $name = $_POST['name'];
    $phNum = $_POST['phNum'];
    $email = $_POST['email'];

    try {
        $dbcon = mysqli_connect("localhost", "root", "");
        mysqli_select_db($dbcon, "fito");

        $updateRegisterQuery = "UPDATE register SET name='$name', phone = '$phNum', email ='$email' WHERE userId = '$ID'";
        $updateRegister = mysqli_query($dbcon, $updateRegisterQuery);
        mysqli_close($dbcon);

        if ($updateRegister) {
            echo "<meta http-equiv='refresh' content='2; url=./mypage.php' />";
        }
    } catch (mysqli_sql_exception $e) {
        echo "<div class='container'>";
        echo "<div class='card error'>";
        if ($e->getCode() === 1062) { // 중복 오류 코드
            echo "<h1>⚠️ 중복된 정보</h1>";
            echo "<p>이메일, 또는 전화번호가 이미 등록되어 있습니다.</p>";
        } else {
            echo "<h1>⚠️ 시스템 오류</h1>";
            echo "<p>문제가 발생했습니다. <br> 고객센터로 문의해주세요 (010-xxxx-xxxx).</p>";
        }
        echo "<a href='./modification.php'>다시 시도하기</a>";
        echo "</div>";
        echo "</div>";
    }

    ?>
</body>

</html>