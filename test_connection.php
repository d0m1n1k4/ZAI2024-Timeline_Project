<?php
include_once 'config/config.php';
include_once 'classes/Database.php';

$database = new Database();
$conn = $database->getConnection();

if ($conn) {
    echo "Connection to DB completed successfully";
} else {
    echo "Connection to DB failed";
}
?>
