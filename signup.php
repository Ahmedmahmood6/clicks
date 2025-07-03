<?php
session_start();
include 'conn.php';
?>
<?php
    if($_SERVER['REQUEST_METHOD'] =='POST') {
        $username = $_POST['username'];
        $password =  password_hash($_POST['password'], PASSWORD_DEFAULT);        
        $check = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'") ;
        if(mysqli_num_rows($check) > 0){
            echo "<h2>إسم المستخدم موجود بالفعل</h2>";
        }else{
            $stmt = $conn->prepare("INSERT INTO users (username , password) values (?, ?)"); 
            $stmt->bind_param("ss" , $username, $password);
            if($stmt->execute()){
                header("Location: login.php");
            }else{
                echo "<h2>حدث خطأ أثناء إنشاء الحساب</h2>";
            }
            $stmt->close();
        }
    }
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sign up page</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
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
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            text-align: right;
            border-top: 5px solid #4e54c8;
            transform: perspective(1000px) rotateY(-5deg);
            transition: transform 0.5s, box-shadow 0.5s;
        }
        
        form:hover {
            transform: perspective(1000px) rotateY(0deg);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        
        h2 {
            color: #4e54c8;
            margin-top: 0;
            margin-bottom: 30px;
            text-align: center;
            font-size: 28px;
            position: relative;
            padding-bottom: 15px;
        }
        
        h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 50%;
            transform: translateX(50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(to right, #4e54c8, #8f94fb);
            border-radius: 3px;
        }
        
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #4e54c8;
        }
        
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 14px 20px;
            margin-bottom: 25px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
            text-align: right;
            background-color: #f8f9fa;
        }
        
        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #8f94fb;
            background-color: white;
            box-shadow: 0 0 0 4px rgba(143, 148, 251, 0.2);
            outline: none;
        }
        
        input[type="submit"] {
            background: linear-gradient(to right, #4e54c8, #8f94fb);
            color: white;
            border: none;
            padding: 15px;
            width: 100%;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 15px;
            letter-spacing: 1px;
        }
        
        input[type="submit"]:hover {
            background: linear-gradient(to right, #4348a8, #7a7fd3);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(78, 84, 200, 0.4);
        }
        
        .login-link {
            display: block;
            margin-top: 25px;
            color: #666;
            text-align: center;
            font-size: 15px;
        }
        
        .login-link a {
            color: #4e54c8;
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 500px) {
            form {
                padding: 30px 20px;
                margin: 15px;
                transform: none;
            }
            
            h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
        <h2>إنشاء حساب</h2>
        <label for="">username</label>
        <input type="text" name="username" id="username" required> <br>
        <label for="">password</label>
        <input type="password" name="password" id="password" required> <br>
        <input type="submit" value="Sign Up" name="signup">
    </form>
</body>
</html>
