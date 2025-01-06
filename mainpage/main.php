<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>fi-to 메인페이지</title>
    <link rel="stylesheet" href="../static/css/mainPageStyle.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
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

    if (!empty($ID)) {
        $dbcon = mysqli_connect('localhost', 'root', '');
        mysqli_select_db($dbcon, 'FiTo');

        $searchUserBodyInfoQuery = "SELECT * FROM userBodyInfo WHERE userId = '$ID' ORDER BY date DESC LIMIT 1";
        $searchUserBodyInfo = mysqli_query($dbcon, $searchUserBodyInfoQuery);
        $UserBodyInfo = mysqli_fetch_array($searchUserBodyInfo);

        // 데이터 유무 확인
        if ($UserBodyInfo) {
            $bmi = round(($UserBodyInfo['weight'] / ($UserBodyInfo['height'] * 0.01) ** 2), 1);
        } else {
            $UserBodyInfo['height'] = '미측정';
            $UserBodyInfo['weight'] = '미측정';
            $UserBodyInfo['fatPercentage'] = '미측정';
            $bmi = '미측정';
        }

        // 그래프 데이터 가져오기
        $query = "SELECT * FROM userBodyInfo WHERE userId = '$ID' ORDER BY date ASC";
        $result = mysqli_query($dbcon, $query);

        $dataPoints = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $date = $row['date'] ?? '미측정';
            $height = $row['height'] ?? 0;
            $weight = $row['weight'] ?? 0;
            $bmiGraph = ($height > 0) ? round(($weight / (($height * 0.01) ** 2)), 1) : 0;

            $dataPoints[] = [
                'date' => $date,
                'height' => $height,
                'weight' => $weight,
                'bmi' => $bmiGraph,
            ];
        }
        mysqli_close($dbcon);
    }
    ?>

    <div class="wrapper">
        <header>fi-to</header>

        <div class="summary">
            <h2>인바디 정보</h2>
            <div class="info">
                <div class="card">
                    <p>키</p>
                    <strong id="displayHeight"><?php
                    if ($UserBodyInfo['height'] === '미측정') {
                        echo $UserBodyInfo['height']; // 단위 출력 없음
                    } else {
                        echo $UserBodyInfo['height'] . ' Cm'; // cm 단위 포함
                    }
                    ?></strong>
                    <svg class="edit-icon" onclick="editData('height')" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536M4 16v4h4l10-10-4-4L4 16z" />
                    </svg>
                </div>
                <div class="card">
                    <p>몸무게</p>
                    <strong id="displayWeight"><?php
                    if ($UserBodyInfo['weight'] === '미측정') {
                        echo $UserBodyInfo['weight']; // 단위 출력 없음
                    } else {
                        echo $UserBodyInfo['weight'] . ' Kg'; // Kg 단위 포함
                    }
                    ?></strong>
                    <svg class="edit-icon" onclick="editData('weight')" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536M4 16v4h4l10-10-4-4L4 16z" />
                    </svg>
                </div>
                <div class="card">
                    <p>BMI</p>
                    <strong id="bmiResult"><?php echo $bmi; ?></strong>
                </div>
                <div class="card">
                    <p>체지방률</p>
                    <strong id="displayBodyFat"> <?php
                    if ($UserBodyInfo['fatPercentage'] === '미측정') {
                        echo $UserBodyInfo['fatPercentage']; // 단위 출력 없음
                    } else {
                        echo $UserBodyInfo['fatPercentage'] . ' %'; // % 단위 포함
                    }
                    ?></strong>
                    <svg class="edit-icon" onclick="editData('bodyFat')" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536M4 16v4h4l10-10-4-4L4 16z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- 그래프 추가 -->
        <div class="graph-widget">
            <h3>사용자 데이터 그래프</h3>
            <canvas id="userDataChart" width="400" height="200"></canvas>
        </div>

        <script>
            const dataPoints = <?php echo json_encode($dataPoints); ?>;

            const labels = dataPoints.map(point => point.date || '미측정');
            const weights = dataPoints.map(point => point.weight);
            const heights = dataPoints.map(point => point.height);
            const bmi = dataPoints.map(point => point.bmi);

            const ctx = document.getElementById('userDataChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: '몸무게 (Kg)',
                            data: weights,
                            borderColor: 'blue',
                            borderWidth: 2,
                            fill: false,
                        },
                        {
                            label: '키 (Cm)',
                            data: heights,
                            borderColor: 'green',
                            borderWidth: 2,
                            fill: false,
                        },
                        {
                            label: 'BMI',
                            data: bmi,
                            borderColor: 'red',
                            borderWidth: 2,
                            fill: false,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: '날짜'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: '값'
                            },
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>

        <div class="todo-widget">
            <h3>오늘의 할 일</h3>
            <p><?php
            $dbcon = mysqli_connect('localhost', 'root', '');
            mysqli_select_db($dbcon, 'FiTo');
            $todayYear = date("Y") ?? null;
            $todayMonth = date("m") ?? null;
            $todayDay = date("d") ?? null;

            $searchEventQuery = "SELECT * FROM events WHERE user_id = '$ID' and year='$todayYear' and month ='$todayMonth'and day='$todayDay' ";
            $searchEvent = mysqli_query($dbcon, $searchEventQuery);
            if (mysqli_num_rows($searchEvent) === 0) {
                echo "오늘의 할 일이 없습니다.";
            } else {
                while ($Event = mysqli_fetch_array($searchEvent)) {
                    echo "시작 시간: " . htmlspecialchars($Event['time_from']);
                    echo " : " . htmlspecialchars($Event['title']) . "<br>";
                }
            }
            mysqli_close($dbcon);
            ?></p>
        </div>
      

        <footer>
            <a href="./main.php">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24"
                    height="24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 10l9-7 9 7v11a1 1 0 01-1 1H4a1 1 0 01-1-1V10z" />
                </svg>
                홈
            </a>
            <a href="../record/recordBody.php">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24"
                    height="24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-3.314 0-6 2.686-6 6 0 1.657.676 3.157 1.757 4.243A5.978 5.978 0 0012 20c1.657 0 3.157-.676 4.243-1.757A5.978 5.978 0 0018 14c0-3.314-2.686-6-6-6zm0 8a2 2 0 100-4 2 2 0 000 4z" />
                </svg>
                인바디정보
            </a>
            <a href="../todo/todo.php">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24"
                    height="24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m0 4h.01M8 12v8m0-8h.01M16 7V3m0 4h.01M16 12v8m0-8h.01" />
                </svg>
                투두리스트
            </a>
            <a href="#">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24"
                    height="24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
                마이페이지
            </a>
        </footer>
    </div>
</body>

</html>
