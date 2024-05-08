<?php
session_start();
// Check if the logout button is clicked
if(isset($_POST['logout'])) {
    // Unset all session variables
    $_SESSION = [];

    // Destroy the session cookie
    if(isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-3600, '/');
    }

    // Destroy the session
    session_destroy();

    // Redirect to the login page or any other desired page
    header("Location: reviewer_login.php");
    exit();
}

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$name = $_SESSION['name'];

// Function to create or recreate the articles database
function createOrRecreateArticlesDatabase() {
    try {
        $db = new PDO('sqlite:database/articles.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Drop the table if it exists
        $db->exec("DROP TABLE IF EXISTS articles");

        // Create the table
        $db->exec("CREATE TABLE articles (
                    id INTEGER PRIMARY KEY,
                    article_name TEXT,
                    author_name TEXT,
                    roll_number TEXT,
                    article_content TEXT,
                    article_image_name TEXT,
                    article_image_data BLOB,
                    article_date TEXT,
                    reviewer_name TEXT,
                    issue_number INTEGER
                )");

        return $db;
    } catch(PDOException $e) {
        echo "Database error: " . $e->getMessage();
        exit();
    }
}

// Function to accept an article
function acceptArticle($unreviewed_db, $articles_db, $article_id, $reviewer_name, $issue_number) {
    try {
        // Get the article details from unreviewed articles
        $stmt = $unreviewed_db->prepare("SELECT * FROM articles WHERE id = ?");
        $stmt->execute([$article_id]);
        $article = $stmt->fetch(PDO::FETCH_ASSOC);

        // Insert the article into the articles database
        $stmt = $articles_db->prepare("INSERT INTO articles (article_name, author_name, roll_number, article_content, article_image_name, article_image_data, article_date, reviewer_name, issue_number)
                                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$article['article_name'], $article['author_name'], $article['roll_number'], $article['article_content'], $article['article_image_name'], $article['article_image_data'], date("Y-m-d"), $reviewer_name, $issue_number]);

        // Delete the article from unreviewed articles
        $stmt = $unreviewed_db->prepare("DELETE FROM articles WHERE id = ?");
        $stmt->execute([$article_id]);

        return true;
    } catch(PDOException $e) {
        echo "Database error: " . $e->getMessage();
        return false;
    }
}

// Function to reject an article
function rejectArticle($unreviewed_db, $article_id) {
    try {
        // Delete the article from unreviewed articles
        $stmt = $unreviewed_db->prepare("DELETE FROM articles WHERE id = ?");
        $stmt->execute([$article_id]);
        return true;
    } catch(PDOException $e) {
        echo "Database error: " . $e->getMessage();
        return false;
    }
}

// Check if the articles database exists, if not, create it
if (!file_exists("database/articles.db")) {
    $articles_db = createOrRecreateArticlesDatabase();
} else {
    // If the articles database exists, open it
    try {
        $articles_db = new PDO('sqlite:database/articles.db');
        $articles_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        echo "Database error: " . $e->getMessage();
        exit();
    }
}

// Check if form is submitted to accept/reject an article
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $unreviewed_db = new PDO('sqlite:database/unreviewed_articles.db');
    $unreviewed_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $article_id = $_POST['article_id'];
    $reviewer_name = $name;
    $issue_number = $_POST['issue_number'];

    if ($_POST['action'] === "accept") {
        acceptArticle($unreviewed_db, $articles_db, $article_id, $reviewer_name, $issue_number);
    } elseif ($_POST['action'] === "reject") {
        rejectArticle($unreviewed_db, $article_id);
    }
}

// Open the users database
$users_db = openUsersDatabase();
// Fetch user's keywords based on their username
$username = $_SESSION['username'];
try {
    $stmt = $users_db->prepare("SELECT name, keywords FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $name = $user['name'];
    $keywords = $user['keywords'];

    // Split the keywords into an array
    $keywordArray = explode(",", $keywords);
} catch(PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit();
}

// Fetch unreviewed articles from the database that mention any keyword or show all articles if * keyword is mentioned
try {
    $unreviewed_db = new PDO('sqlite:database/unreviewed_articles.db');
    $unreviewed_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare the SQL query with a placeholder for the keyword condition
    $sql = "SELECT * FROM articles WHERE ";
    $conditions = [];
    foreach ($keywordArray as $keyword) {
        if ($keyword == '*') {
            // If * keyword is mentioned, include a condition to select all articles
            $sql = "SELECT * FROM articles";
            $conditions = [];
            break;
        }
        // For each keyword other than *, add a condition to check if the article content contains the keyword (case insensitive)
        $conditions[] = "LOWER(article_content) LIKE ?";
    }
    if (!empty($conditions)) {
        // Combine all conditions with OR operator if * keyword is not mentioned
        $sql .= implode(" OR ", $conditions);
    }

    // Prepare the statement with the SQL query
    $stmt = $unreviewed_db->prepare($sql);

    // Bind the keyword placeholders with actual values (if any)
    if (!empty($conditions)) {
        foreach ($keywordArray as $index => $keyword) {
            if ($keyword != '*') {
                $stmt->bindValue($index + 1, '%' . strtolower($keyword) . '%');
            }
        }
    }

    // Execute the statement
    $stmt->execute();

    // Fetch the filtered articles
    $unreviewed_articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit();
}


// Function to open the users database
function openUsersDatabase() {
    try {
        $db = new PDO('sqlite:database/users.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch(PDOException $e) {
        echo "Database error: " . $e->getMessage();
        exit();
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Page</title>
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
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
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
            text-align: center;
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

        .container form button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 16px;
            margin-left: 10px;
        }

        .container form button[type="submit"]:hover {
            background-color: #45a049;
        }

        .container h3 {
            margin-bottom: 10px;
            font-size: 20px;
            color: #333;
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
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Review Page</h1>
    </div>

    <div class="container">

		<h2>Welcome, <?php echo $name; ?></h2>
		<p>User Keywords:
				<?php foreach ($keywordArray as $keyword) : ?>
					<span style="border: 1px solid #ccc; border-radius: 5px; padding: 5px; margin-right: 5px;"><?php echo $keyword; ?></span>
				<?php endforeach; ?>
		</p>
        <form action="" method="post">
            <button type="submit" name="logout">Logout</button>
        </form>
        <p>This is the review page.</p>

        <h3>Unreviewed Articles</h3>
        <?php if (!empty($unreviewed_articles)) : ?>
            <ul>
                <?php foreach ($unreviewed_articles as $article) : ?>
                    <li>
                        <strong>Article Name:</strong> <?php echo $article['article_name']; ?><br>
                        <strong>Author:</strong> <?php echo $article['author_name']; ?><br>
                        <strong>Content:</strong> <?php echo $article['article_content']; ?><br>
                        <!-- Add more article details as needed -->

                        <form action="" method="POST">
                            <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
                            <label for="issue_number">Issue Number:</label>
                            <input type="number" id="issue_number" name="issue_number" required><br>
                            <button type="submit" name="action" value="accept">Accept</button>
                            <button type="submit" name="action" value="reject">Reject</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <p>No unreviewed articles available.</p>
        <?php endif; ?>
    </div>

    <div class="footer">
        <p>&copy; 2024 Abhas Kumar Sinha</p>
    </div>
</body>
</html>

