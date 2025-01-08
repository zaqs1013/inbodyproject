<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do</title>
    <link rel="stylesheet" href="../static/css/todoStyle.css" />
    <link rel="stylesheet" href="../static/css/todo_nutrition-summary.css" />
</head>

<body>
    <?php
    session_start();
    include 'db.php';

    if (!isset($_SESSION['ID'])) {
        echo "잘못된 접근<br>";
        echo '<div id="register"><a href="../logRes/register.php"> 회원 가입</a></div><br>';
        echo '<div id="login"><a href="../logRes/login.php"> 로그인</a></div><br>';
        exit;
    }

    $userId = $_SESSION['ID'];
    $selectedDate = $_GET['date'] ?? date('Y-m-d'); // 선택된 날짜, 기본값은 오늘 날짜

    // 섭취한 음식 정보 가져오기
    $stmt = $conn->prepare(
        "SELECT nd.foodName, nd.kcal, nd.protein, nd.fat, nd.carbohydrate, nd.sugar 
        FROM nutrition_summary ns
        JOIN nutrition_details nd ON ns.id = nd.summaryId
        WHERE ns.userId = ? AND ns.date = ?"
    );
    $stmt->execute([$userId, $selectedDate]);
    $foods = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 선택된 날짜의 섭취 총합 가져오기
    $stmtTotal = $conn->prepare(
        "SELECT totalKcal, totalProtein, totalFat, totalCarbohydrate, totalSugar 
        FROM nutrition_summary
        WHERE userId = ? AND date = ?"
    );
    $stmtTotal->execute([$userId, $selectedDate]);
    $total = $stmtTotal->fetch(PDO::FETCH_ASSOC);
    ?>

<div class="container">
    <div class="wrapper">
        <header>fi-to</header>

        <div class="calendar-container">
            <div class="calendar">
                <div class="month">
                    <div class="date"><?= date('F Y', strtotime($selectedDate)) ?></div>
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
                <div class="days" id="calendar-days">
                    <!-- JavaScript로 동적으로 날짜 생성 -->
                </div>
            </div>
        </div>

        
        <div class="nutrition-summary">
    <h3><?= htmlspecialchars($selectedDate) ?> 섭취 정보</h3>
    <?php if ($foods): ?>
        <div class="nutrition-table-wrapper">
            <table class="nutrition-table">
                <thead>
                    <tr>
                        <th>음식명</th>
                        <th>칼로리 (kcal)</th>
                        <th>단백질 (g)</th>
                        <th>지방 (g)</th>
                        <th>탄수화물 (g)</th>
                        <th>당 (g)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($foods as $food): ?>
                        <tr>
                            <td><?= htmlspecialchars($food['foodName']) ?></td>
                            <td><?= htmlspecialchars($food['kcal']) ?></td>
                            <td><?= htmlspecialchars($food['protein']) ?></td>
                            <td><?= htmlspecialchars($food['fat']) ?></td>
                            <td><?= htmlspecialchars($food['carbohydrate']) ?></td>
                            <td><?= htmlspecialchars($food['sugar']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($total): ?>
            <div class="nutrition-totals">
                <p><strong>총 섭취량</strong></p>
                <ul>
                    <li>칼로리: <?= htmlspecialchars($total['totalKcal']) ?> kcal</li>
                    <li>단백질: <?= htmlspecialchars($total['totalProtein']) ?> g</li>
                    <li>지방: <?= htmlspecialchars($total['totalFat']) ?> g</li>
                    <li>탄수화물: <?= htmlspecialchars($total['totalCarbohydrate']) ?> g</li>
                    <li>당: <?= htmlspecialchars($total['totalSugar']) ?> g</li>
                </ul>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <p class="no-data">선택된 날짜에 섭취한 음식이 없습니다.</p>
    <?php endif; ?>
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
                    <svg class="down-svg" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" width="24" height="24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3" />
                    </svg>
                    <input type="time" class="edit-event-time-to" />
                    <button class="save-event-btn">저장</button>
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
            <a href="todo.php">
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
</div>
        </footer>

        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const selectedDate = new Date("<?= $selectedDate ?>");
                const daysContainer = document.getElementById("calendar-days");

                const year = selectedDate.getFullYear();
                const month = selectedDate.getMonth();
                const firstDay = new Date(year, month, 1);
                const lastDay = new Date(year, month + 1, 0);
                const startDay = firstDay.getDay();
                const daysInMonth = lastDay.getDate();

                let daysHTML = "";

                for (let i = 0; i < startDay; i++) {
                    daysHTML += `<div class="day empty"></div>`;
                }

                for (let day = 1; day <= daysInMonth; day++) {
                    const isSelected =
                        day === selectedDate.getDate() &&
                        month === selectedDate.getMonth() &&
                        year === selectedDate.getFullYear();

                    daysHTML += `
                        <div class="day ${isSelected ? "selected" : ""}" onclick="selectDate('${year}-${String(
                        month + 1
                    ).padStart(2, "0")}-${String(day).padStart(2, "0")}')">
                            ${day}
                        </div>`;
                }

                daysContainer.innerHTML = daysHTML;
            });

            function selectDate(date) {
                window.location.href = `todo.php?date=${date}`;
            }
        </script>
    </div>
    <script src="../static/js/calender.js"></script>
    <script src="../static/js/edit.js"></script>
    
</body>

</html>
