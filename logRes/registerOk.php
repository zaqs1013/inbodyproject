<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>registerOk</title>
    <link rel="stylesheet" href="https://unpkg.com/mvp.css" />
    <link rel="stylesheet" href="./css/censter.css" />


</head>

<body>

    <?php
    $name = $_POST['name'] ?? null;

    $sex = $_POST['sex'] ?? null;
    $birth = $_POST['birth'] ?? null;
    $email = $_POST['email'] ?? null;

    $ID = $_POST['ID'] ?? null;
    $PW = $_POST['PW'] ?? null;


    $phNum1 = $_POST['phNum1'] ?? null;
    $phNum2 = $_POST['phNum2'] ?? null;

    $phone = "010-" . $phNum1 . "-" . $phNum2;

    if (empty($$name)&&empty($sex)&&empty($birth)&&empty($email)&&empty($ID)&&empty($PW) &&empty($phNum1) &&empty($phNum2)) {
        echo "<div class='error'>오류가 발생했습니다. </div>";
        exit;
    }

    $dbcon = mysqli_connect('localhost', 'root', '');
    mysqli_select_db($dbcon, 'FiTo');

    try {
        $query = "INSERT INTO register VALUES (NULL, '$ID', '$PW', '$name','$sex','$phone',' $email','$birth')";
        $result = mysqli_query($dbcon, $query);

        if ($result) {
            echo "<div class='suecces'>가입 성공</div>";
            echo "환영합니다 $name 님";
            echo "";
        }


    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() === 1062) { // 중복 오류 코드
            echo "<div class='error'>중복된 아이디, 이메일, 전화번호가 있습니다.</div>";
        } else {
            echo "<div class='error'>오류가 발생했습니다 010-xxxx-xxxx으로 전화를 주세요</div>";
        }
    }
    mysqli_close($dbcon);

    ?>

    <div id="login"></div> <a href="./login.php">로그인 하러가기</a></div>

</body>

</html>