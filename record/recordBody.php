<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>recoredBod</title>
    <link rel="stylesheet" href="https://unpkg.com/mvp.css" />
    <link rel="stylesheet" href="./css/censter.css" />
</head>

<?php
session_start();
if (!isset($_SESSION['ID'])) {
    echo '잘못된 접근';
    echo '<div id="register"><a href="./register.php"> 회원 가입</a>';
    echo '<div id="login"><a href="./login.php"> 로그인</a></div>';
    exit;
}
?>


<form action="./recordBodyOk.php" method="post" id="from" autocomplete="off">
    <h1>정보 입력</h1>
    <div id="height">
      <label for="ID">키</label>
      <input type="number" id="height" name="height" value=""  step="0.1" required title="신장을 입력해 주세요." />
    </div>
    <div id="weight">
      <label for="PW">체중</label>
      <input type="number" id="weight" name="weight" value="" step="0.1" required title="체중을 입력해 주세요." />
    </div>
    <div id="fatpercentage">
      <label for="fatpercentage">체지방률</label>
      <input type="number" id="fatpercentage" name="fatpercentage" value="" step="0.1" required title="체지방률을 입력해 주세요." />
    </div>

    <button id="submit" type="submit">입력</button>

  </form>
</body>

</html>