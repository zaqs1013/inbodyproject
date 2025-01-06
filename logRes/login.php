<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Fi-To</title>
  <link rel="stylesheet" href="https://unpkg.com/mvp.css" />
  <link rel="stylesheet" href="../static/css/center.css" />

</head>

<body>
  <?php
    session_start();
    session_unset();
    session_destroy();
    ?>

  
  <h1>Fi-To</h1>

  <form action="./loginCheck.php" method="post" id="from" autocomplete="off">
    <h1>로그인</h1>
    <div id="ID">
      <label for="ID">아이디</label>
      <input type="text" id="ID" name="ID" value="" required title="아이디를 입력해주세요" />
    </div>
    <div id="pw">
      <label for="PW">비밀번호</label>
      <input type="password" id="PW" name="PW" value="" required title="비밀번호를 입력해주세요" />
    </div>

    <button id="loginbutton" type="submit">로그인</button>

  </form>

  <div id="register">
    <a href="./register.php"> <button>회원 가입</button></a>
  </div>
</body>

</html>
