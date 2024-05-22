<?php
include 'config.php';
include 'session.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

// Fungsi untuk menambahkan buku
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_book'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];

    $sql = "INSERT INTO books (title, author, year) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $title, $author, $year);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php");
    exit();
}

// Fungsi untuk mengedit buku
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_book'])) {
    $book_id = $_POST['book_id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];

    $sql = "UPDATE books SET title = ?, author = ?, year = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $title, $author, $year, $book_id);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php");
    exit();
}

// Fungsi untuk menghapus buku
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_book'])) {
    $book_id = $_POST['book_id'];

    $sql = "DELETE FROM books WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php");
    exit();
}

// Ambil daftar buku dari database
$sql = "SELECT * FROM books";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
        }

        nav {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            margin: 0 10px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            text-align: center;
            margin-bottom: 20px;
        }

        form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            text-align: left;
        }

        input[type="text"],
        input[type="number"],
        button {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="text"],
        input[type="number"] {
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }

        th, td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        button {
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Panel</h1>
        <nav>
            <a href="books.php">Back to Books</a>
        </nav>

        <h2>Add New Book</h2>
        <form method="post" action="index.php">
            <label for="title">Title:</label>
            <input type="text" name="title" required>
            <label for="author">Author:</label>
            <input type="text" name="author" required>
            <label for="year">Year:</label>
            <input type="number" name="year" required>
            <button type="submit" name="add_book">Add Book</button>
        </form>

        <h2>Edit Book</h2>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Year</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <form method="post" action="index.php">
                            <input type="hidden" name="book_id" value="<?php echo $row['id']; ?>">
                            <td><input type="text" name="title" value="<?php echo htmlspecialchars($row['title']); ?>"></td>
                            <td><input type="text" name="author" value="<?php echo htmlspecialchars($row['author']); ?>"></td>
                            <td><input type="number" name="year" value="<?php echo htmlspecialchars($row['year']); ?>"></td>
                            <td>
                                <button type="submit" name="edit_book">Save</button>
                                <button type="submit" name="delete_book">Delete</button>
                            </td>
                        </form>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>

