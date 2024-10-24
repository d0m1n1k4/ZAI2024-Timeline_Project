<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once 'classes/Database.php';
    $database = new Database();
    $conn = $database->getConnection();

    $username = $_POST['username'];
    $password = $_POST['password'];


    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Weryfikacja użytkownika i hasła
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_role'] = $user['user_role'];
        $_SESSION['username'] = $user['username'];
        header("Location: index.php");
        exit();
    } else {
        $error_message = "Błędna nazwa użytkownika lub hasło.";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie do pamiętnika</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Logowanie do pamiętnika</h1>
        </div>
    </header>
    <section>
        <div class="container">
            <?php
            // Wyświetlenie komunikatu o błędzie, jeśli logowanie się nie powiodło
            if (!empty($error_message)) {
                echo "<p style='color: red;'>$error_message</p>";
            }
            ?>
            <form action="login.php" method="post">
                <div class="form-group">
                    <label for="username">Nazwa użytkownika:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Hasło:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <button type="submit">Zaloguj</button>
                </div>
            </form>
        </div>
    </section>
</body>
</html>
