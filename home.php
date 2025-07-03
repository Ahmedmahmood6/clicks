<?php
session_start();
include 'conn.php';
?>
<?php
// التحقق من تسجيل الدخول 
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
// التحقق من وجود المستخدم في قاعدة البيانات
$user_id = $_SESSION['user_id'];
// 
$stmt = $conn->prepare("SELECT username , number_of_clicks FROM users WHERE id = ?") ;
$stmt ->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$current_clicks = $user['number_of_clicks']; // عدد الضغطات الحالية
$username = $user['username']; // اسم المستخدم

// استعلام للحصول على بيانات المستخدم
$stmt =$conn->prepare("SELECT  username , number_of_tasks FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$userDate = $result->fetch_assoc() ;
$current_tasks = $userDate['number_of_tasks'] ; // عدد المهام الحالية
$username = $userDate['username'] ; // اسم المستخدم
// تعريف $user_id
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'مستخدم'; // تعريف $username
$click_date = date("Y-m-d");
$stmt = $conn->prepare("SELECT * FROM check_clicks WHERE user_id = ? AND click_date = ?") ;
$stmt->bind_param("is", $user_id, $click_date);
$stmt->execute();
$clicked = $stmt->get_result()->num_rows > 0 ;
//  عدد الضغطات
$stmt = $conn->prepare("SELECT COUNT(*) AS total_clicks FROM check_clicks WHERE user_id = ?"); 
$stmt->bind_param("i" , $user_id, );
$stmt->execute();
$result = $stmt->get_result();
$rows = $result->fetch_assoc();
$total_clicks = $rows['total_clicks'];
// اخر مره
$stmt = $conn->prepare("SELECT MAX(click_date) AS last_click FROM check_clicks WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$rows = $result->fetch_assoc();
$last_click = $rows['last_click'] ;
// 
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_tasks'])){
        $new_tasks = intval($_POST['number_of_tasks']);
        $stmt = $conn->prepare("UPDATE users SET number_of_tasks = ? WHERE id = ?");
        $stmt->bind_param("ii", $new_tasks, $user_id);
        $stmt->execute();
        $stmt->close();
        header("Location: home.php");
        exit();        
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_clicks'])){
    $new_clicks = intval($_POST['number_of_clicks']);
    $stmt = $conn->prepare("UPDATE users SET number_of_clicks = ? WHERE id = ?");
    $stmt->bind_param("ii", $new_clicks, $user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: home.php");
    exit();        
}
// التحقق من الضغط على الزر
if($_SERVER['REQUEST_METHOD'] == 'POST' && !$clicked){
    $stmt = $conn->prepare("INSERT INTO check_clicks (user_id , click_date) VALUES (?, ?)");
    $stmt->bind_param("is" , $user_id, $click_date);
    $stmt->execute();
    $stmt->close();
    header("Location: home.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home</title>
<style>
    body {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
        background: linear-gradient(135deg, #f8fafc 0%, #c9e7fa 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #222;
    }

    h2 {
        margin-bottom: 10px;
        color: #2563eb;
    }

    p {
        margin-bottom: 20px;
        font-size: 1.1em;
    }

    form {
        margin-bottom: 20px;
    }

    button[type="submit"] {
        padding: 12px 32px;
        font-size: 1.1em;
        background: #2563eb;
        color: #fff;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.2s;
    }

    button[type="submit"]:hover:enabled {
        background: #1d4ed8;
    }

    button[type="submit"]:disabled {
        background: #a5b4fc;
        cursor: not-allowed;
    }

    a {
        color: #2563eb;
        text-decoration: none;
        font-weight: bold;
        transition: color 0.2s;
    }

    a:hover {
        color: #1d4ed8;
    }
</style>
</head>
<body>
    <h2> Welcome , <?php echo htmlspecialchars($username); ?></h2>
    <p> DATE, <?php echo $click_date ; ?></p>
    <p>📅 آخر مرة ضغطت فيها: <?php echo $last_click ?: 'لم تضغط من قبل'; ?> </p>
    <p>📌 عدد مرات الضغط 
    <strong style="color: green;"><?php echo $current_clicks; ?></strong>
</p>

<form method="post">
    <label for="number_of_clicks">✏️ تعديل عدد الضفطات :</label><br>
    <input type="number" name="number_of_clicks" id="number_of_clicks" min="0" value="<?php echo $current_clicks; ?>" required>
    <button type="submit" name="update_clicks">تحديث</button>
</form>
<br>
<p>🔢 عدد مرات الضغط: <?php echo $total_clicks; ?></p></form>
<p>📌 عدد المهام اليومية المطلوبة منك: 
    <strong style="color: green;"><?php echo $current_tasks; ?></strong>
</p>

<form method="post">
    <label for="number_of_tasks">✏️ تعديل عدد المهام:</label><br>
    <input type="number" name="number_of_tasks" id="number_of_tasks" min="0" value="<?php echo $current_tasks; ?>" required>
    <button type="submit" name="update_tasks">تحديث</button>
</form>
<br>

    <form action="" method="post">
<button type="submit" name="click" <?php if($clicked) echo 'disabled'; ?>>
    <?php echo $clicked ? 'لقد قمت بالضغط اليوم' : 'اضغط هنا'; ?>
</button>
    </form>
    <?php if($clicked): ?>
    <p id="countdown" style="font-size: 18px; color: #888;"></p>
<?php endif; ?>
    <a href="login.php">تسجيل الخروج</a>
    <?php if($clicked): ?>
<script>
    // الوقت الحالي
    const now = new Date();

    // نهاية اليوم (الساعة 23:59:59)
    const endOfDay = new Date();
    endOfDay.setHours(23, 59, 59, 999);

    // التحديث كل ثانية
    const countdownElement = document.getElementById("countdown");

    function updateCountdown() {
        const now = new Date();
        const diff = endOfDay - now;

        if (diff <= 0) {
            countdownElement.textContent = "يمكنك الضغط الآن.";
            return;
        }

        const hours = Math.floor(diff / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);

        countdownElement.textContent = `⏳ يمكنك الضغط مرة أخرى خلال ${hours} ساعة و ${minutes} دقيقة و ${seconds} ثانية`;
    }

    updateCountdown(); // أول مرة
    setInterval(updateCountdown, 1000); // كل ثانية
</script>
<?php endif; ?>

</body>
</html>