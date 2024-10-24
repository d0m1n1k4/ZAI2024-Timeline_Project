<?php
include_once 'config/config.php';
include_once 'classes/Database.php';

$database = new Database();
$conn = $database->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $event_name = $_POST['event_name'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $description = $_POST['description'];
        $category_id = $_POST['category_id'];

        $image_path = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
            $image_path = $target_file;
        }

        $query = "INSERT INTO events (event_name, start_time, end_time, description, image_path, category_id) VALUES (:event_name, :start_time, :end_time, :description, :image_path, :category_id)";
        $stmt = $conn->prepare($query);

        $stmt->bindParam(':event_name', $event_name);
        $stmt->bindParam(':start_time', $start_time);
        $stmt->bindParam(':end_time', $end_time);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':image_path', $image_path);
        $stmt->bindParam(':category_id', $category_id);

        if ($stmt->execute()) {
            echo "Wydarzenie zostało dodane pomyślnie!";
        } else {
            echo "Błąd przy dodawaniu wydarzenia.";
        }

    } catch (Exception $e) {
        echo "Błąd: " . $e->getMessage();
    }
}
?>
