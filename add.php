<?php
include 'config.php';
include 'session.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];

    $sql = "INSERT INTO books (title, author, year) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $title, $author, $year);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        $error = "Failed to add book. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Book</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <form method="post" action="add.php">
        <h2>Add New Book</h2>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" required>
        <label for="author">Author:</label>
        <input type="text" name="author" id="author" required>
        <label for="year">Year:</label>
        <input type="number" name="year" id="year" required>
        <button type="submit">Add Book</button>
    </form>
</body>
</html>
