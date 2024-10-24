<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit;
}

include_once 'config/config.php';
include_once 'classes/Database.php';

$database = new Database();
$conn = $database->getConnection();

if (!isset($_GET['id'])) {
    echo "Nie podano identyfikatora wydarzenia do usunięcia.";
    exit;
}

$event_id = $_GET['id'];

$query = "DELETE FROM events WHERE id = :event_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':event_id', $event_id);

if ($stmt->execute()) {
    header("Location: view_events.php?message=success_delete");
    exit;
} else {
    echo "Błąd przy usuwaniu wydarzenia.";
}
?>
