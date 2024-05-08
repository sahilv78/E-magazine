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
    header("Location: admin.php");
    exit();
}

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit();
}

// Function to create or recreate the SQLite database
function createOrRecreateDatabase() {
    try {
        $db = new PDO('sqlite:database/users.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Drop the table if it exists
        $db->exec("DROP TABLE IF EXISTS users");

        // Create the table
        $db->exec("CREATE TABLE users (
            id INTEGER PRIMARY KEY,
            name TEXT,
            username TEXT,
            password TEXT,
            designation TEXT,
            email TEXT,
            keywords TEXT
        )");

		// Insert some sample data
		$stmt = $db->prepare("INSERT INTO users (name, username, password, designation, email, keywords) VALUES (?, ?, ?, ?, ?, ?)");
		$stmt->execute(["John Doe", "johndoe", "password123", "Admin", "johndoe@example.com", "admin,moderator"]);
		$stmt->execute(["Jane Smith", "janesmith", "secret456", "Moderator", "janesmith@example.com", "moderator,reviewer"]);

        return $db;
    } catch(PDOException $e) {
        echo "Database error: " . $e->getMessage();
        exit();
    }
}

// Function to get all users from the database
function getAllUsers($db) {
    $stmt = $db->query("SELECT name, username, designation, email, keywords FROM users");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to add a new user to the database
function addUser($db, $name, $username, $password, $designation, $email, $keywords) {
    $stmt = $db->prepare("INSERT INTO users (name, username, password, designation, email, keywords) VALUES (?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$name, $username, $password, $designation, $email, $keywords]);
}

// Function to delete a user from the database
function deleteUser($db, $username) {
    $stmt = $db->prepare("DELETE FROM users WHERE username = ?");
    return $stmt->execute([$username]);
}

// Check if the database exists, if not, create it
if (!file_exists("database/users.db")) {
    $db = createOrRecreateDatabase();
} else {
    // If the database exists, open it
    try {
        $db = new PDO('sqlite:database/users.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        echo "Database error: " . $e->getMessage();
        exit();
    }
}

// Check if form is submitted to add a new user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $designation = $_POST['designation'];
    $email = $_POST['email'];
    $keywords = $_POST['keywords'];

    if (addUser($db, $name, $username, $password, $designation, $_POST['email'], $_POST['keywords'])) {
		echo "<p>User added successfully!</p>";
	} else {
		echo "<p>Error adding user.</p>";
	}
}

// Check if form is submitted to delete a user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user'])) {
    $username = $_POST['username'];

    if (deleteUser($db, $username)) {
        echo "<p>User deleted successfully!</p>";
    } else {
        echo "<p>Error deleting user.</p>";
    }
}

// Get all users from the database
$users = getAllUsers($db);
?>

<!-- <form action="" method="post"><button type="submit" name="logout">Logout</button></form> -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
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
            text-align: right;
        }

        .header button {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin-left: 20px;
            cursor: pointer;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .panel {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            flex: 1;
            min-width: 300px;
        }

        .panel h3 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        .user-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .user-list li {
            background-color: #f8f8f8;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .user-list li:last-child {
            margin-bottom: 0;
        }

        .user-list li .user-info {
            flex: 1;
        }

        .user-list li h4 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .user-list li p {
            margin: 0;
        }

        .user-list li .delete-btn {
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 8px 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 14px;
        }

        .user-list li .delete-btn:hover {
            background-color: #c0392b;
        }

        .footer {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
            width: 100%;
            position: fixed;
            bottom: 0;
        }
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
            text-align: right;
        }

        .header button {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin-left: 20px;
            cursor: pointer;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .panel {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            flex: 1;
            margin: 0 10px 20px;
        }

        .panel h3 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        .panel form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .panel form label {
            margin-bottom: 10px;
            font-size: 16px;
            color: #666;
        }

        .panel form input[type="text"],
        .panel form input[type="password"] {
            width: calc(100% - 40px);
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: #f8f8f8;
            color: #333;
        }

        .panel form button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 16px;
            width: 100%;
        }

        .panel form button[type="submit"]:hover {
            background-color: #45a049;
        }

        .user-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }

        .user-card {
            background-color: #f8f8f8;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: calc(33.333% - 20px);
            color: #333;
        }

        .user-card h4 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .user-card p {
            margin: 0;
        }

        .footer {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
            width: 100%;
            position: fixed;
            bottom: 0;
        }
		.panel form input[type="email"] {
			width: calc(100% - 40px);
			padding: 10px;
			margin-bottom: 20px;
			border-radius: 5px;
			border: 1px solid #ccc;
			background-color: #f8f8f8;
			color: #333;
		}
    </style>
</head>
<body>
    <div class="header">
        <form action="" method="post"><button type="submit" name="logout">Logout</button></form>
    </div>

    <div class="container">
        <div class="panel">
            <h3>Available Users</h3>
            <ul class="user-list">
				<?php foreach ($users as $user): ?>
				<li>
					<div class="user-info">
						<h4><?php echo $user['name']; ?></h4>
						<p><strong>Username:</strong> <?php echo $user['username']; ?></p>
						<p><strong>Designation:</strong> <?php echo $user['designation']; ?></p>
						<p><strong>Email:</strong> <?php echo $user['email']; ?></p>
						<p><strong>Keywords:</strong> <?php echo $user['keywords']; ?></p>
					</div>
				</li>
				<?php endforeach; ?>
			</ul>
        </div>

        <div class="panel">
            <h3>Add New User</h3>
            <form action="" method="post">
				<label for="name">Name:</label>
				<input type="text" id="name" name="name" required>
				<label for="username">Username:</label>
				<input type="text" id="username" name="username" required>
				<label for="password">Password:</label>
				<input type="password" id="password" name="password" required>
				<label for="designation">Designation:</label>
				<input type="text" id="designation" name="designation" required>
				<label for="email">Email:</label>
				<input type="email" id="email" name="email" required>
				<label for="keywords">Keywords (comma-separated):</label>
				<input type="text" id="keywords" name="keywords" required>
				<button type="submit" name="add_user">Add User</button>
			</form>
        </div>

        <div class="panel">
            <h3>Delete User</h3>
            <form action="" method="post">
                <label for="username_delete">Username:</label>
                <input type="text" id="username_delete" name="username" required>
                <button type="submit" name="delete_user">Delete User</button>
            </form>
        </div>
    </div>

    <div class="footer">
        &copy; 2024 Abhas Kumar Sinha
    </div>
</body>
</html>
