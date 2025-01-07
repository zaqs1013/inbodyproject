<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Do Hyeon font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Do+Hyeon&family=Oswald:wght@200..700&display=swap" rel="stylesheet">
    <title>register</title>
    <link rel="stylesheet" href="../static/css/register.css" />
    <link rel="stylesheet" href="../static/css/register.css" />
</head>
<body>
    <form action="./registerOk.php" method="post" id="from" enctype="multipart/form-data">
        <h1>JOIN</h1>

        <label for="name">이름</label>
        <input type="text" id="name" name="name" required title="이름을 입력해주세요.">

        <div>
            <label for="sex">성별</label>
            <text id="man">남</text>
            <input type="radio" id="male" name="sex" value="1">
            <text id="wonman">여</text>
            <input type="radio" id="female" name="sex" value="2">
        </div>


        <label for="birth">생년월일</label>
        <input type="date" id="birth" name="birth" required title="생년월일을 입력해주세요.">

        <label for="email">이메일</label>
        <input type="email" id="email" name="email" placeholder="xxx@xxx.com" required title="이메일을 입력해주세요.">

        <div>
            <label for="phone">전화 번호</label>
            &nbsp;&nbsp;010&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;
            <input type="tel" id="phNum1" name="phNum1" maxlength="4"   pattern="\d{4}" placeholder="1234" required title="전화번호를 입력해주세요."> 
            &nbsp;-&nbsp;
            <input type="tel" id="phNum2"
            name="phNum2" maxlength="4"  pattern="\d{4}" placeholder="1234" required title="전화번호를 입력해주세요.">
        </div>

        <label for="ID">아이디</label>
        <input type="text" id="ID" name="ID" required title="아이디를 입력해주세요."><br>
        <label for="PW">비밀번호</label>
        <input type="password" id="PW" name="PW" required title="비밀번호를 입력해주세요.">



        <button>가입</button>
    </form>
</body>
</html>