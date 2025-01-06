<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do</title>
    <link rel="stylesheet" href="style.css">
  
</head>
<body>
<?php
    session_start();
    if (!isset($_SESSION['ID'])) {
        echo "잘못된 접근<br>";
        echo '<div id="register"><a href="./register.php"> 회원 가입</a></div><br>';
        echo '<div id="login"><a href="./login.php"> 로그인</a></div><br>';
        exit;
    }
    ?>
 

    <div class="wrapper">
        <header>fi-to</header>

        <div class="calendar-container">
            <div class="calendar">
                <div class="month">
                    <!-- <button class="prev">&lt;</button> -->
                    <svg class="prev" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"  d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                    <div class="date">12월 2024</div>
                    <svg class="next" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>

                    <!-- <button class="next">&gt;</button> -->
                </div>

                <div class="weekdays">
                    <div>일</div>
                    <div>월</div>
                    <div>화</div>
                    <div>수</div>
                    <div>목</div>
                    <div>금</div>
                    <div>토</div>
                </div>
                <div class="days">
                    <!--일 수 생성영역-->
                </div>
            </div>

            <div class="event-container">
                <h3>오늘의 일정</h3>
                <div class="event-day"></div>
                <div class="event-date"></div>
                <div class="event-list">
                    <!-- 일정 리스트가 여기에 동적으로 생성됩니다 -->
                </div>
                <button class="edit-event-btn">일정 추가</button>
                <div class="edit-event-wrapper">
                    <input type="text" class="edit-event-title" placeholder="일정 내용" />
                    <input type="time" class="edit-event-time-from" />
                    <svg class="down-svg" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3" />
                    </svg>
                    <input type="time" class="edit-event-time-to" />
                    <button class="save-event-btn">저장</button>
                    <br>
                    <br>
                    <br>
                    <br>
                </div>
            </div>        
    </div>

    <footer>
        <a href="../mainpage/main.php">
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
        <a href="../todo/todo">
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
    <script src="calender.js"></script>
    <script src="edit.js"></script>
    
</body>
</html>
