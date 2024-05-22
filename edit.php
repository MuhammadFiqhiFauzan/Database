<?php
include 'config.php';
include 'session.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];

    $sql = "UPDATE books SET title = ?, author = ?, year = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $title, $author, $year, $id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        $error = "Failed to update book. Please try again.";
    }
} else {
    $sql = "SELECT * FROM books WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Book</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <form method="post" action="edit.php?id=<?php echo $id; ?>">
        <h2>Edit Book</h2>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>
        <label for="author">Author:</label>
        <input type="text" name="author" id="author" value="<?php echo htmlspecialchars($book['author']); ?>" required>
        <label for="year">Year:</label>
        <input type="number" name="year" id="year" value="<?php echo htmlspecialchars($book['year']); ?>" required>
        <button type="submit">Update Book</button>
    </form>
</body>
</html>
