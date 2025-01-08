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

$dbcon = mysqli_connect('localhost', 'root', '', 'FiTo');

$searchRegisterQuery = "SELECT * FROM register WHERE userId = '$ID'";
$searchUserBodyInfoQuery = "SELECT * FROM userBodyInfo WHERE userId = '$ID' ORDER BY date DESC LIMIT 1";

$searchUserBodyInfo = mysqli_query($dbcon, $searchUserBodyInfoQuery);
$searchRegister = mysqli_query($dbcon, $searchRegisterQuery);
$register = mysqli_fetch_assoc($searchRegister);
$UserBodyInfo = mysqli_fetch_assoc($searchUserBodyInfo);

mysqli_close($dbcon);

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
                <section>
                    <div class="userdata">
                        <h2 id="report">FITO 회원정보</h2><br>
                        <div class="userprofile">
                            <!-- 기본 프로필 이미지 -->
                            <img id="profileImage" src="./img/profile.png" width="150px" height="150px" alt="프로필 이미지">
                            <!-- 변경 버튼 -->
                            <input type="file" name="image" id="input" accept="image/*" style="display: none;">
                            <button id="changbutton" type="button"><img src="./img/camera.png" width="35px"
                                    height="35px"></button>
                        </div><br>
                        <text id="user"> 이름 : <?php echo $register['name'] ?> </text>
                        <!-- loginout Button -->
                        <button id="outbutton">
                            <a href="../logRes/login.php">login out</a>
                        </button><br><br>
                        <text id="user">생년월일 : <?php echo $register['birth'] ?> </text><br><br>
                        <text id="user">성별 : <?php
                        if ($register['sex'] == 1) {
                            echo '남자';
                        } else if ($register['sex'] == 2) {
                            echo '여성';
                        } else {
                            echo '오류';
                        }

                        ?> </text><br><br>
                        <text id="user">키 : <?php echo $UserBodyInfo['height'] ?> </text><br><br>
                        <text id="user">몸무게 : <?php echo $UserBodyInfo['weight'] ?></text><br><br>
                        <text id="user">Email : <?php echo $register['email'] ?></text><br><br>
                        <text id="user">전화번호 : <?php echo $register['phone'] ?></text><br><br>
                        <div class="dropout">
                            <a href="#"><text>FTO 시스템 회원탈퇴</text></a>
                            <button id="inbodyupload"><a href="modification.php">회원정보 수정</a></button><br><br>
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
            <div class="home">
                <a href="../mainpage/main.php">
                    <img src="../mainpage/image/home_icon.png" alt="홈 아이콘" width="28" height="28">
                    홈
                </a>
            </div>
            <a href="../rank/ranking.php">
            <img src="../mainpage/image/notice.png" alt="랭킹 아이콘" width="30" height="30">
                랭킹
            </a>
            <a href="../todo/todo.php">
            <img src="../mainpage/image/column.png" alt="일정관리 아이콘" width="30" height="30">
                일정관리
            </a>
            <a href="./mypage.php">
                <img src="../mainpage/image/body_icon.png" alt="마이페이지 아이콘" width="30" height="30">
                마이페이지
            </a>
        </footer>
    </div>
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
