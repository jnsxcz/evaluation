<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cap";

// Establishing a PDO connection
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Update the department status to active
        $stmt = $pdo->prepare("UPDATE question SET status = 'active' WHERE ques_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: question.php"); // Redirect to the department page
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>