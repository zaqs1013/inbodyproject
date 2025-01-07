<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>registerOk</title>
    <link rel="stylesheet" href="https://unpkg.com/mvp.css" />
    <link rel="stylesheet" href="./css/censter.css" />
    <link rel="stylesheet" href="./css/erros.css" />

</head>

<body>

    <?php
    include '../static/dbconfig.php';

    $name = $_POST['name'] ?? null;

    $sex = $_POST['sex'] ?? null;
    $birth = $_POST['birth'] ?? null;
    $email = $_POST['email'] ?? null;

    $ID = $_POST['ID'] ?? null;
    $PW = $_POST['PW'] ?? null;


    $phNum1 = $_POST['phNum1'] ?? null;
    $phNum2 = $_POST['phNum2'] ?? null;

    $phone = "010-" . $phNum1 . "-" . $phNum2;

    if (empty($name) || empty($sex) || empty($birth) || empty($email) || empty($ID) || empty($PW) || empty($phNum1) || empty($phNum2)) {
        echo "<div class='container'>";
        echo "<div class='card error'>";
        echo "<h1>⚠️ 입력 오류</h1>";
        echo "<p>모든 필드를 올바르게 입력해주세요.</p>";
        echo "<a href='./register.php'>다시 시도하기</a>";
        echo "</div>";
        echo "</div>";
        exit;
    }

    $dbcon = mysqli_connect($host, $user, $password);
    mysqli_select_db($dbcon, $database);

    try {
        $query = "INSERT INTO register VALUES (NULL, '$ID', '$PW', '$name','$sex','$phone','$email','$birth')";
        $result = mysqli_query($dbcon, $query);

        if ($result) {
            echo "<div class='container'>";
            echo "<div class='card'>";
            echo "<h1>✅ 가입 성공</h1>";
            echo "<p>환영합니다, <strong>$name</strong>님!</p>";
            echo "<a href='./login.php'>로그인 하러가기</a>";
            echo "</div>";
            echo "</div>";
        }

    } catch (mysqli_sql_exception $e) {
        echo "<div class='container'>";
        echo "<div class='card error'>";
        if ($e->getCode() === 1062) { // 중복 오류 코드
            echo "<h1>⚠️ 중복된 정보</h1>";
            echo "<p>아이디, 이메일, 또는 전화번호가 이미 등록되어 있습니다.</p>";
        } else {
            echo "<h1>⚠️ 시스템 오류</h1>";
            echo "<p>회원가입 중 문제가 발생했습니다. <br> 고객센터로 문의해주세요 (010-xxxx-xxxx).</p>";
        }
        echo "<a href='./register.php'>다시 시도하기</a>";
        echo "</div>";
        echo "</div>";
    }
    mysqli_close($dbcon);
    ?>

    <div id="login"></div> <a href="./login.php">로그인 하러가기</a></div>

</body>

</html>