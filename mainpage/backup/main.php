<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FITOMAINPAGE</title>
    <link rel="stylesheet" href="../static/css/mainPageStyle.css" />
    <link rel="stylesheet" href="../static/css/ads.css" />
    <link rel="stylesheet" href="../static/css/mainpage.css"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../static/js/ads.js"></script>
</head>

<body>
    <?php
    include '../static/dbconfig.php';
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
        $dbcon = mysqli_connect($host, $user, $password);
        mysqli_select_db($dbcon, $database);

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
        <header>FITO</header>

        <div class="summary">
            <h2 id="h2">My Inbody Report</h2>
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
            <h2>User Data Graph</h2>
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
            <h3>TODAY TODO LIST</h3>
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
            ?></p><br>
            <!--광고영역-->
            <div class="ad-slider">
                <div class="ad-slider-wrapper">
                    <div class="ad-slide">
                    <img src="001.jpg" alt="Ad 1">
                    </div>
                    <div class="ad-slide">
                    <img src="002.jpg" alt="Ad 2">
                    </div>
                    <div class="ad-slide">
                    <img src="003.png" alt="Ad 3">
                    </div>
                </div>
            </div>
            <button class="ad-slider-btn prev">◀</button>
            <button class="ad-slider-btn next">▶</button>
        </div>

        <footer>
            <div class="home">
                <a href="./main.php">
                    <img src="image/home_icon.png" alt="홈 아이콘" width="28" height="28">
                    HOME
                </a>
            </div>
            <a href="../record/recordBody.php">
            <img src="image/body_icon.png" alt="홈 아이콘" width="30" height="30">
                INBODY
            </a>
            <a href="../todo/todo.php">
            <img src="image/column.png" alt="홈 아이콘" width="30" height="30">
                TODOLIST
            </a>
            <a href="#">
                <img src="image/notice.png" alt="홈 아이콘" width="30" height="30">
                MYPAGE
            </a>
        </footer>
    </div>
</body>

</html>
