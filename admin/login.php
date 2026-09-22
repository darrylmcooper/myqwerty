<?php
// admin/login.php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = $_POST['username'] ?? '';
    $p = $_POST['password'] ?? '';

    if ($u === 'admin' && $p === '123456') {
        $_SESSION['is_admin'] = true;
        header('Location: index.php');
        exit;
    } else {
        $error = "Invalid credentials";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Admin Login</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            background: #222;
            color: #fff;
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .login-container {
            max-width: 330px;
            margin: 100px auto;
            background: #333;
            padding: 10px 20px 0 20px;
            border-radius: 6px;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 20px;
        }
        .login-container input[type=text],
        .login-container input[type=password] {
            width: 100%;
            height: 30px;
            padding: 8px;
            margin: 8px 0;
            border: 1px solid #555;
            border-radius: 4px;
            background: #444;
            color: #fff;
            outline: none;
        }
        .login-container button {
            width: 100%;
            padding: 10px;
            margin: 20px 0 60px 0;
            border: none;
            background: #555;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
        }
        .login-container button:hover {
            background: #666;
        }
        .error {
            color: red;
            text-align: center;
        }
        
        .mklogo {
            text-align: center;
        }
        
        .mklogo img {
            width: 148px;
        }
    </style>
</head>
<body>
<div class="login-container">
    <div class="mklogo">
        <img src="mkteam.svg" alt="MK TEAM">
    </div>
    <?php if(!empty($error)): ?>
      <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="post">
        <input type="text" name="username" placeholder="User">
        <input type="password" name="password" placeholder="password">
        <button type="submit">Login</button>
    </form>
</div>
</body>
</html>
