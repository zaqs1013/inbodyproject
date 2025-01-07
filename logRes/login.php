<!DOCTYPE html>
<html lang="ko">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- Oswald font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap" rel="stylesheet">
  <!-- Do Hyeon font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Do+Hyeon&family=Oswald:wght@200..700&display=swap" rel="stylesheet">
  <!-- CSS link -->
  <link rel="stylesheet" href="../static/css/center.css" />
  <link rel="stylesheet" href="../static/css/login.css"/>
  <title>FITO</title>
</head>
<body>
  <?php
    session_start();
    session_unset();
    session_destroy();
  ?>
  <!-- 제목  -->
  <h1>FITO</h1>
  <!-- login-box -->
  <div class="loginbox">
    <form action="./loginCheck.php" method="post" id="from" autocomplete="off">
      <lebel class="lebel">LOGIN</label>
      <div class="id"><br>
        <label for="ID">ID</label>
        <input type="text" id="inputid" name="ID" value="" required title="아이디를 입력해주세요" />
      </div>
      <div id="pw">
        <label for="PW">PASSWORD</label>
        <input type="password" id="inputpw" name="PW" value="" required title="비밀번호를 입력해주세요" />
      </div>
    </form><br>
    <button id="loginbutton" type="submit">로그인</button>
    <button id="joinbutton"><a href="./register.php" id="join">회원가입</button></a>
  <div>
  
</body>

</html>
