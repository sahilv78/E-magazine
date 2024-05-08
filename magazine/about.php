<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About E-Magazine Project</title>
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

        main {
            flex: 1;
        }

        .container {
            max-width: 600px;
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

        footer {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
            width: 100%;
            flex-shrink: 0;
        }

        footer p {
            margin: 0;
        }

        footer a {
            color: #fff;
            text-decoration: none;
            margin: 0 10px;
        }

        /* Card Design for Project Team */
        .card {
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .card h3 {
            margin-bottom: 10px;
            font-size: 20px;
            color: #333;
        }

        .card p {
            margin-bottom: 10px;
            font-size: 16px;
            color: #555;
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>About E-Magazine Project</h1>
    </header>

    <main>
        <div class="container">
            <section>
                <h2>Project Synopsis</h2>
                <p>The "E-Magazine" project is a self-hosted digital platform designed to streamline the process of creating and managing an online magazine within an academic institution...</p>
            </section>

            <section>
                <h2>Key Features</h2>
                <ol>
                    <li>
                        <h3>Self-Hosting and Database Management</h3>
                        <p>The E-Magazine is entirely self-hosted, eliminating the dependence on external support for databases or servers...</p>
                    </li>
                    <li>
                        <h3>Admin Panel for Magazine Editors</h3>
                        <p>An intuitive admin panel empowers magazine administrators to effortlessly add, edit, or remove magazine editors on an annual basis...</p>
                    </li>
					<li>
                        <h3>Editorial Workflow</h3>
                        <p>Appointed editors are granted access to a review panel where they can accept, reject, or review articles submitted by students....</p>
                    </li>
					<li>
                        <h3>Technology Stack</h3>
                        <p>The project leverages the latest PHP pre-processors and SQLite Databases, ensuring a modern and efficient codebase...</p>
                    </li>
                    <!-- Add more key features as needed -->
                </ol>
            </section>

            <section>
                <h2>Technology Stack</h2>
                <ul>
                    <li>PHP</li>
                    <li>SQLite</li>
                    <!-- Add more technologies as needed -->
                </ul>
            </section>

            <section>
                <h2>Project Team</h2>
                <div class="card">
                    <h3>Aditya Narayan Mohapatra</h3>
                    <p>Roll Number: CS-20-25</p>
                </div>
                <div class="card">
                    <h3>Soumitree N. Mohanty</h3>
                    <p>Roll Number: CS-20-20</p>
                </div>
                <div class="card">
                    <h3>Abhas Kumar Sinha</h3>
                    <p>Roll Number: CS-20-16</p>
                </div>
                <div class="card">
                    <h3>Suman Samal</h3>
                    <p>Roll Number: CS-20-09</p>
                </div>
                <div class="card">
                    <h3>Rakesh Kumar Jena</h3>
                    <p>Roll Number: CS-21LE-55</p>
                </div>
            </section>
        </div>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> sounitree nandan mohanty</p>
    </footer>
</body>
</html>
