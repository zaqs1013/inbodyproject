<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register</title>
    <link rel="stylesheet" href="https://unpkg.com/mvp.css" />
    <link rel="stylesheet" href="./css/censter.css" />
    </head>

<body>
    <form action="./registerOk.php" method="post" id="from" enctype="multipart/form-data">
        <h1>회원 가입</h1>


        <label for="name">이름</label>
        <input type="text" id="name" name="name" required title="이름을 입력해주세요.">

        <div>
            <label for="sex">성별</label>
            남 <input type="radio" id="male" name="sex" value="1">
            여 <input type="radio" id="female" name="sex" value="2">
        </div>


        <label for="birth">생년월일</label>
        <input type="date" id="birth" name="birth" required title="생년월일을 입력해주세요.">

        <label for="email">이메일</label>
        <input type="email" id="email" name="email" placeholder="xxx@xxx.com" required title="이메일을 입력해주세요.">

        <div>
            <label for="phone">전화 번호</label>
            010 - <input type="tel" id="phNum1" name="phNum1" maxlength="4"   pattern="\d{4}" placeholder="1234" required title="전화번호를 입력해주세요."> - <input type="tel" id="phNum2"
                name="phNum2" maxlength="4"  pattern="\d{4}" placeholder="1234" required title="전화번호를 입력해주세요.">
        </div>

        <label for="ID">아이디</label>
        <input type="text" id="ID" name="ID" required title="아이디를 입력해주세요.">
        <label for="PW">비밀번호</label>
        <input type="password" id="PW" name="PW" required title="비밀번호를 입력해주세요.">



        <button>가입</button>
    </form>
</body>

</html>