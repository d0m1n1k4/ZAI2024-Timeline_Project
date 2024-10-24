<?php
session_start();
include_once 'config/config.php';
include_once 'classes/Database.php';

$database = new Database();
$conn = $database->getConnection();

$query = "SELECT * FROM events";
$stmt = $conn->prepare($query);
$stmt->execute();

$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Wszystkie wydarzenia</title>
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
</head>
<body>
    <h1>Wszystkie wydarzenia</h1>
    <?php foreach ($events as $event) { ?>
        <div class="event-container">
            <h2><?php echo htmlspecialchars($event['event_name']); ?></h2>
            <p>Data: <?php echo htmlspecialchars($event['start_time']) . " - " . htmlspecialchars($event['end_time']); ?></p>
            <p>Opis: <?php echo htmlspecialchars($event['description']); ?></p>
            <?php if ($event['image_path']) { ?>
                <img src="<?php echo htmlspecialchars($event['image_path']); ?>" alt="Zdjęcie wydarzenia" width="200">
            <?php } ?>

            <!-- Sekcja dla administratora -->
            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') { ?>
                <div class="admin-controls">
                    <a href="edit_event.php?id=<?php echo $event['id']; ?>">Edytuj</a> |
                    <a href="delete_event.php?id=<?php echo $event['id']; ?>" onclick="return confirm('Czy na pewno chcesz usunąć to wydarzenie?');">Usuń</a>
                </div>
            <?php } ?>
        </div>
        <hr>
    <?php } ?>
</body>
</html>
