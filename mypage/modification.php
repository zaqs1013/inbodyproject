<?php
    session_start();
    if (!isset($_SESSION['ID'])) {
        echo "<body style='display: block; margin: 0; font-family: Arial, sans-serif; background-color: #f4f4f9; color: #333;'>";
        echo "<div style='display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh; text-align: center;'>";
        echo "<div style='max-width: 400px; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background: white; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);'>";
        echo "<h1 style='font-size: 2rem; color: #e74c3c; margin-bottom: 16px;'>⚠️ 잘못된 접근</h1>";
        echo "<p style='font-size: 1rem; margin-bottom: 20px;'>로그인 후 이용하실 수 있습니다.</p>";
        echo "<a href='../logRes/register.php' style='display: block; text-decoration: none; padding: 10px 20px; margin-bottom: 10px; background-color: #3498db; color: white; border-radius: 4px; font-size: 1rem;'>회원가입</a>";
        echo "<a href='../logRes/login.php' style='display: block; text-decoration: none; padding: 10px 20px; background-color: #4CAF50; color: white; border-radius: 4px; font-size: 1rem;'>로그인</a>";
        echo "</div>";
        echo "</div>";
        echo "</body>";
        exit;
    }
 
    $ID = $_SESSION['ID'] ?? null;

?>


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
                        <?php
                            $dbconn = mysqli_connect("localhost","root","");
                            mysqli_select_db($dbconn, "fito");

                            $sql = "SELECT userid, name, sex, phone, email FROM register";
                            $result = mysqli_query($dbconn, $sql);

                            if(!$result){
                                die("쿼리 실행 실패 : ". mysql_error());
                            }

                            $row = mysqli_fetch_assoc($result);
                            mysqli_close($dbconn);
                        ?>
                        <text id="user">아이디: <input type="text" value="<?php echo $row['userid']; ?>" readonly></text><br><br>
                        <text id="user">이름: <input type="text" value="<?php echo $row['name']; ?>" readonly></text><br><br>
                        <text id="user">성별: <input type="text" value="<?php echo $row['sex']; ?>" readonly></text><br><br>
                        <text id="user">전화번호: <input type="text" value="<?php echo $row['phone']; ?>" readonly></text><br><br>
                        <text id="user">Email: <input type="text" value="<?php echo $row['email']; ?>" readonly></text><br><br>
                        <div class="dropout">
                            <a href="#"><text>FTO 시스템 회원탈퇴</text></a>
                            <button id="inbodychangbutton">회원정보 수정</button><br><br>
                        </div>
                        <div class="inbody">
                            <button id="inbodyupload"><a href='../record/recordBody.php'>인바디 업데이트</button><br><br>
                        </div>
                    </div>
                </section>
            </div>
            <br><br>
        </main>
        <footer>
            <footer>
                <a href="./main.php">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10l9-7 9 7v11a1 1 0 01-1 1H4a1 1 0 01-1-1V10z" />
                    </svg>
                    홈
                </a>
                <a href="../record/recordBody.php">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-3.314 0-6 2.686-6 6 0 1.657.676 3.157 1.757 4.243A5.978 5.978 0 0012 20c1.657 0 3.157-.676 4.243-1.757A5.978 5.978 0 0018 14c0-3.314-2.686-6-6-6zm0 8a2 2 0 100-4 2 2 0 000 4z" />
                    </svg>
                    인바디정보
                </a>
                <a href="../todo/todo.php">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m0 4h.01M8 12v8m0-8h.01M16 7V3m0 4h.01M16 12v8m0-8h.01" />
                    </svg>
                    투두리스트
                </a>
                <a href="../mypage/mypage.php">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                    </svg>
                    마이페이지
                </a>
            </footer>
        </footer>
    </div>
    <script>
        const input = document.getElementById('input');
        const changbutton = document.getElementById('changbutton');
        const inbodychangbutton = document.getElementById('inbodychangbutton');
        
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

        inbodychangbutton.addEventListener('click', () => {
            alert("수정이 완료되었습니다");
        });
    </script>
</body>
</html>