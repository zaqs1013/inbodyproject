<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Details</title>
    <link rel="stylesheet" href="https://unpkg.com/mvp.css" />
    <link rel="stylesheet" href="../static/css/center.css" />
</head>

<body>
    <?php
    session_start();

    // 세션 배열 초기화
    if (!isset($_SESSION['nutrition_data'])) {
        $_SESSION['nutrition_data'] = [];
    }

    // 항목 추가
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['foodData'])) {
        $foodData = json_decode($_POST['foodData'], true);

        $_SESSION['nutrition_data'][] = [
            'foodName' => $foodData['foodName'],
            'kcal' => $foodData['kcal'],
            'protein' => $foodData['protein'],
            'fat' => $foodData['fat'],
            'carbohydrate' => $foodData['carbohydrate'],
            'sugar' => $foodData['sugar'],
            'foodWeight' => $foodData['foodWeight'],
        ];

        // PRG 패턴: GET 요청으로 리다이렉트
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // 삭제 처리
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteIndex'])) {
        $deleteIndex = (int) $_POST['deleteIndex'];
        if (isset($_SESSION['nutrition_data'][$deleteIndex])) {
            unset($_SESSION['nutrition_data'][$deleteIndex]);
            $_SESSION['nutrition_data'] = array_values($_SESSION['nutrition_data']); // 배열 인덱스 재정렬
        }
    }
    ?>


    <h1>추가된 목록</h1>
    <a href="./foodSearch.php"><button>추가</button></a>
    <table>
        <thead>
            <tr>
                <th>음식 이름</th>
                <th>칼로리 (kcal)</th>
                <th>단백질 (g)</th>
                <th>지방 (g)</th>
                <th>탄수화물 (g)</th>
                <th>당 (g)</th>
                <th>섭취량</th>
                <th>1회 제공량 (g)</th>
                <th>삭제</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION['nutrition_data'] as $index => $food): ?>
                <tr>
                    <td><?= htmlspecialchars($food['foodName']) ?></td>
                    <td id="kcal-<?= $index ?>"><?= htmlspecialchars($food['kcal']) ?></td>
                    <td id="protein-<?= $index ?>"><?= htmlspecialchars($food['protein']) ?></td>
                    <td id="fat-<?= $index ?>"><?= htmlspecialchars($food['fat']) ?></td>
                    <td id="carbohydrate-<?= $index ?>"><?= htmlspecialchars($food['carbohydrate']) ?></td>
                    <td id="sugar-<?= $index ?>"><?= htmlspecialchars($food['sugar']) ?></td>
                    <td>
                        <input type="number" id="weight-<?= $index ?>" value="100" min="1"
                            oninput="updateNutrition(<?= $index ?>, <?= htmlspecialchars(json_encode($food)) ?>)">

                    </td>
                    <td id="foodWeight-<?= $index ?>"><?= htmlspecialchars($food['foodWeight']) ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="deleteIndex" value="<?= $index ?>">
                            <button type="submit">삭제</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php
    include '../static/dbconfig.php';
    $dbcon = mysqli_connect($host, $user, $password, $database);
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['saveToDb'])) {
        $ID = $_SESSION['ID']; // 사용자 ID
        $date = date('Y-m-d');

        $nutritionData = json_decode($_POST['nutritionData'], true);

        // 총 영양 계산
        $totalKcal = 0;
        $totalProtein = 0;
        $totalFat = 0;
        $totalCarbohydrate = 0;
        $totalSugar = 0;

        for ($i = 0; $i < count($nutritionData); $i++) {
            $totalKcal += $nutritionData[$i]['kcal'];
            $totalProtein += $nutritionData[$i]['protein'];
            $totalFat += $nutritionData[$i]['fat'];
            $totalCarbohydrate += $nutritionData[$i]['carbohydrate'];
            $totalSugar += $nutritionData[$i]['sugar'];
        }
        $nutrition_summaryQuery = " INSERT INTO nutrition_summary VALUES ('','$ID', '$date', '$totalKcal', '$totalProtein', '$totalFat', '$totalCarbohydrate', '$totalSugar')";

        mysqli_query($dbcon, $nutrition_summaryQuery);

        $summaryId = mysqli_insert_id($dbcon);

        foreach ($nutritionData as $food) {
            $foodName = mysqli_real_escape_string($dbcon, $food['foodName']);
            $kcal = $food['kcal'];
            $protein = $food['protein'];
            $fat = $food['fat'];
            $carbohydrate = $food['carbohydrate'];
            $sugar = $food['sugar'];
            $weight = $food['weight'];

            $queryDetails = "
            INSERT INTO nutrition_details (summaryId, foodName, kcal, protein, fat, carbohydrate, sugar, weight)
            VALUES ('$summaryId', '$foodName', '$kcal', '$protein', '$fat', '$carbohydrate', '$sugar', '$weight')
        ";
            mysqli_query($dbcon, $queryDetails);
        }


        // 저장 후 세션 초기화 
        unset($_SESSION['nutrition_data']);
        echo "데이터가 성공적으로 저장되었습니다!";
    }



    ?>
    <form method="POST" id="saveForm">
        <input type="hidden" name="nutritionData" id="nutritionData">
        <button type="submit" name="saveToDb">DB에 저장</button>
    </form>




    <script>
        // 초기 데이터 업데이트
        window.onload = () => {
            <?php foreach ($_SESSION['nutrition_data'] as $index => $food): ?>
                updateNutrition(<?= $index ?>, <?= json_encode($food) ?>);
            <?php endforeach; ?>
        };

        // 사용자의 입력값과 계산된 결과를 저장할 전역 객체
        const updatedNutritionData = {};

        function updateNutrition(index, food) {
            const weightInput = document.getElementById(`weight-${index}`);
            const newWeight = parseFloat(weightInput.value);

            if (isNaN(newWeight) || newWeight <= 0) {
                alert("올바른 섭취량을 입력하세요!");
                return;
            }

            // 비례 계산
            const factor = newWeight / 100;

            // 업데이트할 필드 목록
            const fields = ["kcal", "protein", "fat", "carbohydrate", "sugar"];
            const updatedValues = { foodName: food.foodName, weight: newWeight.toFixed(2) };

            // 데이터 업데이트 및 화면 반영
            fields.forEach(field => {
                const updatedValue = (food[field] * factor).toFixed(2);
                updatedValues[field] = updatedValue; // 업데이트된 데이터 저장
                document.getElementById(`${field}-${index}`).innerText = updatedValue; // 화면 업데이트
            });

            // 업데이트된 값을 객체에 저장
            updatedNutritionData[index] = updatedValues;

            console.log(updatedNutritionData); // 디버깅용, 저장된 데이터 확인
        }

        document.getElementById('saveForm').addEventListener('submit', () => {
            const hiddenInput = document.getElementById('nutritionData');
            hiddenInput.value = JSON.stringify(updatedNutritionData); // JSON 문자열로 변환
        });
    </script>
</body>

</html>