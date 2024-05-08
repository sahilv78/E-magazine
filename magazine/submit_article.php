<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $article_name = $_POST['article_name'];
    $author_name = $_POST['author_name'];
    $roll_number = $_POST['roll_number'];
    $article_content = $_POST['article_content'];
    $article_date = date("Y-m-d");

    // Check if an image file is uploaded
    if(isset($_FILES['article_image']) && $_FILES['article_image']['error'] === UPLOAD_ERR_OK) {
        $image_name = $_FILES['article_image']['name'];
        $image_tmp_name = $_FILES['article_image']['tmp_name'];
        $image_data = file_get_contents($image_tmp_name);
    } else {
        $image_name = null;
        $image_data = null;
    }

    // Filter allowed HTML tags
    $article_content = strip_tags($article_content, '<b><i><u><h1><h2><h3><h4><h5><h6><img><br>');

    try {
        // Connect to the database
        $db = new PDO('sqlite:database/unreviewed_articles.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create the table if it doesn't exist
        $db->exec("CREATE TABLE IF NOT EXISTS articles (
                    id INTEGER PRIMARY KEY,
                    article_name TEXT,
                    author_name TEXT,
                    roll_number TEXT,
                    article_content TEXT,
                    article_image_name TEXT,
                    article_image_data BLOB,
                    article_date TEXT
                )");

        // Insert the article into the database
        $stmt = $db->prepare("INSERT INTO articles (article_name, author_name, roll_number, article_content, article_image_name, article_image_data, article_date)
                              VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$article_name, $author_name, $roll_number, $article_content, $image_name, $image_data, $article_date]);

        $success_message = "Article submitted successfully!";
    } catch(PDOException $e) {
        $error_message = "Database error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Article Submission</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .header {
            background-color: #333;
            color: #fff;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .header a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
            margin-right: 20px;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .container form {
            display: flex;
            flex-direction: column;
        }

        .container form label {
            margin-bottom: 10px;
            font-size: 16px;
            color: #666;
        }

        .container form input[type="text"],
        .container form textarea,
        .container form input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: #f8f8f8;
            color: #333;
            box-sizing: border-box; /* Added */
        }

        .container form textarea {
            min-height: 150px; /* Increased height */
        }

        .container form button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 16px;
            width: 150px;
            align-self: flex-end;
        }

        .container form button[type="submit"]:hover {
            background-color: #45a049;
        }

        .footer {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        .footer p {
            margin: 0;
        }

        .html-tags-info {
            background-color: #f8f8f8;
            border-radius: 5px;
            padding: 10px;
            margin-top: 10px; /* Moved below the label */
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Article Submission</h1>
        <a href="issue.php"><i class="fas fa-book-open"></i> View Issues</a>
    </div>

    <div class="container">
        <h2>Submit Your Article</h2>
        <?php if(isset($error_message)) { ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php } ?>
        <?php if(isset($success_message)) { ?>
            <p style="color: green;"><?php echo $success_message; ?></p>
        <?php } ?>
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="article_name">Article Name:</label>
            <input type="text" id="article_name" name="article_name" required>
            <label for="author_name">Author Name:</label>
            <input type="text" id="author_name" name="author_name" required>
            <label for="roll_number">Roll Number:</label>
            <input type="text" id="roll_number" name="roll_number" required>
            <div class="html-tags-info">
    <p><strong>Note:</strong> You can format your article using simple styling tags. For example:</p>
    <ul>
        <li><code>&lt;b&gt;Bold text&lt;/b&gt;</code> - Makes text bold</li>
        <li><code>&lt;i&gt;Italic text&lt;/i&gt;</code> - Makes text italic</li>
        <li><code>&lt;u&gt;Underlined text&lt;/u&gt;</code> - Underlines text</li>
        <li><code>&lt;h1&gt;Heading 1&lt;/h1&gt;</code> to <code>&lt;h6&gt;Heading 6&lt;/h6&gt;</code> - Defines different levels of headings</li>
        <li><code>&lt;img src="image.jpg" alt="Image"&gt;</code> - Inserts an image (replace "image.jpg" with the image URL and "Image" with the alt text)</li>
        <li><code>&lt;br&gt;</code> - Inserts a line break</li>
    </ul>
    <p>Feel free to use these tags to enhance the appearance of your article!</p>
</div>

            <label for="article_content">Article Content:</label>
            <textarea id="article_content" name="article_content" rows="6" required></textarea>
            <label for="article_image">Article Image:</label>
            <input type="file" id="article_image" name="article_image">
            <button type="submit">Submit Article</button>
        </form>
    </div>

    <div class="footer">
        <p>&copy; 2024 Abhas Kumar Sinha</p>
    </div>
</body>
</html>
