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
    <!-- Oswald font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap" rel="stylesheet">
    <!-- Do Hyeon font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Do+Hyeon&display=swap" rel="stylesheet">

    <title>FTO_project</title>
    <link rel="stylesheet" href="../static/css/modification.css"/>
</head>

<body>
    <div class="wrapper">
        <header>MYPAGE</header>
        <main>
            <div class="mainscren">
                <section>
                    <div class="userdata">
                        <h2 id="report">FITO 회원정보</h2>
                        <div class="userprofile">
                            <!-- 기본 프로필 이미지 -->
                            <img id="profileImage" src="./img/profile.png" width="150px" height="150px" alt="프로필 이미지">
                            <!-- 변경 버튼 -->
                            <input type="file" name="image" id="input" accept="image/*" style="display: none;">
                            <button id="changbutton" type="button"><img src="./img/camera.png" width="35px"
                                    height="35px"></button>
                        </div><br>
                        <?php
                        $dbconn = mysqli_connect("localhost", "root", "");
                        mysqli_select_db($dbconn, "fito");

                        $sql = "SELECT * FROM register WHERE userId = '$ID' ";
                        $result = mysqli_query($dbconn, $sql);

                        if (!$result) {
                            die("쿼리 실행 실패 : " . mysql_error());
                        }
                        $row = mysqli_fetch_assoc($result);
                        mysqli_close($dbconn);
                        ?>

                         <form action="modificationOk.php" method="post">
                    
                        <text id="user">이름: <input type="text" value="<?php echo $row['name']; ?>"
                                required id="name" name="name"></text><br><br>
                        <text id="user">전화번호: <input type="text" value="<?php echo $row['phone']; ?>"
                                required id="phNum" name="phNum"></text><br><br>
                        <text id="user">Email: <input type="text" value="<?php echo $row['email']; ?>"
                                required id="email" name="email"></text><br><br>
                        <div class="dropout">
                            <button id="inbodyupload1"><a href="modification.php">회원정보 수정</a></button>
                            <button id="inbodyupload2"><a href='../record/recordBody.php'>인바디 업데이트</button>
                    </div>
                </section>
            </div>
            <br><br>
        </main>
        <footer>
            <a href="../mainpage/main.php">
                <img src="../mainpage/image/home_icon.png" alt="홈 아이콘" width="28" height="28">
                홈
            </a>
            <a href="../rank/ranking.php">
                <img src="../mainpage/image/notice.png" alt="랭킹 아이콘" width="30" height="30">
                랭킹
            </a>
            <a href="../todo/todo.php">
                <img src="../mainpage/image/column.png" alt="일정관리 아이콘" width="30" height="30">
                일정관리
            </a>
            <a href="../mypage/mypage.php">
                <img src="../mainpage/image/body_icon.png" alt="마이페이지 아이콘" width="30" height="30">
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