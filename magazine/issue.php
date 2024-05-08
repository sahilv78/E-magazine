<?php
// Function to retrieve articles for a specific issue number
function getArticlesForIssue($db, $issue_number) {
    try {
        $stmt = $db->prepare("SELECT * FROM articles WHERE issue_number = ?");
        $stmt->execute([$issue_number]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        echo "Database error: " . $e->getMessage();
        exit();
    }
}

// Check if the articles database exists
if (!file_exists("database/articles.db")) {
    echo "Articles database does not exist.";
    exit();
}

// Open the articles database
try {
    $db = new PDO('sqlite:database/articles.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit();
}

// Fetch the latest issue number
try {
    $stmt = $db->query("SELECT DISTINCT issue_number FROM articles ORDER BY issue_number DESC LIMIT 1");
    $latest_issue = $stmt->fetch(PDO::FETCH_ASSOC);
    $latest_issue_number = $latest_issue['issue_number'];
} catch(PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit();
}

// Check if form is submitted to select an issue number
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['issue_number'])) {
    $issue_number = $_POST['issue_number'];
} else {
    $issue_number = $latest_issue_number;
}

// Fetch articles for the selected issue number
$articles = getArticlesForIssue($db, $issue_number);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issue <?php echo $issue_number; ?></title>
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
            text-align: center;
            position: fixed;
            top: 0;
            width: 100%;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .container {
            max-width: 800px;
            margin: 70px auto 20px; /* Adjusted margin to accommodate fixed header */
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
            text-align: center;
        }

        .container h3 {
            margin-bottom: 10px;
            font-size: 20px;
            color: #333;
        }

        .container p {
            text-align: center;
            margin-bottom: 20px;
        }

        .container form {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .container form label {
            margin-right: 10px;
            font-size: 16px;
            color: #333;
        }

        .container form select {
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: #f8f8f8;
            color: #333;
            font-size: 16px;
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
        }

        .container form button[type="submit"]:hover {
            background-color: #45a049;
        }

        .container ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .container li {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
        }

        .container li strong {
            font-weight: bold;
        }

        .footer {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>E-Magazine</h1>
    </div>

    <div class="container">
        <h2>Issue <?php echo $issue_number; ?></h2>

        <form action="" method="POST">
            <label for="issue_number">Select Issue Number:</label>
            <select name="issue_number" id="issue_number">
                <?php for ($i = $latest_issue_number; $i >= 1; $i--) : ?>
                    <option value="<?php echo $i; ?>" <?php if ($i == $issue_number) echo "selected"; ?>>Issue <?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
            <button type="submit">View Issue</button>
        </form>

        <?php if (!empty($articles)) : ?>
            <h3>Articles</h3>
            <ul>
                <?php foreach ($articles as $article) : ?>
                    <li>
                        <strong>Article Name:</strong> <?php echo $article['article_name']; ?><br>
                        <strong>Author:</strong> <?php echo $article['author_name']; ?><br>
                        <strong>Content:</strong> <?php echo $article['article_content']; ?><br>
                        <!-- Add more article details as needed -->
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <p>No articles available for this issue.</p>
        <?php endif; ?>
    </div>

    <div class="footer">
        <p>&copy; 2024 Abhas Kumar Sinha</p>
    </div>
</body>
</html>


