<?php
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if username and password are correct
    if ($_POST['username'] === 'admin' && $_POST['password'] === '2001230012') {
        // Set session variable to indicate user is logged in
        $_SESSION['admin_logged_in'] = true;
        // Redirect to admin panel
        header("Location: admin_panel.php");
        exit();
    } else {
        // Display error message if username or password is incorrect
        $error_message = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        
        .container {
            background-color: #fff;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: #333;
            text-align: center;
            position: relative;
            overflow: hidden;
            width: 300px;
        }
        
        h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }
        
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        label {
            color: #666;
            margin-bottom: 10px;
            font-size: 16px;
            display: block;
        }
        
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: #f8f8f8;
            color: #333;
        }
        
        button[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        button[type="submit"]:hover {
            background-color: #45a049;
        }
        
        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            background-color: #f4f4f4;
            padding: 10px 0;
            color: #666;
            font-size: 14px;
            text-align: center;
        }
        
        .footer span {
            color: #4CAF50;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Login</h2>
        <?php if(isset($error_message)) { ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php } ?>
        <form action="" method="POST">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required><br>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>
            <button type="submit">Login</button>
        </form>
    </div>
    <div class="footer">
        &copy; 2024 Abhas Kumar Sinha
    </div>
</body>
</html>
