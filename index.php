<?php
session_start(); 


include_once 'config/config.php'; 
include_once 'classes/Database.php';

$database = new Database();
$conn = $database->getConnection();


$query = "SELECT * FROM events ORDER BY start_time";
$stmt = $conn->prepare($query);
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <title>The history of Samsung</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
</head>
<body>
    <header>
        <div class="container">
            <h1>The history of Samsung</h1>

            <div class="login-button" style="position: absolute; top: 20px; right: 20px;">
                <?php if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') { ?>
                    <a href="login.php" class="btn-login">Zaloguj się jako administrator</a>
                <?php } else { ?>
                    <a href="logout.php" class="btn-login">Wyloguj się</a>
                <?php } ?>
            </div>
        </div>
    </header>
    
    <section>
        <div class="container">
            <div id="timeline">
                <?php
                foreach ($events as $event) {
                    echo '<div class="tl-block">';
                    echo '<div class="tl-year"><div>' . date('Y', strtotime($event['start_time'])) . '</div></div>';
                    echo '</div>';

                    echo '<div class="tl-block">';
                    echo '<div class="tl-event">';
                    echo '<div class="event-' . (($event['id'] % 2 == 0) ? 'l' : 'r') . '">'; // Naprzemienne umieszczanie po lewej/prawej stronie
                    echo '<div>';
                    echo '<h3><time datetime="' . $event['start_time'] . '">' . date('M d', strtotime($event['start_time'])) . '</time> ' . htmlspecialchars($event['event_name']) . '</h3>';
                    echo '<p>' . htmlspecialchars($event['description']) . '</p>';

                    if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') {
                        echo '<div class="admin-controls">';
                        echo '<a href="views/edit_event.php?id=' . $event['id'] . '">Edytuj</a> | ';
                        echo '<a href="views/delete_event.php?id=' . $event['id'] . '" onclick="return confirm(\'Czy na pewno chcesz usunąć to wydarzenie?\');">Usuń</a>';
                        echo '</div>';
                    }

                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </section>
    
    <footer>
        <div class="container">
            <p>The history of Samsung</p>
        </div>
    </footer>
</body>
</html>
