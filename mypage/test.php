<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>
<body>
    <h1>MySQL</h1>
    <?php
    $dbconn = mysqli_connect("localhost", "root", "");
    mysqli_select_db($dbconn, "fito");

    $sql = "SELECT userid, name, sex, phone, email FROM register";
    $result = mysqli_query($dbconn, $sql);

    if(!$result){
        die("쿼리 실행 실패 : ". mysql_error());
    }

    while ($row = mysqli_fetch_array($result)) {
        echo "번호: " . $row['userid'] . " / 이름: " . $row['name'] . " / 성별: " . $row['sex'] .
             " / 전화번호: " . $row['phone'] . " / 이메일: " . $row['email'] . "<br/>";
    }
    
    mysqli_close($dbconn);
    ?>
</body>
</html>