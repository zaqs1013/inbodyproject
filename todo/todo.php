<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do</title>
    <?php date_default_timezone_set('Asia/Seoul'); ?> <!--서버 시간을 한국시간으로 설정-->
    <link rel="stylesheet" href="../static/css/todoStyle.css" />
    <link rel="stylesheet" href="../static/css/todo_nutrition-summary.css" />
</head>

<body>
    <?php
    session_start();
    include '../static/php/db.php';

    if (!isset($_SESSION['ID'])) {
        echo "잘못된 접근<br>";
        exit;
    }

    $userId = $_SESSION['ID'];
    $selectedDate = $_GET['date'] ?? date('Y-m-d'); // 기본값: 오늘 날짜

    // 섭취한 음식 정보 조회
    $stmt = $conn->prepare("
        SELECT nd.foodName, nd.kcal, nd.protein, nd.fat, nd.carbohydrate, nd.sugar 
        FROM nutrition_summary ns
        JOIN nutrition_details nd ON ns.id = nd.summaryId
        WHERE ns.userId = ? AND ns.date = ?
    ");
    $stmt->execute([$userId, $selectedDate]);
    $foods = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 선택된 날짜의 섭취 총합
    $stmtTotal = $conn->prepare("
        SELECT totalKcal, totalProtein, totalFat, totalCarbohydrate, totalSugar
        FROM nutrition_summary
        WHERE userId = ? AND date = ?
    ");
    $stmtTotal->execute([$userId, $selectedDate]);
    $total = $stmtTotal->fetch(PDO::FETCH_ASSOC);
    ?>

    <!-- PHP에서 선택된 날짜를 JS 변수로 넘김 -->
    <script>
        const serverSelectedDate = "<?= $selectedDate ?>";
    </script>

    <div class="container">
        <div class="wrapper">
            <header>fi-to</header>

            <!-- 달력 영역 -->
            <div class="calendar-container">
                <div class="calendar">
                    <div class="month">
                        <button class="prev">&lt;</button>
                        <div class="date"></div>
                        <button class="next">&gt;</button>
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
                    <div class="days" id="calendar-days"></div>
                </div>
            </div>

            <!-- 섭취 정보 영역 -->
            <div class="nutrition-summary">
                <h3><?= htmlspecialchars($selectedDate) ?> 섭취 정보</h3>
                <?php if ($foods): ?>
                    <table>
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

                    <?php if ($total): ?>
                        <div class="nutrition-totals">
                            <p><strong>총 섭취량</strong></p>
                            <p>칼로리: <?= htmlspecialchars($total['totalKcal']) ?> kcal</p>
                            <p>단백질: <?= htmlspecialchars($total['totalProtein']) ?> g</p>
                            <p>지방: <?= htmlspecialchars($total['totalFat']) ?> g</p>
                            <p>탄수화물: <?= htmlspecialchars($total['totalCarbohydrate']) ?> g</p>
                            <p>당: <?= htmlspecialchars($total['totalSugar']) ?> g</p>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <p>선택된 날짜에 섭취한 음식이 없습니다.</p>
                <?php endif; ?>
            </div>

            <!-- 일정(event) 영역 -->
            <div class="event-container">
                <h3>오늘의 일정</h3>
                <div class="event-list" id="event-list">
                    <!-- JS에서 일정이 동적으로 들어감 -->
                </div>
                <button class="edit-event-btn">일정 추가</button>
                <div class="edit-event-wrapper">
                    <input type="text" class="edit-event-title" placeholder="일정 내용" />
                    <input type="time" class="edit-event-time-from" />
                    <svg class="down-svg" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3"/>
                    </svg>
                    <input type="time" class="edit-event-time-to" />
                    <button class="save-event-btn">저장</button>
                </div>
            </div>

            <!-- 하단 메뉴 -->
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
            <a href="./todo.php">
            <img src="../mainpage/image/column.png" alt="일정관리 아이콘" width="30" height="30">
                일정관리
            </a>
            <a href="../mypage/mypage.php">
                <img src="../mainpage/image/body_icon.png" alt="마이페이지 아이콘" width="30" height="30">
                마이페이지
            </a>
            </footer>
        </div>
    </div>

    <!-- 달력 & 이벤트 관련 JS -->
    <script src="../static/js/calender.js"></script>
    <script src="../static/js/edit.js"></script>
</body>
</html>
