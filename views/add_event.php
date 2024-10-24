<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

include_once '../classes/Database.php';
$database = new Database();
$conn = $database->getConnection();

$query = "SELECT * FROM categories";
$stmt = $conn->prepare($query);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dodaj nowe wydarzenie</title>
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
</head>
<body>
    <h1>Dodaj nowe wydarzenie</h1>
    <form action="create_event.php" method="post" enctype="multipart/form-data">
        <label>Nazwa wydarzenia:</label>
        <input type="text" name="event_name" required><br><br>

        <label>Data rozpoczęcia:</label>
        <input type="date" name="start_time" required><br><br>

        <label>Data zakończenia:</label>
        <input type="date" name="end_time" required><br><br>

        <label>Opis wydarzenia:</label>
        <textarea name="description" required></textarea><br><br>

        <label>Ilustracja graficzna:</label>
        <input type="file" name="image" accept="image/*"><br><br>

        <label>Kategoria wydarzenia:</label>
        <select name="category_id">
            <?php foreach ($categories as $category) { ?>
                <option value="<?php echo $category['id']; ?>">
                    <?php echo htmlspecialchars($category['category_name']); ?>
                </option>
            <?php } ?>
        </select><br><br>

        <input type="submit" value="Dodaj wydarzenie">
    </form>
</body>
</html>
