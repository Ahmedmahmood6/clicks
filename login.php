<?php 
session_start();
include 'conn.php';
?>
<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOG IN</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #333;
        }
        
        form {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: right;
        }
        
        h2 {
            color: #2c3e50;
            margin-top: 0;
            margin-bottom: 25px;
            text-align: center;
            font-size: 28px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
        }
        
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s;
            text-align: right;
        }
        
        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }
        
        input[type="submit"] {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 12px 20px;
            width: 100%;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
        }
        
        input[type="submit"]:hover {
            background-color: #2980b9;
        }
        
        a {
            display: inline-block;
            margin-top: 15px;
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
            text-align: center;
            width: 100%;
        }
        
        a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 480px) {
            form {
                padding: 20px;
                margin: 15px;
            }
            
            h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <form action="<?php $_SERVER['PHP_SELF']?>" method="POST">
        <h2>تسجيل دخول</h2>
        <label for="">اسم المستخدم</label>
        <input type="text" name="username" id="username" required> <br>
        <label for="">كلمة المرور</label>
        <input type="password" name="password" id="password" required> <br>
        <input type="submit" value="تسجيل الدخول" name="login"> <br>
        <a href="signup.php">انشاء حساب جديد</a>
    </form>
</body>
</html>
<?php
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $stmt= $conn->prepare("SELECT * FROM users WHERE username= ?");
    $stmt->bind_param("s" , $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc() ;

    if($user && password_verify($password , $user['password'])) {
        $_SESSION['user_id'] = $user['id'] ;
        $_SESSION['username'] = $user['username'];
        header("Location: home.php");
        exit();
    }else{
        echo "اسم المستخدم أو كلمة المرور غير صحيحة";
    }
    $stmt->close();

}


?>