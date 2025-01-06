<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jua&display=swap" rel="stylesheet">
    <title>FTO_project</title>
    <link rel="stylesheet" href="./style/mypage.css">
</head>
<body>
    <div class="wrapper">
        <head>
            <div class="maintext">
                <text id="headtext">마이페이지</text>
            </div>
            
        </head>
        <main>
            <div class="mainscren">
                <section >
                    <div class="userdata">
                        <h2 id="report">FITO 회원정보</h2>
                        <div class="userprofile">
                            <!-- 기본 프로필 이미지 -->
                            <img id="profileImage" src="./img/profile.png" width="150px" height="150px" alt="프로필 이미지">
                            <!-- 변경 버튼 -->
                                <input type="file" name="image" id="input" accept="image/*" style="display: none;">
                                <button id="changbutton" type="button"><img src="./img/camera.png" width="35px" height="35px"></button>
                        </div><br>
                        <text id="user"> 아이디 : 
                            <input type="text" name="userid" id="userid">
                        </text>
                        <button id="outbutton">
                            <a href="../logRes/login.php">login out</a>
                        </button><br><br>
                        <text id="user">나이 : 
                            <input type="text" name="age" id="age"></text><br><br>
                        <text id="user">성별 : 
                            <input type="text" name="gender" id="gender">
                        </text><br><br>
                        <text id="user">키 : 
                            <input type="text" name="stature" id="stature">
                        </text><br><br>
                        <text id="user">몸무게 : 
                            <input type="text" name="weight" id="weight">
                        </text><br><br>
                        <text id="user">Email : 
                            <input type="text" name="Email" id="Email">
                        </text><br><br>
                        <text id="user">전화번호 : 
                            <input type="text" name="phone" id="phone">
                        </text><br><br><br>
                        <div class="dropout">
                            <a href="#"><text>FTO 시스템 회원탈퇴</text></a>
                            <button id="crystal">회원정보 수정</button><br><br>
                        </div>
                    </div>
                </section>
            </div>
            <br><br>
        </main>
        <footer>
            <footer>
                <a href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10l9-7 9 7v11a1 1 0 01-1 1H4a1 1 0 01-1-1V10z" />
                    </svg>
                    홈
                </a>
                <a href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-3.314 0-6 2.686-6 6 0 1.657.676 3.157 1.757 4.243A5.978 5.978 0 0012 20c1.657 0 3.157-.676 4.243-1.757A5.978 5.978 0 0018 14c0-3.314-2.686-6-6-6zm0 8a2 2 0 100-4 2 2 0 000 4z" />
                    </svg>
                    인바디정보
                </a>
                <a href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m0 4h.01M8 12v8m0-8h.01M16 7V3m0 4h.01M16 12v8m0-8h.01" />
                    </svg>
                    투두리스트
                </a>
                <a href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                    </svg>
                    마이페이지
                </a>
            </footer>
        </footer>
    </div>
    <?php
        $host = "localhost";
        $username = "root";
        $password = "";
        $database = "fito";
        
    ?>
    <script>
        const input = document.getElementById('input');
        const changbutton = document.getElementById('changbutton');
        
        changbutton.addEventListener('click', () => {
            input.click();
        })

        input.addEventListener('change', () => {
            if (input.files && input.files.length > 0) {
            const file = input.files[0];

            // 파일 이름 출력
            alert(`선택된 파일: ${file.name}`);

            // FileReader로 파일 읽기
            const reader = new FileReader();
            reader.onload = function (e) {
                profileImage.src = e.target.result; // 프로필 이미지 변경
            };
            reader.readAsDataURL(file);
            } else {
                alert("선택된 파일 없음");
            }
        });

    </script>
</body>
</html>
</body>
</html>