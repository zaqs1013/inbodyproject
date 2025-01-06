<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>recoredBod</title>
    <link rel="stylesheet" href="https://unpkg.com/mvp.css" />
  <link rel="stylesheet" href="../static/css/center.css" />

</head>

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
?>


<form action="./recordBodyOk.php" method="post" id="from" autocomplete="off">
    <h1>정보 입력</h1>
    <div id="height">
      <label for="ID">키</label>
      <input type="number" id="height" name="height" value=""  step="0.1" required title="신장을 입력해 주세요." />
    </div>
    <div id="weight">
      <label for="PW">체중</label>
      <input type="number" id="weight" name="weight" value="" step="0.1" required title="체중을 입력해 주세요." />
    </div>
    <div id="fatpercentage">
      <label for="fatpercentage">체지방률</label>
      <input type="number" id="fatpercentage" name="fatpercentage" value="" step="0.1" required title="체지방률을 입력해 주세요." />
    </div>

    <button id="submit" type="submit">입력</button>

  </form>
</body>

</html>