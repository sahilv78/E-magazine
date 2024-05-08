<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .header {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            flex: 1; /* Fill remaining vertical space */
        }

        .container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
            text-align: center;
        }

        .container p {
            text-align: center;
            margin-bottom: 10px;
        }

        .container ul {
            list-style: none;
            padding: 0;
            margin: 0;
            text-align: center;
        }

        .container li {
            margin-bottom: 10px;
        }

        .container li a {
            text-decoration: none;
            color: #333;
            font-size: 18px;
            display: block;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }

        .container li a:hover {
            background-color: #333;
            color: #fff;
        }

        .footer {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
            width: 100%;
            flex-shrink: 0; /* Do not shrink */
        }

        .footer p {
            margin: 0;
        }

        .footer a {
            color: #fff;
            text-decoration: none;
            margin: 0 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>E-Magazine</h1>
    </div>

    <div class="container">
        <h2>Welcome to the Homepage</h2>
        <p>Feel free to contact us if you have any questions or check out more about us below:</p>
        <ul>
            <li><a href="admin.php"><i class="fas fa-user-cog"></i> Admin Login</a></li>
            <li><a href="reviewer_login.php"><i class="fas fa-user"></i> Reviewer Login</a></li>
            <li><a href="submit_article.php"><i class="far fa-edit"></i> Submit Article</a></li>
            <li><a href="issue.php"><i class="far fa-newspaper"></i> Browse Issues and Latest Articles</a></li>
        </ul>
        <p><a href="contact_us.php">Contact Us</a> | <a href="about.php">About</a></p>
    </div>

    <div class="footer">
        <p>&copy; 2024 Abhas Kumar Sinha</p>
        <a href="contact_us.php">Contact Us</a>
        <a href="about.php">About</a>
    </div>
</body>
</html>
