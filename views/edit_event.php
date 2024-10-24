<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

include_once '../config/config.php';
include_once '../classes/Database.php';

$database = new Database();
$conn = $database->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_id = $_POST['event_id'];
    $event_name = $_POST['event_name'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $description = $_POST['description'];
    $category_id = $_POST['category_id'];

    $query = "UPDATE events SET event_name = :event_name, start_time = :start_time, end_time = :end_time, description = :description, category_id = :category_id WHERE id = :event_id";
    $stmt = $conn->prepare($query);

    $stmt->bindParam(':event_name', $event_name);
    $stmt->bindParam(':start_time', $start_time);
    $stmt->bindParam(':end_time', $end_time);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':category_id', $category_id);
    $stmt->bindParam(':event_id', $event_id);

    if ($stmt->execute()) {
        echo "Wydarzenie zostało zaktualizowane pomyślnie!";
    } else {
        echo "Błąd przy aktualizowaniu wydarzenia.";
    }
} elseif ($_GET['id']) {
    $event_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM events WHERE id = :event_id");
    $stmt->bindParam(':event_id', $event_id);
    $stmt->execute();
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt_categories = $conn->prepare("SELECT * FROM categories");
    $stmt_categories->execute();
    $categories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edytuj wydarzenie</title>
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
</head>
<body>
    <h1>Edytuj wydarzenie</h1>
    <form action="edit_event.php" method="post">
        <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($event['id']); ?>">
        <label>Nazwa wydarzenia:</label>
        <input type="text" name="event_name" value="<?php echo htmlspecialchars($event['event_name']); ?>" required><br><br>

        <label>Data rozpoczęcia:</label>
        <input type="date" name="start_time" value="<?php echo htmlspecialchars($event['start_time']); ?>" required><br><br>

        <label>Data zakończenia:</label>
        <input type="date" name="end_time" value="<?php echo htmlspecialchars($event['end_time']); ?>" required><br><br>

        <label>Opis wydarzenia:</label>
        <textarea name="description" required><?php echo htmlspecialchars($event['description']); ?></textarea><br><br>

        <label>Kategoria wydarzenia:</label>
        <select name="category_id">
            <?php foreach ($categories as $category) { ?>
                <option value="<?php echo $category['id']; ?>" <?php echo ($category['id'] == $event['category_id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($category['category_name']); ?>
                </option>
            <?php } ?>
        </select><br><br>

        <input type="submit" value="Zaktualizuj wydarzenie">
    </form>
</body>
</html>
