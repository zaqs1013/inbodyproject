<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Search</title>
    <link rel="stylesheet" href="https://unpkg.com/mvp.css" />
    <link rel="stylesheet" href="../static/css/center.css" />


</head>

<body>
    <h1>음식 검색</h1>
    <form method="POST">
        <label for="foodName">음식을 검색해 주세요</label>
        <input type="text" name="foodName" id="foodName" required>
        <button type="submit">검색</button>
    </form>

    <?php
    include '../static/dbconfig.php';
    $dbcon = mysqli_connect($host, $user, $password, $database);
  

    if (!$dbcon) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // 검색기능
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $foodName = $_POST['foodName'];

        // 음식이름찾기 내 대가리로는 검색문은 이게 최선
        $searchFoodNameQuery = "SELECT * FROM foodinfo WHERE foodName LIKE '%$foodName%'";
        $searchFoodName = mysqli_query($dbcon, $searchFoodNameQuery);

        if ($searchFoodName && mysqli_num_rows($searchFoodName) > 0) {
            echo "<h2>Search Results:</h2>";
            while ($FoodName = mysqli_fetch_assoc($searchFoodName)) {

                //음식 정보 json으로 변환
                $foodJson = json_encode($FoodName, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);

                echo "
                    <form method='POST' action='foodTotalAdd.php' style='margin-bottom: 10px;'>
                    <input type='hidden' name='foodData' value='{$foodJson}'>
                    <button type='submit'>{$FoodName['foodName']}</button>
                </form>";
            }
        } else {
            echo "<p>'<strong>$foodName</strong>'에 대한 결과를 찾을 수 없습니다.</p>";
        }
    }

    mysqli_close($dbcon);
    ?>
</body>

</html>